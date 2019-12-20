<?php

class pager_helper
{
    /**
     * @return pager
     */
    public static function get_pager($list, $current, $per_page)
    {
        return new pager($list, $current, $per_page);
    }

    public static function get_full_ajax($list, $current = null, $per_page = null, $show_pages = 5, $q = '', $srch = 0)
    {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        $start = $pager->get_page() - floor($show_pages / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + $show_pages;
        if ($end > $pager->get_pages()) {
            $start = $start - ($end - $pager->get_pages());
            if ($start < 1) {
                $start = 1;
            }

            $end = $pager->get_pages();
        }

        if ($pager->get_previous()) {
            $html .= '<a '.self::onclick_page(1, $q, $srch).'>&larr;</a>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<a '.($i == $pager->get_page() ? 'class="selected"' : '').self::onclick_page($i, $q, $srch).'>'.$i.'</a>';
        }

        if ($pager->get_next()) {
            $html .= '<a '.self::onclick_page($current + ($show_pages / 2), $q, $srch).'>&rarr;</a>';
        }

        return $html;
    }


    public function onclick_page($page = 1, $q = '', $srch = 0)
    {
        if ($srch) {
            return "onclick=\"Application.goToSrchPage('$q',$page)\" ";
        } else {
            return "onclick=\"Application.goToPage('$q',$page)\" ";
        }
    }

    public static function get_full($list, $current = null, $per_page = null, $show_pages = 5)
    {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        $start = $pager->get_page() - floor($show_pages / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + $show_pages;
        if ($end > $pager->get_pages()) {
            $start = $start - ($end - $pager->get_pages());
            if ($start < 1) {
                $start = 1;
            }

            $end = $pager->get_pages();
        }

        if ($pager->get_previous()) {
            $html .= '<a style="border:0;" href="'.$pager->get_previous_link().'">&larr;</a>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<a '.($i == $pager->get_page() ? 'class="selected"' : '').' href="'.$pager->get_uri($i).'">'.$i.'</a>';
        }

        if ($pager->get_next()) {
            $html .= '<a style="border:0;" href="'.$pager->get_next_link().'">&rarr;</a>';
        }

        return $html;
    }

    public static function renderAllNumberOfPages($pager)
    {
        // if (!($pager instanceof pager)) {
        //     $pager = self::get_pager($pager, null, null);
        // }

        $anchors = array_map(
            static function ($pageNumber) {
                return sprintf('<a href="">%s</a>', $pageNumber);
            },
            $pager->get_()
        );

        return implode('', $anchors);
    }

    public static function get_short($list, $current = null, $per_page = null)
    {
        if ($list instanceof pager) {
            $pager = $list;
        } else {
            $pager = self::get_pager($list, $current, $per_page);
        }

        $html = '';

        if ($pager->get_previous()) {
            $html .= '<a href="'.$pager->get_previous_link().'">&larr; Назад</a>';
        }
        if ($pager->get_next()) {
            $html .= '<a href="'.$pager->get_next_link().'">Вперед &rarr;</a>';
        }

        return $html;
    }
}

class PaginatorFactory
{

    /**
     * @param array $collection
     *
     * @return \Paginator
     */
    public static function create($collection = [])
    {
        return new Paginator($collection);
    }
}

class Paginator
{
    const INDEX_TEMPLATE = '<a href="%s?%s" class="%s">%s</a>';

    /** @var string */
    private $queryMapKey = 'page';

    private $urlPath;

    /** @var array */
    private $queryMap = [];

    /** @var array */
    private $collection;

    /** @var int */
    private $currentPage;

    /**
     * Paginator constructor.
     *
     * @param array $collection
     */
    public function __construct($collection)
    {
        $this->collection = $collection;
        $requestUri       = $_SERVER['REQUEST_URI'];
        $result           = preg_match('/(?P<url_path>(.+))\?(?P<parameters>([\w\-\[\]=&]+))/', $requestUri, $match);

        $this->urlPath = $match['url_path'];

        if (isset($match['parameters'])) {
            parse_str($match['parameters'], $this->queryMap);
        }

        $this->currentPage = array_key_exists($this->queryMapKey, $this->queryMap)
            ? (int) $this->queryMap[$this->queryMapKey]
            : 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        $collection     = array_chunk($this->collection, 24);
        $collectionKeys = array_keys($collection);
        $map            = $this->queryMap;
        $key            = $this->queryMapKey;

        $pages = array_map(
            function ($value) use ($map, $key) {
                $value++;
                $map[$key]   = $value;
                $queryString = http_build_query($map);

                return sprintf(
                    self::INDEX_TEMPLATE,
                    $this->urlPath,
                    $queryString,
                    $map[$key] === $this->currentPage ? 'selected' : '',
                    $map[$key]
                );
            },
            $collectionKeys
        );

        return implode('', $pages);
    }
}

class pager
{
    private $list     = 0;
    private $current  = 0;
    private $per_page = 0;
    private $pages;
    private $total    = 0;

    public $external_uri = '';

    public function __construct($list, $current, $per_page)
    {
        $this->list     = $list;
        $this->current  = $current > 1 ? $current : 1;
        $this->per_page = $per_page;
        $this->total    = count($list);
        $this->pages    = ceil($this->total / $per_page);
    }

    public function get_uri($page)
    {
        $uri = ($this->external_uri == '') ? preg_replace('/[?&]page=[0-9]+/', '', $_SERVER['REQUEST_URI']) : preg_replace('/[?&]page=[0-9]+/', '', $this->external_uri);
        $uri .= strpos($uri, '?') ? '&' : '?';
        $uri .= 'page='.$page;

        return $uri;
    }

    public function set_external_uri($uri)
    {
        $this->external_uri = $uri;
    }

    public function get_previous()
    {
        return $this->current > 1 ? $this->current - 1 : null;
    }

    public function get_next()
    {
        return $this->current < $this->pages ? $this->current + 1 : null;
    }

    public function get_next_link()
    {
        return $this->get_uri($this->get_next());
    }

    public function get_previous_link()
    {
        return $this->get_uri($this->get_previous());
    }

    public function get_page()
    {
        return $this->current;
    }

    public function get_pages()
    {
        return $this->pages;
    }

    public function get_total()
    {
        return $this->total;
    }

    public function get_list()
    {
        return array_slice($this->list, ($this->current - 1) * $this->per_page, $this->per_page);
    }
}

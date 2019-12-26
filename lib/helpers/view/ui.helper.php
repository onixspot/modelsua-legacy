<?php

load::view_helper('tag', true);

class ui_helper
{
    const MONTH_ZERO      = 0;
    const MONTH_JANUARY   = 1;
    const MONTH_FEBRUARY  = 2;
    const MONTH_MART      = 3;
    const MONTH_APRIL     = 4;
    const MONTH_MAY       = 5;
    const MONTH_JUNE      = 6;
    const MONTH_JULY      = 7;
    const MONTH_AUGUST    = 8;
    const MONTH_SEPTEMBER = 9;
    const MONTH_OCTOBER   = 10;
    const MONTH_NOVEMBER  = 11;
    const MONTH_DECEMBER  = 12;

    const MONTHS = [
        self::MONTH_JANUARY   => 'Январь',
        self::MONTH_FEBRUARY  => 'Февраль',
        self::MONTH_MART      => 'Март',
        self::MONTH_APRIL     => 'Апрель',
        self::MONTH_MAY       => 'Май',
        self::MONTH_JUNE      => 'Июнь',
        self::MONTH_JULY      => 'Июль',
        self::MONTH_AUGUST    => 'Август',
        self::MONTH_SEPTEMBER => 'Сентябрь',
        self::MONTH_OCTOBER   => 'Октябрь',
        self::MONTH_NOVEMBER  => 'Ноябрь',
        self::MONTH_DECEMBER  => 'Декабрь',
    ];

    public static function display_date($timestamp, $demiliter = ' ')
    {

        $date['day']    = date('d', $timestamp);
        $date['mounth'] = self::get_mounth(date('n', $timestamp));
        $date['year']   = date('Y', $timestamp);

        $formated_date = implode($demiliter, $date);

        return $formated_date;
    }

    public static function get_mounth($id = null)
    {
        $months[self::MONTH_ZERO] = '&mdash;';
        $months[1]                = t('января');
        $months[2]                = t('февраля');
        $months[3]                = t('марта');
        $months[4]                = t('апреля');
        $months[5]                = t('мая');
        $months[6]                = t('июня');
        $months[7]                = t('июля');
        $months[8]                = t('августа');
        $months[9]                = t('сентября');
        $months[10]               = t('октября');
        $months[11]               = t('ноября');
        $months[12]               = t('декабря');

        return ($id) ? (isset($months[$id]) ? $months[$id] : false) : $months;
    }

    public static function get_mounth_list($id = null)
    {
        $months = array_merge([self::MONTH_ZERO => '&mdash;'], self::MONTHS);

        if ($id !== null) {
            if (!array_key_exists($id, $months)) {
                return false;
            }

            return $months[$id];
        }

        return $months;
    }

    public static function datefields($name = '', $date = 0, $multiple = false, $options = [], $empty = false, $yearstart = 1970)
    {
        if ($empty) {
            $days[0]   = '&mdash;';
            $months[0] = '&mdash;';
            $years[0]  = '&mdash;';
        } elseif (!$date) {
            $date = time();
        }

        $curday   = $date > 0 ? date('j', $date) : 0;
        $curmonth = $date > 0 ? date('n', $date) : 0;
        $curyear  = $date > 0 ? date('Y', $date) : 0;

        for ($d = 1, $dMax = date('t', $date); $d <= $dMax; $d++) {
            $days[$d] = $d;
        }

        $months = self::get_mounth();
        ///////////////
        for ($y = date('Y'); $y >= $yearstart; $y--) {
            $years[$y] = $y;
        }
        if ($multiple) {
            $multi = '[]';
        }

        $tags = [
            tag_helper::select(
                sprintf('%s_day%s', $name, $multi),
                $days,
                array_merge(
                    $options,
                    [
                        'id'    => $name.'_day',
                        'class' => 'datefield',
                        'value' => $curday,
                    ]
                )
            ),
            tag_helper::select(
                sprintf('%s_month%s', $name, $multi),
                $months,
                array_merge(
                    $options,
                    [
                        'id'      => $name.'_month',
                        'class'   => 'datefield',
                        'value'   => $curmonth,
                        'onclick' => 'Calendar.checkdate(this)',
                    ]
                )
            ),
            tag_helper::select(
                sprintf('%s_year%s', $name, $multi),
                $years,
                array_merge(
                    $options,
                    [
                        'id'      => $name.'_year',
                        'class'   => 'datefield',
                        'value'   => $curyear,
                        'onclick' => 'Calendar.checkdate(this)',
                    ]
                )
            ),
        ];

        return sprintf('<div>%s</div>', implode('', $tags));
    }

    public static function dateval($field = '', $multiple = false)
    {
        if ($multiple) {
            $days   = request::get($field.'_day');
            $months = request::get($field.'_month');
            $years  = request::get($field.'_year');
            if (is_array($days) && is_array($months) && is_array($years)) {
                foreach ($days as $k => $v) {
                    $arr[$k] = mktime(0, 0, 0, $months[$k], $days[$k], $years[$k]);
                }

                return $arr;
            } else {
                return 0;
            }
        } else {
            if (request::get_int($field.'_year') > 0) {
                return mktime(0, 0, 0, request::get_int($field.'_month'), request::get_int($field.'_day'), request::get_int($field.'_year'));
            } else {
                return 0;
            }
        }


    }

    public static function photo($user_data, $options = [])
    {
        $html = '<img src="%src%" %options%/>';

        $replace = [self::set_options($options)];
        if ($user_data['pid']) {
            if ($user_data['ph_crop']) {
                $crop      = unserialize($user_data['ph_crop']);
                $replace[] = sprintf('/imgserve?pid=%s&w=%s&h=%s&x=%s&y=%s&z=crop', $user_data['pid'], $crop['w'], $crop['h'], $crop['x'], $crop['y']);
            } else {
                $replace[] = sprintf('/imgserve?pid=%s', $user_data['pid']);
            }
        } else {
            $replace[] = '/no_image.png';
        }

        return preg_replace(['/%options%/', '/%src%/'], $replace, $html);
    }

    public static function set_options($options)
    {
        $html = '';
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $html .= $key.'="'.$value.'" ';
            }
        }

        return $html;

    }


}

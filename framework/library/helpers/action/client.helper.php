<?

class client_helper
{
    private static $variables = [];
    private static $title = '';
    private static $meta = [
        "Content-Type" => [
            "http-equiv" => "Content-Type",
            "content" => "text/html; charset=utf-8",
        ],
    ];

    public static function register_variable($name, $value)
    {
        self::$variables[$name] = $value;
    }

    public static function get_variables()
    {
        $clientController = context::get_controller()->get_module()."Controller";

        $js = '';

        foreach (self::$variables as $name => $value) {
            $js .= "{$clientController}.{$name} = ".json_encode($value).';';
        }

        return $js;
    }

    /**
     * @return string
     */
    public static function get_title()
    {
        return self::$title;
    }

    public static function set_title($title)
    {
        self::$title = $title;
    }

    public static function get_html_title()
    {
        return "<title>".self::$title."</title>\n";
    }

    public static function get_meta()
    {
        $output = "";
        foreach (self::$meta as $meta_data) {
            $output .= self::render_meta($meta_data);
        }

        return $output;
    }

    public static function set_meta($data)
    {
        $key = $data["name"] ? $data["name"] : $data["http-equiv"];
        self::$meta[$key] = $data;
    }

    private static function render_meta($data)
    {
        $attrs = array();
        foreach ($data as $key => $value) {
            $value = htmlspecialchars($value);
            $attrs[] = "{$key}=\"{$value}\"";
        }

        return "<meta ".implode(" ", $attrs)."/>\n";
    }
}
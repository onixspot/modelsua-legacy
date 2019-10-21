<?php

load::view_helper('tag', true);

class ui_helper
{
    public static function display_date($timestamp, $demiliter = ' ')
    {

        $date['day']    = date('d', $timestamp);
        $date['mounth'] = self::get_mounth(date('n', $timestamp));
        $date['year']   = date('Y', $timestamp);

        $formated_date = implode($demiliter, $date);

        return $formated_date;
    }

    public function get_mounth($id = null)
    {
        $months[1]  = t('января');
        $months[2]  = t('февраля');
        $months[3]  = t('марта');
        $months[4]  = t('апреля');
        $months[5]  = t('мая');
        $months[6]  = t('июня');
        $months[7]  = t('июля');
        $months[8]  = t('августа');
        $months[9]  = t('сентября');
        $months[10] = t('октября');
        $months[11] = t('ноября');
        $months[12] = t('декабря');

        return ($id) ? (isset($months[$id]) ? $months[$id] : false) : $months;
    }

    public static function get_mounth_list($id = null)
    {
        $months[0]  = '&mdash;';
        $months[1]  = t('Январь');
        $months[2]  = t('Февраль');
        $months[3]  = t('Март');
        $months[4]  = t('Апрель');
        $months[5]  = t('Май');
        $months[6]  = t('Июнь');
        $months[7]  = t('Июль');
        $months[8]  = t('Август');
        $months[9]  = t('Сентябрь');
        $months[10] = t('Октябрь');
        $months[11] = t('Ноябрь');
        $months[12] = t('Декабрь');

        return $id ? (isset($months[$id]) ? $months[$id] : false) : $months;
    }

    public function photo($user_data, $options = [])
    {
        $html = '<img src="%src%" %options%/>';

        $replace = [self::set_options($options)];
        if ($user_data['pid']) {
            if ($user_data['ph_crop']) {
                $crop = unserialize($user_data['ph_crop']);
                array_push($replace, '/imgserve?pid='.$user_data['pid'].'&w='.$crop['w'].'&h='.$crop['h'].'&x='.$crop['x'].'&y='.$crop['y']
                    .'&z=crop');
            } else {
                array_push($replace, '/imgserve?pid='.$user_data['pid']);
            }
        } else {
            array_push($replace, '/no_image.png');
        }

        return preg_replace(["/%options%/", "/%src%/"], $replace, $html);
    }

    public function set_options($options)
    {
        $html = '';
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $html .= $key.'="'.$value.'" ';
            }
        }

        return $html;

    }

    public static function datefields($name = '', $date = 0, $multiple = false, $options = [], $empty = false, $yearstart = 1930)
    {
        if ($empty) {
            $days[0]   = "&mdash;";
            $months[0] = "&mdash;";
            $years[0]  = "&mdash;";
        } else {
            if (!$date) {
                $date = time();
            }
        }

        $curday   = ($date) ? date("j", $date) : 0;
        $curmonth = ($date) ? date("n", $date) : 0;
        $curyear  = ($date) ? date("Y", $date) : 0;

        for ($d = 1, $dMax = date("t", $date); $d <= $dMax; $d++) {
            $days[$d] = $d;
        }

        $months = self::get_mounth();
        ///////////////
        for ($y = date("Y"); $y >= $yearstart; $y--) {
            $years[$y] = $y;
        }
        if ($multiple) {
            $multi = '[]';
        }

        return '<div>
                '.tag_helper::select($name.'_day'.$multi, $days, array_merge($options, [
                'id'    => $name.'_day',
                'class' => 'datefield',
                'value' => $curday,
            ])).'
                '.tag_helper::select($name.'_month'.$multi, $months, array_merge($options, [
                'id'      => $name.'_month',
                'class'   => 'datefield',
                'value'   => $curmonth,
                'onclick' => 'Calendar.checkdate(this)',
            ])).'
                '.tag_helper::select($name.'_year'.$multi, $years, array_merge($options, [
                'id'      => $name.'_year',
                'class'   => 'datefield',
                'value'   => $curyear,
                'onclick' => 'Calendar.checkdate(this)',
            ])).'
                </div>';
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
            if (request::get_int($field.'_day') > 0 && request::get_int($field.'_year') > 0) {
                return mktime(0, 0, 0, request::get_int($field.'_month'), request::get_int($field.'_day'), request::get_int($field.'_year'));
            } else {
                return 0;
            }
        }
    }


}

?>

<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_or_set_menu')) {
    function get_or_set_menu($fromSession = true)
    {
        $CI = get_instance();

        $menus = iterateMenu(getMenu());

        $CI->session->set_userdata("app_menu", $menus);

        return $menus;
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function is_local_ip()
    {
        $client_ip = get_client_ip();
        if ($client_ip !== 'UNKNOWN') {
            // Define the ranges for local IP addresses (IPv4)
            $local_ranges_v4 = [
                '10.0.0.0|10.255.255.255',        // Class A private network
                '172.16.0.0|172.31.255.255',      // Class B private network
                '192.168.0.0|192.168.255.255',    // Class C private network
                '127.0.0.0|127.255.255.255'       // Loopback address
            ];

            // Define the local IP addresses (IPv6)
            $local_ips_v6 = [
                '::1'                             // IPv6 loopback address
            ];

            // Check if the IP is an IPv4 address
            if (filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ip_long = ip2long($client_ip);
                if ($ip_long !== false) {
                    foreach ($local_ranges_v4 as $range) {
                        list($start, $end) = explode('|', $range);
                        if ($ip_long >= ip2long($start) && $ip_long <= ip2long($end)) {
                            return true;
                        }
                    }
                }
            } elseif (filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                // Check if the IP is an IPv6 address
                if (in_array($client_ip, $local_ips_v6)) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('number_format_indo')) {
    function number_format_indo($number)
    {
        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('getColor')) {
    function getColor($value, $max = 100)
    {
        $percentage = $value * 100 / $max;
        if ($percentage > 100) {
            return 'red'; //kalau persentase lebih dari 100%, cek maksimal jumlah cuti pertahun
        } else if ($percentage == 100) {
            return '#AAFF00';
        } else if ($percentage > 75) {
            return '#00c0ef';
        } else if ($percentage > 50) {
            return '#3c8dbc';
        } else if ($percentage > 0) {
            return '#FEB139';
        } else {
            return '#f56954';
        }
    }
}

if (!function_exists('is_development')) {
    function is_development()
    {
        return ENVIRONMENT != 'production';
    }
}

if (!function_exists('add_days_to_date')) {
    function add_days_to_date($date, $days)
    {
        return date('Y-m-d', strtotime($date . (intval($days) >= 0 ? ' + ' : ' - ') . abs($days) . ' days'));
    }
}

if (!function_exists('add_currency_symbol')) {
    function add_currency_symbol($amount, $currencySymbol = 'Rp')
    {
        return $currencySymbol . ' ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('cleansePhoneNumbers')) {
    function cleansePhoneNumbers($number, $includeInvalid = false)
    {
        $cleanedNumbers = [];

        // Temukan semua potongan angka dalam string (baik dengan atau tanpa tanda +).
        preg_match_all('/(\+?\d+)/', str_replace(['-', ' '], '', $number), $matches);

        foreach ($matches[0] as $match) {
            // Hapus karakter yang tidak valid.
            $cleanedPiece = preg_replace('/[^0-9+]/', '', $match);

            // Periksa apakah nomor telepon dimulai dengan kode negara atau kode area, jika tidak, tambahkan kode negara Indonesia (+62).
            if (!in_array(substr($cleanedPiece, 0, 2), ['62', '60']) && substr($cleanedPiece, 0, 1) != '+') {
                $cleanedPiece = '+62' . ltrim($cleanedPiece, '0'); // Tambahkan +62 dan hapus angka 0 di depan.
            }

            if ($includeInvalid || strlen($cleanedPiece) >= 10) {
                // Hapus karakter tambahan selain angka.
                $cleanedNumbers[] = preg_replace('/[^0-9]/', '', $cleanedPiece);
            }
        }

        return $cleanedNumbers;
    }
}

if (!function_exists('merge_html_class')) {
    function merge_html_class($class1, $class2 = '')
    {
        // Extract the class names from the strings
        $class1 = str_replace('class="', '', $class1);
        $class2 = str_replace('class="', '', $class2);
        $class1 = rtrim($class1, '"');
        $class2 = rtrim($class2, '"');

        // Merge the class names into a single string
        $mergedString = 'class="' . $class1 . ' ' . $class2 . '"';

        return $mergedString;
    }
}

if (!function_exists('guzzle_form_urlencoded')) {
    function guzzle_form_urlencoded($url, $type = 'GET', $data = null)
    {
        $CI = get_instance();
        $CI->load->library('Guzzle');

        $client = new GuzzleHttp\Client([
            'verify' => false
        ]);
        $request = new GuzzleHttp\Psr7\Request(strtoupper($type), $url, [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query($data));

        try {
            return $client->sendAsync($request)->then(function ($response) {
                return json_decode($response->getBody()->getContents(), true);
            })->wait();
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            if ($exception->hasResponse()) {
                $statusCode = $exception->getResponse()->getStatusCode();
                $body = $exception->getResponse()->getBody()->getContents();
            } else {
                $statusCode = $exception->getCode();
                $body = $exception->getMessage();
            }
            return api_return(false, $body, $statusCode);
        } catch (\Exception $exception) {
            // Handle other general exceptions here if needed
            return api_return(false, $exception->getMessage());
        }
    }

    function hit_api($endpoint, $type = 'GET', $data = null, $token = null)
    {
        $CI = get_instance();
        $CI->load->library('Guzzle');

        // if (!$CI->session->userdata('disableApiRequest')) {
        // $client = new GuzzleHttp\Client();
        $client = new GuzzleHttp\Client([
            'verify' => false
        ]);
        $request = new GuzzleHttp\Psr7\Request(strtoupper($type), $endpoint);

        try {
            $promise = $client->sendAsync($request, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => $data,
            ]);
            return $promise->then(function ($response) {
                return api_return(true, $response->getBody()->getContents(), $response->getStatusCode());
            })->wait();
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            if ($exception->hasResponse()) {
                $statusCode = $exception->getResponse()->getStatusCode();
                $body = $exception->getResponse()->getBody()->getContents();
            } else {
                $statusCode = $exception->getCode();
                $body = $exception->getMessage();
            }

            // $CI->session->set_userdata('disableApiRequest', 1);

            return api_return(false, $body, $statusCode);
        } catch (\Exception $exception) {
            // $CI->session->set_userdata('disableApiRequest', 1);

            // Handle other general exceptions here if needed
            return api_return(false, $exception->getMessage());
        }
        // }
    }

    function api_return($status, $response, $code = null)
    {
        return [
            'status' => $status,
            'code' => $code,
            'response' => $response,
        ];
    }
}

if (!function_exists('curl_reset')) {
    function curl_reset(&$ch)
    {
        curl_close($ch);
        $ch = curl_init();
    }
}

if (!function_exists('is_owner')) {
    function is_owner($id_pegawai)
    {
        return get_instance()->user->id == $id_pegawai;
    }
}

if (!function_exists('arraySearchKeyIndex')) {
    function arraySearchKeyIndex($key, $value, $array)
    {
        foreach ($array as $k => $val) {
            if ($val[$key] === $value) {
                return $k;
            }
        }
        return null;
    }
}

if (!function_exists('get_dates')) {
    function get_dates($month, $year)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("Y-m-d", $mktime);
            $dates_month[$i] = $date;
        }

        return $dates_month;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = "%d %B %Y")
    {
        if (!$date || $date == '0000-00-00') {
            return null;
        }
        return getLocaleTime(strftime($format, strtotime($date)));
    }
}

if (!function_exists('getLocaleTime')) {
    function getLocaleTime($time)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $time = str_replace(array_keys($days), array_values($days), $time);
        $time = str_replace(array_keys($months), array_values($months), $time);
        return $time;
    }
}

if (!function_exists('getSemesterFromDate')) {
    function getSemesterFromDate($dateString)
    {
        $date = new DateTime($dateString);
        $month = (int)$date->format('n');
        return floor($month / 6) + 1;
    }
}

if (!function_exists('do_upload')) {
    function do_upload($field, $folder, $filename = null, $types = 'pdf', $max_size = 10240)
    {
        $CI = get_instance();

        $config['upload_path'] = FOLDER_ROOT_UPLOAD . $folder;
        $config['allowed_types'] = $types; //'gif|jpg|png|jpeg'
        $config['max_size']  = $max_size;
        $config['file_name'] = $filename;
        $config['encrypt_name'] = false;
        // $config['max_width'] = 1024;
        // $config['max_height'] = 1024;

        $CI->load->library('upload', $config, $folder);
        $CI->$folder->initialize($config);
        if (!$CI->$folder->do_upload($field)) {
            return ['success' => false, 'message' => $CI->$folder->display_errors()];
        }

        return ['success' => true, 'filename' => pathinfo($CI->$folder->data()['full_path'], PATHINFO_BASENAME)];
    }
}

if (!function_exists('rename_file')) {
    function rename_file($folder, $oldFilename, $newFilename)
    {
        if (file_exists(($oldPath = FOLDER_ROOT_UPLOAD . $folder . '/' . $oldFilename))) {
            $newFilename .= (($pathInfo = pathinfo($oldPath)) && isset($pathInfo['extension']) ? ".{$pathInfo['extension']}" : '');
            if (rename($oldPath, FOLDER_ROOT_UPLOAD . $folder . '/' . $newFilename)) {
                return $newFilename;
            }
        }
        return false;
    }
}

if (!function_exists('delete_file')) {
    function delete_file($folder, $filename = null)
    {
        return $filename && file_exists(($path = FOLDER_ROOT_UPLOAD . $folder . '/' . $filename)) ? unlink($path) : true;
    }
}

if (!function_exists('file_path')) {
    function file_path($folder, $filename = null)
    {
        return $filename && file_exists(($path = FOLDER_ROOT_UPLOAD . $folder . '/' . $filename)) ? $path : null;
    }
}

if (!function_exists('file_url')) {
    function file_url($folder, $filename = null)
    {
        return $filename && file_exists(FOLDER_ROOT_UPLOAD  . $folder . '/' . $filename) ? base_url(FOLDER_ROOT_UPLOAD . $folder . '/' . $filename) : null;
    }
    function file_url2($path)
    {
        return $path && file_exists($path) ? base_url($path) : null;
    }
}

if (!function_exists('warning_message')) {
    function warning_message($message, $textClass = 'warning', $class = '')
    {
        $html = '<div class="warning-message text-' . $textClass . ' mb-2 ' . $class . '">';
        $html .= $message;
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('alert')) {
    function alert($message, $title = 'Perhatian', $options = [])
    {
        $html = '<div class="alert ' . (isset($options['class']) ? $options['class'] : 'alert-warning') . ' alert-dismissible">';
        $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
        $html .= '<h6 class="font-weight-bold"><i class="icon fa fa-' . (isset($options['icon']) ? $options['icon'] : 'warning') . '"></i> ' . strtoupper($title) . '</h6>';
        $html .= $message;
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('is_holiday')) {
    function is_holiday($date, $holidays)
    {
        if (!($date instanceof DateTime)) {
            $date = new DateTime($date);
        }
        return in_array($date->format("l"), ['Saturday', 'Sunday']) || in_array($date->format("Y-m-d"), $holidays);
    }
}

if (!function_exists('is_honorer')) {
    function is_honorer($id_jabatan)
    {
        return empty($id_jabatan) || in_array($id_jabatan, get_or_set_jabatan_honorer());
    }
}

if (!function_exists('jabatan_class')) {
    function jabatan_class($id_jabatan)
    {
        $class = [
            JABATAN_HONORER => 'honorer',
            JABATAN_HONORER_NON_DIPA => 'honorer-non-dipa',
        ];
        return isset($class[$id_jabatan]) ? $class[$id_jabatan] : '';
    }
}

if (!function_exists('get_user_config')) {
    function get_user_config($id_user)
    {
        $CI = get_instance();
        $CI->load->model('UserConfig_Model', 'userconfig');
        return $CI->userconfig->findOne($id_user, true);
    }
}

if (!function_exists('get_layout_classes')) {
    function get_layout_classes($layout)
    {
        $classes = [
            'navbar' => 'main-header navbar navbar-expand',
            'sidebar' => 'main-sidebar elevation-4',
            'mode-layout-plain' => 'hold-transition layout-footer-fixed',
            'mode-layout' => 'sidebar-mini layout-footer-fixed layout-navbar-fixed layout-fixed',
            // 'mode-layout' => 'sidebar-mini sidebar-collapse layout-footer-fixed layout-navbar-fixed layout-fixed',
        ];
        return isset($classes[$layout]) ? $classes[$layout] : '';
    }
}

if (!function_exists('spell_number')) {
    function spell_number($number)
    {
        return (new NumberFormatter("id", NumberFormatter::SPELLOUT))->format($number);
    }
}

if (!function_exists('spell_number')) {
    function spell_number($number)
    {
        return (new NumberFormatter("id", NumberFormatter::SPELLOUT))->format($number);
    }
}

if (!function_exists('random_date_between')) {
    function random_date_between($start, $end)
    {
        $start = DateTime::createFromFormat('Y-m-d', $start);
        $end = DateTime::createFromFormat('Y-m-d', $end);

        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate->format('Y-m-d');
    }
}

if (!function_exists('random_int_length')) {
    function random_int_length($length)
    {
        return join('', array_map(function ($value) {
            return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9);
        }, range(1, $length)));
    }
}

if (!function_exists('number_to_day')) {
    function number_to_day($string_to_replace)
    {
        return str_replace(
            [0, 1, 2, 3, 4, 5],
            ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            $string_to_replace
        );
    }
}

//yes it is dumb
if (!function_exists('var_dumb')) {
    function var_dumb($data, $exit = false)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';

        if ($exit) {
            exit;
        }
    }
}

if (!function_exists('getYearsRange')) {
    function getYearsRange($start_year, $end_year, $sorting = 'desc')
    {
        $years = array(); // inisialisasi array kosong
        if ($sorting == 'asc') { // jika sorting adalah ascending
            for ($i = $start_year; $i <= $end_year; $i++) {
                array_push($years, $i); // menambahkan tahun ke array
            }
        } else { // jika sorting adalah descending
            for ($i = $end_year; $i >= $start_year; $i--) {
                array_push($years, $i); // menambahkan tahun ke array
            }
        }
        return $years; // mengembalikan array
    }
}

if (!function_exists('arrayToAssoc')) {
    function arrayToAssoc($arr)
    {
        $new_arr = array(); // inisialisasi array kosong
        foreach ($arr as $value) {
            $new_arr[$value] = $value; // menambahkan key dan value ke array baru
        }
        return $new_arr; // mengembalikan array baru
    }
}

if (!function_exists('removeSpecialChars')) {
    function removeSpecialChars($str)
    {
        // menghapus karakter selain huruf, angka, dan spasi
        $str = preg_replace('/[^A-Za-z0-9\s]/', '', $str);
        // menghapus spasi di awal dan akhir string
        $str = trim($str);
        return $str;
    }
}

if (!function_exists('arrayRemoveDuplicate')) {
    function arrayRemoveDuplicate($array1, $array2)
    {
        return array_diff($array1, $array2);
    }
}

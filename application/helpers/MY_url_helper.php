<?php

/**
 * judge is ajax
 */
if ( ! function_exists('_is_ajax'))
{
    function _is_ajax($uri = '', $method = 'location', $http_response_code = 302)
    {
        $CI =& get_instance();

        /*$is_ajax = false;

        $x_requested = $CI->input->server('HTTP_X_REQUESTED_WITH');
        $x_requested = strtolower($x_requested);

        // 確定為ajax請求
        if ($x_requested === 'xmlhttprequest')
        {
            $is_ajax = true;
        }

        return $is_ajax;*/
        return $CI->input->is_ajax_request();
    }
}

/**
 * redirect use new function = my_redirect
 */
if ( ! function_exists('my_redirect'))
{
    function my_redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        // judge is ajax
        if (_is_ajax())
        {
            $rs = new stdClass();
            $rs->redirect = $uri;

            echo json_encode($rs);
            exit;
        }
        else
        {
            if ( ! preg_match('#^https?://#i', $uri))
            {
                $uri = site_url($uri);
            }

            switch($method)
            {
                case 'refresh'  : header("Refresh:0;url=".$uri);
                    break;
                default         : header("Location: ".$uri, TRUE, $http_response_code);
                    break;
            }
        }
        exit;
    }
}

/**
 * 取得當前動作所使用的資料名稱
 */
if ( ! function_exists('get_action_directory'))
{
    function get_action_directory()
    {
        $CI =& get_instance();

        // 當前動作的資料夾
        $directory = str_replace('/', '', $CI->router->directory);

        return $directory;
    }
}
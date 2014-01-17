
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * show error message
 */
if ( ! function_exists('my_show_error'))
{
    function my_show_error($message = '', $status_code = 500, $heading = 'An Error Was Encountered')
    {
        // judge is ajax
        if (_is_ajax())
        {
            $rs = new stdClass();
            $rs->error = true;
            $rs->message = $message;

            echo json_encode($rs);
        }
        else
        {
            $_error =& load_class('Exceptions', 'core');
            echo $_error->show_error($heading, $message, 'error_general', $status_code);
        }
        exit;
    }
}

/**
 * set current language
 */
if ( ! function_exists('set_current_language'))
{
    function set_current_language($id = '')
    {
        $CI =& get_instance();
        $CI->load->model('language_model');
        $CI->load->library('user_agent');

        $list = $CI->language_model->get_all();

        // 語系id
        $lang_id = null;

        foreach($list as $v) {
            $lang_id = $v->id;
            break;
        }

        // robot - 預設第一語系
        if ($CI->agent->is_robot())
        {
            foreach($list as $v) {
                $lang_id = $v->id;
                break;
            }
        }
        // 自訂語系
        else if ($id)
        {
            foreach($list as $v) {
                if ($v->id === $id)
                {
                    $lang_id = $v->id;
                    break;
                }
            }
        }
        // 系統設定
        else
        {
            $old_id = $CI->session->userdata('lang_id');
            // session無值
            if (!isset($old_id))
            {


                foreach($list as $v) {
                    if ($CI->agent->accept_lang($v->browser))
                    {
                        $lang_id = $v->id;
                        break;
                    }
                }
            }
        }

        $CI->session->set_userdata('lang_id', $lang_id);
    }
}
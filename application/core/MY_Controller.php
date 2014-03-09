<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自製化
 */
class MY_Controller extends CI_Controller {


    function __construct()
    {
        parent::__construct();

        // 設定header
        if(preg_match('/application\/json/i', $this->input->server('HTTP_ACCEPT'))){
            $this->output->set_content_type('application/json');
        }

        // 載入my_common
        $this->load->helper('my_common');

        // 設定語系
        set_current_language();

        // 權限檢查
        $this->permissions_check();
    }

    /**
     * 權限檢查
     * @return [type] [description]
     */
    function permissions_check()
    {
        // $this->fb->info($this->router->class);
        // $this->fb->info($this->router->method);
        // 後台首頁不檢查
        // if ()
        // {

        // }
    }
}

/**
 * 後台管理者
 */
class Admin_Controller extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        // 檢查有無登入後台
        if (! $this->admin_login())
        {
            my_redirect('admin/login');
        }

        // 載入jgrid fun
        $this->load->helper('jgrid');
    }

    // 檢查有無登入後台
    function admin_login()
    {
        $bool = true;

        return $bool;
    }

    // 載入預設model
    function load_default_model()
    {
        $class_name = strtolower(get_class($this));
        $model_name = str_replace('admin_', '', $class_name);
        $this->load->model($model_name . '_model', 'post');
    }
}

/**
 * 後台瀏覽者
 */
class Visitor_Controller extends MY_Controller {
    function __construct()
    {
        parent::__construct();
    }
}

/**
 * 前台瀏覽者
 */
class User_Controller extends MY_Controller {
    function __construct()
    {
        parent::__construct();
    }
}

/**
 * 會員
 */
class Member_Controller extends MY_Controller {
    function __construct()
    {
        parent::__construct();
    }
}
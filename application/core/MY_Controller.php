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
    }
}

/**
 * 後台管理者
 */
class Admin_Controller extends MY_Controller {

    var $jqgrid_options;
    var $directory;

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

        // // 建構directory
        // $this->init_directory();

        // // 建構jqgrid options
        // $this->init_jqgrid_options();
    }

    // 檢查有無登入後台
    function admin_login()
    {
        $bool = true;

        return $bool;
    }

    /**
     * 建構directory
     */
    function init_directory()
    {
        $this->directory = str_replace('/', '', $this->router->directory);
    }

    /**
     * 建構jqgrid options
     */
    function init_jqgrid_options()
    {

        $options = new stdClass;
        $options->url = $this->directory . '/list_data';
        $options->editurl = $this->directory . '/edit_data';
        $options->datatype = "json";
        $options->mtype = 'POST';
        $options->colModel = new stdClass;
        $options->rowNum = 10;
        $options->rowList = array(10, 20, 30);
        $options->pager = '#jqGrid-pager';
        $options->sortname = 'id';
        $options->viewrecords = true;
        $options->sortorder = 'desc';
        $options->caption = $this->directory;
        // $options->postData = [];
        $options->height = '100%';

        $this->jqgrid_options = $options;
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
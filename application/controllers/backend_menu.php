<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend_Menu extends Admin_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

        $parent_id = $this->uri->segment(3);

		$colModel = array();
		$colModel[] = get_colmodel(array('name'=> 'id', 'hidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'sort', 'hidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'deleted', 'hidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'parent_id', 'hidden' => true, 'value' => $parent_id, 'edithidden' => false));

		$colModel[] = get_colmodel(array('name'=> 'name__1', 'required' => true));
        $colModel[] = get_colmodel(array('name'=> 'name__2', 'required' => true, 'hidden' => true, 'edithidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'name__3', 'required' => true, 'hidden' => true, 'edithidden' => true));
		$colModel[] = get_colmodel(array('name'=> 'url'));
		$colModel[] = get_colmodel(array('name'=> 'childrens', 'editable' => false, 'formatter' => 'childrens_link'));

		$jgrid_options = get_jgrid_options();
		$jgrid_options->colModel = $colModel;

        $jgrid_options->postData['parent_id'] = $parent_id;

		$rs = new stdClass;
        $rs->fun = 'jqrid';
        $rs->options = $jgrid_options;

        $data['json_data'] = $rs;

		$this->template->render('json', $data);
	}


	/**
	 * 取得列表頁資料
	 * @return [type] [description]
	 */
	public function list_data()
    {
    	$this->load_default_model();

        $info = $this->post->get_list_data();

        $controller = get_action_controller();

        foreach($info->rows as &$v)
        {
        	$v->childrens_url = $controller . '/index/' . $v->id;
        }

        $rs = new stdClass;
        $data['json_data'] = $info;

        $this->template->render('json', $data);
    }

    /**
     * 更新資料
     * @return [type] [description]
     */
    public function edit_data()
    {
    	$this->load_default_model();

        $data = $this->input->post();

        $id = $this->input->post('id');
        if ($data['oper'] === 'add') {
            $rs = $this->post->insert($data);
        } else if ($data['oper'] === 'edit') {
            $rs = $this->post->update($id, $data);
        } else if ($data['oper'] === 'del') {
            $rs = $this->post->delete($id);
        }

        $data['json_data'] = $rs;

        $this->template->render('json', $data);
    }

    /**
     * 後台樹狀選單設定
     * @return [type] [description]
     */
    public function tree()
    {
        $parent_id = $this->input->post('parent_id');

        $colModel = array();
        $colModel[] = get_colmodel(array('name'=> 'id', 'hidden' => true, 'key' => true));

        $colModel[] = get_colmodel(array('name'=> 'name__1', 'viewable' => false));

        $jgrid_options = get_treejgrid_options(array('hidegrid' => false, 'pager' => '', 'ExpandColumn' => 'name__1'));
        $jgrid_options->colModel = $colModel;

        $rs = new stdClass;
        $rs->fun = 'tree_menu';
        $rs->options = $jgrid_options;

        $data['json_data'] = $rs;

        $this->template->render('json', $data);
    }

    /**
     * 取得樹狀資料
     * @return [type] [description]
     */
    public function tree_data()
    {


        $this->load_default_model();

        // 當前動作的資料夾
        // $directory = get_action_directory();

        // $this->load->model($directory . '_model', 'post');

        $info = $this->post->get_tree_data();

        foreach($info->rows as &$v)
        {
            // $v->childrens_url = $directory . '/index/' . $v->id;
        }

        $rs = new stdClass;
        $data['json_data'] = $info->rows;

        $this->template->render('json', $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
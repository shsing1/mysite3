<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends Admin_Controller {

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
        // 取得type 資料
        $this->load->model('type_model');
        $type_list =  $this->type_model->get_all();
        $type_value = convert_editoptions_value($type_list);

		$colModel = array();
		$colModel[] = get_colmodel(array('name'=> 'id', 'hidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'type_id', 'required' => true, 'select' => true, 'value' => $type_value));
		$colModel[] = get_colmodel(array('name'=> 'name__1', 'required' => true));
        $colModel[] = get_colmodel(array('name'=> 'name__2', 'required' => true, 'hidden' => true, 'edithidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'name__3', 'required' => true, 'hidden' => true, 'edithidden' => true));
		$colModel[] = get_colmodel(array('name'=> 'childrens', 'editable' => false, 'formatter' => 'childrens_link'));

		$jgrid_options = get_jgrid_options();
		$jgrid_options->colModel = $colModel;

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
        // 取得type 資料
        $this->load->model('type_model');
        $type_list =  $this->type_model->get_all();
        $type_value = convert_editoptions_value($type_list);

    	$this->load_default_model();

        $info = $this->post->get_list_data();

        foreach($info->rows as &$v)
        {
            $v->type_id = $type_value->{$v->type_id};
        	$v->childrens_url = 'option_item/index/' . $v->id;
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $parent_id = $this->input->post('parent_id');

        $colModel = array();
        $colModel[] = get_colmodel(array('name'=> 'id', 'hidden' => true));
        $colModel[] = get_colmodel(array('name'=> 'parent_id', 'hidden' => true, 'value' => $parent_id, 'edithidden' => false));
        $colModel[] = get_colmodel(array('name'=> 'name', 'required' => true));
        $colModel[] = get_colmodel(array('name'=> 'column_name'));
        $colModel[] = get_colmodel(array('name'=> 'type_id', 'required' => true, 'integer' => true));
        $colModel[] = get_colmodel(array('name'=> 'length', 'integer' => true));
        $colModel[] = get_colmodel(array('name'=> 'nullable', 'checkbox' => true));
        $colModel[] = get_colmodel(array('name'=> 'updatable', 'checkbox' => true, 'defaultValue' => '1'));
        $colModel[] = get_colmodel(array('name'=> 'multilingual', 'checkbox' => true));

        $jgrid_options = get_jgrid_options();
        $jgrid_options->colModel = $colModel;

        $jgrid_options->postData = $this->input->post();

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
        // 當前動作的資料夾
        $directory = get_action_directory();

        $this->load->model($directory . '_model', 'post');

        $info = $this->post->get_list_data();

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
        // 當前動作的資料夾
        $directory = get_action_directory();

        $this->load->model($directory . '_model', 'post');

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
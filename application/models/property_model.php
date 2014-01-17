<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Property_Model extends CHH_Model {
    protected $_table = 'meta_property';
    protected $_drop_id = null;

    protected $before_get = array('set_parent');
    protected $after_get = array('format_data');
    protected $after_create = array('set_sort', 'add_column');
    protected $before_delete = array('set_drop_column');
    protected $after_delete = array('drop_column');

    function set_parent()
    {
        $parent_id = (int)$this->input->post('parent_id');
        if ($parent_id !== 0) {
            $this->db->where('parent_id', (int)$this->input->post('parent_id'));
        }
    }

    /**
     * 自動新增資料表欄位
     * @param int $id [description]
     */
    function add_column($id = null)
    {
        $this->load->dbforge();
        $this->load->model('entity_model');
        $this->load->model('type_model');

        $info = $this->get($id);

        if (!$info->column_name)
        {
            $info->column_name = $info->name;
        }

        $paretn_info = $this->entity_model->get($info->parent_id);
        $type_info = $this->type_model->get($info->type_id);

        // 新增資料表
        if (!$this->db->field_exists($info->column_name, $paretn_info->table_name))
        {
            $column = array();
            $column['type'] = $type_info->column_type;
            $column['constraint'] = $info->length;
            // int, boolean
            if (in_array($info->type_id, array(1, 5))) {
                $column['unsigned'] = true;
            }

            $fields = array();
            // multilingual
            if ((boolean)$info->multilingual) {
                $this->load->model('language_model');
                $language_list = $this->language_model->get_all();
                foreach ($language_list as $v) {
                    $fields[$info->column_name . '__' . $v->id] = $column;
                }
            } else {
                $fields[$info->column_name] = $column;
            }

            $this->dbforge->add_column($paretn_info->table_name, $fields);
        }
        return $id;
    }

    /**
     * 設定欲刪除的id
     * @param int $id [description]
     */
    function set_drop_column($id = null)
    {
        $this->_drop_id = $id;
    }

    /**
     * 自動刪除欄位
     * @param  boolean $result [description]
     */
    function drop_column($result = false)
    {
        if ($result) {
            $this->load->dbforge();

            $this->set_soft_delete(false);
            $info = $this->get($this->_drop_id);

            // $this->fb->info($info);

            // 有該筆資料
            if ($info) {
                // delete record
                $this->delete($info->id);

                $this->load->model('entity_model');
                $paretn_info = $this->entity_model->get($info->parent_id);

                // drop column
                $this->dbforge->drop_column($paretn_info->table_name, $info->column_name);
            }
        }
    }

    /**
     * 格式化資料
     * @param  object $row 資料庫資料
     * @return [type]      [description]
     */
    function format_data($row)
    {
        return $row;
    }
}
?>
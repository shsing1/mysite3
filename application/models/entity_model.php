<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Entity_Model extends CHH_Model {
    protected $_table = 'meta_entity';
    protected $_drop_id = null;

    protected $list_count_childrens = true;
    protected $after_create = array('create_table');
    protected $before_delete = array('set_drop_table');
    protected $after_delete = array('drop_table');

    function count_childrens()
    {
        $this->load->model('property_model');

        $this->select("*, (select count(*) FROM `".$this->db->dbprefix($this->property_model->table())."` WHERE `parent_id` = `".$this->db->dbprefix($this->_table)."`.`id`) AS `childrens`");
    }

    /**
     * 自動新增資料表
     * @param  int $id entity新增的id
     * @return [type]     [description]
     */
    function create_table($id = null)
    {
        $info = $this->get($id);
        $this->load->dbforge();

        $fields = array(
                        'id' => array(
                            'type' => 'INT',
                            'constraint' => 10,
                            'unsigned' => TRUE,
                            'auto_increment' => TRUE
                        ),
                        'deleted' => array(
                                'type' => 'tinyint',
                                'constraint' => 1,
                                'default' => 0
                          )
                );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($info->table_name, TRUE);
    }

    /**
     * 設定欲刪除的id
     * @param int $id [description]
     */
    function set_drop_table($id = null)
    {
        $this->_drop_id = $id;

        return $id;
    }

    /**
     * 自動刪除資料表
     * @param  boolean $result [description]
     */
    function drop_table($result = false)
    {
        if ($result) {
            $this->load->dbforge();

            $this->set_soft_delete(false);
            $info = $this->get($this->_drop_id);
            $this->fb->info($info);

            if ($info)
            {
                // delete record
                $this->delete($info->id);

                // drop table
                $this->dbforge->drop_table($info->table_name);

                // delete property
                $this->load->model('property_model');
                $this->property_model->set_soft_delete(false);
                $property_list = $this->property_model->get_many_by('parent_id', $this->_drop_id);
                foreach ($property_list as $v)
                {
                    $this->property_model->delete($v->id);
                }
            }
        }
    }
}
?>
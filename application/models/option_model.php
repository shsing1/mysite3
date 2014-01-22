<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Option_Model extends CHH_Model {
    protected $_table = 'meta_option';
    protected $_drop_id = null;

    protected $list_count_childrens = true;

    function count_childrens()
    {
        $this->load->model('option_item_model', 'children_model');

        $this->select("*, (select count(*) FROM `".$this->db->dbprefix($this->children_model->table())."` WHERE `parent_id` = `".$this->db->dbprefix($this->_table)."`.`id`) AS `childrens`");
    }
}
?>
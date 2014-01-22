<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Option_Item_Model extends CHH_Model {
    protected $_table = 'meta_option_item';

    protected $before_get = array('set_parent');

    function set_parent()
    {
        $parent_id = (int)$this->input->post('parent_id');
        if ($parent_id !== 0) {
            $this->db->where('parent_id', (int)$this->input->post('parent_id'));
        }
    }
}
?>
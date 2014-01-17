<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Backend_Menu_Model extends Category_Model {
    protected $_table = 'backend_menu';

    protected $leafnodes = array();

    /**
     * 取得樹狀資料
     * @return [type] [description]
     */
    public function get_tree_data()
    {

        $table_name = $this->db->dbprefix($this->_table);
        // 尾節點資料
        // $sql = "SELECT t1.id
        //           FROM " . $table_name . " AS t1
        //      LEFT JOIN " . $table_name . " AS t2
        //             ON t1.id = t2.parent_id
        //          WHERE t2.id IS NULL";

        $this->db->select($table_name.'.id');
        $this->db->from($table_name);
        $this->db->join($table_name . ' AS t2', $table_name.'.id = t2.parent_id', 'left');
        $this->db->where('t2.id IS NULL');

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($table_name.'.' .$this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $query = $this->db->get();

        foreach($query->result() as $row)
        {
           $this->leafnodes[$row->id] = $row->id;
        }

        // $this->fb->info($this->leafnodes);

        // 一次取得所有的節點
        $rows = $this->get_all_node('', 0);
        // $this->fb->info($rows);

        /*$page = (int)$this->input->post('page');
        $limit = (int)$this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $this->db->select('count(id) AS records');
        $rows = $this->get_all();
        $records = 0;
        foreach($rows as $row){
            $records = $row->records;
        }
        if ( $records > 0 ) {
            $total_pages = ceil($records / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = $limit * $page - $limit;
        if ($start < 0) {
            $start = 0;
        }
        if ($this->get_list_count_childrens()) {
            $this->count_childrens();
        }

        // 有指定排序
        if ($sidx && $sord)
        {
            $this->order_by($sidx, $sord);
        }

        $this->order_by($this->order_by, $this->order_sort);

        $this->limit($limit, $start);
        $rows = $this->get_all();*/

        $page = 1;
        $total_pages = 1;
        $records = count($rows);

        $info = new stdClass;

        $info->page = $page;
        $info->total = $total_pages;
        $info->records = $records;
        $info->rows = $rows;

        return $info;
    }

    /**
     * 一次取得所有節點
     * @return [type] [description]
     */
    public function get_all_node($parent = null, $level = 0)
    {
        $table_name = $this->db->dbprefix($this->_table);

        $this->db->from($table_name);
        // $this->db->where('parent_id', $parent);

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $query = $this->db->get();

        // $this->fb->info($parent, 'parent');
        // $list = $this->get_many_by('parent_id', $parent);

        $list = array();
        $sublist = array();
        foreach($query->result() as $v)
        {
            $v->leaf = false;
            if (isset($this->leafnodes[$v->id]))
            {
                $v->leaf = true;
            }
// $this->fb->info($v, '$v');
            $v->expanded = false;
            $v->level = $level;

            // $sublist = $this->get_all_node($v->id, $level + 1);

            $item = new stdClass;
            $item->id = 'jstree_' . $v->id;
            $item->parent = empty($v->parent_id) ? '#' : 'jstree_' . $v->parent_id;
            $item->text = $v->name__1;
            if ($v->leaf) {
                $item->a_attr = array('href' => $v->url);
            }
            $item->state = array('opened' => true);

            $list[] = $item;
        }
// $this->fb->info($list, 'list');
// $this->fb->info($sublist, 'sublist');
        // $list = array_merge($list, $sublist);


        return $list;

    }
}
?>
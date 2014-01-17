<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class MY_Model extends CI_Model
{

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * This model's default database table. Automatically
     * guessed by pluralising the model name.
     */
    protected $_table;

    /**
     * The database connection object. Will be set to the default
     * connection. This allows individual models to use different DBs
     * without overwriting CI's global $this->db connection.
     */
    public $_database;

    /**
     * This model's default primary key or unique identifier.
     * Used by the get(), update() and delete() functions.
     */
    protected $primary_key = 'id';

    /**
     * Support for soft deletes and this model's 'deleted' key
     */
    protected $soft_delete = FALSE;
    protected $soft_delete_key = 'deleted';
    protected $_temporary_with_deleted = FALSE;
    protected $_temporary_only_deleted = FALSE;

    /**
     * The various callbacks available to the model. Each are
     * simple lists of method names (methods will be run on $this).
     */
    protected $before_create = array();
    protected $after_create = array();
    protected $before_update = array();
    protected $after_update = array();
    protected $before_get = array();
    protected $after_get = array();
    protected $before_delete = array();
    protected $after_delete = array();

    protected $callback_parameters = array();

    /**
     * Protected, non-modifiable attributes
     */
    protected $protected_attributes = array();

    /**
     * Relationship arrays. Use flat strings for defaults or string
     * => array to customise the class name and primary key
     */
    protected $belongs_to = array();
    protected $has_many = array();

    protected $_with = array();

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     */
    protected $validate = array();

    /**
     * Optionally skip the validation. Used in conjunction with
     * skip_validation() to skip data validation for any future calls.
     */
    protected $skip_validation = FALSE;

    /**
     * By default we return our results as objects. If we need to override
     * this, we can, or, we could use the `as_array()` and `as_object()` scopes.
     */
    protected $return_type = 'object';
    protected $_temporary_return_type = NULL;

    /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the model, tie into the CodeIgniter superobject and
     * try our best to guess the table name.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('inflector');

        $this->_fetch_table();
        $this->_database = $this->db;

        array_unshift($this->before_create, 'protect_attributes');
        array_unshift($this->before_update, 'protect_attributes');

        $this->_temporary_return_type = $this->return_type;
    }

    /* --------------------------------------------------------------
     * CRUD INTERFACE
     * ------------------------------------------------------------ */

    /**
     * Fetch a single record based on the primary key. Returns an object.
     */
    public function get($primary_value)
    {
        $this->trigger('before_get');

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $row = $this->_database->where($this->primary_key, $primary_value)
                        ->get($this->_table)
                        ->{$this->_return_type()}();
        $this->_temporary_return_type = $this->return_type;

        $row = $this->trigger('after_get', $row);

        $this->_with = array();
        return $row;
    }

    /**
     * Fetch a single record based on an arbitrary WHERE call. Can be
     * any valid value to $this->_database->where().
     */
    public function get_by()
    {
        $where = func_get_args();
        $this->_set_where($where);

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $this->trigger('before_get');

        $row = $this->_database->get($this->_table)
                        ->{$this->_return_type()}();
        $this->_temporary_return_type = $this->return_type;

        $row = $this->trigger('after_get', $row);

        $this->_with = array();
        return $row;
    }

    /**
     * Fetch an array of records based on an array of primary values.
     */
    public function get_many($values)
    {
        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $this->_database->where_in($this->primary_key, $values);

        return $this->get_all();
    }

    /**
     * Fetch an array of records based on an arbitrary WHERE call.
     */
    public function get_many_by()
    {
        $where = func_get_args();
        $this->_set_where($where);

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        return $this->get_all();
    }

    /**
     * Fetch all the records in the table. Can be used as a generic call
     * to $this->_database->get() with scoped methods.
     */
    public function get_all()
    {
        $this->trigger('before_get');

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $result = $this->_database->get($this->_table)
                           ->{$this->_return_type(1)}();
        $this->_temporary_return_type = $this->return_type;

        foreach ($result as $key => &$row)
        {
            $row = $this->trigger('after_get', $row, ($key == count($result) - 1));
        }

        $this->_with = array();
        return $result;
    }

    /**
     * Insert a new row into the table. $data should be an associative array
     * of data to be inserted. Returns newly created ID.
     */
    public function insert($data, $skip_validation = FALSE)
    {
        if ($skip_validation === FALSE)
        {
            $data = $this->validate($data);
        }

        if ($data !== FALSE)
        {
            $data = $this->trigger('before_create', $data);

            $this->_database->insert($this->_table, $data);
            $insert_id = $this->_database->insert_id();

            $this->trigger('after_create', $insert_id);

            return $insert_id;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Insert multiple rows into the table. Returns an array of multiple IDs.
     */
    public function insert_many($data, $skip_validation = FALSE)
    {
        $ids = array();

        foreach ($data as $key => $row)
        {
            $ids[] = $this->insert($row, $skip_validation, ($key == count($data) - 1));
        }

        return $ids;
    }

    /**
     * Updated a record based on the primary value.
     */
    public function update($primary_value, $data, $skip_validation = FALSE)
    {
        $data = $this->trigger('before_update', $data);

        if ($skip_validation === FALSE)
        {
            $data = $this->validate($data);
        }

        if ($data !== FALSE)
        {
            $result = $this->_database->where($this->primary_key, $primary_value)
                               ->set($data)
                               ->update($this->_table);

            $this->trigger('after_update', array($data, $result));

            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Update many records, based on an array of primary values.
     */
    public function update_many($primary_values, $data, $skip_validation = FALSE)
    {
        $data = $this->trigger('before_update', $data);

        if ($skip_validation === FALSE)
        {
            $data = $this->validate($data);
        }

        if ($data !== FALSE)
        {
            $result = $this->_database->where_in($this->primary_key, $primary_values)
                               ->set($data)
                               ->update($this->_table);

            $this->trigger('after_update', array($data, $result));

            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Updated a record based on an arbitrary WHERE clause.
     */
    public function update_by()
    {
        $args = func_get_args();
        $data = array_pop($args);

        $data = $this->trigger('before_update', $data);

        if ($this->validate($data) !== FALSE)
        {
            $this->_set_where($args);
            $result = $this->_database->set($data)
                               ->update($this->_table);
            $this->trigger('after_update', array($data, $result));

            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Update all records
     */
    public function update_all($data)
    {
        $data = $this->trigger('before_update', $data);
        $result = $this->_database->set($data)
                           ->update($this->_table);
        $this->trigger('after_update', array($data, $result));

        return $result;
    }

    /**
     * Delete a row from the table by the primary value
     */
    public function delete($id)
    {
        $this->trigger('before_delete', $id);

        $this->_database->where($this->primary_key, $id);

        if ($this->soft_delete)
        {
            $result = $this->_database->update($this->_table, array( $this->soft_delete_key => TRUE ));
        }
        else
        {
            $result = $this->_database->delete($this->_table);
        }

        $this->trigger('after_delete', $result);

        return $result;
    }

    /**
     * Delete a row from the database table by an arbitrary WHERE clause
     */
    public function delete_by()
    {
        $where = func_get_args();

	    $where = $this->trigger('before_delete', $where);

        $this->_set_where($where);


        if ($this->soft_delete)
        {
            $result = $this->_database->update($this->_table, array( $this->soft_delete_key => TRUE ));
        }
        else
        {
            $result = $this->_database->delete($this->_table);
        }

        $this->trigger('after_delete', $result);

        return $result;
    }

    /**
     * Delete many rows from the database table by multiple primary values
     */
    public function delete_many($primary_values)
    {
        $primary_values = $this->trigger('before_delete', $primary_values);

        $this->_database->where_in($this->primary_key, $primary_values);

        if ($this->soft_delete)
        {
            $result = $this->_database->update($this->_table, array( $this->soft_delete_key => TRUE ));
        }
        else
        {
            $result = $this->_database->delete($this->_table);
        }

        $this->trigger('after_delete', $result);

        return $result;
    }


    /**
     * Truncates the table
     */
    public function truncate()
    {
        $result = $this->_database->truncate($this->_table);

        return $result;
    }

    /* --------------------------------------------------------------
     * RELATIONSHIPS
     * ------------------------------------------------------------ */

    public function with($relationship)
    {
        $this->_with[] = $relationship;

        if (!in_array('relate', $this->after_get))
        {
            $this->after_get[] = 'relate';
        }

        return $this;
    }

    public function relate($row)
    {
		if (empty($row))
        {
		    return $row;
        }

        foreach ($this->belongs_to as $key => $value)
        {
            if (is_string($value))
            {
                $relationship = $value;
                $options = array( 'primary_key' => $value . '_id', 'model' => $value . '_model' );
            }
            else
            {
                $relationship = $key;
                $options = $value;
            }

            if (in_array($relationship, $this->_with))
            {
                $this->load->model($options['model'], $relationship . '_model');

                if (is_object($row))
                {
                    $row->{$relationship} = $this->{$relationship . '_model'}->get($row->{$options['primary_key']});
                }
                else
                {
                    $row[$relationship] = $this->{$relationship . '_model'}->get($row[$options['primary_key']]);
                }
            }
        }

        foreach ($this->has_many as $key => $value)
        {
            if (is_string($value))
            {
                $relationship = $value;
                $options = array( 'primary_key' => singular($this->_table) . '_id', 'model' => singular($value) . '_model' );
            }
            else
            {
                $relationship = $key;
                $options = $value;
            }

            if (in_array($relationship, $this->_with))
            {
                $this->load->model($options['model'], $relationship . '_model');

                if (is_object($row))
                {
                    $row->{$relationship} = $this->{$relationship . '_model'}->get_many_by($options['primary_key'], $row->{$this->primary_key});
                }
                else
                {
                    $row[$relationship] = $this->{$relationship . '_model'}->get_many_by($options['primary_key'], $row[$this->primary_key]);
                }
            }
        }

        return $row;
    }

    /* --------------------------------------------------------------
     * UTILITY METHODS
     * ------------------------------------------------------------ */

    /**
     * Retrieve and generate a form_dropdown friendly array
     */
    function dropdown()
    {
        $args = func_get_args();

        if(count($args) == 2)
        {
            list($key, $value) = $args;
        }
        else
        {
            $key = $this->primary_key;
            $value = $args[0];
        }

        $this->trigger('before_dropdown', array( $key, $value ));

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE)
        {
            $this->_database->where($this->soft_delete_key, FALSE);
        }

        $result = $this->_database->select(array($key, $value))
                           ->get($this->_table)
                           ->result();

        $options = array();

        foreach ($result as $row)
        {
            $options[$row->{$key}] = $row->{$value};
        }

        $options = $this->trigger('after_dropdown', $options);

        return $options;
    }

    /**
     * Fetch a count of rows based on an arbitrary WHERE call.
     */
    public function count_by()
    {
        $where = func_get_args();
        $this->_set_where($where);

        return $this->_database->count_all_results($this->_table);
    }

    /**
     * Fetch a total count of rows, disregarding any previous conditions
     */
    public function count_all()
    {
        return $this->_database->count_all($this->_table);
    }

    /**
     * Tell the class to skip the insert validation
     */
    public function skip_validation()
    {
        $this->skip_validation = TRUE;
        return $this;
    }

    /**
     * Get the skip validation status
     */
    public function get_skip_validation()
    {
        return $this->skip_validation;
    }

    /**
     * Return the next auto increment of the table. Only tested on MySQL.
     */
    public function get_next_id()
    {
        return (int) $this->_database->select('AUTO_INCREMENT')
            ->from('information_schema.TABLES')
            ->where('TABLE_NAME', $this->_table)
            ->where('TABLE_SCHEMA', $this->_database->database)->get()->row()->AUTO_INCREMENT;
    }

    /**
     * Getter for the table name
     */
    public function table()
    {
        return $this->_table;
    }

    /* --------------------------------------------------------------
     * GLOBAL SCOPES
     * ------------------------------------------------------------ */

    /**
     * Return the next call as an array rather than an object
     */
    public function as_array()
    {
        $this->_temporary_return_type = 'array';
        return $this;
    }

    /**
     * Return the next call as an object rather than an array
     */
    public function as_object()
    {
        $this->_temporary_return_type = 'object';
        return $this;
    }

    /**
     * Don't care about soft deleted rows on the next call
     */
    public function with_deleted()
    {
        $this->_temporary_with_deleted = TRUE;
        return $this;
    }

    /**
     * Only get deleted rows on the next call
     */
    public function only_deleted()
    {
        $this->_temporary_only_deleted = TRUE;
        return $this;
    }

    /* --------------------------------------------------------------
     * OBSERVERS
     * ------------------------------------------------------------ */

    /**
     * MySQL DATETIME created_at and updated_at
     */
    public function created_at($row)
    {
        if (is_object($row))
        {
            $row->created_at = date('Y-m-d H:i:s');
        }
        else
        {
            $row['created_at'] = date('Y-m-d H:i:s');
        }

        return $row;
    }

    public function updated_at($row)
    {
        if (is_object($row))
        {
            $row->updated_at = date('Y-m-d H:i:s');
        }
        else
        {
            $row['updated_at'] = date('Y-m-d H:i:s');
        }

        return $row;
    }

    /**
     * Serialises data for you automatically, allowing you to pass
     * through objects and let it handle the serialisation in the background
     */
    public function serialize($row)
    {
        foreach ($this->callback_parameters as $column)
        {
            $row[$column] = serialize($row[$column]);
        }

        return $row;
    }

    public function unserialize($row)
    {
        foreach ($this->callback_parameters as $column)
        {
            if (is_array($row))
            {
                $row[$column] = unserialize($row[$column]);
            }
            else
            {
                $row->$column = unserialize($row->$column);
            }
        }

        return $row;
    }

    /**
     * Protect attributes by removing them from $row array
     */
    public function protect_attributes($row)
    {
        foreach ($this->protected_attributes as $attr)
        {
            if (is_object($row))
            {
                unset($row->$attr);
            }
            else
            {
                unset($row[$attr]);
            }
        }

        return $row;
    }

    /* --------------------------------------------------------------
     * QUERY BUILDER DIRECT ACCESS METHODS
     * ------------------------------------------------------------ */

    /**
     * A wrapper to $this->_database->order_by()
     */
    public function order_by($criteria, $order = 'ASC')
    {
        if ( is_array($criteria) )
        {
            foreach ($criteria as $key => $value)
            {
                $this->_database->order_by($key, $value);
            }
        }
        else
        {
            $this->_database->order_by($criteria, $order);
        }
        return $this;
    }

    /**
     * A wrapper to $this->_database->limit()
     */
    public function limit($limit, $offset = 0)
    {
        $this->_database->limit($limit, $offset);
        return $this;
    }

    /* --------------------------------------------------------------
     * INTERNAL METHODS
     * ------------------------------------------------------------ */

    /**
     * Trigger an event and call its observers. Pass through the event name
     * (which looks for an instance variable $this->event_name), an array of
     * parameters to pass through and an optional 'last in interation' boolean
     */
    public function trigger($event, $data = FALSE, $last = TRUE)
    {
        if (isset($this->$event) && is_array($this->$event))
        {
            foreach ($this->$event as $method)
            {
                if (strpos($method, '('))
                {
                    preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);

                    $method = $matches[1];
                    $this->callback_parameters = explode(',', $matches[3]);
                }

                $data = call_user_func_array(array($this, $method), array($data, $last));
            }
        }

        return $data;
    }

    /**
     * Run validation on the passed data
     */
    public function validate($data)
    {
        if($this->skip_validation)
        {
            return $data;
        }

        if(!empty($this->validate))
        {
            foreach($data as $key => $val)
            {
                $_POST[$key] = $val;
            }

            $this->load->library('form_validation');

            if(is_array($this->validate))
            {
                $this->form_validation->set_rules($this->validate);

                if ($this->form_validation->run() === TRUE)
                {
                    return $data;
                }
                else
                {
                    return FALSE;
                }
            }
            else
            {
                if ($this->form_validation->run($this->validate) === TRUE)
                {
                    return $data;
                }
                else
                {
                    return FALSE;
                }
            }
        }
        else
        {
            return $data;
        }
    }

    /**
     * Guess the table name by pluralising the model name
     */
    private function _fetch_table()
    {
        if ($this->_table == NULL)
        {
            $this->_table = plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
        }
    }

    /**
     * Set WHERE parameters, cleverly
     */
    protected function _set_where($params)
    {
        if (count($params) == 1)
        {
            $this->_database->where($params[0]);
        }
    	else if(count($params) == 2)
		{
			$this->_database->where($params[0], $params[1]);
		}
		else if(count($params) == 3)
		{
			$this->_database->where($params[0], $params[1], $params[2]);
		}
        else
        {
            $this->_database->where($params);
        }
    }

    /**
     * Return the method name for the current return type
     */
    protected function _return_type($multi = FALSE)
    {
        $method = ($multi) ? 'result' : 'row';
        return $this->_temporary_return_type == 'array' ? $method . '_array' : $method;
    }
}

class CHH_Model extends MY_Model
{
    protected $soft_delete = TRUE;

    protected $before_create = array('match_fields');
    protected $after_create = array('set_sort');
    protected $before_update = array('match_fields');

    protected $list_count_childrens = false;

    protected $order_by = 'sort';
    protected $order_sort = 'asc';

    /**
     * 匹配資料與資料庫欄位
     * @param  array $data 欲進資料庫資料
     * @return array       處理後資料
     */
    public function match_fields($data)
    {
        $new_data = array();
        foreach($data as $key => $val)
        {
            if ($key !== $this->primary_key) {
                if ($this->db->field_exists($key, $this->_table))
                {
                   $new_data[$key] = $val;
                }
            }
        }
        return $new_data;
    }

    public function select($select = '*', $escape = NULL){
        $this->db->select($select, $escape);
    }

    public function get_list_count_childrens() {
        return $this->list_count_childrens;
    }

    public function get_property_list()
    {
        $query  = $this->db->select('*')->from('meta_entity')->join($this->db->dbprefix('meta_property'), ''.$this->db->dbprefix('meta_property').'.parent_id = '.$this->db->dbprefix('meta_entity').'.id')->where('table_name', $this->_table)->get();

        $list = array();
        foreach ($query->result() as $row)
        {
            $list[] = $row;
        }

        return $list;
    }
    public function get_col_model() {
        $property_list = $this->get_property_list();

        $list = array();
        foreach ($property_list as $row)
        {
            $col = new stdClass;
            $col->id = $row->name;
            $col->name = $row->name;
            $col->label = $row->name;
            $col->index = $row->column_name;


            $col->editable = (boolean)$row->editable;

            $type_id = (int)$row->type_id;

            $col->editrules = new stdClass;
            // 可以編輯
            if ($col->editable) {
                // $editrules = new stdClass;
                $col->editrules->required = !(boolean)$row->nullable;

                // 數字
                if ($type_id === 1) {
                    $col->editrules->integer = true;
                // 布林
                } else if ($type_id === 3) {
                    $col->edittype = 'checkbox';
                    $col->editoptions = json_decode('{"value" : "1:0"}');
                // 浮點數
                } else if ($type_id === 4) {
                    $col->editrules->number = true;
                // 網址
                } else if ($type_id === 6) {
                    $col->editrules->url = true;
                // email
                } else if ($type_id === 7) {
                    $col->editrules->email = true;
                }

                // $col->editrules = $editrules;
            } else {
                $col->hidden = true;
            }

            // // 數字
            // if ($type_id === 1) {
            //     $col->width = 50;
            // // 布林
            // } else if ($type_id === 3) {
            //     $col->width = 50;
            // // 浮點數
            // } else if ($type_id === 4) {
            //     $col->width = 50;
            // }

            // if (isset($row->width) && $row->width !== null) {
            //     $col->width = $row->width;
            // }

            // 多語系欄位
            if ((boolean)$row->multilingual) {
                $this->load->model('language_model');
                $language_list = $this->language_model->get_all();
                foreach ($language_list as $v2) {
                    $col2 = json_decode(json_encode($col));

                    $col2->name = $row->column_name. '__' . $v2->id;
                    $col2->label = $row->column_name. '(' . $v2->name . ')';
                    $col2->index = $row->column_name. '__' . $v2->id;

                    // 等於當前語系
                    if ($v2->id === $this->session->userdata('current_language')->id) {
                        $col2->hidden = false;
                    } else {
                        $col2->hidden = true;
                        $col2->editrules->edithidden = true;

                    }
                    $list[] = $col2;
                }
            } else {
                 $list[] = $col;
            }

        }
        // $this->fb->info($row);
        return $list;
    }

    public function set_soft_delete ($boolean = true)
    {
        $this->soft_delete = $boolean;
    }

    /**
     * 取得頁碼資訊
     * @param  array $list 全部資料數
     * @return object
     */
    public function get_list_data() {

        // $this->load->model($this->router->fetch_module() . '_model', 'post');

        $page = (int)$this->input->post('page');
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
        $rows = $this->get_all();

        $info = new stdClass;

        $info->page = $page;
        $info->total = $total_pages;
        $info->records = $records;
        $info->rows = $rows;

        return $info;
    }

    /**
     * 資料新增後更新排序字
     * @param [type] $id [description]
     */
    function set_sort($id = null)
    {
        // $this->fb->info($id);
        $data = $this->get($id);
        $data->sort = $id;
        $this->update($id, $data);

        return $id;
    }
}

class Category_Model extends CHH_Model
{
    protected $list_count_childrens = true;

    protected $before_get = array('set_parent');

    protected $data_model = '';

    // 層數
    protected $depth = 1;

    /**
     * 統計下層數量
     * @return [type] [description]
     */
    function count_childrens()
    {
        $parent_id = (int)$this->input->post('parent_id');
        $this->select("*, (select count(*) FROM `".$this->db->dbprefix($this->_table)."` AS `children_table` WHERE `children_table`.`parent_id` = `".$this->db->dbprefix($this->_table)."`.`id`) AS `childrens`");
    }

    /**
     * 指定上層id
     */
    function set_parent()
    {
        $parent_id = (int)$this->input->post('parent_id');

        $this->db->where('parent_id', $parent_id);
    }
}

class CHH_TREE_Model extends CHH_Model
{
    protected $before_get = array('ignored_root', 'set_parent', 'set_order_by');
    protected $order_by = array(
                                array('lft', 'ASC')
                            );

    public function __construct()
    {
        parent::__construct();

        $count_all = $this->count_all();

        // 新增root節點
        if ($count_all === 0)
        {
            $property_list = $this->get_property_list();

            $data = array(
                    'lft' => 1,
                    'rgt' => 2
                );
            foreach ($property_list as $v)
            {
                if ($v->column_name === 'name')
                {
                    if ((boolean)$v->multilingual === true)
                    {
                        $this->load->model('language_model');
                        $language_list = $this->language_model->get_all();
                        foreach ($language_list as $v2) {
                            $data[$v->column_name . '__' . $v2->id] = 'ROOT';
                        }
                    } else
                    {
                        $data[$v->column_name] = '';
                    }
                }
            }
            $this->fb->info($property_list);
            // $data = array(
            //         'name' => 'ROOT',
            //         'lft' => 1,
            //         'rgt' => 2
            //     );
            $this->insert($data);
        }
    }

    /**
     * 取得頁碼資訊
     * @param  array $list 全部資料數
     * @return object
     */
    function get_page_info() {
        $info = parent::get_page_info();

        $rows = $info->rows;

        foreach ($rows as $row) {
            $row->childrens = $this->get_childrens_count($row->id);
        }

        $info->rows = $rows;

        return $info;
    }

    function get_childrens_count($id = null)
    {
        $sql = "SELECT COUNT(*) AS count
                  FROM (SELECT node.id,
                              (COUNT(parent.id) - (sub_tree.depth + 1)) AS depth
                         FROM ".$this->db->dbprefix($this->_table)." AS node,
                              ".$this->db->dbprefix($this->_table)." AS parent,
                              ".$this->db->dbprefix($this->_table)." AS sub_parent,
                              ( SELECT node.id,
                                       (COUNT(parent.id) - 1) AS depth
                                  FROM ".$this->db->dbprefix($this->_table)." AS node,
                                       ".$this->db->dbprefix($this->_table)." AS parent
                                 WHERE node.lft BETWEEN parent.lft AND parent.rgt
                                   AND node.id = " . $id . "
                              GROUP BY node.id
                              ORDER BY node.lft
                              ) AS sub_tree
                        WHERE node.lft BETWEEN parent.lft AND parent.rgt
                          AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
                          AND sub_parent.id = sub_tree.id
                     GROUP BY node.id
                       HAVING depth = 1
                     ORDER BY node.lft
                  ) AS `childrens`";

        $query = $this->db->query($sql);

        $count = 0;
        foreach ($query->result() as $row)
        {
            $count = $row->count;
        }
        return $count;
    }


    /**
     * 排除root節點
     * @return [type] [description]
     */
    public function ignored_root()
    {
        $this->db->where('id !=', 1);
    }

    function set_parent()
    {
        $parent_id = (int)$this->input->post('parent_id');
        $level = 1;
        $this->fb->info($parent_id);
        if ($parent_id !== 0) {
            // 取得父節點的info
            $parent_info = $this->post->get($parent_id);
            if ($parent_info) {
                $level = $parent_info->level + 1;
            }
        }
        // $this->db->where('level', $level);
    }

    public function set_order_by()
    {
        foreach($this->order_by as $v)
        {
            $this->db->order_by($v[0], $v[1]);
        }
    }

    /**
     * 新增多層分類節點
     * @param  array   $data            [description]
     * @param  boolean $skip_validation [description]
     * @return intege                   [description]
     */
    public function insert($data, $skip_validation = FALSE)
    {
        if ($skip_validation === FALSE)
        {
            $data = $this->validate($data);
        }

        if ($data !== FALSE)
        {
            $data = $this->trigger('before_create', $data);

            $parent_id = $this->input->post('parent_id');
            if (!$parent_id) {
                $parent_id = 1;
            }

            // 取得父節點
            $this->before_get = array();
            $parent_info = $this->get($parent_id);
            $this->fb->info($parent_info);

            // 鎖住資料表
            $this->db->query('LOCK TABLE '.$this->db->dbprefix($this->_table).' WRITE;');


            // 空出位置
            $this->db->query('UPDATE '.$this->db->dbprefix($this->_table).' SET rgt = rgt + 2 WHERE rgt > ?;', $parent_info->lft);
            $this->db->query('UPDATE '.$this->db->dbprefix($this->_table).' SET lft = lft + 2 WHERE lft > ?;', $parent_info->lft);

            $this->fb->info($data);
            $data['lft'] = (int)$parent_info->lft + 1;
            $data['rgt'] = (int)$parent_info->lft + 2;
            // 新增資料
            $this->_database->insert($this->_table, $data);
            $insert_id = $this->_database->insert_id();
            $this->fb->info($data);

            // 解鎖
            $this->db->query('UNLOCK TABLES;');


            $this->trigger('after_create', $insert_id);

            return $insert_id;
        }
        else
        {
            return FALSE;
        }
    }
}
<?php

/**
 * 取得jgrid options
 */
if ( ! function_exists('get_jgrid_options'))
{
    function get_jgrid_options($setting = null)
    {
        $CI =& get_instance();

        if (!$setting) {
            $setting = new stdClass;
        }
        else if (is_array($setting))
        {
            $setting = (object)$setting;
        }

        // 當前動作的controller
        $controller = get_action_controller();

        $options = new stdClass;
        $options->url = $CI->config->item('base_url') . '/' . $controller . '/list_data';
        $options->editurl = $CI->config->item('base_url') . '/' .  $controller . '/edit_data';
        $options->datatype = "json";
        $options->mtype = 'POST';
        $options->colModel = new stdClass;
        $options->hidegrid = false;
        $options->rownumbers = true;
        $options->rowNum = 10;
        $options->rowList = array(10, 20, 30);
        $options->pager = '#jqGrid-pager';
        $options->sortname = 'sort';
        $options->viewrecords = true;
        $options->sortorder = 'asc';
        $options->caption = $controller;
        $options->height = '100%';

        // 覆寫預設值
        foreach($setting as $k=>$v)
        {
            $options->{$k} = $v;
        }

        return $options;
    }
}

/**
 * 取得jgrid ColModel
 */
if ( ! function_exists('get_jgrid_colmodel'))
{
    function get_jgrid_colmodel($setting = null)
    {
        if (!$setting)
        {
            $setting = new stdClass;
        }

        $editrules = array();
        $editoptions = array();

        // 設定為必填欄位
        if (isset($setting->required))
        {
            $editrules['required'] = $setting->required;
            unset($setting->required);
        }

        // 設定為數字欄位
        if (isset($setting->integer))
        {
            $editrules['integer'] = $setting->integer;
            unset($setting->integer);
        }

        // 設定為checkbox
        if (isset($setting->checkbox))
        {
            $setting->edittype = 'checkbox';
            $editoptions['value'] = '1:0';
        }

        // 設定為select
        if (isset($setting->select))
        {
            $setting->edittype = 'select';
        }

        // 有設定預設值
        if (isset($setting->value))
        {
            $editoptions['value'] = $setting->value;
            unset($setting->value);
        }

        // 有設定預設值
        if (isset($setting->defaultValue))
        {
            $editoptions['defaultValue'] = $setting->defaultValue;
            unset($setting->defaultValue);
        }

        // 編輯隱藏
        if (isset($setting->edithidden))
        {
            $editrules['edithidden'] = $setting->edithidden;
            unset($setting->edithidden);
        }

        $options = new stdClass;
        $options->editable = true;

        if ($editrules) {
            $options->editrules = $editrules;
        }

        if ($editoptions) {
            $options->editoptions = $editoptions;
        }

        // 覆寫預設值
        foreach($setting as $k=>$v)
        {
            $options->{$k} = $v;
        }

        return $options;
    }
}

/**
 * 取得ColModel
 */
if ( ! function_exists('get_colmodel'))
{
    function get_colmodel($setting = null)
    {
        if (!$setting)
        {
            $setting = new stdClass;
        }
        else if (is_array($setting))
        {
            $setting = (object)$setting;
        }

        $options = get_jgrid_colmodel($setting);

        return $options;
    }
}

/**
 * 轉換成editoptions所需的參數
 */
if ( ! function_exists('convert_editoptions_value'))
{
    function convert_editoptions_value($elm = null)
    {
        $obj = new stdClass;

        if (is_object($elm))
        {
            foreach ($elm as $v)
            {
                $obj->{$v->id} = $v->name;
            }
        }
        else if (is_array($elm))
        {
            foreach ($elm as $v)
            {
                $obj->{$v->id} = $v->name;
            }
        }

        return $obj;
    }
}

/**
 * 取得treejgrid options
 */
if ( ! function_exists('get_treejgrid_options'))
{
    function get_treejgrid_options($setting = null)
    {
        $CI =& get_instance();

        if (!$setting) {
            $setting = new stdClass;
        }
        else if (is_array($setting))
        {
            $setting = (object)$setting;
        }

        // 當前動作的controller
        $controller = get_action_controller();

        $options = new stdClass;
        $options->treeGrid = true;
        $options->treeGridModel = 'adjacency';
        $options->ExpandColumn = 'name';
        $options->url = $CI->config->item('base_url') . '/' . $controller . '/tree_data';
        $options->datatype = "json";
        $options->mtype = 'POST';
        $options->colModel = new stdClass;
        $options->pager = '#ptreegrid';
        $options->caption = $controller;
        $options->height = 'auto';
        $options->treeReader = array(
                "level_field" => "level",
                "parent_id_field" => "parent_id",
                "leaf_field" => "leaf",
                "expanded_field" => "expanded"
            );

        // 覆寫預設值
        foreach($setting as $k=>$v)
        {
            $options->{$k} = $v;
        }

        return $options;
    }
}
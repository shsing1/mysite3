<?php

/**
 * 取得jgrid options
 */
if ( ! function_exists('get_jgrid_options'))
{
    function get_jgrid_options($setting = null)
    {
        if (!$setting) {
            $setting = new stdClass;
        }

        // 當前動作的資料夾
        $directory = get_action_directory();

        $options = new stdClass;
        $options->url = $directory . '/list_data';
        $options->editurl = $directory . '/edit_data';
        $options->datatype = "json";
        $options->mtype = 'POST';
        $options->colModel = new stdClass;
        $options->rowNum = 10;
        $options->rowList = array(10, 20, 30);
        $options->pager = '#jqGrid-pager';
        $options->sortname = 'id';
        $options->viewrecords = true;
        $options->sortorder = 'desc';
        $options->caption = $directory;
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
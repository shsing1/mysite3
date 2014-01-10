<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * default layout
 * Location: application/views/
 */
$config['template_layout'] = 'admin/layout_json';

/**
 * default css
 */
$config['template_css'] = array(
    'http://reset5.googlecode.com/hg/reset.min.css' => 'screen',
    'http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.min.css' => 'screen'
    // 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.min.css' => 'screen'
    // '/assets/css/index.css' => 'screen'
);

/**
 * default javascript
 * load javascript on header: FALSE
 * load javascript on footer: TRUE
 */
$config['template_js'] = array(
    'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' => TRUE,
    'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js' => TRUE
);

/**
 * default variable
 */
$config['template_vars'] = array(
    'site_description' => 'xxxx',
    'site_keywords' => 'xxxx'
);

/**
 * default site title
 */
$config['base_title'] = 'xxxxx';

/**
 * default title separator
 */
$config['title_separator'] = ' | ';
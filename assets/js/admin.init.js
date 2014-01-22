/*global $*/
/*jslint browser : true, devel: true, regexp: true */
$(function () {
    'use strict';
    var site_root = 'http://mysite3',
        win = $(window),
        // content = $('#content'),
        // doc = $(document),
        handler_fun = {},
        my_alert,
        west = $('#west');

    $.ajaxSetup({
        type: 'POST',
        dataType: 'json',
        /*complete: function(xhr, textStatus) {
            //called when complete
        },
        success: function(data, textStatus, xhr) {
            //called when successful
        },*/
        error: function(xhr) {
            //called when there is an error
            if (xhr.status !== 200) {
                my_alert(xhr.statusText);
            }
        }
    });

    my_alert = function (message) {
        var div = $('<div>');
        div.attr({'title' : 'title'})
            .append(message)
            .dialog({
                modal : true,
                close: function () {
                    console.log(this);
                    div.dialog('destroy').remove();
                }
            });
    };

    /**
     * [load_list_elm description]
     * @return {[type]} [description]
     */
    // function load_list_elm() {
    //     $.ajax({
    //         'url' : site_root + 'admin/entity/list_elm'
    //     });

    // }
    // load_list_elm();

    // 客製化jqgrid link
    $.extend($.fn.fmatter, {
        childrens_link: function (cellValue, options, rowData) {
            options.target = null;
            return '<a href="' + rowData.childrens_url + '">' + (cellValue || '&nbsp;') + '</a>';
        }
    });

    // 設定主區塊高度
    function set_east_height() {
        var nh = $('#north').height(),
            sh = $('#south').height();
        $('#west').height(win.height() - nh - sh);
    }
    // set_east_height();


    /**
     * 設定grid寬度
     */
    function set_grid_width() {
        var ew = $('#east').width();
        $("#jqGrid-table").jqGrid('setGridWidth', ew - 10);
    }
    /**
     * 資料載入後處理後續動作
     * @param  {[type]} data   [description]
     */
    function loadComplete() {
        $("#jqGrid-table").find('a:not([href^=http])').address();
        set_grid_width();
    }

    /**
     * [ 產生jqgrid列表]
     * @param  {[object]} options [設定參數]
     */
    handler_fun.jqrid = function (options) {
        var html,
            div;
        // 刪除舊有的
        $('#jqGrid-panel').remove();
        html =  '<div id="jqGrid-panel">' +
                    '<table id="jqGrid-table"></table>' +
                    '<div id="jqGrid-pager"></div>' +
                '</div>';
        div = $(html);
        div.appendTo('#east');
        options.loadComplete = loadComplete;
        options.gridComplete = set_grid_width;
        div.find("#jqGrid-table").jqGrid(options);
        div.find("#jqGrid-table").jqGrid('navGrid', '#jqGrid-pager',
                {}, // navGrid options
                { editData: options.postData }, // add options
                { editData: options.postData }, // edit options
                { delData: options.postData }  // del options
            );
        // div.find("#jqGrid-table").jqGrid('navGrid', '#jqGrid-pager', {edit : false, add : false, del : false});
    };

    /**
     * 產生樹狀grid
     * @param  {[type]} options [description]
     * @return {[type]}         [description]
     */
    handler_fun.tree_menu = function (options) {
        var html,
            div;
        // 刪除舊有的
        $('#tree_menu-panel').remove();
        html =  '<div id="tree_menu-panel">' +
                    '<table id="tree_menu"></table>' +
                    '<div id="ptreegrid"></div>' +
                '</div>';
        div = $(html);
        div.appendTo('#west');
        // options.loadComplete = loadComplete;
        // options.gridComplete = set_grid_width;
        div.find("#tree_menu").jqGrid(options);
    };

    function ajax_handler(rs) {
        rs = rs || {};
        if (rs.fun) {
            handler_fun[rs.fun].call(this, rs.options);
        }
    }

    // 設定不換頁連結
    function set_address_link() {
        // Init and change handlers
        $.address.init(function() {
            $('a:not([href^=http])').address();
        }).bind('change', function(event) {
            // Identifies the page selection
            // var handler;

            /*ajax_handler = function(data) {
                $('.content').html($('.content', data).html()).parent().show();
                $.address.title(/>([^<]*)<\/title/.exec(data)[1]);
            };*/
            // Loads the page content and inserts it into the content area
            $.ajax({
                // url: site_root + event.path,
                url: site_root + event.path,
                data : event.parameters,
                success: function(data) {
                    ajax_handler(data);
                }
            });
        });
    }
    set_address_link();

    // 建立後台左方選單
    window.init_menu = function () {
        $.ajax({
            url: 'backend_menu/tree',
            success: function(data) {
                ajax_handler(data);
            }
        });
        /*treegrid.jqGrid({
            treeGrid: true,
            treeGridModel: 'adjacency',
            ExpandColumn : 'name',
            url: 'server.php?q=tree',
            datatype: "xml",
            mtype: "POST",
            colNames: ["id", "Account", "Acc Num", "Debit", "Credit", "Balance"],
            colModel: [
                {name: 'id', index: 'id', width: 1, hidden: true, key: true},
                {name: 'name', index: 'name', width: 180},
                {name: 'num', index: 'acc_num', width: 80, align: "center"},
                {name: 'debit', index: 'debit', width: 80, align: "right"},
                {name: 'credit', index: 'credit', width: 80, align: "right"},
                {name: 'balance', index: 'balance', width: 80, align: "right"}
            ],
            height: 'auto',
            pager : "#ptreegrid",
            caption: "Treegrid example"
        });*/
    };

    // 載入後台左方選單
    if (west.length === 1) {
        // window.init_menu();
        $('#jstree_demo_div').jstree({
            'core' : {
                // "themes" : { "stripes" : true, "dots" : false },
                'data' : {
                    'url' : site_root + '/backend_menu/tree_data/'
                }
            }
        })
            .on('select_node.jstree', function (node, seleted) {
                if (seleted.node) {
                    if (seleted.node.a_attr) {
                        if (seleted.node.a_attr.href) {
                            if (seleted.node.a_attr.href !== '#') {
                                $.address.value(seleted.node.a_attr.href);
                            }
                        }
                    }
                }
            });
    }

    win.on('resize load', function () {
        set_east_height();
        set_grid_width();
    });
});
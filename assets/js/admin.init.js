/*global $*/
/*jslint browser : true, devel: true, regexp: true */
$(function () {
    'use strict';
    var site_root = '/admin',
        win = $(window),
        // content = $('#content'),
        // doc = $(document),
        handler_fun = {},
        my_alert;

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
        div.attr({'title' : 'title'}).append(message).dialog({'modal' : true});
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
                url: site_root + event.path,
                data : event.parameters,
                success: function(data) {
                    ajax_handler(data);
                }
            });
        });
    }
    set_address_link();

    win.on('resize load', function () {
        set_east_height();
        set_grid_width();
    });
});
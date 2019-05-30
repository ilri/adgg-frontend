/**
 * Created by mconyango on 7/17/15.
 */
//document.ready bootstraping
(function ($) {
    'use strict';
    //shorthand for $( document ).ready....
    $(function () {
        let init = {
            updateGridView: function () {
                let updateGrid = function (e) {
                    let url = $(e).data('href')
                        , confirm_msg = $(e).data('confirm-message') || 'Are you sure?'
                        , dataType = $(e).data('data-type')
                        , pjax_id = $(e).data('grid');

                    if (MyApp.utils.empty(dataType)) {
                        dataType = 'html';
                    }

                    let ajax = function () {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            dataType: dataType,
                            success: function (data) {
                                MyApp.grid.updateGrid(pjax_id);
                                if (!MyApp.utils.empty(data) && data.message) {
                                    MyApp.utils.showAlertMessage(data.message, 'success');
                                }
                            }
                            ,
                            beforeSend: function () {
                                MyApp.utils.startBlockUI('Please wait...');
                            }
                            ,
                            complete: function () {
                                MyApp.utils.stopBlockUI();
                            }
                            ,
                            error: function (XHR) {
                                let message = XHR.responseText;
                                MyApp.utils.showAlertMessage(message, 'error');
                                return false;
                            }
                        });
                    }
                    swal({
                        title: 'Confirmation',
                        text: confirm_msg,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel',
                        reverseButtons: false
                    }).then(function (result) {
                        if (result.value) {
                            ajax();
                        }
                    });
                };
                $('body').on('click', 'a.grid-update', function (e) {
                    e.preventDefault();
                    updateGrid(this);
                });
            }
            ,
            activateTabs: function () {
                var path = window.location.pathname;
                path = path.replace(/\/$/, "");
                path = decodeURIComponent(path);
                var checkLink = function (e) {
                    var href = $(e).attr('href');
                    if (href.substring(0, path.length) === path) {
                        return true;
                    } else {
                        return false;
                    }
                };
                //activate tabs
                $('ul.my-nav li>a').each(function () {
                    if (checkLink(this)) {
                        $(this).addClass('active');
                    }
                });
                //activate list-group links
                $('.my-list-group a').each(function () {
                    if (checkLink(this)) {
                        $(this).addClass('active');
                        if ($(this).closest('ul').hasClass('kt-nav')) {
                            $(this).closest('li').addClass('kt-nav__item--active');
                        }
                    }
                });
            }
            ,
            enableLinkableRow: function () {
                var selector = 'table tr.linkable > td:not(.skip-export ,.grid-actions)';
                $(document.body).on('click.tr.linkable', selector, function () {
                    var url = $(this).parent('tr').data('href');
                    if (!MyApp.utils.empty(url)) {
                        MyApp.utils.reload(url);
                    }
                });
            }
            ,
            showDatePicker: function () {
                $(document.body).on('focusin.datepicker', "input[type='text'].show-datepicker,.show-datepicker input[type='text']", function () {
                    let dateFormat = $(this).data('date-format') || 'yy-mm-dd',
                        miDate = $(this).data('min-date') || null,
                        maxDate = $(this).data('max-date') || null,
                        changeYear = $(this).data('change-year') || true,
                        changeMonth = $(this).data('change-year') || true;
                    $(this).datepicker({
                        dateFormat: dateFormat,
                        prevText: '<i class="fa fa-chevron-left"></i>',
                        nextText: '<i class="fa fa-chevron-right"></i>',
                        minDate: miDate,
                        maxDate: maxDate,
                        changeYear: changeYear,
                        changeMonth: changeMonth,
                        yearRange: "-120:+120"
                    });
                });
            }
            ,
            showTimePicker: function () {
                $(document.body).on('focusin.timepicker', "input[type='text'].show-timepicker", function () {
                    $(this).timepicker();
                });
            }
            ,
            collapsePanel: function () {
                $('.collapse').on('show.bs.collapse', function () {
                    $(this).parent().find(".fas.fa-chevron-right").removeClass("fa-chevron-right").addClass("fa-chevron-down");
                }).on('hide.bs.collapse', function () {
                    $(this).parent().find(".fas.fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-right");
                });
            },
            disableCopyPaste: function () {
                $("input.disable-copy-paste").each(function (i, obj) {
                    obj.onpaste = function (e) {
                        e.preventDefault();
                    }
                });
            },
            simpleAjaxPost: function () {
                let selector = 'a.simple-ajax-post,button.simple-ajax-post';
                let ajaxPost = function (e) {
                    let url = $(e).data('href');
                    let _post = function () {
                        $.ajax({
                            url: url,
                            type: 'post',
                            dataType: 'json',
                            success: function (data) {
                                if (data.success) {
                                    MyApp.utils.reload(null, 0);
                                }
                            },
                            error: function (xhr) {
                                if (MyApp.DEBUG_MODE) {
                                    console.log(xhr);
                                }
                            }
                        })
                    }
                    let confirm_msg = $(e).data('confirm-message') || 'Are you sure?'
                    swal({
                        title: 'Confirmation',
                        text: confirm_msg,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel',
                        reverseButtons: false
                    }).then(function (result) {
                        if (result.value) {
                            _post();
                        }
                    });
                };

                //event
                $(document.body).on('click.myapp', selector, function (e) {
                    e.preventDefault();
                    ajaxPost(this);
                })
            },
            initPlugins: function () {
                //modal form
                MyApp.plugin.modal({});
                //notifications
                MyApp.plugin.notif({});
                //dependent dropdowns
                MyApp.plugin.depDropDown({});
                //grid filter form
                MyApp.plugin.filterSearch({});
                //generate report
                MyApp.plugin.generateReport({});
                //show tooltip
                $('#content').tooltip({
                    selector: '.show-tooltip',
                });
                //show popover
                $(document.body).popover({
                    selector: '.show-popover,[data-toggle="popover"]',
                    html: true,
                    trigger: 'hover focus',
                    container: 'body'
                });
                //$("form input:text:not(.no-autofocus), form textarea").first('').focus();
                //disable inspect element
                document.addEventListener('contextmenu', function (e) {
                    // e.preventDefault();
                });
            }
        };
        var key;
        //inherit the properties in parent
        for (key in init) {
            MyApp.utils.executeMethodByName(key, init);
        }
        // dynamic confirmation message
        $("a[data-prompt-confirmation]").on('click', function (e) {
            var element = $(this);
            e.preventDefault();
            var message = element.data('confirm-message') || "Are you sure?";
            if (confirm(message)) {
                window.location.href = element.prop('href');
            }
        });

        //sweet alerts
        swal.mixin({
            width: 400,
            heightAuto: false,
            padding: '2.5rem',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-success',
            confirmButtonColor: null,
            cancelButtonClass: 'btn btn-secondary',
            cancelButtonColor: null
        });
    });

})(jQuery);

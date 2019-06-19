/*
 *Module based js functions
 *@author Fred <mconyango@gmail.com>
 */

//module definitions
MyApp.modules.core = {};

//table attributes form
(function ($) {
    'use strict';
    let FORM = function (options) {
        let defaultOptions = {
            inputTypeFieldSelector: null,
            listTypeIdFieldSelector: null
        }
        this.options = $.extend({}, defaultOptions, options || {});
    }

    FORM.prototype.toggleListTypeField = function () {
        let $this = this;
        let _toggle = function (elem) {
            let showListTypes = $(elem).data('show-list-type'),
                val = $(elem).val(),
                listTypeField = $($this.options.listTypeIdFieldSelector);
            if (showListTypes == val) {
                listTypeField.closest('.form-group').show();
            } else {
                listTypeField.closest('.form-group').hide();
            }
        }
        //on load
        _toggle(this.options.inputTypeFieldSelector);
        //on change
        $(this.options.inputTypeFieldSelector).on('change', function (event) {
            _toggle(this);
        })
    }

    MyApp.modules.core.initTableAttributesForm = function (options) {
        let obj = new FORM(options);
        obj.toggleListTypeField();
    }
}(jQuery));
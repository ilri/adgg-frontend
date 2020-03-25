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
                val = parseInt($(elem).val()),
                listTypeField = $($this.options.listTypeIdFieldSelector);
            if (showListTypes.includes(val)) {
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

//show odk json data
(function ($) {
    'use strict';

    function ODK_DATA_VIEW(options) {
        let defaultOptions = {
            odkJsonDataWrapperSelector: '.show-pretty-json',
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    let showPretty = function (el) {
        let data = $(el).data('json');
        if (MyApp.utils.empty(data)) {
            return;
        }

        function syntaxHighlight(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        if (typeof data === "string") {
            data = JSON.parse(data);
        }
        let str = JSON.stringify(data, undefined, 4);
        $(el).html(syntaxHighlight(str));
    }

    ODK_DATA_VIEW.prototype.showPretty = function () {
        let $this = this;
        $($this.options.odkJsonDataWrapperSelector).each(function (i, obj) {
            showPretty.call($this, obj);
        });
    }
    MyApp.modules.core.showPrettyOdkJson = function (options) {
        let obj = new ODK_DATA_VIEW(options);
        obj.showPretty();
    }

}(jQuery));
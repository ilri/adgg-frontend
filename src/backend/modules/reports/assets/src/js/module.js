//Core module definition
MyApp.modules.reports = {};
(function ($) {
    "use strict";
    var REPORTBUILDER = function (options) {
        let defaultOptions = {
            fieldSelector: '.attribute',
            selectedFieldsHolder: '#selectedFields',
            filterFormSelector: '#report-filter-form',
            submitButtonSelector: '#filterReport',
            reportContainerSelector: '#reportContainer'
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    REPORTBUILDER.prototype.init = function () {
        let $this = this;
        let selectedFields = [];
        let selectedParentModel = null;

        let _load = function (e) {
            let form = $($this.options.filterFormSelector),
                url = form.attr('action');
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'html',
                data: form.serialize(),
                success: function (data) {
                    $($this.options.reportContainerSelector).html(data);
                },
                beforeSend: function (xhr) {
                    $($this.options.reportContainerSelector).html('<h1 class="text-center text-warning" style="margin-top:50px;"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</h1>');
                },
                error: function (xhr) {
                    if (MyApp.DEBUG_MODE) {
                        console.log(xhr);
                    }
                }
            })
        }

        let _populateSelected = function(e){
            var name = $(e).data('name');
            var parentModel = $(e).data('parent-model');
            // if parentModel changes, prompt to clear selectedFields
            if(selectedParentModel !== parentModel){
                selectedFields.length = 0;
            }
            selectedFields.push(name);
            selectedParentModel = parentModel;
            console.log(selectedParentModel);
            console.log(name);
            console.log(selectedFields);
            // write to html
            _showSelected();
        }
        let _removeSelected = function(e){
            var name = $(e).data('name');
            selectedFields = selectedFields.filter(function(e) { return e !== name; });
            console.log(name);
            console.log(selectedFields);
            // write to html
            _showSelected();
        }

        let _showSelected = function(){
            let arr = selectedFields;
            $($this.options.selectedFieldsHolder).html('');
            arr.forEach(function (fieldName){
                var item = '<li class="removeField" data-name="'+fieldName+'">'+fieldName+'</li>';
                $($this.options.selectedFieldsHolder).append(item);
            });
        }

        //on click
        $($this.options.fieldSelector).on('click', function (event) {
            event.preventDefault();
            _populateSelected(this);
        });
        $('body').on('click','.removeField', function (event) {
            event.preventDefault();
            _removeSelected(this);
        });

    }

    MyApp.modules.reports.reportbuilder = function (options) {
        let obj = new REPORTBUILDER(options);
        obj.init();
    }

}(jQuery));


//Core module definition
MyApp.modules.reports = {};
(function ($) {
    "use strict";
    var REPORTBUILDER = function (options) {
        let defaultOptions = {
            fieldSelector: '.attribute',
            selectedFieldsHolder: '#selectedFields',
            builderFormSelector: '#report-builder-form',
            generateQueryBtnSelector: '#generateQuery',
            saveReportBtnSelector: '#saveReport',
            queryOptionsContainer: '#queryOptions',
            queryHolderContainer: '#queryHolder',
            orderBySelector: '#orderby',
            inputSelectOptions: {},
            generateQueryURL: '',
            saveReportURL: '',
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    Array.prototype.move = function(from,to){
        this.splice(to,0,this.splice(from,1)[0]);
        return this;
    };

    REPORTBUILDER.prototype.init = function () {
        let $this = this;
        let selectedFields = [];
        let selectedFilterOperators = {};
        let selectedFilterValues = {};
        let selectedParentModel = null;
        let selectedParentModelTitle = null;

        //console.log($this.options.inputSelectOptions);

        let _generateQuery = function (e) {
            let form = $($this.options.builderFormSelector),
                url = $this.options.generateQueryURL;
            let data =  JSON.stringify( form.serializeArray() );
            //console.log(data);

            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: form.serialize(),
                success: function (data) {
                    //$($this.options.queryHolderContainer).text(data);
                    //$($this.options.queryHolderContainer).parent().attr('contenteditable','true');
                    window.editor.setValue(data);
                },
                beforeSend: function (xhr) {
                    MyApp.utils.startBlockUI();
                },
                complete: function () {
                    MyApp.utils.stopBlockUI();
                },
                error: function (xhr) {
                    if (MyApp.DEBUG_MODE) {
                        console.log(xhr);
                    }
                }
            })

        }

        let _saveReport = function(e){
            let form = $($this.options.builderFormSelector),
                originalButtonHtml = $(e).html(),
                url = $this.options.saveReportURL;
            let data =  JSON.stringify( form.serializeArray() );

            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function (response) {
                    if(response.success){
                        let message = '<div class="alert alert-outline-success">' + response.message + '</div>';
                        swal("SUCCESS!", message, "success");
                    }
                    else {
                        if (typeof response.message === 'string' || response.message instanceof String) {
                            let message = '<div class="alert alert-outline-danger">' + response.message + '</div>';
                            swal("ERROR!", message, "error");
                        } else {
                            let summary = '<ul>';
                            if (typeof response.message === 'object') {
                                $.each(response.message, function (i) {
                                    if ($.isArray(response.message[i])) {
                                        $.each(response.message[i], function (j, msg) {
                                            let $input = $('#' + i);
                                            $input.addClass('is-invalid');
                                            $input.next('.invalid-feedback').html(msg);
                                            summary += '<li>' + msg + '</li>';
                                        });
                                    }
                                });
                            }
                            summary += '</ul>';
                            swal("ERROR!", summary, "error");
                        }
                    }
                },
                beforeSend: function () {
                    MyApp.utils.startBlockUI();
                },
                complete: function () {
                    MyApp.utils.stopBlockUI();
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
            var parentModelTitle = $(e).data('parent-model-title');
            // if parentModel changes, prompt to clear selectedFields
            if(selectedParentModel !== parentModel){
                selectedFields.length = 0;
            }
            // check for duplicates
            var index = selectedFields.indexOf(name);
            if (index <= -1) {
                selectedFields.push(name);
            }
            //selectedFields.push(name);
            selectedParentModel = parentModel;
            selectedParentModelTitle = parentModelTitle;
            //console.log(selectedParentModel);
            //console.log(name);
            //console.log(selectedFields);
            // write to html
            _showSelected();
        }
        let _removeSelected = function(e){
            var name = $(e).data('name');
            selectedFields = selectedFields.filter(function(e) { return e !== name; });
            //console.log(name);
            //console.log(selectedFields);
            // write to html
            _showSelected();
            //_toggleQueryOptions();
        }

        let _showSelected = function(){
            $('#selectedModel').html(selectedParentModelTitle);
            $('input#model').val(selectedParentModel);
            let arr = selectedFields;
            $($this.options.selectedFieldsHolder).html('');
            arr.forEach(function (fieldName, index){
                var dropdown = _buildDropdownSelect(fieldName);
                var filterInput = '<div class="col-md-4 mr-0 pr-0"><input name="filterValue['+fieldName+']" class="form-control form-control-sm" type="text" /></div>';
                var removeBtn = '<div class="col-md-1 pt-2"><span class="flaticon2-delete removeField" data-name="'+fieldName+'"></span></div>';
                var nameElem = '<div class="col-md-3"><span class="text-wrap word-wrap">'+ fieldName +'</span></div>';
                var item = '<li class="list-group-item d-flex pr-0 pl-0" data-index="'+index+'" data-name="'+fieldName+'">'+ nameElem + dropdown + filterInput + removeBtn +'</li>';
                $($this.options.selectedFieldsHolder).append(item);
            });
            // display the query options
            _toggleQueryOptions();
            // rebuild orderby dropdown
            _buildOrderByDropdown();
        }

        let _toggleQueryOptions = function(){
            var elem = $this.options.queryOptionsContainer;
            if(selectedFields.length > 0){
                if($(elem).hasClass('hidden')){
                    $(elem).removeClass('hidden');
                }
            }
            else {
                $(elem).addClass('hidden');
            }
        }

        let _buildDropdownSelect = function(fieldName){
            var input = '<div class="col-md-4 mr-0 pr-0"><select name="filterCondition['+fieldName+']" class="form-control form-control-sm p-0">';
            input += '<option value=""> - Select Operator - </option>';
            var options = $this.options.inputSelectOptions;
            for (var prop in options) {
                if (Object.prototype.hasOwnProperty.call(options, prop)) {
                    var option = '<option value="'+prop+'">'+options[prop]+'</option>';
                    input += option;
                }
            }
            input += '</select></div>';
            return input;
        }

        let _buildOrderByDropdown = function(){
            let options = '<option value=""> - Select Field - </option>';
            selectedFields.forEach(function (item, index) {
                var option = '<option value="'+item+'">'+item+'</option>';
                options += option;
            });
            $($this.options.orderBySelector).html(options);
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
        $($this.options.generateQueryBtnSelector).on('click', function (event) {
            event.preventDefault();
            _generateQuery(this);
        });
        $($this.options.saveReportBtnSelector).on('click', function (event) {
            event.preventDefault();
            _saveReport(this);
        });

        $('#select_org_id').on('change', function (event) {
            event.preventDefault();
            var elem = $('#report-builder-container');
            if($(elem).hasClass('hidden')){
                $(elem).removeClass('hidden');
            }
        });
        // enable sorting of the selected items
        $($this.options.selectedFieldsHolder).sortable({
            stop: function( event, ui ) {
                var item = ui.item;
                var newIndex = item.index();
                var formerIndex = item.data('index');
                //console.log(item);
                //console.log(newIndex);
                selectedFields.move(formerIndex, newIndex);
                //console.log(selectedFields);
                _showSelected();
            }
        });

    }

    MyApp.modules.reports.reportbuilder = function (options) {
        let obj = new REPORTBUILDER(options);
        obj.init();
        window.editor = CodeMirror.fromTextArea(document.getElementById('queryHolder'), {
            value: '',
            mode: 'text/x-mysql',
            indentWithTabs: true,
            smartIndent: true,
            lineNumbers: false,
            lineWrapping: true,
            matchBrackets : true,
            autofocus: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            foldGutter: true,
        });
        const copy = new ClipboardJS('.btn-clipboard', {
            text: function (trigger) {
                return window.editor.getValue();
            }
        });

        copy.on('success',function(e){
            e.clearSelection();
            $(e.trigger).text("Copied!");
            setTimeout(function () {
                $(e.trigger).text("Copy");
            }, 2500);
        });
        copy.on('error',function(e){
            console.error('Action:',e.action);
            console.error('Trigger:',e.trigger);
        });
    }

}(jQuery));


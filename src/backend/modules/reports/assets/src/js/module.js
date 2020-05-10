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
            inputTypeOptions: {},
            generateQueryURL: '',
            saveReportURL: '',
            searchAttributesSelector: '.search-attributes',
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    Array.prototype.move = function (from, to) {
        this.splice(to, 0, this.splice(from, 1)[0]);
        return this;
    };

    REPORTBUILDER.prototype.init = function () {
        let $this = this;
        let selectedFields = [];
        let selectedFilterOperators = {};
        let selectedFilterValues = {};
        let selectedFieldLabels = {};
        let selectedFieldTypes = {};
        let selectedParentModel = null;
        let selectedParentModelTitle = null;

        let _generateQuery = function (e) {
            let form = $($this.options.builderFormSelector),
                url = $this.options.generateQueryURL;
            let data = JSON.stringify(form.serializeArray());
            var elem = $('#previewQueryCard');

            if ($(elem).hasClass('hidden')) {
                $(elem).removeClass('hidden');
            }

            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: form.serialize(),
                success: function (data) {
                    $($this.options.queryHolderContainer).text(data);
                    $($this.options.queryHolderContainer).parent().attr('contenteditable', 'false');
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

        let _saveReport = function (e) {
            let form = $($this.options.builderFormSelector),
                originalButtonHtml = $(e).html(),
                url = $this.options.saveReportURL;
            let data = JSON.stringify(form.serializeArray());
            let errorMsgContainer = '#reportNameMessage';

            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        let {message} = response;
                        MyApp.utils.showAlertMessage(message, 'success', errorMsgContainer);

                        if (response.redirectUrl !== '') {
                            MyApp.utils.reload(response.redirectUrl, 2000);
                        }
                    } else {
                        if (typeof response.message === 'string' || response.message instanceof String) {
                            let {message} = response;
                            MyApp.utils.showAlertMessage(message, 'error', errorMsgContainer);
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

                            MyApp.utils.showAlertMessage(summary, 'error', errorMsgContainer);
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

        let _populateSelected = function (e) {
            var name = $(e).data('name');
            var label = $(e).data('original-title');
            var type = $(e).data('type');
            var parentModel = $(e).data('parent-model');
            var parentModelTitle = $(e).data('parent-model-title');

            // if parentModel changes, prompt to clear selectedFields
            if (selectedParentModel !== parentModel) {
                selectedFields.length = 0;
                selectedFieldLabels = {}; selectedFieldTypes = {};
                // uncheck all previously checked checkboxes,
                // where data-name is not this field
                $('label.attribute input[type=checkbox]').not("[data-name='" + name + "']").prop('checked', false);
            }
            // check for duplicates
            var index = selectedFields.indexOf(name);

            if (index <= -1) {
                selectedFields.push(name);
            } else {
                _removeSelected(e);
            }

            if (selectedFieldLabels[name] === undefined) {
                selectedFieldLabels[name] = label;
            }
            if (selectedFieldTypes[name] === undefined) {
                selectedFieldTypes[name] = type;
            }
            //selectedFields.push(name);
            selectedParentModel = parentModel;
            selectedParentModelTitle = parentModelTitle;
            //console.log(selectedParentModel);
            //console.log(name);
            //console.log(selectedFields);
            //console.log(selectedFieldLabels);
            // write to html
            _showSelected(e);
        }
        let _removeSelected = function (e) {
            var name = $(e).data('name');
            selectedFields = selectedFields.filter(function (e) {
                return e !== name;
            });
            delete selectedFieldLabels[name];
            delete selectedFieldTypes[name];
            //console.log(name);
            //console.log(selectedFields);
            //console.log(selectedFieldLabels);
            // write to html
            _showSelected(e);
            //_toggleQueryOptions();
        }

        let _showSelected = function (e) {
            var name = $(e).data('name');
            let elem = $('.builder-attributes label[data-name="' + name + '"]');
            let checkbox = elem.find('input[type=checkbox]');
            //check if name is in array
            let checkedItem = selectedFields.find(i => i === name);

            // toggle checkbox state
            if (checkedItem) {
                checkbox.prop('checked', true);
            } else {
                checkbox.prop('checked', false);
            }

            if (selectedFields.length !== 0) {
                $('#selectedModel').html(selectedParentModelTitle);
            } else {
                $('#selectedModel').html('');
            }

            $('input#model').val(selectedParentModel);
            let arr = selectedFields;
            $($this.options.selectedFieldsHolder).html('');
            arr.forEach(function (fieldName, index) {
                // filter parts
                // when this dropdown changes, rebuild the filterInput
                var dropdown = _buildOperatorsDropdown(fieldName);
                var filterInput = '<div class="col-md-12 p-0"><input name="filterValue[' + fieldName + ']" class="form-control form-control-sm" type="text" /></div>';
                // assemble filter parts
                var operandSelector = '<div class="d-flex"><div class="filter-operators-holder col-md-5">' + dropdown + '</div><div class="filter-input-holder col-md-7">' + filterInput + '</div></div>';
                // title parts
                var removeBtn = '<div class="ml-auto"><span class="flaticon2-delete removeField" data-name="' + fieldName + '"></span></div>';
                var nameElem = '<div class=""><span class="text-wrap word-wrap">' + fieldName + '</span></div>';
                // assemble title parts
                var title = '<div class="d-flex mb-3">' + nameElem + removeBtn + '</div>';
                var item = '<li class="groupeditem p-3 mb-1" data-index="' + index + '" data-name="' + fieldName + '" data-type="' + selectedFieldTypes[fieldName] + '">' + title + operandSelector + '</li>';
                $($this.options.selectedFieldsHolder).append(item);
            });
            // display the query options
            _toggleQueryOptions();
            // rebuild orderby dropdown
            _buildOrderByDropdown();
        }

        let _toggleQueryOptions = function () {
            var elem = $this.options.queryOptionsContainer;
            if (selectedFields.length > 0) {
                if ($(elem).hasClass('hidden')) {
                    $(elem).removeClass('hidden');
                }
            } else {
                $(elem).addClass('hidden');
            }
        }

        let _buildOperatorsDropdown = function (fieldName) {
            var input = '<div class="col-md-12 p-0 mr-2"><select name="filterCondition[' + fieldName + ']" class="form-control form-control-sm attributeFilterOperator" data-field="' + fieldName + '">';
            input += '<option value=""> - Select Operator - </option>';
            var options = $this.options.inputSelectOptions;
            for (var prop in options) {
                if (Object.prototype.hasOwnProperty.call(options, prop)) {
                    var option = '<option value="' + prop + '">' + options[prop] + '</option>';
                    input += option;
                }
            }
            input += '</select></div>';
            return input;
        }

        let _buildFieldChoicesDropdown = function (fieldName, options, multipleSelection = false) {
            var input = '<div class="col-md-12 p-0 mr-2"><select name="filterValue[' + fieldName + ']'+ (multipleSelection ? "[]" : "") +'" class="form-control form-control-sm select2" '+ (multipleSelection ? "multiple" : "") +'>';
            input += _buildSelectOptions(options, ' - Select Value -');
            input += '</select></div>';
            return input;
        }

        let _buildSelectOptions = function(options, prompt = '- Select -'){
            let input = '<option value="">' + prompt + '</option>';
            for (var prop in options) {
                if (Object.prototype.hasOwnProperty.call(options, prop)) {
                    var option = '<option value="' + prop + '">' + options[prop] + '</option>';
                    input += option;
                }
            }
            return input;
        }

        let _buildAttributeFilter = function (fieldName, filterOperator){
            let attributeListItem = $('.builder-attributes label[data-name="' + fieldName + '"]');
            let attributeSelectOptions = attributeListItem.data('selectoptions'); // null or a json object of options
            let filterInput;
            let defaultFilterInput = $('<div class="col-md-12 p-0"><input name="filterValue[' + fieldName + ']" class="form-control form-control-sm" type="text" /></div>');
            if (attributeSelectOptions !== null) {
                let isMultipleSelect = false;
                if(filterOperator === 'IN' ||  filterOperator === 'NOT IN'){
                    isMultipleSelect = true;
                }
                defaultFilterInput = '<div class="col-md-12 p-0">' + _buildFieldChoicesDropdown(fieldName, attributeSelectOptions, isMultipleSelect) + '</div>';
            }
            else {
                if(filterOperator === 'IN' ||  filterOperator === 'NOT IN'){
                    let innerhtml = $(defaultFilterInput).html() + '<span class="hint">enter a comma separated list in the input field</span>';
                    $(defaultFilterInput).html($(innerhtml));
                }
            }

            let fieldContainer = $('#selectedFields').find('li[data-name="' + fieldName + '"]');
            let filterInputHolder = $(fieldContainer).find('.filter-input-holder');
            // check the field type and operator
            // if field type is date, show datepicker, if operator is BETWEEN, show two date fields (from, to)
            // if operator is IN or NOT IN and there is a dropdown, make it a multiselect
            let fieldType = selectedFieldTypes[fieldName];
            let inputTypeOptions = $this.options.inputTypeOptions;
            switch (fieldType) {
                case inputTypeOptions['DATE']:
                    // show-datepicker
                    if(filterOperator === 'BETWEEN'){
                        filterInput = '<div class="row p-0">' +
                            '<div class="col-md-5">' +
                            '<input name="filterValue[' + fieldName + '][]" class="form-control form-control-sm show-datepicker" type="text" />' +
                            '</div>' +
                            '<div class="col-md-2">AND</div>' +
                            '<div class="col-md-5">' +
                            '<input name="filterValue[' + fieldName + '][]" class="form-control form-control-sm show-datepicker" type="text" />' +
                            '</div>' +
                            '</div>';
                    }
                    else {
                        filterInput = '<div class="col-md-12 p-0"><input name="filterValue[' + fieldName + ']" class="form-control form-control-sm show-datepicker" type="text" /></div>';
                    }
                break;
                case inputTypeOptions['SELECT']:
                case inputTypeOptions['MULTI SELECT']:
                    if(filterOperator === 'BETWEEN'){
                        filterInput = '';
                    }
                    else {
                        filterInput = defaultFilterInput;
                    }
                break;
                default:
                    if(filterOperator === 'BETWEEN'){
                        filterInput = '<div class="row p-0">' +
                            '<div class="col-md-5">' +
                            '<input name="filterValue[' + fieldName + '][]" class="form-control form-control-sm" type="text" />' +
                            '</div>' +
                            '<div class="col-md-2">AND</div>' +
                            '<div class="col-md-5">' +
                            '<input name="filterValue[' + fieldName + '][]" class="form-control form-control-sm" type="text" />' +
                            '</div>' +
                            '</div>';
                    }
                    else {
                        filterInput = defaultFilterInput;
                    }
            }

            if(filterOperator === 'IS NULL' || filterOperator === 'NOT NULL'){
                filterInput = '';
            }

            _showAttributeFilter(filterInput, filterInputHolder);
        }

        let _showAttributeFilter = function(content, container) {
            $(container).html(content);
            $('.select2').select2();
        }

        let _buildOrderByDropdown = function () {
            let options = '<option value=""> - Select Field - </option>';
            selectedFields.forEach(function (item, index) {
                var option = '<option value="' + item + '">' + item + '</option>';
                options += option;
            });
            $($this.options.orderBySelector).html(options);
        }

        let _searchAttributes = function (e) {
            let model = $(e).data('model');
            let inputField = document.querySelector('#collapse' + model + ' .search-attributes');
            let searchValue = inputField.value.toLowerCase();
            let attributes = document.querySelectorAll('#collapse' + model + ' ul li label');
            let relations = document.querySelectorAll('#collapse' + model + ' ul div.collapse');

            for (let i = 0; i < relations.length; i++) {
                if (searchValue.length > 0 && !(relations[i].classList.contains('show'))) {
                    relations[i].classList.add('show');
                } else if (searchValue.length === 0 && relations[i].classList.contains('show')) {
                    relations[i].classList.remove('show');
                }
            }

            for (let i = 0; i < attributes.length; i++) {
                if (attributes[i].innerText.toLowerCase().indexOf(searchValue) > -1) {
                    attributes[i].parentElement.style.display = "";
                } else {
                    attributes[i].parentElement.style.display = "none";
                }
            }
        }

        //on click
        $($this.options.searchAttributesSelector).on('keyup', function (event) {
            event.preventDefault();
            _searchAttributes(this);
        });

        $($this.options.fieldSelector).on('click', function (event) {
            event.preventDefault();
            _populateSelected(this);
        });
        $('body').on('click', '.removeField', function (event) {
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

        $('#select_country_id').on('change', function (event) {
            event.preventDefault();
            var elem = $('#report-builder-container');
            if ($(elem).hasClass('hidden')) {
                $(elem).removeClass('hidden');
            }
        });

        $('body').on('change', '.attributeFilterOperator', function (event) {
            event.preventDefault();
            let operator = this.value;
            let fieldName = $(this).data('field');
            _buildAttributeFilter(fieldName, operator);

        });
        // enable sorting of the selected items
        $($this.options.selectedFieldsHolder).sortable({
            stop: function (event, ui) {
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
        window.editor = new CodeMirror.fromTextArea(document.getElementById('queryHolder'), {
            value: '',
            mode: 'text/x-mysql',
            indentWithTabs: true,
            smartIndent: true,
            lineNumbers: false,
            lineWrapping: true,
            matchBrackets: true,
            autofocus: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            foldGutter: false,
            readOnly: true,
        });

        const copy = new ClipboardJS('.btn-clipboard', {
            text: function (trigger) {
                return window.editor.getValue();
            }
        });

        copy.on('success', function (e) {
            e.clearSelection();
            $(e.trigger).text("Copied!");
            setTimeout(function () {
                $(e.trigger).text("Copy");
            }, 2500);
        });
        copy.on('error', function (e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });
    }

}(jQuery));

// standard report

(function ($) {
    "use strict";
    var STDREPORT = function (options) {
        let defaultOptions = {
            filterFormSelector: '#std-report-form',
            submitButtonSelector: '#buildReport',
            reportContainerSelector: '#reportContainer'
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    STDREPORT.prototype.init = function () {
        let $this = this;

        let _load = function (e) {
            let form = $($this.options.filterFormSelector),
                url = form.attr('action');
            $.ajax({
                url: url,
                type: form.attr('method'),
                dataType: 'json',
                data: form.serialize(),
                success: function (response) {
                    if (response.success) {
                        let message = '<div class="alert alert-outline-success">' + response.message + '</div>';
                        swal("SUCCESS!", message, "success");
                        if (response.redirectUrl !== '') {
                            MyApp.utils.reload(response.redirectUrl, 2000);
                        }
                    } else {
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

        //on click
        $($this.options.filterFormSelector).find('button[type="submit"]').on('click', function (event) {
            event.preventDefault();
            _load(this);
        });

    }

    MyApp.modules.reports.stdreport = function (options) {
        let obj = new STDREPORT(options);
        obj.init();
    }

}(jQuery));


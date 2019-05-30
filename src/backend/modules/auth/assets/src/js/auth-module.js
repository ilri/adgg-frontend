/*
 *Module based js functions
 *@author Fred <mconyango@gmail.com>
 */

//module definitions
MyApp.modules.auth = {};

//roles
(function ($) {
    "use strict";

    var ROLES = function (options) {
        var defaultOptions = {
            select2Selector: "select.select2",
            selectAllSelector: "button.my-select-all"
        };

        this.options = $.extend({}, defaultOptions, options || {});
    };

    ROLES.prototype.toggleColumnCheckAll = function () {
        let _toggle = function (e) {
            let isChecked = $(e).is(":checked"),
                targetCheckBoxClass = $(e).data('target-class');
            if (isChecked) {
                $('.' + targetCheckBoxClass).prop("checked", true);
            } else {
                $('.' + targetCheckBoxClass).prop("checked", false);
            }
        };

        $(document.body).on("click", 'input.check-all-checkbox', function (e) {
            _toggle(this);
        });
    }

    //toggle
    ROLES.prototype.toggleCheckAll = function () {
        var $this = this;

        var _toggle = function (e) {
            var checkbox = $("input:checkbox.my-roles-checkbox")
                , isChecked = checkbox.is(":checked");
            if (isChecked) {
                checkbox.prop("checked", false);
                $(this).text("Uncheck all");
            } else {
                checkbox.prop("checked", true);
                $(this).text("Check all");
            }
        };

        $($this.options.selectAllSelector).on("click", function (e) {
            e.preventDefault();
            _toggle(this);
        });
    };

    var PLUGIN = function (options) {
        var obj = new ROLES(options);
        obj.toggleCheckAll();
        obj.toggleColumnCheckAll();
    };

    MyApp.modules.auth.roles = PLUGIN;
}(jQuery));
//users
(function ($) {
    "use strict";

    var USER = function (options) {
        var defaultOptions = {
            changeStatusSelector: "a.change-user-status"
        }
        this.options = $.extend({}, defaultOptions, options || {});
    }

    USER.prototype.changeStatus = function () {
        var $this = this
            , selector = $this.options.changeStatusSelector;

        var ajaxPost = function (e) {
            var url = $(e).data("href");
            $.ajax({
                url: url,
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        MyApp.utils.reload();
                    }
                },
                error: function (xhr) {
                    if (MyApp.DEBUG_MODE) {
                        console.log(xhr);
                    }
                }
            })
        }

        //event
        $(selector).on("click.myapp", function (e) {
            e.preventDefault();
            ajaxPost(this);
        })
    }

    var PLUGIN = function (options) {
        var obj = new USER(options);
        obj.changeStatus();
    }

    MyApp.modules.auth.user = PLUGIN;
}(jQuery));
//user form filters (country,region,branch)
(function ($) {
    "use strict";

    var FILTER = function (options) {
        var defaultOptions = {
            baseId: undefined,
            filterOnLoad: true,
            countryField: "country_id",
            regionField: "region_id",
            branchField: "branch_id",
        };
        this.options = $.extend({}, defaultOptions, options || {})
    }

    var getSelector = function (field) {
        return "#" + this.options.baseId + "-" + field;
    }
    /**
     *
     * @param selector
     * @param targetSelector
     * @param changeOnLoad
     */
    var filter = function (selector, targetSelector, changeOnLoad) {
        if (changeOnLoad === "undefined") {
            changeOnLoad = false;
        }
        var ajaxPost = function (e) {
            var url = $(e).data("href")
                , value = $(e).val();

            $.ajax({
                url: url,
                type: "post",
                data: "id=" + value,
                dataType: "json",
                success: function (data) {
                    MyApp.utils.populateDropDownList(targetSelector, data);
                    $(targetSelector).change();
                },
                error: function (xhr) {
                    if (MyApp.DEBUG_MODE) {
                        console.log(xhr);
                    }
                }
            })
        }

        //event
        $(selector).on("change", function (e) {
            ajaxPost(this);
        });
        if (changeOnLoad) {
            $(selector).change();
        }
    }

    FILTER.prototype.country = function () {
        var $this = this;
        var selector = getSelector.call($this, $this.options.countryField)
            , targetSelector = getSelector.call($this, $this.options.regionField);
        filter.call($this, selector, targetSelector, $this.options.filterOnLoad);
    }

    FILTER.prototype.region = function () {
        var $this = this;
        var selector = getSelector.call($this, $this.options.regionField)
            , targetSelector = getSelector.call($this, $this.options.branchField);
        filter.call($this, selector, targetSelector);
    }

    var PLUGIN = function (options) {
        var obj = new FILTER(options);
        obj.country();
        obj.region();
    }

    MyApp.modules.auth.filter = PLUGIN;
}(jQuery));
//auto generate password
(function ($) {
    "use strict";
    let FORM = function (options) {
        let defaultOptions = {
            autoGeneratePasswordFieldSelector: '#users-auto_generate_password',
            passwordFieldsWrapper: '#password-fields-wrapper'
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }
    FORM.prototype = {
        toggleFields: function () {
            let $this = this,
                selector = $this.options.autoGeneratePasswordFieldSelector;
            let _toggle = function (e) {
                let isChecked = $(e).is(':checked');
                if (isChecked) {
                    $($this.options.passwordFieldsWrapper).hide();
                } else {
                    $($this.options.passwordFieldsWrapper).show();
                }
            }
            _toggle(selector);
            $(selector).on('click', function (e) {
                _toggle(this);
            })
        }
    }

    MyApp.modules.auth.autoGeneratePassword = function (options) {
        let obj = new FORM(options);
        obj.toggleFields();
    }
}(jQuery));

//toggle organization
(function ($) {
    "use strict";
    let FORM = function (options) {
        let defaultOptions = {
            organizationWrapperSelector: '#organization-id-wrapper',
            levelIdFieldSelector: '#users-level_id',
            orgIdFieldSelector: '#users-org_id'
        }
        this.options = $.extend({}, defaultOptions, options || {});
    }

    FORM.prototype = {
        toggleOrganization: function () {
            let $this = this,
                selector = $this.options.levelIdFieldSelector;
            let _toggle = function (e) {
                let val = $(e).val(),
                    showOrganizationFlags = $(e).data('show-organization'),
                    orgSelect = $($this.options.orgIdFieldSelector);
                if (showOrganizationFlags.includes(parseInt(val))) {
                    orgSelect.removeAttr("disabled");
                } else {
                    orgSelect.select2("enable", false);
                    orgSelect.val('').trigger('change');
                }
            }
            //on page load
            _toggle(selector);
            //on change
            $(selector).on('change', function (event) {
                _toggle(this);
            })
        }
    };

    MyApp.modules.auth.toggleOrganization = function (options) {
        let obj = new FORM(options);
        obj.toggleOrganization();
    }
}(jQuery));

//init user create/update form
(function ($) {
    "use strict";
    MyApp.modules.auth.initUserForm = function (options) {
        MyApp.modules.auth.toggleOrganization(options);
        MyApp.modules.auth.autoGeneratePassword(options);
    }
}(jQuery));

//carousel
//$('.carousel').carousel()
(function ($) {
    "use strict";

    function changeBackground(i) {
        let element = $('#kt-login-v1-body');
        let images = element.data('images');
        let imagesCount = images.length;
        let index = i || 0;
        if (typeof i !== 'undefined') {
            element.stop().animate({opacity: 0.7}, 1000, function () {
                $(this).css('background-image', 'url(' + images[i] + ')').animate({opacity: 1}, {duration: 1000});
            });
        }

        $.fn.preloadImages = function () {
            this.each(function () {
                $('<img/>')[0].src = this;
            });
        }
        $([images[0], images[1], images[2], images[3]]).preloadImages();
        setTimeout(function () {
            let i = index + 1;
            if (i > (imagesCount - 1)) {
                i = 0;
            }
            changeBackground(i);
        }, 10000);
    }

    changeBackground();
}(jQuery));

//dynamic form after add
(function ($) {
    'use strict';
    MyApp.modules.auth.dynamicFormAfterAdd = function (index) {
        var script = $('#_raw_script_' + index).data('content');
        if (!MyApp.utils.empty(script)) {
            //alert(script);
            eval(script);
        }
    }
}(jQuery));

//registration steps
(function ($) {
    'use strict';
    let FORM = function (options) {
        let defaultOptions = {
            formId: 'registration-form',
            step1SectionSelector: '#reg-step1-form-section',
            step2SectionSelector: '#reg-step2-form-section',
            step3SectionSelector: '#reg-step3-form-section',
            step4SectionSelector: '#reg-step4-form-section',
            regStepsLabelsSelector: '#reg-steps-labels',
            regStep1LabelSelector: "#reg-step1-label",
            regStep2LabelSelector: "#reg-step2-label",
            regStep3LabelSelector: "#reg-step3-label",
            regStep4LabelSelector: "#reg-step4-label",
            alertId: 'my-modal-notif'
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    let activateNext = function (step, nextStep, goNext = true) {
        let $this = this;
        let currentStepButtonSelector = "#reg-step" + step + "-label";
        let currentSectionSelector = "#reg-step" + step + "-form-section";
        let currentSection = $(currentSectionSelector);
        let currentStepButton = $(currentStepButtonSelector);
        let nextButton = $('button[data-ktwizard-type="action-next"]');
        if (goNext) {
            currentStepButton.attr('data-ktwizard-state', 'done');
        } else {
            currentStepButton.removeAttr('data-ktwizard-state');
        }
        currentSection.removeAttr('data-ktwizard-state');

        let nextStepButtonSelector = "#reg-step" + nextStep + "-label";
        let nextSectionSelector = "#reg-step" + nextStep + "-form-section";
        let nextSection = $(nextSectionSelector);
        let nextStepButton = $(nextStepButtonSelector);

        nextStepButton.attr('data-ktwizard-state', 'current');
        nextButton.attr('data-active-step', nextStep);
        nextSection.attr('data-ktwizard-state', 'current');
        if (nextStep == 1) {
            $($this.options.regStepsLabelsSelector).attr('data-ktwizard-state', 'first');
        } else if (nextStep == 2 || nextStep == 3) {
            $($this.options.regStepsLabelsSelector).attr('data-ktwizard-state', 'between');
        } else {
            $($this.options.regStepsLabelsSelector).attr('data-ktwizard-state', 'last');
        }
    }

    FORM.prototype.submitStep = function () {
        let $this = this;
        let selector = 'button[data-ktwizard-type="action-next"]';

        let _submitStep = function (e) {
            let section = $('[data-ktwizard-type="step-content"][data-ktwizard-state="current"]');
            let form = $('#' + $this.options.formId);
            let data = section.find('input,select,textarea').serialize();
            let url = form.attr('action');
            let originalButtonHtml = $(e).html();
            //console.log(data);
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function (response) {
                    let alertWrapper = $('#' + $this.options.alertId);
                    if (response.success) {
                        let activeStep = parseInt($(e).attr('data-active-step'));
                        alertWrapper.addClass('hidden');
                        activateNext.call($this, activeStep, activeStep + 1);
                    } else {
                        let summary = '<ul>';
                        if (response.message) {
                            MyApp.utils.showAlertMessage(response.message, 'error', alertWrapper);
                        } else {
                            if (typeof response === 'object') {
                                $.each(response, function (i) {
                                    if ($.isArray(response[i])) {
                                        $.each(response[i], function (j, msg) {
                                            let $input = $('#' + i);
                                            $input.addClass('is-invalid');
                                            $input.next('.invalid-feedback').html(msg);
                                            summary += '<li>' + msg + '</li>';
                                        });
                                    }
                                });
                            }
                        }
                        summary += '</ul>';
                        if (alertWrapper.length) {
                            MyApp.utils.showAlertMessage(summary, 'error', alertWrapper);
                        }
                    }
                },
                beforeSend: function () {
                    $(e).attr('disabled', 'disabled').html('Please wait....');
                },
                complete: function () {
                    $(e).html(originalButtonHtml).removeAttr('disabled');
                },
                error: function (XHR) {
                    console.log(XHR.responseText);
                    MyApp.utils.showAlertMessage(XHR.responseText, 'error', '#' + $this.options.notif_id);
                }
            });
        }

        $(selector).on('click', function (event) {
            _submitStep(this);
        })
    }

    FORM.prototype.goBack = function () {
        let $this = this;
        let selector = 'button[data-ktwizard-type="action-prev"]';
        let _goBack = function (e) {
            let nextButton = $('button[data-ktwizard-type="action-next"]');
            let activeStep = parseInt(nextButton.attr('data-active-step'));
            activateNext.call($this, activeStep, activeStep - 1, false);
        }

        $(selector).on('click', function (event) {
            _goBack(this);
        })
    }

    MyApp.modules.auth.initRegForm = function (options) {
        let obj = new FORM(options);
        obj.submitStep();
        obj.goBack();
    }

}(jQuery));
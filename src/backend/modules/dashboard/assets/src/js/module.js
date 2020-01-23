MyApp.modules.dashboard = {};
(function ($) {
    'use strict';
    var PIE = function (container, series, graphOptions) {
        var defaultOptions = {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null, //null,
                plotShadow: false,
                backgroundColor: 'transparent',
                spacingTop: 3,
                type: 'pie',
            },
            credits: {
                enabled: false,
            },
            title: {
                text: null,
            },
            tooltip: {
                pointFormat: '{point.y}: <b>{point.percentage:.0f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    //size: 100,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.y}({point.percentage:.0f}%)',
                    },
                    innerSize: '30%',
                    size: 300,
                }
            },
        };
        this.container = container;
        this.series = series;
        this.graphOptions = $.extend(true, {}, defaultOptions, graphOptions || {});
    };

    PIE.prototype.create = function () {
        var $this = this;
        var options = $.extend(true, {}, $this.graphOptions, {series: $this.series});
        $('#' + $this.container).highcharts(options);
    };

    MyApp.modules.dashboard.piechart = function (container, series, graphOptions) {
        var obj = new PIE(container, series, graphOptions);
        obj.create();
    };
    //MyApp.plugin.gridPieChart = PLUGIN;
}(jQuery));
MyApp.modules.dashboard = {};
(function ($) {
    'use strict';
    var PIE = function (container, series, graphOptions) {
        var innerSize = '30%';
        var size = 300;

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
                    innerSize: innerSize,
                    size: size,
                }
            },
        };
        this.container = container;
        this.series = series;
        this.graphOptions = $.extend(true, {}, defaultOptions, graphOptions || {});
    };
    PIE.prototype.screenSize = function ()
    {
        var smallScreen = window.matchMedia("(max-width: 768px)");
        var tinyScreen = window.matchMedia("(max-width: 359px)");
        var midScreen = window.matchMedia("(min-width: 769px) and (max-width: 1200px)");
        var largeScreen = window.matchMedia("(min-width: 1201px)");
        if (smallScreen.matches){
            this.innerSize = '10%';
            this.size = 100;
        };
        if (tinyScreen.matches){
            this.innerSize = '1%';
            this.size = 90;
        };
        if (midScreen.matches){
            this.innerSize = '10%';
            this.size = 150;
        };
        if (largeScreen.matches){
            this.innerSize = '25%';
            this.size = 250;

        };
        this.graphOptions.plotOptions.pie.innerSize = this.innerSize;
        this.graphOptions.plotOptions.pie.size = this.size;
        this.create()
    };
    PIE.prototype.create = function () {
        var $this = this;
        var options = $.extend(true, {}, $this.graphOptions, {series: $this.series});
        $('#' + $this.container).highcharts(options);
    };
    MyApp.modules.dashboard.piechart = function (container, series, graphOptions) {
        var obj = new PIE(container, series, graphOptions);
        obj.screenSize();
        $( window ).resize(function () {
            obj.screenSize();
        });
    };
    //MyApp.plugin.gridPieChart = PLUGIN;
}(jQuery));

(function ($) {
    'use strict';
    var CHART = function (container, series, graphOptions) {
        var defaultOptions = {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null, //null,
                plotShadow: false,
                backgroundColor: 'transparent',
                spacingTop: 3,
                type: 'line',
            },
            credits: {
                enabled: false,
            },
            title: {
                text: null,
            },
            subtitle: {
                text: null,
            },
            tooltip: {
                crosshairs: true,
                shared: true,
            },
            yAxis: {
                title: {
                    text: null,
                    style: {fontWeight: 'normal'},
                },
            },
            xAxis: {
                title: {
                    text: null,
                    style: {fontWeight: 'normal'},
                },
                accessibility: {
                    rangeDescription: null
                },
                crosshair: true
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                symbolHeight: 12,
                symbolWidth: 12,
                symbolRadius: 6,
            },
            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                    pointStart: null
                },
                line: {
                    dataLabels: {
                        enabled: false
                    }
                },
                column: {
                    //colorByPoint: true
                }
            },
            colors: [
                '#771957',
                '#7986CB',
                '#7F5298',
            ],
        };
        this.container = container;
        this.series = series;
        this.graphOptions = $.extend(true, {}, defaultOptions, graphOptions || {});
        this.create();
    };
    CHART.prototype.create = function () {
        var $this = this;
        var options = $.extend(true, {}, $this.graphOptions, {series: $this.series});
        $('#' + $this.container).highcharts(options);
    };
    MyApp.modules.dashboard.chart = function (container, series, graphOptions) {
        var obj = new CHART(container, series, graphOptions);
    };
}(jQuery));
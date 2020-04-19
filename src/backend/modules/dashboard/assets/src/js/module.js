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
    PIE.prototype.screenSize = function () {
        var smallScreen = window.matchMedia("(max-width: 768px)");
        var tinyScreen = window.matchMedia("(max-width: 359px)");
        var midScreen = window.matchMedia("(min-width: 769px) and (max-width: 1200px)");
        var largeScreen = window.matchMedia("(min-width: 1201px)");
        if (smallScreen.matches) {
            this.innerSize = '10%';
            this.size = 100;
        }
        ;
        if (tinyScreen.matches) {
            this.innerSize = '1%';
            this.size = 90;
        }
        ;
        if (midScreen.matches) {
            this.innerSize = '10%';
            this.size = 150;
        }
        ;
        if (largeScreen.matches) {
            this.innerSize = '25%';
            this.size = 250;

        }
        ;
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
        $(window).resize(function () {
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
                '#771957', '#7986CB', '#7F5298',
                '#001D00', '#9EEDB3', '#004619',
                '#056030', '#2B7B48', '#489661',
                '#65B27C', '#81D097', '#002E00',
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

(function ($) {
    "use strict";
    var DATAVIZ = function (options) {
        let defaultOptions = {
            ajaxAction: '',
            ajaxCharts: [
                {
                    name: 'milk',
                    renderContainer: '#milkChart'
                },
                {
                    name: 'calf',
                    renderContainer: '#calfChart'
                },
            ],
        };
        this.options = $.extend({}, defaultOptions, options || {});
    }

    DATAVIZ.prototype.loadAjaxCharts = function () {
        let $this = this;

        let _load = function (chart) {
            let url = chart.url;
            let renderContainer = $(chart.renderContainer);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'html',
                data: {name: chart.name},
                success: function (data) {
                    renderContainer.html(data).fadeIn("slow");
                },
                beforeSend: function (xhr) {
                    renderContainer.html('<div class="col-md-12"><h1 class="text-center text-warning" style="margin:40px;"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</h1></div>');
                },
                error: function (xhr) {
                    renderContainer.html(xhr.responseText);
                    if (MyApp.DEBUG_MODE) {
                        console.log(xhr);
                    }
                }
            })
        }
        //note that this code is executed in on load. So we don't need to call on load again, Otherwise this will fail to fire sometimes
        /* $(window).on('load', function() {
             let charts = $this.options.ajaxCharts;
             charts.forEach(function (item, index) {
                 item.url = $this.options.ajaxAction;
                 _load(item);
             })
         });*/

        let charts = $this.options.ajaxCharts;
        charts.forEach(function (item, index) {
            item.url = $this.options.ajaxAction;
            _load(item);
        })
    }

    MyApp.modules.dashboard.dataviz = function (options) {
        let obj = new DATAVIZ(options);
        obj.loadAjaxCharts();
    }

}(jQuery));
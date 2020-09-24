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
                stackLabels: {
                    style: {
                        color: '#000000',
                        fontWeight: 'normal',
                        textOutline: '1px contrast',
                    },
                    enabled: true,
                    verticalAlign: 'bottom',
                    formatter: function () {
                        //console.log(this);
                        return this.stack;
                    }
                }
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
                    pointStart: null,
                    connectNulls: true,
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
                '#9EEDB3', '#001D00', '#004619', '#002C00',
                '#1B4F72', '#336083', '#487293', '#5D84A5',
                '#7197B6', '#86AAC8', '#9BBEDA', '#B0D2EC',
                '#771957', '#7986CB', '#7F5298', '#65B27C',
                '#056030', '#2B7B48', '#27921E', '#81D097',
                '#6298D7', '#45ADC3', '#2EAB86', '#489661',
                '#177380', '#D3E36F', '#DBB450', '#C97434',
                '#AE2921', '#8C2B16', '#F00C0C', '#350d36',
                '#EB6060', '#E39494', '#9C0204', '#853536',
                '#C25D55', '#FF9900', '#875F03', '#F6FF00',
                '#800080', '#902C8E', '#A0479D', '#AF60AC',
                '#BE78BB', '#CD90C9', '#DCA8D9', '#EBC0E8',
                '#FAD8F7', '#000000', '#1E1E1E', '#363636',
                '#4F4F4F', '#6A6A6A', '#878787', '#A4A4A4',
                '#C3C3C3', '#E2E2E2', '#ECBEB3', '#FFD7CD',
                '#641E16', '#783429', '#8B4A3E', '#9F6054',
                '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
            ],
            color: [
                // countries
                '#875F03', '#65B27C', '#5DB4A5',
                // Holsteins
                '#7F5298',
                '#800080', '#902C8E', '#A0479D', '#AF60AC',

                //Holstein Crosses
                '#EBC0E8',
                '#BE78BB', '#CD90C9', '#DCA8D9', '#EBC0E8',
                //jerseys
                '#C25D55',
                '#B2776A', '#C58E82', '#D9A69A',
                '#641E16', '#783429', '#8B4A3E', '#9F6054',
                //Guernsey
                '#A07D62',
                //Ayrshires
                '#C97434',
                '#E3955A', '#FFBF8E', '#A5561A', '#7D3701',
                '#c97a34', '#A55B1A',
                //Ayrshire crosses
                '#DBB450',
                //Brown Swiss
                '#771957',
                //jersey Guernsey crosses
                '#D2C966',
                //Ind Zebu
                '#245A62',
                //Ind Zebu Tzn
                '#509D99',
                '#0d81e2','#0e87eb','#138cf1','#1c90f2',
                '#2695f2','#309af3','#399ef3','#43a3f4','#4ca8f4',
                '#56adf5',
                //Ind Zebu Ken
                '#9BAAD8',
                //Ind Zebu Eth
                '#0A60A8',
                //Ind Sanga
                '#65B27C',
                //Exotic B Taurus Dual purpose
                '#2F158B',
                '#336699', '#1a334d', '#8cb3d9', '',
                //Unknown - use a lighter gray
                '#363636',
                '#4F4F4F', '#6A6A6A', '#878787',
                '#7D3701', '#641E16', '#27921E',
                '#FFBF8E', '#489661', '#C97434',
            ]
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
                /*
                {
                    name: 'milk',
                    renderContainer: '#milkChart'
                },
                {
                    name: 'calf',
                    renderContainer: '#calfChart'
                },
                */
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

        let charts = $this.options.ajaxCharts;
        charts.forEach(function (item, index) {
            item.url = $this.options.ajaxAction;
            _load(item);
        })
    }

    MyApp.modules.dashboard.dataviz = function (options) {
        let obj = new DATAVIZ(options);
        obj.loadAjaxCharts();

        $(".chart-filter-form").submit(function( event ) {
            event.preventDefault();
            //console.log(event.target);
            let form = event.target;
            let name = $(form).data('name');
            let formdata = $(form).serialize();
            let defaultCharts = options.ajaxCharts;
            let chart = defaultCharts.find(function(chartObj) {
                return chartObj['name'] === name;
            });
            //console.log(formdata)
            //console.log(name)
            //console.log(chart)
            //let viz = new DATAVIZ({
            //    ajaxAction: chart.url,
            //    ajaxCharts : [chart]
            //})
            //console.log(viz);
            //viz.loadAjaxCharts();
            let url = chart.url;
            let renderContainer = $(chart.renderContainer);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'html',
                //data: {name: chart.name},
                data: formdata + '&' + $.param({name: chart.name}),
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
        });
    }

}(jQuery));
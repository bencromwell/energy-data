$(document).ready(function () {

    if (window.chartData) {
        $('#chart').highcharts({
            chart: {
                type: 'line'
            },
            title: false,
            xAxis: {
                title: false,
                type: 'datetime',
                tickInterval: 30 * 24 * 3600 * 1000,
                minRange: 365 * 24 * 3600 * 1000,
                startOnTick: true,
                endOnTick: true,
                min: window.chartData.start
            },
            yAxis: {
                title: {
                    text: 'kWh'
                }
            },
            series: [
                {
                    name: 'Electricity',
                    data: window.chartData.e
                },
                {
                    name: 'Gas',
                    data: window.chartData.g
                }
            ]
        });
    }

});

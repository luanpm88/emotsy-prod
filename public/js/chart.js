/* ------------------------------------------------------------------------------
 *
 *  # Echarts - columns and waterfalls
 *
 *  Columns and waterfalls chart configurations
 *
 *  Version: 1.0
 *  Latest update: August 1, 2015
 *
 * ---------------------------------------------------------------------------- */

function updateChart(item) {
    var url = item.attr("data-url");
    var id = item.attr("id");
    
    $.ajax({
        method: "GET",
        url: url,
    })
    .done(function( msg ) {
        showChart(id, JSON.parse(msg));
    });
}


var charts = new Object();

function showChart(id, data) {

    // Set paths
    // ------------------------------

    require.config({
        paths: {
            echarts: APP_URL + '/assets/js/plugins/visualization/echarts'
        }
    });


    // Configuration
    // ------------------------------

    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],


        // Charts setup
        function (ec, limitless) {


            // Initialize charts
            // ------------------------------            
            
            charts[id] = ec.init(document.getElementById(id), limitless);

            // Charts setup
            // ------------------------------


            //
            // Basic columns options
            //

            basic_columns_options = {

                // Setup grid
                grid: {
                    x: 40,
                    x2: 40,
                    y: 35,
                    y2: 25
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: data["bar_names"]
                },

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: data["columns"]
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value'
                }],

                // Add series
                series: data["data"],

            };


            // Apply options
            // ------------------------------

            charts[id].setOption(basic_columns_options);


            // Resize charts
            // ------------------------------

            window.onresize = function () {
                setTimeout(function () {
                    for (var key in charts) {
                        charts[key].resize();
                    }
                    
                }, 200);
            }
        }
    );
}
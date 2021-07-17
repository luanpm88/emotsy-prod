<div class="row">
    <div class="col-md-12">
        <!-- Basic column chart -->
        <div class="panel panel-flat border p-3 shadow-sm rounded-3">
            <div class="panel-body">
                <div class="chart-container">
                    <div class="chart has-fixed-height-250" id="CustomersGrowthChart"
                        style="width: 100%;height:350px;"
                    ></div>
                </div>
            </div>
        </div>
        <!-- /basic column chart -->
    </div>
</div>

<script>
    $(function() {
        CustomersGrowthChart.showChart();
    });
    var CustomersGrowthChart = {
        url: '{{ action('Admin\CustomerController@growthChart') }}',
        getChart: function() {
            return $('#CustomersGrowthChart');
        },

        showChart: function() {
            $.ajax({
                method: "GET",
                url: this.url,
            })
            .done(function( response ) {
                // console.log(response.columns);
                CustomersGrowthChart.renderChart( response );
            });
        },

        renderChart: function(data) {
                // based on prepared DOM, initialize echarts instance
                var growthChart1 = echarts.init(CustomersGrowthChart.getChart()[0]);

                var option = {
                    grid: {
                        bottom: '20px'
                    },
                    title: {
                        text: '{{ trans('messages.customers_growth') }}',
                        // subtext: '{{ trans('messages.growth_chart') }}',
                        left: 'center'
                    },
                    xAxis: {
                        type: 'category',
                        data: data.columns
                    },
                    yAxis: {
                        type: 'value',
                        name: '{{ trans('messages.subscribers') }}',
                    },
                    series: [{
                        label: {
                            show: true
                        },
                        itemStyle: {
                            color: '#81ac8d'
                        },
                        data: data.data,
                        type: 'bar'
                    }]
                };

                // use configuration item and data specified to show chart
                growthChart1.setOption(option);
        }
    }
</script>

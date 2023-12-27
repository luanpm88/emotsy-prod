<div class="row mb-4">
    <div class="col-md-12">
        <div class="card position-relative h-100 box-card">
            <div class="card-header bg-white">
                 
                <div class="d-flex justify-content-between align-items-center">
                    <div class="headtitle">
                        <h2>Performance</h2>
                        <span>
                            {{ trans('messages.campaign.performance_chart.info') }}
                        </span>
                    </div>
                    <div class="d-flex">
                        <div class="">
                            <select id="PerformancePeriod" class="select select-control" name="last_performance_time">
                                <option value="24h">{{ trans('messages.campaign.24_hour') }}</option>
                                <option value="3_days">{{ trans('messages.campaign.last_3_days') }}</option>
                                <option value="7_days">{{ trans('messages.campaign.last_7_days') }}</option>
                                <option value="last_month">{{ trans('messages.campaign.last_month') }}</option>
                            </select>
                            
                        </div>
                        <div class="perfometag">
                            <ul class="pagination"> 
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item "><a class="page-link" href="#">2</a></li>
                                <li class="page-item "><a class="page-link" href="#">3</a></li> 
                            </ul>
                        </div>
                    </div>
                   
                </div>

            </div>
            <div class="card-body py-4 px-3">
                <div id="Campaigns24hChart"
                    class=""
                    style="width:100%; height:350px;"
                ></div>
            </div>
        </div>
    </div>
</div>



<script>
    var Campaigns24hChart = {
        url: '{{ action('CampaignController@chart24h', [
            'uid' => $campaign->uid,
            'version_b' => request()->version_b,
        ]) }}',

        getChart: function() {
            return $('#Campaigns24hChart');
        },

        getTimePeriod: function() {
            return $('[name="last_performance_time"]').val();
        },

        showChart: function() {
            $.ajax({
                method: "GET",
                url: this.url,
                data: {
                    period: this.getTimePeriod()
                }
            })
            .done(function( response ) {
                Campaigns24hChart.renderChart( response );
            });
        },

        renderChart: function(data) {
                // based on prepared DOM, initialize echarts instance
                var my2Chart = echarts.init(Campaigns24hChart.getChart()[0], ECHARTS_THEME);

                var option = {
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['{{ trans('messages.opened') }}', '{{ trans('messages.clicked') }}']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        show: false
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: data.columns
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name: '{{ trans('messages.opened') }}',
                            type: 'line',
                            itemStyle: {
                                color: '#08c6e1'
                            },
                            data: data.opened
                        },
                        {
                            name: '{{ trans('messages.clicked') }}',
                            type: 'line',
                            itemStyle: {
                                color: '#ffaa6f'
                            },
                            data: data.clicked
                        }
                    ]
                };

                // use configuration item and data specified to show chart
                my2Chart.setOption(option);
        }
    }

    $(function() {
        $('#PerformancePeriod').select2({
            dropdownAutoWidth : true,
            width: 'auto'
        })

        Campaigns24hChart.showChart();

        $('[name="last_performance_time"]').on('change', function() {
            Campaigns24hChart.showChart();
        });
    })
</script>
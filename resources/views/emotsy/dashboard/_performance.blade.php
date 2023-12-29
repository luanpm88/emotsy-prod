<div class="row mb-4">
    <div class="col-md-12">
        <div class="card position-relative h-100 box-card">
            <div class="card-header bg-white border-bottom-0">
                 
                <div class="row px-4 pt-4">
                    <div class="col-md-6 headtitle">
                        <h2 class="mb-2">Performance</h2>
                        <span class="text-muted2">
                            {{ trans('messages.campaign.performance_chart.info') }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="d-none">
                            <select id="PerformancePeriod" class="select select-control" name="last_performance_time">
                                <option value="24h">{{ trans('messages.campaign.24_hour') }}</option>
                                <option value="3_days">{{ trans('messages.campaign.last_3_days') }}</option>
                                <option value="7_days">{{ trans('messages.campaign.last_7_days') }}</option>
                                <option value="last_month">{{ trans('messages.campaign.last_month') }}</option>
                            </select>
                            
                        </div>
                        <div id="PerformancePeriodTabs" class="perfometag">
                            <ul class="pagination"> 
                                <li class="page-item active"><a data-value="24h" class="page-link fs-13px" href="javascript:;">{{ trans('messages.campaign.24_hour') }}</a></li>
                                <li class="page-item "><a data-value="3_days" class="page-link fs-13px" href="javascript:;">{{ trans('messages.campaign.last_3_days') }}</a></li>
                                <li class="page-item "><a data-value="7_days" class="page-link fs-13px" href="javascript:;">{{ trans('messages.campaign.last_7_days') }}</a></li> 
                                <li class="page-item "><a data-value="last_month" class="page-link fs-13px" href="javascript:;">{{ trans('messages.campaign.last_month') }}</a></li>
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
                        top: 'bottom',
                        left: '30',
                        data: ['{{ trans('messages.opened') }}', '{{ trans('messages.clicked') }}']
                    },
                    grid: {
                        top: '2%',
                        left: '2%',
                        right: '2%',
                        bottom: '15%',
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
                                color: '#5CC7D2'
                            },
                            data: data.opened,
                            areaStyle: {
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                {
                                    offset: 0,
                                    color: 'rgba(92,199,210,0.5)'
                                },
                                {
                                    offset: 1,
                                    color: 'rgba(92,199,210,0.0)'
                                }
                                ])
                            },
                        },
                        {
                            name: '{{ trans('messages.clicked') }}',
                            type: 'line',
                            itemStyle: {
                                color: '#ffaa6f'
                            },
                            areaStyle: {
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                {
                                    offset: 0,
                                    color: 'rgba(254,198,125,0.5)'
                                },
                                {
                                    offset: 1,
                                    color: 'rgba(254,198,125,0.0)'
                                }
                                ])
                            },
                            data: data.clicked,
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

        //
        new ChartPeriod();
    })

    var ChartPeriod = class {
        constructor() {
            this.events();
        }

        getPeriodSelect() {
            return $('#PerformancePeriod');
        }

        getPeriodTabLinks() {
            return $('#PerformancePeriodTabs .page-link');
        }

        events() {
            var _this = this;
            this.getPeriodTabLinks().on('click', function() {
                var value = $(this).attr('data-value');

                _this.getPeriodSelect().val(value).change();

                _this.getPeriodTabLinks().closest('.page-item').removeClass('active');
                $(this).closest('.page-item').addClass('active');
            });
        }
    }
</script>
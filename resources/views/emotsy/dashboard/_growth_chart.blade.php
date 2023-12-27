<div class="card position-relative h-100 box-card">
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">List growth</div>
        <span>
             <a href="{{ action('AudienceController@overview') }}">{{ trans('messages.dashboard.details') }}</a>
        </span>
    </div>
    <div class="card-body px-36 pt-0">
        <!-- Basic column chart -->
        <div class="">
            <div class="">
                <div class="chart-container">
                    <div id="ListsGrowthChart1"
                        class="chart has-fixed-height"
                        style="width: 100%; height:250px;"
                    ></div>
                </div>
            </div>
        </div>
        <!-- /basic column chart -->

        <script>
            var ListsGrowthChart1 = {
                url: '{{ action('MailListController@listGrowthChart', $list->uid) }}',
                getChart: function() {
                    return $('#ListsGrowthChart1');
                },
        
                showChart: function() {
                    $.ajax({
                        method: "GET",
                        url: this.url,
                    })
                    .done(function( response ) {
                        ListsGrowthChart1.renderChart( response );
                        //console.log(response);
                    }); 
                }, 
                renderChart: function(data) {
                        // based on prepared DOM, initialize echarts instance
                        var growthChart1 = echarts.init(ListsGrowthChart1.getChart()[0], ECHARTS_THEME); 
                        var option = { 
                            grid: {
                                top: '8%',
                                left: '0%',
                                right: '0%',
                                bottom: '0%',
                                containLabel: true
                            },
                            xAxis: {
                                boundaryGap:false, 
                                type: 'category',
                                data: data.columns,  
                                axisLine: {
                                    onZero: false , 
                                    show:true
                                },
                                splitLine:{
                                    show:false
                                },
                                axisLabel: { 
                                    align: 'right' 
                                }
                            },
                            yAxis: {  
                                boundaryGap:true, 
                                type: 'value', 
                                axisLine: {
                                    onZero: false , 
                                },
                                splitLine:{  
                                    show:true 
                                } 
                            },
                            
                            series: [{ 
                                /* label: {   show: true },  */
                                itemStyle: {
                                    color: '#08c6e1',
                                    borderRadius: [0, 0, 0, 0], 
                                },
                                data: data.data,
                                type: 'bar',
                                barWidth: '8px',
                            }]
                        };
        
                        // use configuration item and data specified to show chart
                        growthChart1.setOption(option);
                }
            }
        
            ListsGrowthChart1.showChart();
        </script>
    </div> 

    <div class="mt-4">
        <div class="d-flex align-items-top">
            <div id="chartsub" class="chart w-40">
                @if (true)
                    <!-- Basic column chart -->
                    <div class="w-100">
                        <div class="">
                            <div class="chart-container">
                                <div id="listPieChart"
                                    class="has-fixed-height"
                                    style="width: 100%; height:150px;"
                                ></div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        $(function() {
                            ListPieChart.showChart();
                        });
                        var ListPieChart = {
                            url: '{{ action('MailListController@statisticsChart', 'all') }}',
                            getChart: function() {
                                return $('#listPieChart');
                            },
                    
                            showChart: function() {
                                $.ajax({
                                    method: "GET",
                                    url: this.url,
                                })
                                .done(function( response ) {
                                    ListPieChart.renderChart( response.data );

                                    // render list
                                    $('.chart-iters ul').html('');

                                    // colors
                                    var colors = [
                                        '#76ddc2',
                                        '#08c6e1',
                                        '#10a6bc',
                                        '#ffaa6f',
                                        '#ffd773',
                                        '#ff4640',
                                        '#ff8068',
                                        '#00bffd',
                                        '#aa2bf3',
                                    ];

                                    var i = 0;
                                    response.data.forEach(item => {
                                        $('.chart-iters ul').append(`
                                            <li class="subscrib text-nowrap">
                                                <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="6.22217" cy="6.42929" r="5.63916" fill="`+colors[i]+`"/>
                                                </svg>  
                                                `+item.name+` `+item.percent+` % (`+item.value+`)
                                            </li>
                                        `);

                                        i += 1;
                                    });
                                        
                                });
                            },
                    
                            renderChart: function(data) {
                                // based on prepared DOM, initialize echarts instance
                                var growthChart2 = echarts.init(ListPieChart.getChart()[0], ECHARTS_THEME);

                                var colors = [
                                    '#76ddc2',
                                    '#08c6e1',
                                    '#10a6bc',
                                    '#ffaa6f',
                                    '#ffd773',
                                    '#ff4640',
                                    '#ff8068',
                                    '#00bffd',
                                    '#aa2bf3',
                                ];

                                var cData = data.map(function(item, index) {
                                    return {
                                        name: item.name,
                                        value: item.value,
                                        itemStyle: {
                                            color: colors[index],
                                            borderWidth: 1,  borderType: 'solid', borderColor: '#fff'
                                        }
                                    };
                                });
                                
                                var colorPalette = ['#76DDC2','#00C1FF', '#FFD569', '#FFAB6F','#FF7F64','#3EE0EA']
                                var option = {
                                        tooltip: {
                                            trigger: 'item',
                                            formatter: '<strong>{b}</strong><br />{c} ({d}%)',  
                                        },  
                                            
                                        legend: {data:[]}, 
                                        series: [
                                            {
                                                name:  {
                                                    trigger: 'item',
                                                },  
                                                top:0,  
                                                left:0,
                                                right:0,
                                                type: 'pie',
                                                center: ['50%', '35%'],
                                                radius: ['50%', '70%'],
                                                avoidLabelOverlap: false,
                                                color: colorPalette,
                                                label: {
                                                    show: false,
                                                    //position: 'center'
                                                }, 
                                                labelLine: {
                                                    show: false
                                                },
                                                data: cData
                                            }
                                        ]
                                    };
                                // use configuration item and data specified to show chart
                                growthChart2.setOption(option);
                            }
                        }    
                    </script>
        
                @else
                    <div class="empty-chart-pie">
                        <div class="empty-list">
                            <span class="material-symbols-rounded">auto_awesome</span>
                            <span class="line-1">
                                {{ trans('messages.log_empty_line_1') }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="chart-info w-50">
                <div class="chart-subscrib pb-2">
                    {{ trans('messages.subscribers') }}
                </div>
                <div class="chart-iters d-flex align-items-center" style="min-height:80px">
                    <ul>
                        <li class="subscrib">
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="6.22217" cy="6.42929" r="5.63916" fill="#3EE0EA"/>
                            </svg>  
                            Subscribed 94.11 % (2638)</li>
                        <li class="unubscrib">
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="6.22217" cy="6.42929" r="5.63916" fill="#FFC67C"/>
                                </svg> 
                            Unsubscribed 5.98 % (165)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        


        

    </div>
</div>
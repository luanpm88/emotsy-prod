<div class="card position-relative h-100 box-card">
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">List growth</div>
        <span>
             <a href="#">Details</a>
        </span>
    </div>
    <div class="card-body px-36 pt-0">
        <!-- Basic column chart -->
        <div class="">
            <div class="">
                <div class="chart-container">
                    <div id="ListsGrowthChart1"
                        class="chart has-fixed-height"
                        style="width: 100%; height:300px;"
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
                                left: '0%',
                                right: '2%',
                                bottom: '3%',
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
        <div class="d-flex align-items-center">
            <div id="chartsub" class="chart w-50">
                <div class="c100 big p94"> 
                    <div class="slice">
                        <div class="bar"></div>
                        <div class="fill"></div>
                    </div>
                </div> 
            </div>
            <div class="chart-info w-50">
                <div class="chart-subscrib pb-4">
                    Subscribers
                </div>
                <div class="chart-iters">
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
        


        @if ($list->readCache('SubscriberCount') || (!isset($list->id) && Auth::user()->customer->readCache('SubscriberCount')))
            <!-- Basic column chart -->
            <div class="">
                <div class="">
                    <div class="chart-container">
                        <div id="ListsGrowthChart2"
                            class="chart has-fixed-height"
                            style="width: 100%;height:450px;"
                        ></div>
                    </div>
                </div>
            </div>
            <!-- /basic column chart -->
 
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
</div>
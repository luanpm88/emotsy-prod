<div class="card position-relative h-100 box-card">
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">Countries</div> 
    </div> 
    <div class="card-body py-4 px-3">
        <div class="card-body py-4 px-3 d-flex align-items-center">
           <!-- Basic column chart -->
            <div class="">
                <div class="">
                    <div class="chart-container">
                        <div id="topcountries"
                            class="chart has-fixed-height"
                            style="width: 100%; height:300px;"
                        ></div>
                    </div>
                </div>
            </div>
            <!-- /basic column chart -->

            <script>
               var Listtopcountries = {
                    url: '{{ action('MailListController@listGrowthChart', $list->uid) }}',
                    getChart: function() {
                        return $('#topcountries');
                    },
            
                    showChart: function() {
                        $.ajax({
                            method: "GET",
                            url: this.url,
                        })
                        .done(function( response ) {
                            Listtopcountries.renderChart( response );
                            //console.log(response);
                        }); 
                    }, 
                    renderChart: function(data) {
                            // based on prepared DOM, initialize echarts instance
                            var topcountries = echarts.init(Listtopcountries.getChart()[0], ECHARTS_THEME); 
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
                            topcountries.setOption(option);
                    }
                }
            
                Listtopcountries.showChart();
            </script>
        </div>
    </div>
</div>
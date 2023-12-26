<div class="card position-relative h-100 box-card"> 
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">Top 5 Countries</div> 
    </div> 
    <div class="card-body p-4"> 
        <div class="p-4 w-100">
            <div class="card-body py-4 px-3 d-flex align-items-center">
               <!-- Basic column chart -->
            <div class="">
                <div class="">
                    <div class="chart-container">
                        <div id="contries"
                            class="chart has-fixed-height"
                            style="width: 100%; height:300px;"
                        ></div>
                    </div>
                </div>
            </div>
            <!-- /basic column chart -->

            <script>
                
                var Listcontries = {
                    url: '{{ action('MailListController@listGrowthChart', $list->uid) }}',
                    getChart: function() {
                        return $('#contries');
                    },
            
                    showChart: function() {
                        $.ajax({
                            method: "GET",
                            url: this.url,
                        })
                        .done(function( response ) {
                            Listcontries.renderChart( response );
                            //console.log(response);
                        }); 
                    }, 
                    renderChart: function(data) {
                            // based on prepared DOM, initialize echarts instance
                            var contries = echarts.init(Listcontries.getChart()[0], ECHARTS_THEME);  
                            var option = {
                                title: { 
                                    sublink: 'http://esa.un.org/wpp/Excel-Data/population.htm',
                                    left: 'center',
                                    top: 'top'
                                },
                                tooltip: {
                                    trigger: 'item',
                                    formatter: function (params) {
                                        var value = (params.value + '').split('.');
                                        value = value[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,')
                                            + '.' + value[1];
                                        return params.seriesName + '<br/>' + params.name + ' : ' + value;
                                    }
                                },

                                visualMap: {
                                    min: 0,
                                    max: 1000000,
                                    text:['High','Low'],
                                    realtime: false,
                                    calculable: true,
                                    color: ['orangered','yellow','lightskyblue']
                                },
                                series: [
                                    {
                                        name: 'World Population (2010)',
                                        type: 'map',
                                        mapType: 'world',
                                        roam: true,
                                        itemStyle:{
                                            emphasis:{label:{show:true}}
                                        },
                                        data:[
                                            {name: 'Afghanistan', value: 28397.812},
                                            {name: 'Angola', value: 19549.124},
                                            {name: 'Albania', value: 3150.143},
                                            {name: 'United Arab Emirates', value: 8441.537},
                                            {name: 'Argentina', value: 40374.224}, 
                                        ]
                                    }
                                ]
                            };
                             
                            // use configuration item and data specified to show chart
                            contries.setOption(option);
                    }
                }
            
                Listcontries.showChart();
            </script>
            </div>
        </div>
    </div>
</div>
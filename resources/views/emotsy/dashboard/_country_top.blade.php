<div class="card position-relative h-100 box-card">
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">Countries</div> 
    </div> 
    <div class="card-body"> 
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

                            var colorPalette = ['#00C1FF','#76DDC2', '#FFD569', '#FFAB6F','#FF7F64','#3EE0EA']
                            var option = {
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: '<strong>{b}</strong><br />{c} ({d}%)'
                                    },
                                    

                                        legend: {
                                            //orient: 'vertical',
                                            position: 'bottom',
                                            //left: 10,
                                            data: ['so 1', 'so 2', 'so 3', 'so 4', 'so 5'], 
                                       
                                    }, 
                                    series: [
                                        {
                                            name:  {
                                                trigger: 'item',
                                            },  
                                            type: 'pie',
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
                                            data: [
                                                {value: 135, name: 'so 1'},
                                                {value: 235, name: 'so 2'},
                                                {value: 110, name: 'so 3'},
                                                {value: 134, name: 'so 4'},
                                                {value: 335, name: 'so 5'},
                                                {value: 948, name: 'so 6'}
                                            ]
                                        }
                                    ]
                                };
            
                            // use configuration item and data specified to show chart
                            topcountries.setOption(option);
                    }
                }
            
                Listtopcountries.showChart();
            </script>
        
    </div>
</div>
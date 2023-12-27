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
                            style="width: 100%; height:450px;"
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

                            var colorPalette = ['#76DDC2','#00C1FF', '#FFD569', '#FFAB6F','#FF7F64','#3EE0EA']
                            var option = {
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: '<strong>{b}</strong><br />{c} ({d}%)',  
                                    },  
                                     
                                    legend: {
                                        //orient: 'vertical',
                                        //type: 'grid', 
                                        bottom: 0, 
                                        textStyle: {
                                            color: '#8094AE',
                                            fontSize: 13,
                                            fontWeight: 'normal',
                                            //fontStyle: 'italic',
                                            // define rich styles
                                            rich: {
                                                a: {
                                                    width: 108,
                                                    height: 30,
                                                    backgroundColor: '#ffffff',
                                                    border: 1,
                                                    fontfamily:'DM Sans',
                                                    //borderRadius: 4,
                                                    //color: '#fff',
                                                    //align: 'center',
                                                    lineHeight: 30,
                                                    fontSize: 14,
                                                }
                                            },
                                        },
                                    
                                        formatter: function(name) {  return '{a|' + name + '}';    }, 
                                        //formatter: function(name) {  return '<span class="w-30">' + name + "</span>";   },
                                        
                                        //left: 10,
                                        padding:  [5, 0, 15, 0],  
                                        color: colorPalette, 
                                        data: [
                                                { name: 'United States', icon: 'circle'  },                                                
                                                { name: 'Lithuania', icon: 'circle'   }, 
                                                { name: 'Singapore', icon: 'circle'  }, 
                                                { name: 'Others', icon: 'circle'   },
                                                { name: 'United Kingdom', icon: 'circle'  },  
                                                { name: 'Latvia', icon: 'circle' }, 
                                            ], 
                                    }, 
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
                                            data: [
                                                {value: 135, name: 'United States'},
                                                {value: 235, name: 'United Kingdom'},
                                                {value: 110, name: 'Lithuania'},
                                                {value: 134, name: 'Singapore'},
                                                {value: 335, name: 'Others'},
                                                {value: 948, name: 'Latvia'}
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
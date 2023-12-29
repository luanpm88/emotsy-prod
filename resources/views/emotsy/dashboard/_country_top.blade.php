<div class="card position-relative h-100 box-card">
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">{{ trans('messages.countries') }}</div> 
    </div> 
    <div class="card-body"> 
        @if ($campaign->uniqueOpenCount())
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
            
            <script>
                $(function() {
                    OpenPieChart.showChart();
                });
                var OpenPieChart = {
                    url: '{{ action('CampaignController@chartCountry', [
                        'uid' => $campaign->uid,
                        'version_b' => request()->version_b,
                    ]) }}',
                    getChart: function() {
                        return $('#topcountries');
                    },
            
                    showChart: function() {
                        $.ajax({
                            method: "GET",
                            url: this.url,
                        })
                        .done(function( response ) {
                            OpenPieChart.renderChart( response.data );
                        });
                    },
            
                    renderChart: function(data) {
                        // based on prepared DOM, initialize echarts instance
                        var growthChart2 = echarts.init(OpenPieChart.getChart()[0], ECHARTS_THEME);

<<<<<<< HEAD
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
                                                    width: 111,
                                                    maxwith:120,
                                                    height: 30,
                                                    backgroundColor: '#ffffff',
                                                   // border: 1,
                                                    fontfamily:'DM Sans',
                                                    //borderRadius: 4,
                                                    //color: '#fff',
                                                    //align: 'center',
                                                    lineHeight: 30,
                                                    fontSize: 14,
                                                }
                                            },
=======
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
                                                // backgroundColor: '#ffffff',
                                                border: 1,
                                                fontfamily:'DM Sans',
                                                //borderRadius: 4,
                                                //color: '#fff',
                                                //align: 'center',
                                                lineHeight: 30,
                                                fontSize: 14,
                                            }
>>>>>>> 1cae555fb68e7f6f86cea4bc5a684294b538df47
                                        },
                                    },
                                
                                    formatter: function(name) {  return '{a|' + name + '}';    }, 
                                    //formatter: function(name) {  return '<span class="w-30">' + name + "</span>";   },
                                    
<<<<<<< HEAD
                                        formatter: function(name) {  return '{a|' + name + '}';    }, 
                                        //formatter: function(name) {  return '<span class="w-30">' + name + "</span>";   },
                                        
                                        left:0,
                                        right:0,
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
=======
                                    //left: 10,
                                    padding:  [5, 0, 15, 0],  
                                    color: colorPalette, 
                                    data: 
                                        data.map(function(item, index) {
                                            return {
                                                name: item.name,
                                                icon: 'circle',
                                            };
                                        })
                                    
                                    , 
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
                                        data: cData
                                    }
                                ]
                            };
                        // var option = {
                        //     title: {
                        //         text: '{{ trans('messages.countries') }}',
                        //         // subtext: '{{ trans('messages.statistics_chart') }}',
                        //         left: 'center'
                        //     },
                        //     tooltip: {
                        //         trigger: 'item',
                        //         formatter: '{b}: {c} ({d}%)'
                        //     },
                        //     legend: {
                        //         orient: 'vertical',
                        //         left: 'right',
                        //     },
                        //     series: [
                        //         {
                        //             selectedMode: 'single',
                        //             type: 'pie',
                        //             radius: '70%',
                        //             data: cData,
                        //             emphasis: {
                        //                 itemStyle: {
                        //                     shadowBlur: 10,
                        //                     shadowOffsetX: 0,
                        //                     shadowColor: 'rgba(0, 0, 0, 0.5)'
                        //                 }
                        //             },
                        //             label: {
                        //                 // position: 'inner',
                        //                 fontSize: 12,
                        //                 color: ECHARTS_THEME == 'dark' ? '#fff' : null,
                        //                 formatter: '{b}\n{d}% ({c})',
                        //             },
                        //         }
                        //     ]
                        // };
>>>>>>> 1cae555fb68e7f6f86cea4bc5a684294b538df47
            
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
</div>
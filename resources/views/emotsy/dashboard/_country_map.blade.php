<div class="card position-relative h-100 box-card"> 
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">{{ trans('messages.dashboard.top_5_countries') }}</div> 
    </div> 
    <div class="card-body">  
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
                                    return '<span style="color:#ffffff">'+ params.name + ' - ' + (params.data ? params.data.percent : '') + ' (' + params.value  + ') </span>';
                                },
                                backgroundColor: "#000000", 
                            },

                            visualMap: {
                                left: 'right',
                                min: 0,
                                max: {{ $campaign->topOpenCountries(20)->count() ? $campaign->topOpenCountries(20)->first()->aggregate : 0 }},
                                inRange: {
                                    color: [
                                    '#313695',
                                    '#4575b4',
                                    '#74add1',
                                    '#abd9e9',
                                    '#e0f3f8',
                                    '#ffffbf',
                                    '#fee090',
                                    '#fdae61',
                                    '#f46d43',
                                    '#d73027',
                                    '#a50026'
                                    ]
                                },
                                text: ['High', 'Low'],
                                calculable: false,
                                show:false,
                            },
                            /* 
                            
                            legend: { 
                                bottom: 0, 
                                left: 'left',
                                show: false,
                            },  
                            */
                            /*
                            dataRange: {
                                x: 'right',
                                y: 'bottom',
                                splitList: [
                                    {start: 1500},
                                    {start: 900, end: 1500},
                                    {start: 310, end: 1000},
                                    {start: 200, end: 300},
                                    {start: 10, end: 200, label: 'rr'},
                                    {start: 5, end: 5, label: 'ss）', color: 'black'},
                                    {end: 10}
                                ],
                                color: ['#E0022B', '#E09107', '#A3E00B']
                            },
                            */
                            series: [
                                {
                                    name:  {
                                        trigger: 'item',
                                    },
                                    type: 'map',
                                    mapType: 'world',
                                    roam: true,  
                                    itemStyle:{
                                        show:true,
                                        emphasis:{label:{show:false}},
                                        normal: {
                                            color: '#E0022B',
                                        }
                                    },
                                    data:[
                                        @foreach ($campaign->topOpenCountries(20)->get() as $location)
                                            {name: '{{ $location->getCountryName() }}', value: {{ $location->aggregate }}, percent: '{{ number_to_percentage($location->aggregate/$campaign->deliveredCount()) }}' },
                                        @endforeach
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
        <div class="list-top"> 
                <ol type="1">
                    @foreach ($campaign->topOpenCountries(5)->get() as $location)
                        <li>
                            <div class="clcontent">
                                <span class="cname">{{ $location->getCountryName() }}</span>
                                <span class="cvalue">{{ number_with_delimiter($location->aggregate, $precision = 0) }}</span>
                                <span class="cpercen">{{ number_to_percentage($location->aggregate/$campaign->deliveredCount()) }}</span>
                            </div>
                        </li>
                    @endforeach 
                </ol>
            
        </div>
    </div>
</div>
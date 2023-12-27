<div class="card position-relative h-100 box-card"> 
    <div class="d-flex justify-content-between px-36 pt-36 ">
        <div class="e-title">Top 5 Countries</div> 
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
                                    var value = (params.value + '').split('.');
                                    var percen = (params.data.percen + '').split('.');
                                    value = value[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,')
                                        + '.' + value[1];
                                    return '<span style="color:#ffffff">'+ params.name + ' - ' + percen[0] + ' % (' + value  + ') </span>';
                                },
                                backgroundColor: "#000000", 
                            },

                            visualMap: {
                                min: 0,
                                max: 1000000,
                                text:['High','Low'],
                                realtime: false,
                                calculable: true,
                                color: ['orangered','yellow','lightskyblue'],
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
                                        {name: 'Afghanistan', value: 28397.812, percen: 23.56},,
                                        {name: 'Angola', value: 19549.124, percen: 23.56},,
                                        {name: 'Albania', value: 3150.143, percen: 23.56},,
                                        {name: 'United Arab Emirates', value: 8441.537, percen: 23.56},,
                                        {name: 'Argentina', value: 40374.224, percen: 23.56},,
                                        {name: 'Armenia', value: 2963.496, percen: 23.56},,
                                        {name: 'French Southern and Antarctic Lands', value: 268.065, percen: 23.56},,
                                        {name: 'Australia', value: 22404.488, percen: 23.56},,
                                        {name: 'Austria', value: 8401.924, percen: 23.56},,
                                        {name: 'Azerbaijan', value: 9094.718, percen: 23.56},,
                                        {name: 'Burundi', value: 9232.753, percen: 23.56},,
                                        {name: 'Belgium', value: 10941.288, percen: 23.56},,
                                        {name: 'Benin', value: 9509.798, percen: 23.56},,
                                        {name: 'Burkina Faso', value: 15540.284, percen: 23.56},,
                                        {name: 'Bangladesh', value: 151125.475, percen: 23.56},,
                                        {name: 'Bulgaria', value: 7389.175, percen: 23.56},,
                                        {name: 'The Bahamas', value: 66402.316, percen: 23.56},,
                                        {name: 'Bosnia and Herzegovina', value: 3845.929, percen: 23.56},,
                                        {name: 'Belarus', value: 9491.07, percen: 23.56},,
                                        {name: 'Belize', value: 308.595, percen: 23.56},,
                                        {name: 'Bermuda', value: 64.951, percen: 23.56},,
                                        {name: 'Bolivia', value: 716.939, percen: 23.56},,
                                        {name: 'Brazil', value: 195210.154, percen: 23.56},,
                                        {name: 'Brunei', value: 27.223, percen: 23.56},,
                                        {name: 'Bhutan', value: 716.939, percen: 23.56},,
                                        {name: 'Botswana', value: 1969.341, percen: 23.56},,
                                        {name: 'Central African Republic', value: 4349.921, percen: 23.56},,
                                        {name: 'Canada', value: 34126.24, percen: 23.56},,
                                        {name: 'Switzerland', value: 7830.534, percen: 23.56},,
                                        {name: 'Chile', value: 17150.76, percen: 23.56},,
                                        {name: 'China', value: 159821.465, percen: 23.56},,
                                        {name: 'Ivory Coast', value: 60508.978, percen: 23.56},,
                                        {name: 'Cameroon', value: 20624.343, percen: 23.56},,
                                        {name: 'Democratic Republic of the Congo', value: 62191.161, percen: 23.56},,
                                        {name: 'Republic of the Congo', value: 3573.024, percen: 23.56},,
                                        {name: 'Colombia', value: 46444.798, percen: 23.56},,
                                        {name: 'Costa Rica', value: 4669.685, percen: 23.56},,
                                        {name: 'Cuba', value: 11281.768, percen: 23.56},,
                                        {name: 'Northern Cyprus', value: 1.468, percen: 23.56},,
                                        {name: 'Cyprus', value: 1103.685, percen: 23.56},,
                                        {name: 'Czech Republic', value: 10553.701, percen: 23.56},,
                                        {name: 'Germany', value: 83017.404, percen: 23.56},,
                                        {name: 'Djibouti', value: 834.036, percen: 23.56},,
                                        {name: 'Denmark', value: 5550.959, percen: 23.56},,
                                        {name: 'Dominican Republic', value: 10016.797, percen: 23.56},,
                                        {name: 'Algeria', value: 37062.82, percen: 23.56},,
                                        {name: 'Ecuador', value: 15001.072, percen: 23.56},,
                                        {name: 'Egypt', value: 78075.705, percen: 23.56},,
                                        {name: 'Eritrea', value: 5741.159, percen: 23.56},,
                                        {name: 'Spain', value: 46182.038, percen: 23.56},,
                                        {name: 'Estonia', value: 1298.533, percen: 23.56},,
                                        {name: 'Ethiopia', value: 87095.281, percen: 23.56},,
                                        {name: 'Finland', value: 5367.693, percen: 23.56},,
                                        {name: 'Fiji', value: 860.559, percen: 23.56},,
                                        {name: 'Falkland Islands', value: 49.581, percen: 23.56},,
                                        {name: 'France', value: 63230.866, percen: 23.56},,
                                        {name: 'Gabon', value: 1556.222, percen: 23.56},,
                                        {name: 'United Kingdom', value: 62066.35, percen: 23.56},,
                                        {name: 'Georgia', value: 4388.674, percen: 23.56},,
                                        {name: 'Ghana', value: 24262.901, percen: 23.56},,
                                        {name: 'Guinea', value: 10876.033, percen: 23.56},,
                                        {name: 'Gambia', value: 1680.64, percen: 23.56},,
                                        {name: 'Guinea Bissau', value: 10876.033, percen: 23.56},,
                                        {name: 'Equatorial Guinea', value: 696.167, percen: 23.56},,
                                        {name: 'Greece', value: 11109.999, percen: 23.56},,
                                        {name: 'Greenland', value: 56.546, percen: 23.56},,
                                        {name: 'Guatemala', value: 14341.576, percen: 23.56},,
                                        {name: 'French Guiana', value: 231.169, percen: 23.56},,
                                        {name: 'Guyana', value: 786.126, percen: 23.56},,
                                        {name: 'Honduras', value: 7621.204, percen: 23.56},,
                                        {name: 'Croatia', value: 4338.027, percen: 23.56},,
                                        {name: 'Haiti', value: 9896.4, percen: 23.56},,
                                        {name: 'Hungary', value: 10014.633, percen: 23.56},,
                                        {name: 'Indonesia', value: 240676.485, percen: 23.56},,
                                        {name: 'India', value: 105624.648, percen: 23.56},,
                                        {name: 'Ireland', value: 4467.561, percen: 23.56},,
                                        {name: 'Iran', value: 240676.485, percen: 23.56},,
                                        {name: 'Iraq', value: 30962.38, percen: 23.56},,
                                        {name: 'Iceland', value: 318.042, percen: 23.56},,
                                        {name: 'Israel', value: 7420.368, percen: 23.56},,
                                        {name: 'Italy', value: 60508.978, percen: 23.56},,
                                        {name: 'Jamaica', value: 2741.485, percen: 23.56},,
                                        {name: 'Jordan', value: 6454.554, percen: 23.56},,
                                        {name: 'Japan', value: 127352.833, percen: 23.56},,
                                        {name: 'Kazakhstan', value: 15921.127, percen: 23.56},,
                                        {name: 'Kenya', value: 40909.194, percen: 23.56},,
                                        {name: 'Kyrgyzstan', value: 5334.223, percen: 23.56},,
                                        {name: 'Cambodia', value: 14364.931, percen: 23.56},,
                                        {name: 'South Korea', value: 51452.352, percen: 23.56},,
                                        {name: 'Kosovo', value: 97.743, percen: 23.56},,
                                        {name: 'Kuwait', value: 2991.58, percen: 23.56},,
                                        {name: 'Laos', value: 6395.713, percen: 23.56},,
                                        {name: 'Lebanon', value: 4341.092, percen: 23.56},,
                                        {name: 'Liberia', value: 3957.99, percen: 23.56},,
                                        {name: 'Libya', value: 6040.612, percen: 23.56},,
                                        {name: 'Sri Lanka', value: 20758.779, percen: 23.56},,
                                        {name: 'Lesotho', value: 2008.921, percen: 23.56},,
                                        {name: 'Lithuania', value: 3068.457, percen: 23.56},,
                                        {name: 'Luxembourg', value: 507.885, percen: 23.56},,
                                        {name: 'Latvia', value: 2090.519, percen: 23.56},,
                                        {name: 'Morocco', value: 31642.36, percen: 23.56},,
                                        {name: 'Moldova', value: 103.619, percen: 23.56},,
                                        {name: 'Madagascar', value: 21079.532, percen: 23.56},,
                                        {name: 'Mexico', value: 117886.404, percen: 23.56},,
                                        {name: 'Macedonia', value: 507.885, percen: 23.56},,
                                        {name: 'Mali', value: 13985.961, percen: 23.56},,
                                        {name: 'Myanmar', value: 51931.231, percen: 23.56},,
                                        {name: 'Montenegro', value: 620.078, percen: 23.56},,
                                        {name: 'Mongolia', value: 2712.738, percen: 23.56},,
                                        {name: 'Mozambique', value: 23967.265, percen: 23.56},,
                                        {name: 'Mauritania', value: 3609.42, percen: 23.56},,
                                        {name: 'Malawi', value: 15013.694, percen: 23.56},,
                                        {name: 'Malaysia', value: 28275.835, percen: 23.56},,
                                        {name: 'Namibia', value: 2178.967, percen: 23.56},,
                                        {name: 'New Caledonia', value: 246.379, percen: 23.56},,
                                        {name: 'Niger', value: 15893.746, percen: 23.56},,
                                        {name: 'Nigeria', value: 159707.78, percen: 23.56},,
                                        {name: 'Nicaragua', value: 5822.209, percen: 23.56},,
                                        {name: 'Netherlands', value: 16615.243, percen: 23.56},,
                                        {name: 'Norway', value: 4891.251, percen: 23.56},,
                                        {name: 'Nepal', value: 26846.016, percen: 23.56},,
                                        {name: 'New Zealand', value: 4368.136, percen: 23.56},,
                                        {name: 'Oman', value: 2802.768, percen: 23.56},,
                                        {name: 'Pakistan', value: 173149.306, percen: 23.56},,
                                        {name: 'Panama', value: 3678.128, percen: 23.56},,
                                        {name: 'Peru', value: 29262.83, percen: 23.56},,
                                        {name: 'Philippines', value: 93444.322, percen: 23.56},,
                                        {name: 'Papua New Guinea', value: 6858.945, percen: 23.56},,
                                        {name: 'Poland', value: 38198.754, percen: 23.56},,
                                        {name: 'Puerto Rico', value: 3709.671, percen: 23.56},,
                                        {name: 'North Korea', value: 1.468, percen: 23.56},,
                                        {name: 'Portugal', value: 10589.792, percen: 23.56},,
                                        {name: 'Paraguay', value: 6459.721, percen: 23.56},,
                                        {name: 'Qatar', value: 1749.713, percen: 23.56},,
                                        {name: 'Romania', value: 21861.476, percen: 23.56},,
                                        {name: 'Russia', value: 21861.476, percen: 23.56},,
                                        {name: 'Rwanda', value: 10836.732, percen: 23.56},,
                                        {name: 'Western Sahara', value: 514.648, percen: 23.56},,
                                        {name: 'Saudi Arabia', value: 27258.387, percen: 23.56},,
                                        {name: 'Sudan', value: 35652.002, percen: 23.56},,
                                        {name: 'South Sudan', value: 9940.929, percen: 23.56},,
                                        {name: 'Senegal', value: 12950.564, percen: 23.56},,
                                        {name: 'Solomon Islands', value: 526.447, percen: 23.56},,
                                        {name: 'Sierra Leone', value: 5751.976, percen: 23.56},,
                                        {name: 'El Salvador', value: 6218.195, percen: 23.56},,
                                        {name: 'Somaliland', value: 9636.173, percen: 23.56},,
                                        {name: 'Somalia', value: 9636.173, percen: 23.56},,
                                        {name: 'Republic of Serbia', value: 3573.024, percen: 23.56},,
                                        {name: 'Suriname', value: 524.96, percen: 23.56},,
                                        {name: 'Slovakia', value: 5433.437, percen: 23.56},,
                                        {name: 'Slovenia', value: 2054.232, percen: 23.56},,
                                        {name: 'Sweden', value: 9382.297, percen: 23.56},,
                                        {name: 'Swaziland', value: 1193.148, percen: 23.56},,
                                        {name: 'Syria', value: 7830.534, percen: 23.56},,
                                        {name: 'Chad', value: 11720.781, percen: 23.56},,
                                        {name: 'Togo', value: 6306.014, percen: 23.56},,
                                        {name: 'Thailand', value: 66402.316, percen: 23.56},,
                                        {name: 'Tajikistan', value: 7627.326, percen: 23.56},,
                                        {name: 'Turkmenistan', value: 5041.995, percen: 23.56},,
                                        {name: 'East Timor', value: 10016.797, percen: 23.56},,
                                        {name: 'Trinidad and Tobago', value: 1328.095, percen: 23.56},,
                                        {name: 'Tunisia', value: 10631.83, percen: 23.56},,
                                        {name: 'Turkey', value: 72137.546, percen: 23.56},,
                                        {name: 'United Republic of Tanzania', value: 44973.33, percen: 23.56},,
                                        {name: 'Uganda', value: 33987.213, percen: 23.56},,
                                        {name: 'Ukraine', value: 46050.22, percen: 23.56},,
                                        {name: 'Uruguay', value: 3371.982, percen: 23.56},,
                                        {name: 'United States of America', value: 102247.116, percen: 23.56},,
                                        {name: 'Uzbekistan', value: 27769.27, percen: 23.56},,
                                        {name: 'Venezuela', value: 236.299, percen: 23.56},,
                                        {name: 'Vietnam', value: 89047.397, percen: 23.56},,
                                        {name: 'Vanuatu', value: 236.299, percen: 23.56},,
                                        {name: 'West Bank', value: 13.565, percen: 23.56},,
                                        {name: 'Yemen', value: 22763.008, percen: 23.56},,
                                        {name: 'South Africa', value: 51452.352, percen: 23.56},,
                                        {name: 'Zambia', value: 13216.985, percen: 23.56},,
                                        {name: 'Zimbabwe', value: 13076.978, percen: 23.56},
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
                    <li>
                        <div class="clcontent">
                            <span class="cname">Latvia</span>
                            <span class="cvalue">984</span>
                            <span class="cpercen">26%</span>
                        </div>
                    </li>
                    <li>
                        <div class="clcontent">
                            <span class="cname">United State</span>
                            <span class="cvalue">984</span>
                            <span class="cpercen">26%</span>
                        </div>
                    </li>
                    <li>
                        <div class="clcontent">
                            <span class="cname">United State</span>
                            <span class="cvalue">984</span>
                            <span class="cpercen">26%</span>
                        </div>
                    </li>
                </ol>
            
        </div>
    </div>
</div>
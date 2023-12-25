
var options = {
    series: [ 9, 7, 5, 8, 20, 50 ],
    chart: {
    width: 380,
    type: 'donut',
  },
  dataLabels: {
    enabled: false
  },
  responsive: [{
    breakpoint: 400,
    options: {
      chart: {
        width: 200
      },
      legend: {
        show: false
      }
    }
  }],
  plotOptions: {
    pie: {
      donut: {
        labels: {
          show: true,
          total: {
            showAlways: true,
            show: true
          }
        }, 
      }
    }
  },
  colors:[
    '#00C1FF', '#76DDC2','#FFD569', '#FFAB6F', '#FF7F64', '#3EE0EA'
  ],
    legend: {
        position: 'bottom',
        margin: '0 auto',
        offsetY: 0,
        height: 50,
        width: 300, 
    }
  };

var chart = new ApexCharts(document.querySelector("#contries"), options);

chart.render();

 

var breakup = {
    color: "#adb5bd",
    series: [9, 18],
    labels: ["2022", "2021"],
    chart: {
      width: 140,
      type: "donut",
      fontFamily: "Plus Jakarta Sans', sans-serif",
      foreColor: "#adb0bb",
    },
    plotOptions: {
      size: '90%',
      pie: {
        startAngle: 0,
        endAngle: 360, 
        donut: {
          labels: {
            show: true, 
            total: {
              show: true,
              label: '',
              formatter: () => 'ok',
            },   
          },   
        },
        
      },
    },
    stroke: {
      show: false,
    },

    dataLabels: {
      enabled: false,
    },

    legend: {
      show: false,
    },
    colors: ["#FAFAFA", "#00E8E8"],

    responsive: [
      {
        breakpoint: 991,
        options: {
          chart: {
            width: 140,
          },
        },
      },
    ],
    tooltip: {
      theme: "dark",
      fillSeriesColor: false,
    },
  };

  var chart = new ApexCharts(document.querySelector("#chart1"), breakup);
  chart.render();

<template>
      <highcharts class="chart" :options="chartOptions"></highcharts>
  
</template>

<script>
export default {
  data () {
    return {
      title: '',
      chartOptions: {
        chart: {
          type: 'column',
            style: {
                fontFamily: 'Roboto',

            },
            backgroundColor: '#f5f5f5',
        },
        
    title: {
        text: 'Project Efficency'
    },
    xAxis: {
        categories: [
            'Seattle HQ',
            'San Francisco',
            'Tokyo'
        ]
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'Employees'
        }
    }, {
        title: {
            text: 'Profit (millions)'
        },
        opposite: true
    }],
    legend: {
        shadow: false
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Employees',
        color: 'rgba(165,170,217,1)',
        data: [150, 73, 20],
        pointPadding: 0.3,
        pointPlacement: -0.2
    }, {
        name: 'Employees Optimized',
        color: 'rgba(126,86,134,.9)',
        data: [140, 90, 40],
        pointPadding: 0.4,
        pointPlacement: -0.2
    }, {
        name: 'Profit',
        color: 'rgba(248,161,63,1)',
        data: [183.6, 178.8, 198.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.3,
        pointPlacement: 0.2,
        yAxis: 1
    }, {
        name: 'Profit Optimized',
        color: 'rgba(186,60,61,.9)',
        data: [203.6, 198.8, 208.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.4,
        pointPlacement: 0.2,
        yAxis: 1
    }]
        
      }
    }
  },
  created () {
    
  },
  watch: {
    
  }
}
</script>

<style scoped>
input[type="color"]::-webkit-color-swatch-wrapper {
  padding: 0;
}
#colorPicker {
  border: 0;
  padding: 0;
  margin: 0;
  width: 30px;
  height: 30px;
}
.numberInput {
  width: 30px;
}
</style>
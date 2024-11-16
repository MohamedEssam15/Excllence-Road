/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./resources/js/used/dashboard.init.js ***!
  \*********************************************/
/*
Template Name: Minible - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: support@themesbrand.com
File: Dashboard
*/

// get colors array from the string
function getChartColorsArray(chartId) {
  if (document.getElementById(chartId) !== null) {
    var colors = document.getElementById(chartId).getAttribute("data-colors");
    if (colors) {
      colors = JSON.parse(colors);
      return colors.map(function (value) {
        var newValue = value.replace(" ", "");
        if (newValue.indexOf(",") === -1) {
          var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
          if (color) return color;else return newValue;
          ;
        } else {
          var val = value.split(',');
          if (val.length == 2) {
            var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
            rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
            return rgbaColor;
          } else {
            return newValue;
          }
        }
      });
    }
  }
}

//
// Total Revenue Chart
//
var BarchartTotalReveueColors = getChartColorsArray("total-revenue-chart");
if (BarchartTotalReveueColors) {
  var options1 = {
    series: [{
      data: [25, 66, 41, 89, 63, 25, 44, 20, 36, 40, 54]
    }],
    fill: {
      colors: BarchartTotalReveueColors
    },
    chart: {
      type: 'bar',
      width: 70,
      height: 40,
      sparkline: {
        enabled: true
      }
    },
    plotOptions: {
      bar: {
        columnWidth: '50%'
      }
    },
    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
    xaxis: {
      crosshairs: {
        width: 1
      }
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function formatter(seriesName) {
            return '';
          }
        }
      },
      marker: {
        show: false
      }
    }
  };
  var chart1 = new ApexCharts(document.querySelector("#total-revenue-chart"), options1);
  chart1.render();
}

//
// Orders Chart
//

var RadialchartOrdersChartColors = getChartColorsArray("orders-chart");
if (RadialchartOrdersChartColors) {
  var options = {
    fill: {
      colors: RadialchartOrdersChartColors
    },
    series: [70],
    chart: {
      type: 'radialBar',
      width: 45,
      height: 45,
      sparkline: {
        enabled: true
      }
    },
    dataLabels: {
      enabled: false
    },
    plotOptions: {
      radialBar: {
        hollow: {
          margin: 0,
          size: '60%'
        },
        track: {
          margin: 0
        },
        dataLabels: {
          show: false
        }
      }
    }
  };
  var chart = new ApexCharts(document.querySelector("#orders-chart"), options);
  chart.render();
}

//
// Customers Chart
//
var RadialchartCustomersColors = getChartColorsArray("customers-chart");
if (RadialchartCustomersColors) {
  var options = {
    fill: {
      colors: RadialchartCustomersColors
    },
    series: [55],
    chart: {
      type: 'radialBar',
      width: 45,
      height: 45,
      sparkline: {
        enabled: true
      }
    },
    dataLabels: {
      enabled: false
    },
    plotOptions: {
      radialBar: {
        hollow: {
          margin: 0,
          size: '60%'
        },
        track: {
          margin: 0
        },
        dataLabels: {
          show: false
        }
      }
    }
  };
  var chart = new ApexCharts(document.querySelector("#customers-chart"), options);
  chart.render();
}
//
// teachers Chart
//
var RadialchartTeachersColors = getChartColorsArray("teachers-chart");
if (RadialchartTeachersColors) {
  var options = {
    fill: {
      colors: RadialchartTeachersColors
    },
    series: [55],
    chart: {
      type: 'radialBar',
      width: 45,
      height: 45,
      sparkline: {
        enabled: true
      }
    },
    dataLabels: {
      enabled: false
    },
    plotOptions: {
      radialBar: {
        hollow: {
          margin: 0,
          size: '60%'
        },
        track: {
          margin: 0
        },
        dataLabels: {
          show: false
        }
      }
    }
  };
  var chart = new ApexCharts(document.querySelector("#teachers-chart"), options);
  chart.render();
}

//
// Sales Analytics Chart
$(document).ready(function () {
  var LinechartsalesColors = getChartColorsArray("sales-analytics-chart");
  if (LinechartsalesColors) {
    var options = {
      chart: {
        height: 343,
        type: 'line',
        stacked: false,
        toolbar: {
          show: false
        }
      },
      stroke: {
        width: [0, 2, 4],
        curve: 'smooth'
      },
      plotOptions: {
        bar: {
          columnWidth: '30%'
        }
      },
      colors: LinechartsalesColors,
      series: [{
        name: 'Packages',
        type: 'area',
        data: []
      }, {
        name: 'Courses',
        type: 'area',
        data: []
      }],
      fill: {
        opacity: [0.85, 0.25, 1],
        gradient: {
          inverseColors: false,
          shade: 'light',
          type: "vertical",
          opacityFrom: 0.85,
          opacityTo: 0.55,
          stops: [0, 100, 100, 100]
        }
      },
      labels: [],
      markers: {
        size: 0
      },
      xaxis: {
        type: 'datetime'
      },
      yaxis: {
        title: {
          text: 'Points'
        }
      },
      tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function formatter(y) {
            if (typeof y !== "undefined") {
              return y.toFixed(0) + " points";
            }
            return y;
          }
        }
      },
      grid: {
        borderColor: '#f1f1f1'
      }
    };
    var chart = new ApexCharts(document.querySelector("#sales-analytics-chart"), options);

    // Fetch monthly counts data
    $.ajax({
      url: '/get-package-course-counts',
      // Your endpoint to get data
      method: 'GET',
      success: function success(response) {
        // Assuming the response structure looks like this:
        // {
        //   "months": ["2023-11", "2023-12", ..., "2024-10"],  // List of months
        //   "package_counts": [12, 15, 20, ..., 10],  // Count of packages for each month
        //   "course_counts": [5, 10, 12, ..., 8]  // Count of courses for each month
        // }
        console.log(response);
        // Prepare the dates (months)
        var months = response.months;
        var packageCounts = response.package_counts;
        var courseCounts = response.course_counts;

        // Update chart options with the data
        chart.updateOptions({
          labels: months,
          // Set the months as labels
          series: [{
            name: 'Packages',
            type: 'area',
            data: packageCounts // Set the package counts as data
          }, {
            name: 'Courses',
            type: 'area',
            data: courseCounts // Set the course counts as data
          }]
        });

        // Render the updated chart
        chart.render();
      },
      error: function error(err) {
        console.error('Error fetching data: ', err);
      }
    });
  }
});
/******/ })()
;
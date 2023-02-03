var url = window.location.href;
$( document ).ready(function() {

     if(url.includes('admin/page/redirect/device/')){
            console.log('Generateing');
            initialize_usage_line_chart('barChart');
            
     }else if(url.includes('admin/dashboard')){
            console.log('Admin dashboard');
            initialize_sparkline_chart('init');
            initialize_barchat('donut', 'donut_chart');
            initialize_barchat('bar', 'bar_chart');
     }else{


        console.log('Not');
     }

     //initialize_sparkline_chart()
});

function initialize_sparkline_chart($operation)
{
  console.log('start sp');
  /*$('#humidity_daily_average').sparkline([100, 130, 150, 140, 120, 150, 140, 160, 130], {
            type: 'line',
            width: '100%',
            height: '100',
            chartRangeMax: 50,
            resize: true,
            lineColor: '#51aaed',
            fillColor: '#60bafd',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });
  console.log('start end');*/

   $.get(getIotSummaryData, { dev_eui : $('#dev_eui').val() }, function(reading_data) {
       console.log(reading_data);
       if(reading_data.status_code == true){
        console.log('In read');
        for (var i in reading_data['data'].graph_keys) {
              var graph_info = reading_data.data.graph_info[i];
              var graph_data = reading_data.data.data[reading_data['data'].graph_keys[i]];
              console.log(graph_data);
              console.log(graph_info.graph_id);

              var target = []; 
              for (var i in graph_data) {
                   console.log(graph_data[i]);
                   target.push(+graph_data[i]);
                   //target = target + graph_data[i] + ',';
              }
       

              console.log(target);
              $('#'+ graph_info.graph_id).sparkline(target, {
                  type: 'line',
                  width: '100%',
                  height: '100',
                  chartRangeMax: graph_info[reading_data['data'].graph_keys[i]],
                  resize: true,
                  lineColor: '#51aaed',
                  fillColor: '#60bafd',
                  highlightLineColor: 'rgba(0,0,0,.1)',
                  highlightSpotColor: 'rgba(0,0,0,.2)',
              });
        }
      }else{
          console.log('Fail');
      }

      


    });
        /*$('#sparkline1').sparkline([100, 130, 150, 140, 120, 150, 140, 160, 130, 110,100, 130, 150, 140, 120, 150, 140, 160, 130, 110], {
            type: 'line',
            width: '100%',
            height: '100',
            chartRangeMax: 50,
            resize: true,
            lineColor: '#51aaed',
            fillColor: '#60bafd',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });*/

}


function initialize_barchat(type, element) 
{
  console.log('Outside bar');
   $.get(getIotSummaryData, { dev_eui : $('#dev_eui').val() }, function(reading_data) {
        console.log('In bar');
        console.log(reading_data);
       if(reading_data.status_code == true){
           $.get(getDashboardChartData, { dev_eui : $('#dev_eui').val() }, function(graph_data) {
                    console.log('Second call');
                    console.log(graph_data);
                    console.log( graph_data['data'].bar_chart);
                    for (var i in reading_data['data'].graph_keys) {
                    }
                     if (type === 'bar') {

                            var target = []; 
                            var ta = graph_data['data'].bar_chart;
                            for (var i in ta) {
                                 console.log(ta[i]);
                                 var x = [];
                                  x['x'] = ta[i].x, 
                                  x['y'] = ta[i].y, 
                                  x['z'] = ta[i].z;
                                 target.push(x);
                                 //target = target + graph_data[i] + ',';
                            }
                            console.log(target);
                            Morris.Bar({
                                element: element,
                                data: target ,
                                /*data: [{
                                    x: 'Mon',
                                    y: 3,
                                    z: 7
                                }, {
                                    x: 'Tue',
                                    y: 3.5,
                                    z: 6
                                }, {
                                    x: 'Wed',
                                    y: 3.25,
                                    z: 5.50
                                }, {
                                    x: 'Thu',
                                    y: 2.75,
                                    z: 8
                                }, {
                                    x: 'Fri',
                                    y: 3.80,
                                    z: 9.50
                                }, {
                                    x: 'Sat',
                                    y: 7,
                                    z: 9.70
                                }, {
                                    x: 'Sun',
                                    y: 8.50,
                                    z: 9.55
                                }],*/

                                xkey: 'x',
                                ykeys: ['y', 'z'],
                                /*labels: [x_axis_label, y_axis_label],*/
                                labels: ['Light', 'Dark'],
                                barColors: ['#b0dd91', '#f7bbc7'],
                            });
                } else if (type === 'donut') {

                      var target = []; 
                      var ta = graph_data['data'].pie_chart;
                      for (var i in ta) {
                        console.log(i);
                           console.log(ta[i]);
                           var key = Object.keys(ta[i])[0];
                           console.log('Key :' + key);
                           var x = [];
                            x['label'] = i.charAt(0).toUpperCase() + i.slice(1), 
                            x['value'] = ta[i], 
                         
                           target.push(x);
                           //target = target + graph_data[i] + ',';
                      }
                      console.log(ta);

                    Morris.Donut({
                        element: element,
                        data: target,
                        colors: ['#f7bbc7', '#b0dd91'],
                        formatter: function (y) {
                            return y + '%'
                        }
                    });
                }
           });//end of second get data
      }//end of status true if
    });
        /*$('#sparkline1').sparkline([100, 130, 150, 140, 120, 150, 140, 160, 130, 110,100, 130, 150, 140, 120, 150, 140, 160, 130, 110], {
            type: 'line',
            width: '100%',
            height: '100',
            chartRangeMax: 50,
            resize: true,
            lineColor: '#51aaed',
            fillColor: '#60bafd',
            highlightLineColor: 'rgba(0,0,0,.1)',
            highlightSpotColor: 'rgba(0,0,0,.2)',
        });*/

}

function initialize_usage_line_chart(chart_id){
       // console.log(chart_id);
        
      
      $.get(getDeviceChartData, { dev_eui : $('#dev_eui').val() }, function(reading_data) {
          console.log('Called back sucess');
           console.log(reading_data);
         if(reading_data.status_code == true){

            for (var i in reading_data['data'].graph_keys) {
                     graph_key = reading_data['data'].graph_keys[i];
                     console.log('Loaded key :' + graph_key);
                      var data  ;
                      var chart    = document.getElementById(graph_key).getContext('2d'),
                          gradient = chart.createLinearGradient(0, 0, 0, 450);
                        console.log(chart);   
                      gradient.addColorStop(0, 'rgba(255, 0,0, 0.5)');
                      gradient.addColorStop(0.5, 'rgba(255, 0, 0, 0.25)');
                      gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');

                        graph_info = reading_data['data'].graph_info[graph_key];
                        console.log(graph_info);
                        y_axis_data =   reading_data['data'].data[graph_key];
                        console.log(y_axis_data );
                        data  = {
                            labels: reading_data['data'].labels,
                            datasets: [{
                              label: graph_info.y_axis,
                              backgroundColor: gradient,
                              pointBackgroundColor: 'white',
                              borderWidth: 1,
                              borderColor: '#911215',
                              data: y_axis_data
                            }]
                        };
             
                            var options = {
                              responsive: true,
                              maintainAspectRatio: true,
                              animation: {
                                easing: 'easeInOutQuad',
                                duration: 520
                              },
                              scales: {
                                xAxes: [{
                                  gridLines: {
                                    color: 'rgba(200, 200, 200, 0.05)',
                                    lineWidth: 1
                                  },

                                  scaleLabel: {
                                      display: true,
                                      labelString: graph_info.x_axis
                                       
                                  },

                                }],
                                yAxes: [{
                                  gridLines: {
                                    color: 'rgba(200, 200, 200, 0.08)',
                                    lineWidth: 1
                                  },
                                  scaleLabel: {
                                      display: true,
                                      labelString: graph_info.y_axis
                                  },
                                  
                                }]
                              },
                              elements: {
                                line: {
                                  tension: 0.4
                                }
                              },
                              legend: {
                                display: false
                              },
                              point: {
                                backgroundColor: 'white'
                              },
                              tooltips: {
                                titleFontFamily: 'Open Sans',
                                backgroundColor: 'rgba(0,0,0,0.3)',
                                titleFontColor: 'red',
                                caretSize: 5,
                                cornerRadius: 2,
                                xPadding: 10,
                                yPadding: 10
                              }
                            };



                        var chartInstance = new Chart(chart, {
                            type: 'line',
                            data: data,
                            options: options
                        });
            }//end type for
              
            //end of call
          }
                     
                });
}
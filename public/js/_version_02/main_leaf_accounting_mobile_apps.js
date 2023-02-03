$(document).ready(function(){
 
    var url = window.location.href;
    var n = url.indexOf("/new");
    if (n == -1) {
      var n = url.indexOf("/edit");
    };
    if (n == -1) {
      var n = url.indexOf("/view");
    };
    if (n == -1) {
      var n = url.indexOf("?");
    };
    $('#editable-select').editableSelect();
    if(url.includes('/apps/ietransactions/dashboard'))
    {
       init_report();
    }else if(url.includes('/apps/ietransactions/expenses') || url.includes('/apps/ietransactions/income'))
    {
      init_date_date_picker_new_ui_by_id('document_date');
      init_date_date_picker_new_ui_by_id('transaction_date');

    }else if(url.includes('utility_charges/dashboard/top/up'))
    {
             $('#btn_pay_now').prop('disabled', "true");
             uIElementSwitch("btn_pay_now",true);
             checkMaxNumInputAndDisableTarget("top_up_amount_txt","btn_pay_now",200);
             
    }else if(url.includes('admin/meter/status/detail'))
    {
       var acc = document.getElementsByClassName("accordion");
        var i;
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    }

});

function uIElementSwitch(id,state){

    $('#'+id).prop('disabled', state);
}

function checkMaxNumInputAndDisableTarget(id_source,id_target,max){
    
    $("#" + id_source).keyup(function(e) {
        amount = $('#'+id_source).val();

        if(amount < 2){
          amount = 2;
        }
        if(amount == ""){
             $('#'+id_target).prop('disabled', "true");
             uIElementSwitch(id_target,true);
         }else if( amount <= max && amount >= 1){
            uIElementSwitch(id_target,false);
         }else if ( amount > 200) {
            $('#'+id_source).val(200);       
         }

        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything

            return;
        }

        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

    });
}

function init_daterange_leaf_ui(me) 
{
    $(me).datepicker({
        format: "yyyy-mm-dd",
    });
}


function init_user_data(me)
{
    
    $.get(setLanguageUrl, { language_code:  $(me).val()}, function(fdata) {

        if(fdata == true)
        { 
             location.reload();
             $('*loading').addClass('hide');

        }else{

        }
        
        

    }, "json");


   
}


function init_language(me)
{
    init_loading_overlay();
    $.get(setLanguageUrl, { language_code:  $(me).val()}, function(fdata) {


        if(fdata == true)
        { 
             location.reload();
        }
        
        init_hide_loading_overlay();

    }, "json");


   
}

function init_date_date_picker_new_ui_by_id(me)
{
    $("input[name=" + me + "]").daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        },
        // hh:mm A
        singleDatePicker: true,
        /*timePicker: true,    
        pick12HourFormat: false,*/
        showDropdowns: true,
        "cancelClass": "btn-secondary",
    });
}

function init_date_range_with_date_new_ui_by_id(me)
{
    $("input[id=" + me + "]").daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        },
        opens: 'center',
        "cancelClass": "btn-secondary",
    /*}, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));*/
    });
}


function init_loading_overlay() {

    $("#overlay").css("z-index","1000");
    $('#overlay').waitMe({
        //none, rotateplane, stretch, orbit, roundBounce, win8, 
        //win8_linear, ios, facebook, rotation, timer, pulse, 
        //progressBar, bouncePulse or img
        effect: 'win8_linear',
        //place text under the effect (string).
        text: stringPleaseWait,
        //background for container (string).
        bg: 'rgba(0,0,0,0.3)',
        //color for background animation and text (string).
        color: '#ffffff',
        //max size
        maxSize: '',
        //wait time im ms to close
        waitTime: -1,
        //url to image
        source: '',
        //or 'horizontal'
        textPos: 'vertical',
        //font size
        fontSize: '16px',
        // callback
        onClose: function() {}
    });
}

function init_hide_loading_overlay() {
    $("#overlay").css("z-index","0");
    $("#overlay").waitMe("hide");
}



function init_bar_chart(){
  token = 123;
  $.get(getARTransactionSummaryUrl, { token: token}, function(data){  
   //var areaChartData = JSON.parse(data);
    //--------------
    //- AREA CHART -
    //--------------

    

   /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
   // var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
    // This will get the first returned node in the jQuery collection.
    //var areaChart       = new Chart(areaChartCanvas)

    var areaChartData =  JSON.parse(data);
   
    //Bar chart for monthly data
    var monthly_data = areaChartData.monthly_data;
    //Line chart for daily data
    var daily_data = areaChartData.daily_data;

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale               : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : false,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - Whether the line is curved between points
      bezierCurve             : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension      : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius          : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth     : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke           : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth      : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill             : true,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio     : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive              : true
    }

    //Create the line chart
    //areaChart.Line(areaChartData, areaChartOptions)

    //--------------------------------------
    //- LINE CHART - Daily transaction
    //--------------------------------------
    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartOptions         = areaChartOptions
    lineChartOptions.datasetFill = false
    lineChart.Line(daily_data, lineChartOptions)

   
    //--------------------------------------
    //- BAR CHART - Monthly transaction
    //--------------------------------------
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = monthly_data
    barChartData.datasets[0].fillColor   = '#00a65a'
    barChartData.datasets[0].strokeColor = '#00a65a'
    barChartData.datasets[0].pointColor  = '#00a65a'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)

  })
}

function init_report()
{
  $.get(getIETransactionSummaryUrl, { leaf_id_user: $('#leaf_id_user').val()}, function(fdata){  

    result = JSON.parse(fdata);

     if(result.status_code == false){
  
            $('#barChart').fadeIn(1500).addClass('hide');
            $('#statistic_icon').fadeIn(1500).removeClass('hide');
 
    }else{

          var areaChartData =  JSON.parse(result);
       
          //Bar chart for monthly data
          var monthly_data = areaChartData.monthly_data;
          //Line chart for daily data
          var daily_data = areaChartData.daily_data;

          //-------------
          //- BAR CHART -
          //-------------
          var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
          var barChart                         = new Chart(barChartCanvas)
          var barChartData                     = areaChartData
          barChartData.datasets[1].fillColor   = '#00a65a'
          barChartData.datasets[1].strokeColor = '#00a65a'
          barChartData.datasets[1].pointColor  = '#00a65a'
          var barChartOptions                  = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing       : 1,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to make the chart responsive
            responsive              : true,
            maintainAspectRatio     : true
          }

          barChartOptions.datasetFill = false
          //barChartData= null;
         
            
            barChart.Bar(barChartData, barChartOptions)
          }
    })
}
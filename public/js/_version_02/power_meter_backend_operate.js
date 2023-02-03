

var url = window.location.href;
$( document ).ready(function() {

    //var current_url = window.location.href;
  //  if(current_url.includes('admin/dashboard')){
//  //    
//  //     get_dashboard_data();
//  //     init_transaction_chat();
//  //     init_bar_chart();
//  //  }
//
//  //  if(current_url.includes('utility_charges/dashboard')){
//  //        //initialize_usage_line_chart('barChart');
//  //        if(!current_url.includes('utility_charges/dashboard/transaction-listing') && !current_url.includes('utility_charges/dashboard/top/up') && !current_url.includes('utility_charges/dashboard/help')) /*&& !current_url.includes('utility_charges/dashboard/testing_version') )*/{
//  //            /*
//  //              if($('#owl_demo_1').val() !== 'undefined')
//  //              {
//  //                    $('#owl_demo_1').owlCarousel({
//  //                    items: 1,
//  //                    animateOut: 'Out',
//  //                    loop: true,
//  //                    margin: 10,
//  //                    autoplay: true,
//  //                    mouseDrag: false,
//  //                    dots:false
//
//  //                  });
//  //              }*/
//
//
//  //            if($('#account_data').val() == false)
//  //            {
//  //              initialize_new_power_meter_account();
//  //            }else{
//  //              initialize_mobile_app_report('barChart');
//  //            }
//  //            
//  //        }
//
//  //  }else if(current_url.includes('admin/meter/status') && !current_url.includes('admin/meter/status/detail')){
//  //       init_power_meter_hosue_listing('house_listing_div');
//  //  }else if(current_url.includes('edit') || current_url.includes('new') || current_url.includes('export_by')){
//  //      init_floating_footer();
//  //  }else if(current_url.includes('index')){
//  //      init_data_table();
//  //  }else if(current_url.includes('payment-received/new') || current_url.includes('payment-received/edit')){
//  //      $('[data-mask]').inputmask()
//
//  //      show_only_div_step_by_step_no(get_current_step());
//  //      if($('#customer_id').val() != undefined && $('#customer_id').val() != ""){
//  //      init_customer_info();
//  //      }
//
//  //      $('#alert_msg_div').css("display", "none");
//  //      // init_payment_received_ui();
//  //      $(document).on('submit','form.form-horizontal',function(){
//  //      check_payment_received();
//  //      });
//
//  //      if(current_url.includes('payment-received/edit')){
//  //      $('#customer_id').css("display", "block");
//  //      }
//
  //  }else if(current_url.includes('ar-refunds/new')){
  //      init_ar_refund_UI();
  //  }else if(current_url.includes('admin/dashboardWIP')){
  //      get_latest_meter_daily_reading();
  //      get_latest_meter_daily_reading_by_daily_record_summary();
  //  }else if(current_url.includes('admin/meter/subsidiaries')){
  //      init_meter_susidy_ui();
  //  }else if(current_url.includes('admin/umrah/users/new') || current_url.includes('admin/umrah/users/edit/') ){
  //      init_date_picker_with_time('power_mangement_start_charging_date');
  //  }else if(current_url.includes('admin/products/new') ){
  //      init_date_range_new_ui_by_id('date_range');
  //  }else if(current_url.includes('admin/settings') ){
  //      init_date_date_picker_new_ui_by_id('system_live_date');
  //  }else if(current_url.includes('ietransactions/new') ){
  //      init_date_date_picker_new_ui_by_id('document_date');
  //  }
});

function update_power_meter_summary_table(table_id)
{
  return;
  //alert('x');
  var tbody = $("#"+table_id).find("tbody");
  $.get(get_status_detail_url, {  leaf_house_id : $('#start').val()  }, function(data) {
      console.log(data);
     if(data.status_code == true){
      alert('recall');
       houses =  data.data.houses;
       language = data.language;
       loading_gif = data.loading_gif;
       console.log('house leng' +  houses.length);
             for(house_counter=0; house_counter < houses.length ; house_counter ++){
              tr = ''
              console.log('Able to get hose ?:' + houses[house_counter]);
                house = houses[house_counter];
                console.log('HOue :' + house['house_unit'] )
                room_counter = 1;
                tr += '    <tr>';
                tr += '        <td>';
                tr += '            <hr>';
                tr += '            <div class="info-box-content">';      
                tr += '                <span class="info-box-number"><span class="label label-primary pull-left">' + house_counter + '  </span> &nbsp ' + house['house_unit'] + '';
                tr += '                    <span class="badge badge-success mt-15 mr-1 pull-right">id : ' + house['id_house']+ '</span>';
                tr += '                </span>';
                tr += '            </div>';
                tr += '            <hr>';


                rooms = house['house_rooms'];
                
                for(loop_room_counter=0; loop_room_counter < rooms.length ; loop_room_counter ++){
                     room = rooms[loop_room_counter];
                     console.log(JSON.stringify(room));

                     meter = room['meter'] !== undefined ? room['meter'] : '';
                     button_style = meter  !== null ? 'background-image: linear-gradient(to right, rgba(255,0,0,0), rgba(255,0,0,0.7))' : 'background-image: linear-gradient(to right, rgba(140,207,127,0)), rgba(140,207,127,0.7))';

                    tr += '        <br>';
                        label_class = 'success';
                        if(meter !== undefined)
                        {
                             label_class = 'danger';
                        }
                    tr += '        <button onclick="get_meter_reading_detail(' + room['id_house_room']+ ');" id="' + room['id_house_room']+ '" class="badge badge-soft- '+ label_class + 'badge-outline pull-right box-success accordion mb-1" style="' + button_style + ';height:75px;">';
                    tr += '            <span class="label label-primary pull-left">' + room_counter+ '  </span> &nbsp  Room ' + room['house_room_name'];

                        label_class = 'danger';
                        if(room['house_room_members'].length > 0)
                        {
                             label_class = 'success';
                        }

                        tenant_label_class = 'Empty';
                        if(room['house_room_members'].length > 0)
                        {
                             tenant_label_class = 'Tenanted';
                        }

                    tr += '            <span class="badge badge-' + label_class + '  mr-1 pull-right">' + tenant_label_class + '</span>';
                    tr += '            <span class="badge badge-success  mr-1 pull-right">' + room['house_room_type']+ '</span>';
                    tr += '            <span class="badge badge-success  mr-1 pull-right">Id : ' + room['id_house_room']+ '</span>';
                    tr += '        </button>';
                    tr += '        <div class="panel">';
                    tr += '            <hr>';
                    tr += '            <div class="row">';
                    tr += '                <div class="col-md-6">';
                        //<!-- /.box -->
                    tr += '                    <div class="box box-success box-solid">';
                        //<!-- /.box-header -->
                    tr += '              <div class="hk-pg-header">';
                    tr += '                  <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="toggle-right"></i></span></span>' + language['Tenant Detail'] + '</h4>';
                    tr += '              </div>';
                        //<!-- /.box-header -->
                        //<!-- /.box-body -->
                    tr += '              <div class="box-body">';
                   // tr += '                  <?php  $i = 1; ?>';
                    tr += '                      <table class="table table-hover">';
                    tr += '                          <tr class="table-light">';
                    tr += '                              <th style="width: 10px">#</th>';
                    tr += '                              <th>' +language['Name']+ '</th>';
                    tr += '                              <th>' +language['Check In Date']+ '</th>';
                    tr += '                              <th>' +language['Total Consumption (kwh)']+ '</th>';
                    tr += '                          </tr>';



                    if( room['house_room_members'] !== undefined){
                        for(loop_house_room_member_counter=0; loop_house_room_member_counter < room['house_room_members'].length ; loop_house_room_member_counter ++){
                                member =    room['house_room_members'][loop_house_room_member_counter] ;
                                // $member = (array)$member;
                                tr += '   <tr>';
                                tr += '       <td><span class="label label-primary pull-left"> ' + loop_house_room_member_counter + ' </span> </td>';
                                tr += '       <td>' + member['house_member_name']+ '</td>';
                                tr += '       <td>' + member['house_room_member_start_date']+ '';
                                tr += '       </td>';
                                tr += '       <td><label id="lbl_' + member['house_member_id_user']+ '_total_usage_' + room['id_house_room']+ '"><img src=" '+ data.loading_gif+' " alt=""/></label></td>';
                                tr += '   </tr>';
                        }
                    }// end no member


                    tr += '                      </table>';
                    tr += '              </div>';
                        //<!-- /.box-body -->
                    tr += '          </div>';
                        //<!-- /.box -->
                        //<!-- /.box -->
                    tr += '          <div class="box box-success box-solid">';
                    tr += '              <div class="hk-pg-header">';
                                                                

                     tr += '       <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="toggle-right"></i></span></span>' +language['Reading Monitoring']+ ' </h4>';
                     tr += '       </div>';
                        //<!-- /.box-header -->
                        //<!-- /.box-body -->

                    tr += '    <div class="box-body">';
                    tr += '        <table class="table table-hover">';
                    tr += '            <tr class="table-light">';
                    tr += '                <th style="width: 10px">#</th>';
                    tr += '                <th>' +language['Item']+ ' </th>';
                    tr += '                <th></th>';
                    tr += '            </tr>';
                    tr += '            <tr>';
                    tr += '                <td><span class="label label-primary pull-left">2</span> </td>';
                    tr += '                <td>' +language['Last Reading At']+ ' </td>';
                    tr += '                <td><label id="lbl_last_reading_at_' + room['id_house_room']+ ' "><img src="' +loading_gif+ ' " alt=""/></label> ' + meter['last_reading_at']+ '  </td>';
                    tr += '            </tr>';
                    tr += '            <tr>';
                    tr += '                <td><span class="label label-primary pull-left">3</span> </td>';
                    tr += '                <td> ' +language['Last Current Reading']+ ' </td>';
                    tr += '                <td><label id="lbl_last_reading_' + room['id_house_room']+ ' "><img src="' +loading_gif+ ' " alt=""/></label><!-- ' + meter['last_reading']+ '  --></td>';
                    tr += '            </tr>';
                    tr += '            <tr>';
                    tr += '                <td><span class="label label-primary pull-left">4</span></td>';
                    //importang date('F, Y'] 
                    tr += '                <td>   ' +language['Monthly Usage Reading'] + '  </td>';
                    tr += '                <td>' + meter['monthly_usage'] + '';
                    tr += '                      <label id="lbl_monthly_usage_' + room['id_house_room']+ ' "><img src="' +loading_gif+ ' " alt=""/></label>     ';                                                                   
                    tr += '                </td>';
                    tr += '  </tr>';

                    tr += '                </table>';
                    tr += '            </div>';
                       //<!-- /.box-body -->
                    tr += '        </div>';
                       //<!-- /.box -->
                    tr += '    </div>';
                       //<!-- COL START -->
                    tr += '    <div class="col-md-6"';
                    

                    if(meter !== undefined){
                                tr += '    <Form method = "get" class="form-horizontal" id = "meter_data_" +meter["id"] >';
                                tr += '        <div class="box box-success box-solid">';
                                        //<!-- /.box-header -->
                                tr += '            <div class="hk-pg-header">';
                                tr += '                <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="toggle-right"></i></span></span>' +language['Meter register Detail']+ ' </h4>';
                                tr += '            </div>';
                                        //<!-- /.box-header -->
                                        //<!-- /.box-body -->
                                tr += '            <div class="box-body">';
                                tr += '                <table class="table table-hover">';
                                tr += '                    <tr class="table-light">';
                                tr += '                        <th style="width: 10px">#</th>';
                                tr += '                        <th>' +language['Item']+ ' </th>';
                                tr += '                        <th></th>';
                                tr += '                    </tr>';
                                tr += '                    <tr>';
                                tr += '                        <td><span class="label label-primary pull-left">1 </span></td>';
                                tr += '                        <td>' +language['Meter ID']+ ' </td>';
                                tr += '                        <td>' + meter['id']+ ' ';
                                tr += '                        </td>';
                                tr += '                    </tr>';
                                tr += '                    <tr>';
                                tr += '                        <td><span class="label label-primary pull-left">2 </span></td>';
                                tr += '                        <td>' +language['IP']+ ' </td>';
                                tr += '                        <td><input type="text" class="form-control" name="ip_address" id="ip_address" required/>';
                                tr += '                        </td>';
                                tr += '                    </tr>';
                                tr += '                    <tr>';
                                tr += '                        <td><span class="label label-primary pull-left">3 </span></td>';
                                tr += '                        <td>' +language['Contact No']+ ' </td>';
                                tr += '                        <td><input type="text" class="form-control" name="contract_no" id="contract_no" required/>';
                                tr += '                        </td>';
                                tr += '                    </tr>';
                                tr += '                    <tr>';
                                tr += '                        <td><span class="label label-primary pull-left">4 </span></td>';
                                tr += '                        <td>' +language['Created At']+ ' </td>';
                                tr += '                        <td>' + meter['created_at']+ ' ';
                                tr += '                        </td>';
                                tr += '                    </tr>';
                                tr += '                    <tr>';
                                tr += '                        <td><span class="label label-primary pull-left">5 </span></td>';
                                tr += '                        <td>' +language['Updated At']+ ' </td>';
                                tr += '                        <td>' + meter['updated_at']+ ' ';
                                tr += '                        </td>';
                                tr += '                    </tr>';
                                tr += '                    <tr>';
                                tr += '                        <td><span class="label label-primary pull-left">6 </span></td>';
                                tr += '                        <td>' +language['Meter Register Status']+ ' </td>';
                                tr += '                         <input type="hidden" id="meter_id_' + room['id_house_room']+ ' " name="meter_id_' + room['id_house_room']+ ' " value="' + meter['id'] + ' ">';
                                tr += '                        <input type="hidden" id="meter_switch_status_' + room['id_house_room']+ ' " name="meter_switch_status_' + room['id_house_room']+ ' " value="' + meter['is_power_supply_on']+ ' ">';
                                tr += '                        <td> <div onclick="change_switch_status(' + room['id_house_room']+ ' );" class="toggle toggle-light toggle-bg-green toggle3" id="meter_switch_' + room['id_house_room']+ ' "></div>';
                                tr += '                        </td>';
                                tr += '                    </tr>';
                                tr += '                </table>';
                                tr += '            </div>';
                                        //<!-- /.box-body -->';
                                tr += '        </div>';
                                        //<!-- /.box -->';

                                tr += '                    <div class="col-md-offset-2 col-md-12">';
                                        //<!-- type="submit" -->
                                tr += '                         <label onclick="update_meter_register(' + meter['id']+ ' );"   class="btn btn-success btn-block btn-wth-icon mt-12"> <span class="icon-label"><i class="fa fa-floppy-o fa-fw"> </i></span><span class="btn-text">' +language['Save']+ ' </span></label>';
                                tr += '                    </div>';
                                tr += '                    </form>';
                   }else{
                                tr += '                    <div class="box box-success box-solid">';
                                        //<!-- /.box-header -->
                                tr += '                        <div class="hk-pg-header">';
                                tr += '                            <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="toggle-right"></i></span></span>' +language['No Meter Found']+ ' </h4>';
                                tr += '                        </div>';
                                        //<!-- /.box-header -->
                                        //<!-- /.box-body -->
                                tr += '                        <div class="box-body">';
                                tr += '                            <table class="table table-hover">';
                                tr += '                                <tr>';
                                tr += '                                    <div class="col-md-offset-6 col-md-10">';                                                
                                tr += '                                           <a href="' +action('UMeterRegistersController@getNew', room)+ ' " class="btn btn-primary pull-rigth"><i class="fa fa-floppy-o fa-fw"></i> <span>' +language['Register New Meter']+ ' </span></a>';
                                tr += '                                    </div>';
                                tr += '                                </tr>';
                                tr += '                            </table>';
                                tr += '                        </div>';
                                        //<!-- /.box-body -->
                                tr += '                    </div>';
                                        //<!-- /.box -->
                    }//end no meter else
                                tr += '        </div>';
                                        //<!-- COL END -->
                                tr += '    </div>';

                                tr += '    <div class="row">';
                                tr += '        <div class="col-md-12">';
                                tr += '            <div class="box box-success box-solid">';
                                tr += '                <div class="hk-pg-header">';
                                tr += '                    <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="toggle-right"></i></span></span>' +language['Room History']+ ' </h4>';
                                tr += '                </div>';
                                        //<!-- /.box-header -->
                                        //<!-- /.box-body -->
                                tr += '                 <div class="box-body">';
                                tr += '                     <table class="table table-hover">';
                                tr += '                         <tr class="table-light">';
                                tr += '                             <th style="width: 10px">#</th>';
                                tr += '                             <th>' +language['Start Date']+ ' </th>';
                                tr += '                             <th>' +language['End Date']+ ' </th>';
                                tr += '                             <th>' +language['Total Consumption (Kwh)']+ ' </th>';
                                tr += '                             <th>' +language['Charges (RM)']+ ' </th>';
                                tr += '                             <th></th>';
                                tr += '                         </tr>';
                                tr += '                     </table>';
                                tr += '              </div>';
                                tr += '             </div>';
                                tr += '         </div>';
                                tr += '     </div>';
                                tr += '     <hr>';
                                tr += ' </div>';
                           }//end room for loop
                                tr += '        </td>';
                                tr += '    </tr>';
                   

                         tbody.append(tr);  
            }//end house for
      //cosole.log(data.data.start);
      $('#start').val(data.data.start)     
      console.log('Is cont chekc :' + data.is_continue );
      if(data.is_continue == true)
      {
        //alert('double call');
        update_power_meter_summary_table(table_id);
      }
      
    } // end status code == 1
  });
}



function hide_pw_loading_circle(){
  $('#power_meter_loading_circle').addClass('hide');
}

function init_pw_loading_circle(){
  $('#power_meter_loading_circle').removeClass('hide');
}


function hide_pw_loading_bar(){
  $('#power_meter_loading_bar').addClass('hide');
}

function init_generate_report()
{
   
  $.get(generate_report_url, { leaf_house_id : 0 ,  month_ended :  '01-2019',  month_ended : '08-2019' , export_by : 'pdf' }, function(data) {
     if(data['status_code'] == true){
      
     }
       
  });
}

function init_generate_report(report_name)
{
   
  $.get(generate_report_url, { report_name : report_name}, function(data) {
     if(data['status_code'] == true){
         var data;
         parameter = JSON.parse(data['report_setting'].parameter);

         for( var key in parameter){
            data[parameter[key]] = $('#'+ parameter[key]).val();
         }

         $.get(data['report_setting'].path, { data : data }, function(data) {
           if(data['status_code'] == true){
              update_report_request_history_table(report_name , data.customer_report_request);
           }
             
        });
     }
       
  });
}

function update_report_request_history_table(table_id , data)
{
  var tbody = $("#"+table_id).find("tbody");
  tbody.find("tr").remove();
  for(var i=0 ; i < data.length ; i ++)
  {
      index = i+1;
      var tr = "<tr>";
        tr += "<td class='text-center col-md-1'>"+index+"</td>";
        tr += "<td class='text-center col-md-4'>"+data[i].filename+"</td>";
        tr += "<td class='text-center col-md-4'>"+data[i].updated_at+"</td>";
        tr += "<td class='text-center col-md-4'>"+data[i].response+"</td>";
        tr += "<td class='text-center col-md-4'>"+data[i].retry_time+"</td>";
          tr += "<td class='text-center col-md-1'>"
          if (data[i].response == 'success') 
            { tr += ''; } else { tr += ''; }
          tr += "</td>";
        tr += "</tr>";
      tbody.append(tr);
  }

}



 //href='?leaf_house_id="+data['houses'][i].id_house +"&house_unit=" + data['houses'][i].house_unit+"'
function init_power_meter_hosue_listing(div_id)
{
  $.get(power_meter_house_listing_url, { data : 'test' }, function(data) {
     if(data['status_code'] == true){

       var content ="" ;
        for(i=0; i < data['houses'].length ; i ++){
           content += "<div  class='loading-label' ";
              content += "<div class='col-md-12 text-center margin-bottom-15' onclick='init_power_meter_room_detail_listing(this)';  data-id=" + data['houses'][i].house_unit + " id=" +data['houses'][i].id_house+ ">"
                content += "<i class='fa fa-home fa-fw fa-8x'></i>"
                /*House No. */
                content += "<p class='text-center'> " + data['houses'][i].house_unit + "</p>"
                content += "<hr>";
              content += "</div>";
            content += "</div>";
          }
          hide_pw_loading_bar();
          //console.log(div_id);
          if( document.getElementById(div_id) != null)
          {
            document.getElementById(div_id).innerHTML = content;
          }
          
        }
       
  });
}

function init_total_usage_chart(data)
{ 
    if(document.getElementById("myChart") !== null)
    {
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'pie',
          data: {
            labels: data.chart_label,
            //labels: ["Green", "Blue", "Gray", "Purple", "Yellow", "Red", "Black"],
            datasets: [{
              backgroundColor: data.chart_color,
              //data: [12, 19, 3, 17, 28, 24, 7]
              data: data.reading
            }]
          }
        });

    }
    

    if(document.getElementById("myChart") !== null)
    {
        var ctx_total = document.getElementById("myChart_total").getContext('2d');
        var myChart = new Chart(ctx_total, {
          type: 'pie',
          data: {
            labels: data.chart_label,
            //labels: ["Green", "Blue", "Gray", "Purple", "Yellow", "Red", "Black"],
            datasets: [{
              backgroundColor:  data.chart_color,
               /* "#2ecc71",
                "#3498db",
                "#95a5a6",
                "#9b59b6",
                "#f1c40f",
                "#e74c3c",
                "#34495e"*/
              
              data: data.reading_total
            }]
          }
        });
    }
    
}


function init_power_meter_room_detail_listing( me ,modal_target_div_id=null , modal_id=null)
{
  
   modal_target_div_id  = $('#modal_target_div_id').val();
   modal_id  = $('#modal_id').val();
   house_unit =  $(me).attr('data-id');

   document.getElementById(modal_target_div_id).innerHTML = '';
   init_pw_loading_circle();
   document.getElementById(modal_id + "_title").innerHTML = house_unit;
   $('#'+modal_id).modal('show');
   init_loading_overlay();
  
   $.get(power_meter_room_detail_url, { leaf_house_id : $(me).attr('id') }, function(data) {
    console.log(data);
     if(data['status_code'] == true){
       var content ="" ;
       //graph = number_format(($main_meter['monthly_usage'] > 0 ? ($main_meter['monthly_usage']/$main_meter['total_usage']):$main_meter['monthly_usage'])*100,2,'.',''); @endphp
       usage = (data['main_meter'].monthly_usage > 0 ? data['main_meter'].monthly_usage/data['main_meter'].total_usage : data['main_meter'].monthly_usage)*100;
       graph = usage.toFixed(2);
        content +=       "<section class='hk-sec-wrapper'>"; 
        content += "<h4 class='hk-sec-title'>Unit Summary</h4><hr>";          
      /*  content +=                  "<div class='row'>";
        content +=                      "<div class='col-md-4 text-center margin-bottom-15 margin-left-15 '>";
        content +=                          "<input type='text' value='" + graph + "' class='dial'><hr>";
        content +=                          "<h5 class='text-center'> Total Usage </h5>";
        content +=                          "<p class='text-center mt-10'>"+ data['main_meter'].total_usage +" Kwh</p><hr><hr>";   
        content +=                      "</div>";
        content +=                      "<div class='col-md-4 text-center margin-bottom-15'>";
        content +=                          "<input type='text' value=' "+graph+"' class='dial'><hr>";            
        content +=                          "<h5 class='text-center'> Monthly Usage </h5>";
        content +=                          "<p class='text-center mt-10'> " + data['main_meter'].monthly_usage + " Kwh</p><hr><hr>";
        content +=                      "</div>";
        content +=                "</div>";*/
        content +=               "<div class='row'>";
        if( data['main_meter'].total_usage != 0 )
        {
          content +=                   "<div class='col-md-6 text-center margin-bottom-15 margin-left-15'>";
          content +=                          "<h5 class='text-center'> Total Usage (kWh) </h5>";
          content +=                      "<div class='col-md-12 text-center margin-bottom-15 margin-left-15 '>";
          content +=                         "<div><canvas id='myChart' style='height:500px'></canvas></div>"
          content +=                          "<hr><h5 class='text-center'> " + data['main_meter'].total_usage +" kWh </h5><hr>";
          content +=                      "</div>";   
          content +=                      "</div>";
        }else{
            content +=                   "<div class='col-md-12 text-center margin-bottom-15 margin-left-15'>";
            content +="  <p class='text-center'> No Reading Yet. </p><hr>";
            content +=                      "</div>";
        }
        
        if( data['main_meter'].total_usage != 0 )
        {
          content +=                      "<div class='col-md-6 text-center margin-bottom-15 margin-left-15 '>";
          content +=                          "<h5 class='text-center'> Monthly Usage (kWh) </h5>";
          content +=                      "<div class='col-md-12 text-center margin-bottom-15 margin-left-15 '>";
          content +=                         "<div><canvas id='myChart_total' style='height:500px'></canvas></div>"
          content +=                          "<hr><h5 class='text-center'> " + data['main_meter'].monthly_usage +"  kWh </h5><hr>";
          content +=                      "</div>";   
          content +=                      "</div>";
        }else{
          
        }
        


 
        content +=                "</div>";

        content +=              "</section>";

        content += "<section class='hk-sec-wrapper'>"; 
        content += "<h4 class='hk-sec-title'>Room Usage</h4><hr>";
        content += "<div class='row'>";
        for(i=0; i < data['room_listing'].length ; i ++){

                // $graph = number_format(($room['monthly_usage'] > 0 ? ($room['monthly_usage']/$room['total_usage']):$room['monthly_usage'])*100,2,'.',''); @endphp
                //usage = (data['room_listing'][i].monthly_usage > 0 ? (data['room_listing'][i].monthly_usage/data['room_listing'][i].total_usage):data['room_listing'][i].monthly_usage)*100;
                //graph = usage.toFixed(2);

                content+="<div class='col-md-4 text-center margin-bottom-15 margin-left-15 '>";   
                content+="  <h5 class='text-center'>Room No. "+  data['room_listing'][i].room_name +"</h5>";
                        if(data['room_listing'][i].meter_register_id  == 0)
                        {

                            content+="  <p class='text-center'> <small> Meter No Yet Setup. </small></p> <hr>";
                        }else{
                            content +=               "<div class='row'>";
                            content +=                   "<div class='col-md-12 text-center margin-bottom-15 margin-left-15'>";
                            content +=                          "<p class='text-center'> Daily Usage (kWh) </p>";
                            content +=                      "<div class='col-md-12 text-center margin-bottom-15 margin-left-15 '>";
                            content +=                         "<div><canvas id='room_line_chart_" + data['room_listing'][i].meter_register_id  + "'></canvas></div>";
                            //Retrieving data...
                            content +=                      "</div>";   
                            content +=                      "</div>";
                            content +=                "</div>";
                            content+="  <hr>";
                        }  
                            

                           //content+="  <div id='room_line_chart_" + data['room_listing'][i].meter_register_id  + "' name='room_line_chart_" + data['room_listing'][i].meter_register_id  + "></div>";
                
                

                if(data['room_listing'][i].meter_register_id  == 0)
                        {
                            
                        }else{
                             content+="  <p class='text-center'> Total Usage : " + data['room_listing'][i].total_usage  +" Kwh </p>";
                             content+=" <p class='text-center'> Power Supply Status : <img style='height:10px;width:20px;' src='" + data['room_listing'][i].is_power_supply_on_png + "' alt=''/> </p>";
                             content+="  <span class='label label-success'> Last update: <br>" +  data['room_listing'][i].last_update.date + "</span><hr>";
                        }  
               
                //+ "( "  + data['room_listing'][i].last_update.timezone  +" ) 
                content+="</div>";
          }
        content += "</div>";
        content +=              "</section>";
          init_hide_loading_overlay();
          /*$(function() {
              $(".dial").knob({
                readOnly:true,
            });
          });*/
          document.getElementById(modal_target_div_id).innerHTML = content;

          init_total_usage_chart(data['chart_data']);

          setTimeout(() => {  

            console.log("World!");
                for(i=0; i < data['room_listing'].length ; i ++){
                  if( data['room_listing'][i].meter_register_id !== 0)
                  {
                    initialize_usage_line_chart_backend( data['room_listing'][i].meter_register_id, 'room_line_chart_' );
                  }

                }
             }, 5000);
          
          

        }
       
  });

}


/* takes a string phrase and breaks it into separate phrases 
   no bigger than 'maxwidth', breaks are made at complete words.*/

function formatLabel(str, maxwidth){
    var sections = [];
    var words = str.split(" ");
    var temp = "";

    words.forEach(function(item, index){
        if(temp.length > 0)
        {
            var concat = temp + ' ' + item;

            if(concat.length > maxwidth){
                sections.push(temp);
                temp = "";
            }
            else{
                if(index == (words.length-1))
                {
                    sections.push(concat);
                    return;
                }
                else{
                    temp = concat;
                    return;
                }
            }
        }

        if(index == (words.length-1))
        {
            sections.push(item);
            return;
        }

        if(item.length < maxwidth) {
            temp = item;
        }
        else {
        }

    });
     // sections.push(item);
    return sections;
           
}


// ============================================
// As of Chart.js v2.5.0
// http://www.chartjs.org/docs
// ============================================
function initialize_usage_line_chart(chart_id){
        init_pw_loading_circle();
        var data  ;
        var chart    = document.getElementById(chart_id).getContext('2d'),
            gradient = chart.createLinearGradient(0, 0, 0, 450);

        gradient.addColorStop(0, 'rgba(255, 0,0, 0.5)');
        gradient.addColorStop(0.5, 'rgba(255, 0, 0, 0.25)');
        gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');
        init_loading_overlay();


      $.get(getUserDailyUsageUrl, { leaf_house_id : 0 ,  month_ended :  '01-2019',  month_ended : '08-2019' , export_by : 'pdf' }, function(reading_data) {
           
         if(reading_data.status_code == true){

              data  = {
                  labels: reading_data['data'].labels,
                  datasets: [{
                    label: 'Usage [kWh]',
                    backgroundColor: gradient,
                    pointBackgroundColor: 'white',
                    borderWidth: 1,
                    borderColor: '#911215',
                    data: reading_data['data'].data
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
                            labelString: 'Date'
                             
                        },

                      }],
                      yAxes: [{
                        gridLines: {
                          color: 'rgba(200, 200, 200, 0.08)',
                          lineWidth: 1
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'kWh'
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

              init_hide_loading_overlay();
            //end of call
          }
                     
                });
}


function initialize_usage_line_chart_backend(meter_register_id , chart_base_id){
      console.log('Start :' + meter_register_id + '=' + chart_base_id);
        var chart_id = chart_base_id + meter_register_id;
        //init_pw_loading_circle();
        var data  ;
        var chart    = document.getElementById(chart_id).getContext('2d'),
            gradient = chart.createLinearGradient(0, 0, 0, 450);

        gradient.addColorStop(0, 'rgba(255, 0,0, 0.5)');
        gradient.addColorStop(0.5, 'rgba(255, 0, 0, 0.25)');
        gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');
        //init_loading_overlay();


      $.get(getUserDailyUsageUrl, { meter_register_id : meter_register_id }, function(reading_data) {
           
         if(reading_data.status_code == true){

              data  = {
                  labels: reading_data['data'].labels,
                  datasets: [{
                    label: 'Usage [kWh]',
                    backgroundColor: gradient,
                    pointBackgroundColor: 'white',
                    borderWidth: 1,
                    borderColor: '#911215',
                    data: reading_data['data'].data
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
                            labelString: 'Date'
                             
                        },

                      }],
                      yAxes: [{
                        gridLines: {
                          color: 'rgba(200, 200, 200, 0.08)',
                          lineWidth: 1
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'kWh'
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

              init_hide_loading_overlay();
            //end of call
          }
                     
                });
}



function init_new_check_in(){
  init_loading_overlay();
  $('#visit_log_id').val(0);
  $('#visit_type').val('check_in');
  location.reload();
  /*ui_to_hide = [ 'new_check_in_button' , 'total_check_out' , 'total_records','check_in_at' , 'check_out_at', 'leaf_room', 'leaf_house'];
  ui_to_show = ['','','','',''];

  for ( const [key,value] of Object.entries( ui_to_hide ) ) {
     
      $('#'+value).addClass('hide');
  }*/
  
}

function init_new_visit_log(){
  init_loading_overlay();
  var device_info ;
  var location_info;
  if (WURFL.is_mobile === true && WURFL.form_factor === "Smartphone") {
        device_info = WURFL;
        console.log(WURFL);
        console.log(WURFL.complete_device_name);
        
    }else{
        device_info = WURFL;
        console.log(WURFL);
        console.log(WURFL.complete_device_name);
    }

    location_info = 'empty';
   /* $.getJSON('https://api.ipgeolocation.io/ipgeo?apiKey=4dee2d76cc9445c5b5a7b926603fe860', function(data) {
       location_info = data;*/
      // alert('Log id:' + $('#visit_log_id').val() + ' = '  + $('#visit_type').val());
        ui_update_arr = [ 'total_check_in' , 'total_check_out' , 'total_records','check_in_at' , 'check_out_at', 'leaf_room', 'leaf_house'];
        ui_remove_check_in = ['leaf_room_id', 'leaf_house_id'];

        $.get(getNewVisitLogUrl, { visit_type:$('#visit_type').val(),visit_log_id:$('#visit_log_id').val(),leaf_house_id:$("#leaf_house_id option:selected").val(),leaf_room_id:$("#leaf_room_id option:selected").val(),device_info:device_info,location_info:location_info}, function(data){
          
         

          for ( const [key,value] of Object.entries( data.data.model ) ) {
             
              if(ui_update_arr.includes(key))
              {
                  document.getElementById(key).textContent= value;
              }else if(key == 'check_in_out_button_text')
              {
                  document.getElementById(key).innerText = value;
              }

              if(key =='visit_type' || key =='visit_log_id')
              {
                $('#'+key).val(value);
              }
          }

          for ( const [key,value] of Object.entries( ui_remove_check_in ) ) {

             $('#'+value+'_span').removeClass('hide');
             div= document.getElementById(value + '_div');
             div.style.display = "none";

          }


          if(data.data.model.is_check_in == true &&  data.data.model.is_check_out == true)
          {
              
               $('#visit_log_button').addClass('hide');
               $('#new_check_in_button').removeClass('hide');
              
          //new record
          }
          console.log( data.data.model);

          init_hide_loading_overlay();
        });


   /* });*/

}

function generate_report_msg(me)
{
    export_msg = stringPleaseWaitReportHtml;
    /*export_msg = 'No value';
    export_type = $('#export_by').val();
    alert(export_type);
    if(export_type == 'html')
    {
      export_msg = stringPleaseWaitReportHtml;
    }else if(export_type == 'pdf')
    {
      export_msg = stringPleaseWaitReportPdf;
    }else if(export_type == 'excel')
    {
      export_msg = stringPleaseWaitReportExcel;
    }*/

    $("#overlay").css("z-index","1000");
    $('#overlay').waitMe({
        //none, rotateplane, stretch, orbit, roundBounce, win8, 
        //win8_linear, ios, facebook, rotation, timer, pulse, 
        //progressBar, bouncePulse or img
        effect: 'bounce',
        //place text under the effect (string).
        text:export_msg ,
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

   /* e.preventDefault();
    var url = $(me).attr('href'); 
    window.open(url, '_blank');*/
    setTimeout(init_hide_loading_overlay, 5000);
    
}

function init_loading_overlay_top_up()
{
  if($('#top_up_amount_txt').val() < $('#top_up_min_amount').val())
    { return ;}
  else{
    init_loading_overlay();
  }

}

function initialize_new_power_meter_account()
{
  if( $('#exampleModal').length > 0 ){
    $('#exampleModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('New message to ' + recipient)
      modal.find('.modal-body input').val(recipient)
    });
  }
  
}

function initialize_mobile_app_report(chart_id){
 // Return with commas in between
   
  var numberWithCommas = function(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

  /**Customize the Rectangle.prototype draw method**/
  if( Chart.elements !== undefined)
  {

    Chart.elements.Rectangle.prototype.draw = function() {
            var ctx = this._chart.ctx;
            var vm = this._view;
            var left, right, top, bottom, signX, signY, borderSkipped, radius;
            var borderWidth = vm.borderWidth;

            // If radius is less than 0 or is large enough to cause drawing errors a max
            //      radius is imposed. If cornerRadius is not defined set it to 0.
            var cornerRadius = this._chart.config.options.cornerRadius;
            var fullCornerRadius = this._chart.config.options.fullCornerRadius;
            var stackedRounded = this._chart.config.options.stackedRounded;
            var typeOfChart = this._chart.config.type;

            if (cornerRadius < 0) {
              cornerRadius = 0;
            }
            if (typeof cornerRadius == 'undefined') {
              cornerRadius = 0;
            }
            if (typeof fullCornerRadius == 'undefined') {
              fullCornerRadius = false;
            }
            if (typeof stackedRounded == 'undefined') {
              stackedRounded = false;
            }

            if (!vm.horizontal) {
              // bar
              left = vm.x - vm.width / 2;
              right = vm.x + vm.width / 2;
              top = vm.y;
              bottom = vm.base;
              signX = 1;
              signY = bottom > top ? 1 : -1;
              borderSkipped = vm.borderSkipped || 'bottom';
            } else {
              // horizontal bar
              left = vm.base;
              right = vm.x;
              top = vm.y - vm.height / 2;
              bottom = vm.y + vm.height / 2;
              signX = right > left ? 1 : -1;
              signY = 1;
              borderSkipped = vm.borderSkipped || 'left';
            }

            // Canvas doesn't allow us to stroke inside the width so we can
            // adjust the sizes to fit if we're setting a stroke on the line
            if (borderWidth) {
              // borderWidth shold be less than bar width and bar height.
              var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
              borderWidth = borderWidth > barSize ? barSize : borderWidth;
              var halfStroke = borderWidth / 2;
              // Adjust borderWidth when bar top position is near vm.base(zero).
              var borderLeft = left + (borderSkipped !== 'left' ? halfStroke * signX : 0);
              var borderRight = right + (borderSkipped !== 'right' ? -halfStroke * signX : 0);
              var borderTop = top + (borderSkipped !== 'top' ? halfStroke * signY : 0);
              var borderBottom = bottom + (borderSkipped !== 'bottom' ? -halfStroke * signY : 0);
              // not become a vertical line?
              if (borderLeft !== borderRight) {
                top = borderTop;
                bottom = borderBottom;
              }
              // not become a horizontal line?
              if (borderTop !== borderBottom) {
                left = borderLeft;
                right = borderRight;
              }
            }

            ctx.beginPath();
            ctx.fillStyle = vm.backgroundColor;
            ctx.strokeStyle = vm.borderColor;
            ctx.lineWidth = borderWidth;

            // Corner points, from bottom-left to bottom-right clockwise
            // | 1 2 |
            // | 0 3 |
            var corners = [
              [left, bottom],
              [left, top],
              [right, top],
              [right, bottom]
            ];

            // Find first (starting) corner with fallback to 'bottom'
            var borders = ['bottom', 'left', 'top', 'right'];
            var startCorner = borders.indexOf(borderSkipped, 0);
            if (startCorner === -1) {
              startCorner = 0;
            }

            function cornerAt(index) {
              return corners[(startCorner + index) % 4];
            }

            // Draw rectangle from 'startCorner'
            var corner = cornerAt(0);
            ctx.moveTo(corner[0], corner[1]);


            var nextCornerId, nextCorner, width, height, x, y;
            for (var i = 1; i < 4; i++) {
              corner = cornerAt(i);
              nextCornerId = i + 1;
              if (nextCornerId == 4) {
                nextCornerId = 0
              }

              nextCorner = cornerAt(nextCornerId);

              width = corners[2][0] - corners[1][0];
              height = corners[0][1] - corners[1][1];
              x = corners[1][0];
              y = corners[1][1];

              var radius = cornerRadius;
              // Fix radius being too large
              if (radius > Math.abs(height) / 2) {
                radius = Math.floor(Math.abs(height) / 2);
              }
              if (radius > Math.abs(width) / 2) {
                radius = Math.floor(Math.abs(width) / 2);
              }

                var x_tl, x_tr, y_tl, y_tr, x_bl, x_br, y_bl, y_br;
                if (height < 0) {
                  // Negative values in a standard bar chart
                  x_tl = x;
                  x_tr = x + width;
                  y_tl = y + height;
                  y_tr = y + height;

                  x_bl = x;
                  x_br = x + width;
                  y_bl = y;
                  y_br = y;

                  // Draw
                  ctx.moveTo(x_bl + radius, y_bl);

                  ctx.lineTo(x_br - radius, y_br);

                  // bottom right
                  ctx.quadraticCurveTo(x_br, y_br, x_br, y_br - radius);


                  ctx.lineTo(x_tr, y_tr + radius);

                  // top right
                  fullCornerRadius ? ctx.quadraticCurveTo(x_tr, y_tr, x_tr - radius, y_tr) : ctx.lineTo(x_tr, y_tr, x_tr - radius, y_tr);


                  ctx.lineTo(x_tl + radius, y_tl);

                  // top left
                  fullCornerRadius ? ctx.quadraticCurveTo(x_tl, y_tl, x_tl, y_tl + radius) : ctx.lineTo(x_tl, y_tl, x_tl, y_tl + radius);


                  ctx.lineTo(x_bl, y_bl - radius);

                  //  bottom left
                  ctx.quadraticCurveTo(x_bl, y_bl, x_bl + radius, y_bl);

                } else if (width < 0) {
                  // Negative values in a horizontal bar chart
                  x_tl = x + width;
                  x_tr = x;
                  y_tl = y;
                  y_tr = y;

                  x_bl = x + width;
                  x_br = x;
                  y_bl = y + height;
                  y_br = y + height;

                  // Draw
                  ctx.moveTo(x_bl + radius, y_bl);

                  ctx.lineTo(x_br - radius, y_br);

                  //  Bottom right corner
                  fullCornerRadius ? ctx.quadraticCurveTo(x_br, y_br, x_br, y_br - radius) : ctx.lineTo(x_br, y_br, x_br, y_br - radius);

                  ctx.lineTo(x_tr, y_tr + radius);

                  // top right Corner
                  fullCornerRadius ? ctx.quadraticCurveTo(x_tr, y_tr, x_tr - radius, y_tr) : ctx.lineTo(x_tr, y_tr, x_tr - radius, y_tr);

                  ctx.lineTo(x_tl + radius, y_tl);

                  // top left corner
                  ctx.quadraticCurveTo(x_tl, y_tl, x_tl, y_tl + radius);

                  ctx.lineTo(x_bl, y_bl - radius);

                  //  bttom left corner
                  ctx.quadraticCurveTo(x_bl, y_bl, x_bl + radius, y_bl);

                } else {
                
                    var lastVisible = 0;
                  for (var findLast = 0, findLastTo = this._chart.data.datasets.length; findLast < findLastTo; findLast++) {
                    if (!this._chart.getDatasetMeta(findLast).hidden) {
                      lastVisible = findLast;
                    }
                  }
                  var rounded = this._datasetIndex === lastVisible;

                  if (rounded) {
                  //Positive Value
                    ctx.moveTo(x + radius, y);

                    ctx.lineTo(x + width - radius, y);

                    // top right
                    ctx.quadraticCurveTo(x + width, y, x + width, y + radius);


                    ctx.lineTo(x + width, y + height - radius);

                    // bottom right
                    if (fullCornerRadius || typeOfChart == 'horizontalBar')
                      ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
                    else
                      ctx.lineTo(x + width, y + height, x + width - radius, y + height);


                    ctx.lineTo(x + radius, y + height);

                    // bottom left
                    if (fullCornerRadius)
                      ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                    else
                      ctx.lineTo(x, y + height, x, y + height - radius);


                    ctx.lineTo(x, y + radius);

                    // top left
                    if (fullCornerRadius || typeOfChart == 'bar')
                      ctx.quadraticCurveTo(x, y, x + radius, y);
                    else
                      ctx.lineTo(x, y, x + radius, y);
                  }else {
                    ctx.moveTo(x, y);
                    ctx.lineTo(x + width, y);
                    ctx.lineTo(x + width, y + height);
                    ctx.lineTo(x, y + height);
                    ctx.lineTo(x, y);
                  }
                }
              
            }

            ctx.fill();
            if (borderWidth) {
              ctx.stroke();
            }
          };
  }

  
  if(document.getElementById(chart_id) == undefined)
  {
    return;
  }
  if(document.getElementById(chart_id) !== null)
  {

    //get
    $.get(getUserPowerMeterAccountSummaryDataUrl, { leaf_house_id : 0 ,  month_ended :  '01-2019',  month_ended : '08-2019' , export_by : 'pdf' }, function(data) {
     if(data['status_code'] == true){


            //left data represent left bar and vice versa
           /* var user_balance_data = [40,];
            var user_subsidy_data = [10,];
            //right chart data
            var user_usage_data = [,100];*/
 
          console.log(data.data);
          console.log('data get :' + data.data['credit']);
           var user_balance_data = [ data.data['complementary'] , ] ;
            var user_subsidy_data =  [data.data['credit'], ];
            //right chart data
            var user_usage_data =  ['',data.data['usage']];

            var dates = [formatLabel("Balance", 10), formatLabel("Monthly Usage", 10)];

            var bar_ctx;

            init_loading_overlay();
           // init_pw_loading_circle();
            var bar_ctx = document.getElementById(chart_id).getContext('2d');
          
                var light_green_gradient = bar_ctx.createLinearGradient(0, 0, 0, 600);
                light_green_gradient.addColorStop(0, 'white');
                light_green_gradient.addColorStop(1, 'green');

                var purple_orange_gradient = bar_ctx.createLinearGradient(0, 0, 0, 600);
                purple_orange_gradient.addColorStop(0, 'white');
                purple_orange_gradient.addColorStop(1, 'rgba(161, 232, 212, 1)');
                init_hide_loading_overlay();
                //var fillPattern = bar_ctx.createPattern(img, 'repeat');
                var bar_chart = new Chart(bar_ctx, {
                    type: 'bar',
                    responsive: true,
                    data: {

                        labels: dates,
                        datasets: [
                        {
                            /*backgroundColor: [
                                              pattern.draw('square', '#ff6384'),
                                              pattern.draw('circle', '#36a2eb'),
                                              pattern.draw('diamond', '#cc65fe'),
                                              pattern.draw('triangle', '#ffce56')
                                          ],*/
                            label: 'Complementary',
                            data: user_balance_data,
                            backgroundColor: light_green_gradient,
                            hoverBackgroundColor: light_green_gradient,
                            hoverBorderWidth: 2,
                        },
                        {
                            label: 'Balance',
                            data: user_subsidy_data,
                            backgroundColor: purple_orange_gradient,//"#FFA000",
                            hoverBackgroundColor: purple_orange_gradient,// "#FFCA28",
                            hoverBorderWidth: 0
                        },
                        {
                            label: 'Usage',
                            data: user_usage_data,
                            backgroundColor: "#D32F2F",
                            hoverBackgroundColor: "#EF5350",
                            hoverBorderWidth: 0
                        },
                        ]
                    },
                    options: {

                        //Border radius; Default: 0; If a negative value is passed, it will overwrite to 0;
                        cornerRadius: 10, 
                        //Default: false; if true, this would round all corners of final box;
                        fullCornerRadius: false, 
                        //Default: false; if true, this rounds each box in the stack instead of only final box;
                        stackedRounded: false,
                        
                        elements: {
                          point: {
                            radius: 25,
                            hoverRadius: 35,
                            pointStyle: 'rectRounded',

                          }
                        },

                        animation: {
                          duration: 10,
                        },
                        tooltips: {
                          mode: 'label',
                          callbacks: {
                          label: function(tooltipItem, data) { 

                            return data.datasets[tooltipItem.datasetIndex].label + ": " + numberWithCommas(tooltipItem.yLabel);
                          }
                          }
                         },
                        scales: {
                          xAxes: [{ 
                            stacked: true, 
                            barPercentage: 1,
                            gridLines: { display: false },
                            ticks: {fontSize: 8 },


                            }],
                          yAxes: [{ 
                            stacked: true, 
                             barPercentage: 1,
                              gridLines: { display: false },
                            ticks: {
                              callback: function(value) { return numberWithCommas(value); },
                            }, 

                             scaleLabel: {
                                display: true,
                                labelString: 'MYR'
                                //labelString: 'kWh'
                              },
                            }],
                        },
                        legend: {display: true , position :'position'}
                    },
                    plugins: [{
                    beforeInit: function (chart) {
                      chart.data.labels.forEach(function (value, index, array) {
                        var a = [];
                        a.push(value.slice(0, 5));
                        var i = 1;
                        while(value.length > (i * 5)){
                          a.push(value.slice(i * 5, (i + 1) * 5));
                            i++;
                        }
                        array[index] = a;
                      })
                    }
                  }]
                 
              });//end of chart generation

          //end of status code if
         }
           
      });
    //end of calling
   }//end of if element avaiable

}
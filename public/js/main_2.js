var current_step = 1;

$(document).ready(function(){
  
  var url = window.location.href;
  //console.log(url);
  $( document ).ready(function() {
        //console.log( "document loaded" );
        if(url.includes('admin/dashboard')){
          //console.log("in");
           init_transaction_chat();
           init_bar_chart();
        }else if(url.includes('admin/oc/products')){


       
        }else{
            init_data_table();
            $('#leaf_data_table').DataTable({
              'paging'      : true,
              'lengthChange': true,
              'searching'   : true,
              'ordering'    : true,
              'info'        : true,
              'autoWidth'   : true,
              'fixedHeader': true,
              'bProcessing': true,
            });
        }
  });


     $('#submit_report_button').click(function(e){
         //e.preventDefault();
         loading_on_submit();
     });

        $( window ).on( "load", function() {
           // console.log( "window loaded" );
        });

    if(url.includes('payment-received/new') || url.includes('payment-received/edit'))
    {
        $('[data-mask]').inputmask()

        show_only_div_step_by_step_no(get_current_step());
        if($('#customer_id').val() != undefined && $('#customer_id').val() != ""){
          init_customer_info();
        }
        
        $('#alert_msg_div').css("display", "none");
        init_payment_received_ui();
        $(document).on('submit','form.form-horizontal',function(){
         check_payment_received();
        });

        if(url.includes('payment-received/edit')){
          $('#customer_id').css("display", "block");
        }

    }else if(url.includes('ar-refunds/new')){
        init_ar_refund_UI();
    }else if(url.includes('admin/dashboardWIP')){
      get_latest_meter_daily_reading();
      get_latest_meter_daily_reading_by_daily_record_summary();
    }else if(url.includes('admin/meter/subsidiaries')){
      init_meter_susidy_ui();
    }


        $('select[multiple].active.3col').multiselect({
            columns: 3,
            placeholder: 'Please select',
            search: true,
            searchOptions: {
                'default': 'Search'
            },
            selectAll: true
        });

    

});

function init_data_table(){
  $('#leaf_data_table').DataTable({
        
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
      'fixedHeader': true,
      'bProcessing': true,
  
    });
}

function set_alert_msg(msg,second=null){

  $('#alert_msg_div').removeClass('hide').fadeIn('5000');
  document.getElementById('alert_msg').innerHTML = msg; 
 
  setTimeout(function(){
       $('#alert_msg_div').fadeIn('3000').addClass('hide');    
  },6000);
 
}

function numeric_input_only(me) {
   
    amount = $(me).val();
    $("#" + $(me).attr('id')).keydown(function(e) {

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


/*
|--------------------------------------------------------------------------
| Here to manage of js and jquery of ar payment received
|--------------------------------------------------------------------------
|
*/
function check_payment_received(){
   type = $('#type').val();
   amount = $('#amount').val();
   paymentReceivedItemNo = $('#document').length;
   if(type == 'security_deposit'){
      if(amount == 0 || amount == undefined ){
         set_alert_msg("Please enter amount.");
         event.preventDefault();
      }
    }else if(type == 'invoice_receipt'){                                                                                                              
     if(paymentReceivedItemNo == 0 || paymentReceivedItemNo == undefined ){
       $('#alert_msg').val("test");
       set_alert_msg("Please ensure document is selected.");
       event.preventDefault();
     }   
    }
}

//THE operation variable get the item of previous document
function get_ar_operation_variable(){
   var url = window.location.href;
   var operation_variable = {table_id:"", operation:""};
    if(url.includes('payment-received/new') || url.includes('payment-received/edit')){
      operation_variable = {table_id:"invoice_table", operation:"AR_INVOICE"};
    }else if(url.includes('ar-refunds/new')){
      operation_variable = {table_id:"payment_received_table", operation:"AR_PAYMENT_RECEIVED"};
    }

    return operation_variable;
}

function init_payment_received_type_handle(id){
  
    type = $(id).val();
    operation_variable = get_ar_operation_variable();
    if(type == 'security_deposit'){
        divToHide = "invoice_receipt";
        divToShow = "security_deposit";
        $('#amount').prop("readonly", false);
        $('#amount').val("0.00");
       
          if($('#id_customer').val() != undefined ){
           getCustomerDocumentByCustomerIdAndType(type,operation_variable.operation,operation_variable.table_id,type);
          }
        
    }else if(type == 'invoice_receipt'){
        divToHide = "security_deposit";
        divToShow = "invoice_receipt";
        $('#amount').prop("readonly", true);
        $('#amount').val("0.00");
      
          if($('#id_customer').val() != undefined ){
            getCustomerInvoiceById($('#id_customer').val(),operation_variable.table_id);
          }
    }

    $('div[id*='+divToHide+']').css("display", "none");
    $('div[id*='+divToShow+']').css("display", "block");
}


function init_room_type_subsidize_handle(id){
  
    type = $(id).val();

    if(type == 'single'){
        divToHide = "twin_room_div";
        divToShow = "single_room_div";
         
    }else if(type == 'twin'){
        divToHide = "single_room_div";
        divToShow = "twin_room_div";
       
    }

   
    $('div[id*='+divToHide+']').fadeIn(1000).addClass('hide');
    $('div[id*='+divToShow+']').fadeIn(1000).removeClass('hide');
}


function init_payment_received_ui(){
     $('div[id*=security_deposit]').css("display", "none");
     $('div[id*=invoice_receipt]').css("display", "none");
     if($('#is_by_item_sale').val() !=true){
        $('#amount').val('0.00');
     }
     init_return_payment_div('return_payment');
}

function change_payment_label_text_by_payment_method(id){
   
    labelText = "";
    type = $(id).val();
    $("[name='reference_no']").removeAttr('readonly');
    $("[name='reference_no']").val("");
    $("[name='reference_no']").mask('000000000000');

    if(type=='credit_card'){
        labelText = "Credit Card No.";
        $("[name='reference_no']").mask('9999-9999-9999-9999');
    }else if(type=='cheque'){
         labelText = "Cheque No.";
    }else if(type=='cash'){
         labelText = "None";
         $("[name='reference_no']").val('None');
         $("[name='reference_no']").attr('readonly', 'true');
    }else if(type=='etf'){
         labelText = "ETF No.";
    }else{
        labelText = "Reference No.";
    }
   
    document.getElementById('doc_payment_no_ref_no').innerHTML = labelText;
}

function getCustomerInvoiceById(id , tableId=null) {
     var tbody = $("#"+tableId).find("tbody");
     var tr;
     init_loading_overlay();
    $.get(customerInvoiceUrl, { customer_id: id }, function(data) {
          if(data['status_code'] == true){
            i = 0;
            for(i=0; i < data['listing'].length ; i ++){
                 tbody.find("tr").remove();
                    tr += "<tr>"
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][ar_invoice_id]' value='"+data['listing'][i].id+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][document_no]' value='"+data['listing'][i].document_no+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][due_date]' value='"+data['listing'][i].due_date+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][currency_code]' value='"+data['listing'][i].currency_code+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][document_date]' value='"+data['listing'][i].document_date+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][total_amount]' value='"+data['listing'][i].total_amount+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][outstanding_amount]' value='"+data['listing'][i].outstanding_amount+"' class='form-control' />";
                    tr += "<td> <input type='checkbox'  name='document["+data['listing'][i].id+"][is_checked]' ' id='invoice_item_"+i+"'></td>" 
                    tr += "<td class='col-md-1 text-center' name='document_no"+i+"' id='document_no"+i+"'>"+ data['listing'][i].document_no +"</td>"
                    tr += "<td class='col-md-1 text-center' name='due_date"+i+"' id='due_date"+i+"'>"+ data['listing'][i].due_date +"</td>"
                    tr += "<td class='col-md-1 text-center' name='currency_code"+i+"' id='currency_code"+i+"'>"+ data['listing'][i].currency_code +"</td>"
                    tr += "<td class='col-md-1 text-center' name='document_date"+i+"' id='document_date"+i+"'>"+ data['listing'][i].document_date +"</td>"
                    tr += "<td class='col-md-1 text-center' name='total_amount"+i+"' id='total_amount"+i+"'>"+ data['listing'][i].total_amount +"</td>"
                    tr += "<td class='col-md-1 text-center' value="+ init_decimal_point(data['listing'][i].outstanding_amount) +" name='outstanding_amount_"+i+"' id='outstanding_amount_"+i+"'>"+ init_decimal_point(data['listing'][i].outstanding_amount) +"</td>"
                    tr += "<td class='col-md-1 text-center'><input type='text' name='document["+data['listing'][i].id+"][assign_credit_]' id='assign_credit_"+i+"' class='form-control' oninput='check_is_exceed_maximum(outstanding_amount_"+ i  +",assign_credit_"+i+","+tableId+")' disabled></td>"
                    tr += "<td class='col-md-1 text-center'><input type='text' name='document["+data['listing'][i].id+"][applied_amount]' id='applied_amount"+i+"'  onblur='copy_input_from_to_by_id(this,this);' class='form-control' oninput='check_is_exceed_maximum(outstanding_amount_"+ i  +",applied_amount"+i+","+tableId+");copy_input_from_to_by_id(applied_amount"+ i  +",assign_credit_"+i+");'></td></tr>"             
                  tbody.append(tr);
              }
          }else{
                  tbody.find("tr").remove();
                  tr += "<tr><td class='col-md-8 text-center' colspan='9'>No data found</td></tr>"
                  tbody.append(tr);
          }
           init_hide_loading_overlay();
    }, "json");
}

function getCustomerDocumentByCustomerIdAndType(id, type , tableId=null , document_type) {
     var tbody = $("#"+tableId).find("tbody");
     var tr;
     init_loading_overlay();
    $.get(customerDocumentByCustomerIdAndTypeUrl, { customer_id: id  , type : type , document_type : document_type}, function(data) {
          if(data['status_code'] == true){

            i = 0;
            for(i=0; i < data['listing'].length ; i ++){
                 tbody.find("tr").remove();
                    tr += "<tr>"
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][ar_payment_received_id]' value='"+data['listing'][i].id+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][document_no]' value='"+data['listing'][i].document_no+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][due_date]' value='"+data['listing'][i].due_date+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][currency_code]' value='"+data['listing'][i].currency_code+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][document_date]' value='"+data['listing'][i].document_date+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][total_amount]' value='"+data['listing'][i].total_amount+"' class='form-control' />";
                    tr += "<input type='hidden' name='document["+data['listing'][i].id+"][outstanding_amount]' value='"+data['listing'][i].outstanding_amount+"' class='form-control' />";
                    tr += "<td> <input type='checkbox'  name='document["+data['listing'][i].id+"][is_checked]' ' id='invoice_item_"+i+"'></td>" 
                    tr += "<td class='col-md-1 text-center' name='document_no"+i+"' id='document_no_"+i+"'>"+ data['listing'][i].document_no +"</td>"
                    tr += "<td class='col-md-1 text-center' name='document_date"+i+"' id='document_date_"+i+"'>"+ data['listing'][i].document_date +"</td>"
                    tr += "<td class='col-md-1 text-center' name='currency_code"+i+"' id='currency_code_"+i+"'>"+ data['listing'][i].currency_code +"</td>"
                    tr += "<td class='col-md-1 text-center' name='rate"+i+"' id='rate"+i+"'>"+ data['listing'][i].rate +"</td>"
                    tr += "<td class='col-md-1 text-center' name='total_amount"+i+"' id='total_amount_"+i+"'>"+ init_decimal_point(data['listing'][i].total_amount) +"</td>"
                    tr += "<td class='col-md-1 text-center' value="+ init_decimal_point(data['listing'][i].payment_amount) +" name='payment_amount_"+i+"' id='payment_amount_"+i+"'>"+ init_decimal_point(data['listing'][i].payment_amount) +"</td>"
                    tr += "<td class='col-md-1 text-center'><input type='text' name='document["+data['listing'][i].id+"][applied_amount]' id='applied_amount_"+i+"' class='form-control'  onblur='copy_input_from_to_by_id(this,this);' oninput='check_is_exceed_maximum(total_amount_"+ i  +",applied_amount_"+i+","+tableId+");copy_input_from_to_by_id(applied_amount_"+ i  +", amount);'></td></tr>"
                  tbody.append(tr);
              }
          }else{
   
              tbody.find("tr").remove();
              tr += "<tr><td class='col-md-8 text-center' colspan='9'>No data found</td></tr>"
              tbody.append(tr);
          }
           init_hide_loading_overlay();
    }, "json");
}


function init_return_payment_div(id){

    if($('#'+id).is(":checked")){
        $('#'+id+'_div').removeClass('collapsed-box');
    }else{
        $('#'+id+'_div').addClass('collapsed-box');
    }
    
   
}


/*
|--------------------------------------------------------------------------
| Here to manage of js and jquery of ar refund
|--------------------------------------------------------------------------
|
*/
function init_ar_refund_UI() {
    init_return_payment_div('return_payment');
}

function init_meter_susidy_ui() {
    init_tenant_list_div_by_room_type_combobox();
}
//wipnow
function init_tenant_list_div_by_room_type_combobox(){

    selected_room_type = document.getElementById('room_type').value + "_room";
    if(selected_room_type == 'single_room'){
      div_to_hide = 'twin_room';
    }else{
      div_to_hide = 'single_room';
    }
    div_to_show = selected_room_type;

    $('#' + div_to_hide + '_div').fadeIn(1000).addClass('hide');
    $('#' + div_to_show + '_div').fadeIn(1000).removeClass('hide');
}



function getPaymentDetailByProductId(id, type = null) {

    $.get(paymentInfoByProductIdUrl, {
        product_id: id
    }, function(data) {

        if (data['status_code'] == true) {
            document.getElementById('payable_amount').innerHTML = data.selling_price;
            //document.getElementById('doc_payment_no_ref_no').innerHTML = labelText;
        }

    }, "json");
}

function init_meter_payment_received_operation_by_room_id_and_type(id, type) {
    getMeterRegisterByRoomIdUrl(id);
    getRoomInvoiceDocumentByRoomIdAndType(id, type);

}

function getRoomInvoiceDocumentByRoomIdAndType(id, type , tableId=null) {

     var tbody = $("#"+tableId).find("tbody");
     var tr;
     init_loading_overlay();

    $.get(getInvoiceDocumentByRoomIdAndTypeUrl, { leaf_room_id: id  , type : type}, function(data) {

          if(data['status_code'] == true){

            i = 0;
            for(i=0; i < data['listing'].length ; i ++){
                 tbody.find("tr").remove();
                    tr += "<tr><td>#</td>" 
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].document_date +"</td>"
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].document_no +"</td>"
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].currency_code +"</td>"
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].currency_rate +"</td>"
                    tr += " <td class='col-md-1 text-center'>"+ init_decimal_point(data['listing'][i].total_amount) +"</td>"
                    tr += " <td class='col-md-1 text-center'>"+ init_decimal_point(data['listing'][i].total_amount) +"</td>"
                    tr += " <td class='col-md-1 text-center'> <input type='text' class='form-control' name='fname'></td>"
                    tr += "<td class='col-md-1 text-center'><input type='text' class='form-control' name='fname'></td>"
                    tr += "<td class='col-md-1 text-center'></td></tr>"
                  tbody.append(tr);
              }
          }else{
              tbody.find("tr").remove();
              tr += "<tr><td class='col-md-8 text-center' colspan='9'>No data found</td></tr>"
              tbody.append(tr);
          }
           init_hide_loading_overlay();
    }, "json");
}


function getRoomPaymentReceivedDocumentByRoomIdAndType(id, type , tableId=null) {

     var tbody = $("#"+tableId).find("tbody");
     var tr;
     init_loading_overlay();

    $.get(getInvoiceDocumentByRoomIdAndTypeUrl, { leaf_room_id: id  , type : type}, function(data) {
          if(data['status_code'] == true){

            i = 0;
            for(i=0; i < data['listing'].length ; i ++){
                 tbody.find("tr").remove();
                    tr += "<tr><td>#</td>" 
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].document_date +"</td>"
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].document_no +"</td>"
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].currency_code +"</td>"
                    tr += "<td class='col-md-1 text-center'>"+ data['listing'][i].currency_rate +"</td>"
                    tr += " <td class='col-md-1 text-center'>"+ data['listing'][i].total_amount +"</td>"
                    tr += " <td class='col-md-1 text-center'> </td>"
                    tr += " <td class='col-md-1 text-center'> </td>"
                    tr += "<td class='col-md-1 text-center'></td>"
                    tr += "<td class='col-md-1 text-center'></td></tr>"
                  tbody.append(tr);
              }
          }else{

             tbody.find("tr").remove();
              tr += "<tr><td class='col-md-8 text-center' colspan='9'>No data found</td></tr>"
              tbody.append(tr);

          }
           init_hide_loading_overlay();
    }, "json");
}


function getRoomPaymentReceivedDocumentByRoomIdAndType(id, type , tableId=null) {
     var tbody = $("#"+tableId).find("tbody");
     var tr;
     init_loading_overlay();
    $.get(getPaymentReceivedDocByCustIdAndType, { customer_id: id  , type : type}, function(data) {
          if(data['status_code'] == true){

            i = 0;
            for(i=0; i < data['listing'].length ; i ++){
                 tbody.find("tr").remove();
                    tr += "<tr><td> <input type='checkbox' name='invoice_item_"+i+"' id='invoice_item_"+i+"'></td>" 
                    tr += "<td class='col-md-1 text-center' name='document_no"+i+"' id='document_no"+i+"'>"+ data['listing'][i].document_no +"</td>"
                    tr += "<td class='col-md-1 text-center' name='due_date"+i+"' id='due_date"+i+"'>"+ data['listing'][i].due_date +"</td>"
                    tr += "<td class='col-md-1 text-center' name='currency_code"+i+"' id='currency_code"+i+"'>"+ data['listing'][i].currency_code +"</td>"
                    tr += "<td class='col-md-1 text-center' name='document_date"+i+"' id='document_date"+i+"'>"+ data['listing'][i].document_date +"</td>"
                    tr += "<td class='col-md-1 text-center' name='total_amount"+i+"' id='total_amount"+i+"'>"+ init_decimal_point(data['listing'][i].total_amount) +"</td>"
                    tr += "<td class='col-md-1 text-center' value="+ init_decimal_point(data['listing'][i].payment_amount) +" name='payment_amount_"+i+"' id='payment_amount_"+i+"'>"+ init_decimal_point(data['listing'][i].payment_amount) +"</td>"
                    tr += "<td class='col-md-1 text-center'><input type='text' name='applied_amount"+i+"' id='applied_amount"+i+"' class='form-control' onblur='copy_input_from_to_by_id(this,this)' oninput='check_is_exceed_maximum(outstanding_amount_"+ i  +",applied_amount"+i+",tableId);copy_input_from_to_by_id(applied_amount"+ i  +",assign_credit_"+i+");'></td>"             
                  tbody.append(tr);
              }
          }else{

              tbody.find("tr").remove();
              tr += "<tr><td class='col-md-8 text-center' colspan='9'>No data found</td></tr>"
              tbody.append(tr);

          }
           init_hide_loading_overlay();
    }, "json");
}


function get_meter_reading_detail(room_id){

    $.get(getMeterDetailUrl, {room_id : room_id}, function(fdata){
        for (var key in fdata.meter) {
            if(key != 'id'){
              if(key == 'total_usage'){
                 for (var member_usage_key in fdata.meter[key]) { 
                     document.getElementById('lbl_'+member_usage_key+'_'+key+'_'+room_id).innerHTML = fdata.meter[key][member_usage_key];
                 }         
              }else{
                 document.getElementById('lbl_'+key+'_'+room_id).innerHTML = fdata.meter[key];
              }             
            }             
        }        
    },"json");
}

function get_latest_meter_daily_reading(){

    $.get(getLatestDailyMeterReading, {leaf_group_id : 282}, function(fdata){
        document.getElementById('lbl_daily_record_status').innerHTML = "Done";
    },"json");

}

function loading_on_submit(){

  if($("input[name='export_by']:checked").val() == 'html'){   
   init_loading_overlay();
  }else{
    alert("Report will be generated on new tab , you can continue here for other report.");
  }
}

//with recent record checking
function get_latest_meter_daily_reading_by_daily_record_summary(){

    $.get(getLatestDailyMeterReadingByDailyRecordSummary, {leaf_group_id : 282}, function(fdata){
        document.getElementById('lbl_daily_record_status').innerHTML = "Done";
    },"json");

}

function getMeterRegisterByRoomIdUrl(id) {

    $.get(paymentInfoByProductIdUrl, {leaf_room_id: id}, function(data) {

        if (data['status_code'] == true) {
            $('#meter_account_no').val() = data.model.account_no;
        }

    }, "json");
}

function check_is_exceed_maximum(oustanding_amount_id, me, target_table = null) {

    amount = $(me).val();
    max = $(oustanding_amount_id).html();

    if (+amount > +max) {
        $(me).val(max);
        init_calculate_table_amount_type_by_id('applied_amount', target_table.id);
    } else {
        init_calculate_table_amount_type_by_id('applied_amount', target_table.id);
    }

    status = +amount > +max ? false : (amount > 0 ? true : false);
    init_check_box_by_status("invoice_item_" + (oustanding_amount_id.id.substr(oustanding_amount_id.id.length - 1)), status);

}

function copy_input_from_to_by_id(from, to) {
    $(to).val(init_decimal_point($(from).val()));
}

function init_calculate_table_amount_type_by_id(amountType, tableId) {
    var table = $("#" + tableId);
    var tbody = table.find("tbody");
    var index = table.find("tr").length;
    var total_amount = 0;

    tbody.find("tr").each(function() {
        if (!$(this).hasClass("hidden")) {
            total_amount += parseFloat($(this).find("input[name*=" + amountType + "]").val()) || 0;
        }
    });
    var td = document.getElementById('total_applied_amount');
    document.getElementById('total_' + amountType).innerHTML = init_decimal_point(total_amount);
    update_textbox_by_content_and_id(init_decimal_point(total_amount), 'amount');
}

function init_check_box_by_status($checkBoxId, status) {
    status = status == "true" ? true : false;
    $("#" + $checkBoxId).prop('checked', status);
}

function init_currency_rate(me) {

    $.get(getCurrencyModelByIdUrl, {
        currency_id: $(me).val()
    }, function(data) {
        if (data['status_code'] == true) {
            $('#currency_rate').val(data.model.rate);
            $(".currency_label").html(data.model.code);
        }
    }, "json");
}

function update_textbox_by_content_and_id(content, id) {
    $('#' + id).val(content);
}

//=================================================================================
//Steppper UI Operation function
//=================================================================================
function hide_step_by_step_no_shopping(step_no) {
    //$("span[id*=step]").removeClass('done');
    $('#step_' + (step_no)).fadeIn(1000).addClass('hide');
    $('#step_' + (step_no + 1)).fadeIn(1500).removeClass('hide');
}

function get_current_step() {
    return current_step;
}

function set_current_step(new_step) {
    current_step = new_step;
}

function hide_step_by_step_no(step_no) {
    $('#step_' + step_no).removeClass('done');
    $('#div_step_' + (step_no)).fadeIn(1000).addClass('hide');
    $('#div_step_' + step_no + 1).fadeIn(1000).removeClass('hide');
}

function set_stepper_status_by_step_no(step_no) {
    $('#step_' + step_no).fadeIn(1000).addClass('done');
}

function remove_stepper_status_by_step_no(step_no) {
    $('#step_' + step_no).removeClass('done')
}

function set_current_step_div_and_remove_previous_by_step_no(step_no) {

    $('#div_step_' + step_no).fadeIn(1000).removeClass('hide');
    $('#div_step_' + (step_no - 1)).fadeIn(1000).addClass('hide');

}

function set_current_step_div_by_step_no(step_no) {

    $('#div_step_' + step_no).fadeIn(1000).removeClass('hide');

}

function show_only_div_step_by_step_no(step_no) {

    $("div[id^='div_step']").fadeIn(1000).addClass('hide');
    $('#div_step_' + step_no).fadeIn(1000).removeClass('hide');
}

function show_final_step() {
    $('#div_step_end').fadeIn(1000).removeClass('hide');
}

function hide_final_step() {
    $('#div_step_end').fadeIn(1000).addClass('hide');
}

function show_only_div_step_less_than_step_no(step_no) {

    $("div[id^='div_step']").fadeIn(1000).addClass('hide');
    for (i = 1; i <= step_no; i++) {
        $('#div_step_' + i).fadeIn(1000).removeClass('hide');
    }
}

function go_to_step(me, operation = null) {

    var id = $(me).attr('id') == undefined ? "step_" + me : $(me).attr('id');
    if (document.getElementById(id).className.includes('done')) {

        new_step = parseInt(id.substr(id.length - 1));
        if (operation == 'backward') {
            hide_step_by_step_no(new_step);
            new_step -= 1;

        } else if (operation == 'forward' /*|| operation == undefined*/ ) {
            new_step += 1;
        } else if (operation == 'go_to' || operation == undefined) {
            show_only_div_step_by_step_no(new_step);
            for (i = new_step; i <= $('#total_step').val(); i++) {
                remove_stepper_status_by_step_no(i);
            }
        }

        set_stepper_status_by_step_no(new_step);
        set_current_step(new_step);
        set_current_step_div_and_remove_previous_by_step_no(new_step);

    } else {
        set_alert_msg("Please go step by step.");
    }

}

function go_to_step_with_previous_show(me, operation = null) {

    total_step = $('#total_step').val();
    format_correct = $('#format_correct').val();
    var id = $(me).attr('id') == undefined ? (parseInt(me) > total_step ? "step_" + (parseInt(me) - 1) : ("step_" + me)) : $(me).attr('id');
    new_step = parseInt(id.substr(id.length - 1));
    is_proceedable = format_correct ? (operation == 'backward' ? true : (progression_checker(10) ? (new_step > total_step ? false : true) : false)) : false;
    //new_step = parseInt(id.substr(id.length - 1)) + 1;

    if (document.getElementById(id).className.includes('done') && new_step >= total_step && is_proceedable == true) {

        show_final_step();

    } else if (document.getElementById(id).className.includes('done') && new_step >= 1 && is_proceedable == true) {

        if (operation == 'backward') {
            hide_step_by_step_no(new_step);
            new_step -= 1;

        } else if (operation == 'forward' /*|| operation == undefined*/ ) {
            new_step += 1;
        } else if (operation == 'go_to' || operation == undefined) {

            show_only_div_step_less_than_step_no(new_step);
            for (i = new_step; i < total_step; i++) {
                remove_stepper_status_by_step_no(i);
            }
        }

        set_stepper_status_by_step_no(new_step);
        set_current_step(new_step);
        set_current_step_div_by_step_no(new_step);
        hide_final_step();

    } else if (is_proceedable == false) {
        set_alert_msg("Please ensure all field(s) are correct.");
    } else {
        set_alert_msg("Please go step by step.");
    }

}

function init_flow_by_step_no(step_no) {

    $('#div_step_' + step_no).removeClass('hide');
    $('#div_step_' + step_no).fadeOut(100);
    $('#div_step_' + step_no).fadeIn(1000);
}

function progression_checker(except_step = null) {

    if (($('#reference_no').val() != undefined) == true) {
        if (reference_no_format_checker($('#reference_no')) == false) {
            is_proceedable = false;
            return;
        }
    }
    var object = $("#div_step_" + get_current_step());

    is_proceedable = true;
    if (get_current_step() != except_step) {
        object.find("select").each(function() {
            if ($(this).val() == '') {
                is_proceedable = false;
                return;
            }
        });

        object.find("input[type='text']").each(function() {
            if ($(this).val() == '') {
                is_proceedable = false;
                return;
            }
        });
    }

    return is_proceedable;
}

function progression_checker_by_step_no() {

    var is_proceedable = progression_checker();
    if (is_proceedable == true) {
        step_no = get_current_step();
        step_no++;
        set_current_step(step_no);
        init_flow_by_step_no(step_no);
        set_stepper_status_by_step_no(step_no);
    }

}

function reference_no_format_checker(me) {
    var cheque_etf_format = '[0-9]{9,12}';
    var credit_card_format = /^\(?([0-9]{4})\)?[-]?([0-9]{4})[-]?([0-9]{4})\)?[-]?([0-9]{4})$/;
    var payment_method = $('#payment_method').val();

    if (payment_method == 'credit_card') {
        if ($(me).val().match(credit_card_format)) {
            $('#format_correct').val(true);
            return true;
        }
    } else {
        if ($(me).val().match(cheque_etf_format)) {
            $('#format_correct').val(true);
            return true;
        }
    }

    set_alert_msg('Reference Number format is incorrect.');
    return false;
}

function init_customer_info(me) {

    customer_id = typeof(me) == "undefined" ? document.getElementById('customer_id').value : $(me).val();
    $.get(customerInfoUrl, {
        customer_id: customer_id
    }, function(fdata) {
        for (var key in fdata.data) {

            if (key == "name") {

                $("input[name=customer_" + key + "]").val(fdata.data[key]);

            } else if (key != "status") {
                $("input[name=" + key + "]").val(fdata.data[key]);

                if (key == "currency_label") {
                    $(".currency_label").html(fdata.data[key]);
                }
                if (key.match(/_id/g)) {
                    $("select[name=" + key + "]").val(fdata.data[key]).trigger("change");
                }
            }
        }
    }, "json");

    //getCustomerInvoiceById($(me).val(),'invoice_table');
}

function update_selected_product(product_id) {
    
   
    cost =  $('#cost_'+product_id).val();
    price = $('#price_'+product_id).val();
    selling_price = $('#selling_price_'+product_id).val();
    init_loading_overlay();
    console.log(cost + price + selling_price);
    $.get(getProductPriceUpdate, { id: product_id , cost : cost , price : price , selling_price : selling_price }, function(fdata) {
          init_hide_loading_overlay();
          set_alert_msg(fdata.status_msg);
    }, "json");

    //getCustomerInvoiceById($(me).val(),'invoice_table');
}


function init_transaction_chat(){

  
/*
  token = 123;
      $.get(getARTransactionSummaryUrl, { token: token}, function(data){  

  
          console.log('s');
           console.log(data);
          
              var area = new Morris.Area(data);
              $('.box ul.nav a').on('shown.bs.tab', function () {
                  area.redraw();
                  line.redraw();
                });

      }, "json");*/
}

function preview_image()  
{
     var total_file=document.getElementById("upload_file").files.length;
     for(var i=0;i<total_file;i++)
     {
        id = Math.floor((Math.random() * 9999999) + 1);
       // $('#image_preview').append("<img style='heighh:200px; width:200px;' class='col-md-2' src='"+URL.createObjectURL(event.target.files[i])+"'>");
        $('#image_preview').append("<input name='product_photo' type='radio'  id='"+id+"' class='input-hidden'/> <label for='"+id+"' > <img style='heighh:200px; width:200px;' class='col-md-2' src='"+URL.createObjectURL(event.target.files[i])+"'> </label>");
        
     }
}










function clear_selected(element_id)
{
  $("#" +element_id+ " option:selected").removeAttr("selected");
  var elements = document.getElementById(element_id).options;
  
  for(var i = 0; i < elements.length; i++){
    elements[i].selected = false;
  }
  
}

function init_product_detail_membership_app(){

        $.get(productInfoUrl, {product_id:$(me).val()}, function(fdata){
            
            for (var key in fdata.data) {
              console.log(key);
                if(key == "amount") {
                   $('#top_up_amount_txt').val(fdata.data);
                }
              
            }

        },"json");
    
}

function save_all_product_from_ego88(){

    alert("Data transfer proccess initiated , close this tab will not affect the process .")
    init_loading_overlay();
    $.get(getAllProductsFromEgo88, function(fdata){             
      init_hide_loading_overlay();
    },"json");
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


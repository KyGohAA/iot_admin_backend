

function init_select2(me) {
    if (typeof $(me).select2 !== 'undefined') {
        $(me).select2();
    }
}

function init_daterange_leaf_ui(me) 
{
    $(me).datepicker({
        format: "yyyy-mm-dd",
    });
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

function init_date_range_new_ui_by_id(me)
{
    $("input[id=" + me + "]").daterangepicker({
        opens: 'left',
        timePicker: true,
        "cancelClass": "btn-secondary",
        locale: {
          format: 'DD-MM-YYYY hh:mm A'
        },
    /*}, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));*/
    });
}

function init_date_picker_with_time(name) {
/* Single table*/
    $("input[name=" + name + "]").daterangepicker({
        locale: {
          format: 'YYYY-MM-DD hh:mm A'
        },
        singleDatePicker: true,
        timePicker: true,    
        pick12HourFormat: false,
        showDropdowns: true,
        "cancelClass": "btn-secondary",
        format: 'MM-DD-YYYY HH:mm'
    });
}

function init_single_date_time_picker(me)
{//'input[name="birthday"]'
    $('input[name="dob"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        format: "yyyy-mm-dd",
        "cancelClass": "btn-secondary",
        maxYear: parseInt(moment().format('yyyy-mm-dd'),10),
    });
}

function init_age_control_single_date_time_picker(me)
{//'input[name="birthday"]'
   
    $(me).daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        "cancelClass": "btn-secondary",
        maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
        var years = moment().diff(start, 'years');
        alert("You are " + years + " years old!");
    });
}

window.init_state_selectbox = function(me) {
    var country_label = $(me).prop("name").toString();
    var prefix = country_label.replace(/country_id/i, "");
    var stateSelectbox = $("select[name*="+prefix+"state_id]");
    stateSelectbox.empty();
    init_mobile_overlay();
    $.get(statesComboboxUrl, {country_id:$(me).val()}, function(data){
        for (var i = 0; i < data.length; i++) {
             stateSelectbox.append($("<option>",
             {
                value: data[i].id,
                text : data[i].text 
            }));
        }
        init_mobile_hide_overlay();
    }, "json");
}
window.init_city_selectbox = function(me) {
    var state_label = $(me).prop("name").toString();
    var prefix = state_label.replace(/state_id/i, "");
    var citySelectbox = $("select[name*="+prefix+"city_id]");
    citySelectbox.empty();
    $.get(citiesComboboxUrl, {state_id:$(me).val()}, function(data){
        for (var i = 0; i < data.length; i++) {
             citySelectbox.append($("<option>",
             {
                value: data[i].id,
                text : data[i].text 
            }));
        }
    }, "json");
}


// Load initialize function
function init_mobile_overlay() {
    if (typeof app !== 'undefined') {
        app.preloader.show();
    }
}

function init_mobile_hide_overlay() {
    if (typeof app !== 'undefined') {
        app.preloader.hide();
    }
}

function init_decimal_point(double) {
    return parseFloat(double || 0).toFixed(decimalPoint);
}

function init_room_combobox(me) {
    var leaf_house_id   =   $(me);
    var leaf_room_id    =   $("select[name=leaf_room_id]");

    init_loading_overlay();
    var progress        =   $.get(roomsComboboxUrl, {leaf_house_id:leaf_house_id.val()}, function(data){
        leaf_room_id.empty();
        for (var i = 0; i < data.list.length; i++) {
            leaf_room_id.append($("<option>", {
                value: data.list[i].room_id,
                text : data.list[i].room_name, 
            }));
        }
        //init_hide_loading_overlay();
    },"json");
    $.when(progress).done(function(){
        init_hide_loading_overlay();
    });
}

function init_room_status(me) {

    var leaf_room_id    =   $(me);
    init_loading_overlay();
    var progress        =   $.get(roomInfoUrl, {leaf_room_id:leaf_room_id.val()}, function(data){
        $("input[name=over_due_amount]").val(init_decimal_point(data.over_due_amount));
        $("input[name=last_meter_reading]").val(init_decimal_point(data.last_meter_reading));
    },"json");
    $.when(progress).done(function(){
        init_hide_loading_overlay();
    });

    // init_payment_received_by_room_and_operation_type($(me).val(),$('#type').val());
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


function loading_payment_page() {

    var loading_to_payment = 'Redirecting the payment page.';
    $("#overlay").css("z-index","1000");
    $('#overlay').waitMe({
        //none, rotateplane, stretch, orbit, roundBounce, win8, 
        //win8_linear, ios, facebook, rotation, timer, pulse, 
        //progressBar, bouncePulse or img
        effect: 'win8_linear',
        //place text under the effect (string).
        text: loading_to_payment,
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

function check_is_allow_to_pay(me){
  
    $('#btn_payment').attr("disabled", !$(me).prop("checked"));
    
}


function init_utility_charge(me, tableID) {
    var tbody = $("#"+tableID).find("tbody");
    tbody.find("tr").remove();
    $.get(utilityChargeListUrl, {utility_charge_id:$(me).val()}, function(data){
        for (var i = 0; i < data.length; i++) {
            var tr = "<tr>";
            index = i+1;
            tr += "<td class='text-center col-md-1'>"+index+"</td>";
            tr += "<td class='text-center col-md-4'>"+data[i].started+"</td>";
            tr += "<td class='text-center col-md-4'>"+data[i].ended+"</td>";
            tr += "<td class='text-center col-md-1'>"
            if (data[i].is_gst) { tr += enableLabel; } else { tr += disableLabel; }
            tr += "</td>";
            tr += "<td class='text-center col-md-1'>"+data[i].unit_price+"</td>";
            tr += "</tr>";
            tbody.append(tr);
        }
    },"json");
}

function remove_row_membership_application(id_row, tableID){
    
    $('#'+id_row).remove();
    var tbody = $("#"+tableID).find("tbody");
    
    var tr = "<tr id="+id_row+">" 
         + "<td colspan='6' class='text-center'>" 
         + "<label class='alert alert-success alert-wth-icon alert-dismissible fade show' role='alert' onclick='init_member_register_modal();'>"
         + "<span class='alert-icon-wrap'><i class='zmdi zmdi-file-plus'></i></span> Fill in member detail"
         + "</label>"
         + "</td>"
         + "</tr>" ;

     tbody.append(tr);
}


function append_row_membership_application(tableID,id_row, data){
    console.log('Member id' + id_row);
    console.log("Tblae : " + tableID);
    var tbody = $("#"+tableID).find("tbody");
    var tr = "<tr id="+id_row+">" 
            + "<td><img class='w-80p' src='" + (data.photo =='' ? default_pic_url :  default_asset_url+data.photo)+"' alt='icon' /></td>"
            + "<th scope='row'>" + data.name +"</th>"
            + "<td>" + data.ic +"</td>"
            + "<td class='text-dark'>" + data.email +"</td>"
            + "<td><label class='alert alert-success alert-wth-icon alert-dismissible fade show' role='alert' onclick='create_or_update_member_modal("+ data.id + ");'>"
            +"<span class='alert-icon-wrap'><i class='zmdi zmdi-edit'></i></span> Edit </label></td>"
            + "<td><button type='button' class='close' aria-label='Edit' onclick='remove_row_membership_application(" + data.id_house_member +" , 'membership_item_table');'>"
            + "<span aria-hidden='true'>&times;</span></button></td>" 
            + "</tr>" 

     if(id_row == 0)
     {
        tbody.append(tr);
     }
     
     $('#'+id_row).after(tr);
     $('#'+id_row).remove();
     console.log("End job");
     console.log(data);
}



                                                


//init append file upload field
function append_file_upload_field(me, name)
{
  var form_group = $(me).closest(".form-group");
  var location = form_group.find("div.append_field");

  location.find("input[name*="+name+"]").last().clone().appendTo(location);
}


function init_create_or_update_member_process() {
    $.when(create_or_update_member()).done(function( x ) {
         $('#tracking-position-modal').modal('hide');
         $('#tracking-update-modal').modal('show');
    });    
}

function create_or_update_member(me){

    tracking_model = $('#tracking_model').val();
    //newData = '{"user_check_in_outstation_longitude":'+latitude+',"user_check_in_outstation_latitude":'+longitude+'}';
    newData = "{\'user_check_in_outstation_longitude\':"+longitude+",\'user_check_in_outstation_latitude\':"+latitude+"}";

     $.post(postCreateOrUpdateMembershipUrl, {tracking_model: tracking_model , newData: newData , device_detail : device_detail} , function(data){
        //update success block
        if(data.status_code == true){
            close_modal("tracking-update-modal")
            setTimeout(function(){
                $("#div_informtaion").css("display","block");
                $("#label_informtaion").text("Tracking Number : "+ data.tracking_number+ ", its location is successfully update.");
                $("#div_informtaion").removeClass("callout-danger" );
                $("#div_informtaion").addClass("callout-success" );
            }, 3000); 
           
        }else{

        }
 
     },"json");
}





function add_row(tableID) {
    var table = $("#"+tableID);
    var tbody = table.find("tbody");
    var tr = tbody.find("tr");
    var index = tr.length+1;
    var tr_first = tr.not(".hidden").first();
    tr_first.find("select").select2("destroy");
    if (tr_first.find(".input-daterange").length) {
        tr_first.find(".input-daterange").datepicker("destroy");
    } else if (tr_first.find("input[name*=date]").length) {
        tr_first.find("input[name*=date]").datepicker("destroy");
    }
    var clone = tr_first.clone();
    clone.find("td:first").html(index);
    clone.find("input, select, textarea").each(function(){
        this.id = this.id.replace(/([0-9]\d*)$/, index);
        this.name = this.name.replace(/\[([0-9]\d*)\]/, "["+index+"]");
        this.value = "";
    });
    tbody.append(clone);
    tr_first.find("select").each(function(){
        init_select2($(this));
    });
    var tr_first_daterange = tr_first.find(".input-daterange");
    if (tr_first_daterange.length) {
        init_daterange(tr_first_daterange);
    }
    tbody.find("tr:last").find("select").each(function(){
        init_select2($(this));
    });
    var tr_last_daterange = tbody.find("tr:last").find(".input-daterange");
    if (tr_last_daterange.length) {
        init_daterange(tr_last_daterange);
    } else {
        tbody.find("tr:last").find("input:first").focus();
    }
}

function remove_row(me) {
    var tr = $(me).closest("tr");
    var tbody = tr.closest("tbody");
    var checkpoint = tbody.find("tr").not(".hidden").length;
    if(checkpoint > 1) {
        tr.find("input").remove();
        tr.find("td:first").html("");
        tr.find("textarea").remove();
        
        tr.find("select").empty();
        tr.find("select").remove();
        tr.find("number").remove();
        tr.find("a").remove();
        tr.remove();       
        tr.addClass("hidden");
    } else {
        alert(errorRemoveRow);
        tr.find("input").each(function(){
            this.value = "";
        });
        tr.find("input:first").focus();
    }
    if ($("#product_table").length) {
        init_calculate_product_table("product_table");
    }
}


function init_product_info(me, type , operation_type) {
        
        init_loading_overlay();
        $.get(productInfoUrl, {product_id:$(me).val()}, function(fdata){

            var tr = $(me).closest("tr");
            for (var key in fdata.data) {
                
                tr.find("input[name*="+key+"]").val(fdata.data[key]);
        
                if(key == "description") {
                    tr.find("textarea[name*=description]").val(fdata.data[key]);
                }
                if(key.match(/_id/g)) {
                    tr.find("select[name*="+key+"]").val(fdata.data[key]).trigger("change");;
                }
    
            }

            init_calculate_product_table("product_table",operation_type);
            init_hide_loading_overlay();
        },"json");
        
    }


    function init_calculate_row(me, price_adjust,type=null) {

        var tr                  =   $(me).closest("tr");
        var is_tax_inclusive    =   $("input[name*=is_tax_inclusive]");
        var quantity            =   tr.find("input[name*=quantity]");
        var discount            =   tr.find("input[name*=discount]");
        var amount              =   tr.find("input[name*=amount]");
        var unit_price          =   tr.find("input[name*=unit_price]");
        var tax_percent         =   tr.find("input[name*=tax_percent]");
        var tax_txt             =   tr.find("input[name*=tax_txt]");
        if (price_adjust) {
            if(is_tax_inclusive) {
                var new_unit_price  =   (unit_price.val() || 0)/(parseFloat(1)+parseFloat(tax_percent.val()));
                unit_price.val(init_decimal_point(new_unit_price));
            }
        }
        var tax_total           =   quantity.val() * unit_price.val() * tax_percent.val();
        tax_txt.val(init_decimal_point(tax_total));
        var total               =   quantity.val() * unit_price.val() + tax_total;
        amount.val(init_decimal_point(total));
        init_calculate_product_table("product_table",type);
    }

    
    function init_calculate_product_table(tableID,type=null) {

        var table = $("#"+tableID);
        var tbody = table.find("tbody");
        var index = table.find("tr").length;
        var gst_total = 0;
        var sub_total = 0;
        tbody.find("tr").each(function(){
            if(!$(this).hasClass("hidden")) {
                gst_total += parseFloat($(this).find("input[name*=tax_txt]").val()) || 0;
                sub_total += parseFloat($(this).find("input[name*=quantity]").val() * $(this).find("input[name*=unit_price]").val());
            }
        });
        table.find(".sub_total").html(init_decimal_point(sub_total))
        table.find(".gst_total").html(init_decimal_point(gst_total))
        table.find(".grand_total").html(init_decimal_point(sub_total + gst_total))
     
        if(type == 'arpa'){
            var data = {subtotal : sub_total ,tax : gst_total , total : (sub_total + gst_total)};
            update_arpa_by_product_table(data);
        }
       
    }

    function update_arpa_by_product_table(data)
    {  
        for(var key in data) {   
            
            if(key.indexOf("tax") !== -1){

                $('label[id*='+key+']').html(init_decimal_point(data[key]));   

            }else if(key.includes("subtotal") == true){

                $('label[id*='+key+']').html(init_decimal_point(data[key])); 

            } else if(key.includes("total") == true){

                $('label[id*='+key+']').html(init_decimal_point(data[key]));   
                $('#amount').val(data[key]);
            }   
                            
        } 
        
    }


    function init_integrated_accounting_system_component(me)
    {  
        var selected_system = [];
        $.each($(".integrated_accounting_sytem option:selected"), function(){    
            
            selected_system.push($(me).val());
         
        });

        var options = $('#integrated_accounting_sytem option');
        var values = $.map(options ,function(option) {
            return option.value;
        });


        setTimeout(function(){

            counter = 0 ;
            for(var key in $(".integrated_accounting_sytem option:selected").val()) {  
                if(counter == 0){
                    var systems = selected_system[key];
                    
                    for(var s_key in systems) {  
                        var index = values.indexOf(systems[s_key]);
 
                        if (index > -1) {
                           values.splice(index, 1);
                        }
                        console.log("accounting_" + systems[s_key].toLowerCase() + "_id]");
                        $("input[name=accounting_" + systems[s_key].toLowerCase() + "_id]").closest(".form-group").show("slow");
                    }
                }
            }

            setTimeout(function(){
                for(var o_key in values) {  
                    $("input[name=accounting_" + values[o_key].toLowerCase() + "_id]").closest(".form-group").hide("slow");
                }
            }, 200);

         }, 500);
        
    }

    function reset_form(formElement){
         //Internal $.validator is exposed through $(form).validate()
         var validator = $(formElement).validate({
              rules: {
                name: "required",
                email : "required"
              }
        });
         //Iterate through named elements inside of the form, and mark them as error free
         $('[name]',formElement).each(function(){
           validator.successList.push(this);//mark as error free
           validator.showErrors();//remove error messages if present
         });
         validator.resetForm();//remove error class on name elements and clear history
         validator.reset();//remove all error and success data
         formElement.reset();
    }

    function post_member_detail(table_id){
        
        $("#member-application-form").validate({
              rules: {
                name: "required",
                email : "required"
              }
        });

        var form = $("#member-application-form");
        form.validate();

        if (form.valid()){
            init_loading_overlay();
             $.get(getCreateOrUpdateMembershipUrl, { data : form.serialize() }, function(data){
                   console.log('Return call ' + JSON.stringify(data));
                    if(data.status_code == true){   
                        member_detail = data.data['membership_item'];
                        for (var key in member_detail) {   

                            $('#'+key).val(member_detail[key]);               
                        }   

                         $('#add_nem_member_modal').modal('show');
                    }

                
                     append_row_membership_application(table_id,member_detail.id_house_member,member_detail);
                     init_hide_loading_overlay();
                },"json");

           
        }else{
             
        }      
    }

    function init_member_register_modal(member_id){

        $('#add_nem_member_modal').modal('show');
        var form = document.getElementById("member-application-form");
        reset_form(form);
        $('#name').val('');

     
    }

    function create_or_update_member_modal(me) {
        init_loading_overlay();
        $('#id_house_member_app').val(me);
        $.get(getMemberDetailUrl, { id_house_member : me }, function(data){
            if(data.status_code == true){   
                member_detail = data.data['membership_item'];
                for (var key in member_detail) {   
                    //console.log(key + "===" + member_detail[key]);
                    $('#'+key).val(member_detail[key]);    
                    /*if(){

                    }  */               
                }   

                 $('#add_nem_member_modal').modal('show');
            }
             init_hide_loading_overlay();
        },"json");
    }


    function init_transaction_debug_modal(modal_name) 
    {

        var title = modal_name == "transaction_recovery_modal" ?  "Recovery" : "User Checking";
        var operation = modal_name == 'transaction_recovery_modal' ? 'recovery':'checking';
        console.log("Initi modal:" + operation + title);
        $('#utransaction_debug_operation').val(operation);
        $('#utransaction_debug_modal').modal('show');
        $('#utransaction_debug_modal_title').val(title);       
    }

    function get_utransaction_result_by_operation()
    {
        init_loading_overlay();
        check_list = $('#check_list').val();
        console.log(check_list);
        var modal_name = 'transaction_recovery_modal';
        if(modal_name == 'transaction_recovery_modal'){
            console.log("Before retrive :");
             $.get(getRecoverTransactionUrl, { check_list : check_list }, function(data){
                console.log(data);
                if(data.status_code == true){   
                    member_detail = data.data['membership_item'];
                    for (var key in member_detail) {   
                        //console.log(key + "===" + member_detail[key]);
                        $('#'+key).val(member_detail[key]);    
                        /*if(){

                        }  */               
                    }   

           
                }
                 init_hide_loading_overlay();

             },"json");

        }else{

            $.get(getPersonalDetailUrl, { check_list : check_list }, function(data){

                if(data.status_code == true){   
                    member_detail = data.data['membership_item'];
                    for (var key in member_detail) {   
                        //console.log(key + "===" + member_detail[key]);
                        $('#'+key).val(member_detail[key]);    
                        /*if(){

                        }  */               
                    }   

           
                }

                init_hide_loading_overlay();

             },"json");
        }
    }


    
function init_membership_info_by_leaf_product_id(me, type) {
   
    init_loading_overlay();

        $.get(productInfoByLeafProductIdUrl, {product_id:$(me).val()}, function(fdata){     
           var product = fdata.data;
           var leaf_product = product.leaf_product_model;
           console.log(product);
           //$('#lbl_product_name').html(product.description);
           $('#lbl_product_amount').html(product.amount);
           
           $('#summary_total_amount').html(product.amount);
           $('#btn_payment').html('Pay '+product.amount);

           $('#summary_sub_total').html(product.amount);
           $('#summary_total').html(product.amount);

           $('#member_payable_amount').html(product.amount);
           $('#member_sub_total').html(product.amount);
           
           $('#package_pax_number').val(leaf_product.fee_type_user_per_unit);
           $('#package_min_age').val(leaf_product.fee_type_user_min_age);
           $('#package_max_age').val(leaf_product.fee_type_user_max_age);
           
           var tbody = $("#membership_item_table").find("tbody");
           tbody.find("tr").remove();
           for (i = 0; i < leaf_product.fee_type_user_per_unit; i++) { 
              
              remove_row_membership_application(i, 'membership_item_table');
           }
           
    
           init_hide_loading_overlay();
        
        },"json");
}




function init_meter_status_detail_ui()
{
    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
        console.log(i);
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
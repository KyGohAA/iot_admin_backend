$(document).ready(function() {
    url = window.location.href;
});

function init_floating_footer() {

    if ($('#footer').val() == '') {
        screenHeight = Number($(window).height() * 0.030);
      
        $('#footer').scrollToFixed({
            bottom: screenHeight,
            limit: $('#footer').offset().top
        });
    }
}

function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' + label + '<br>' + Math.round(series.percent) + '%</div>';
}

function numeric_input_only(me) {
   
    amount = $(me).val();
    $("#" + $(me).attr('id')).keydown(function(e) {
        total = $("#" + $(me).attr('id')).val();

        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }

         if (amount > 200) {
         
            e.preventDefault();
            $(me).val(200);
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}


function uIElementSwitch(id,state){
    $('#'+id).prop('disabled', state);
}


function checkMaxNumInputAndDisableTarget(id_source,id_target,max){

    $("#" + id_source).keyup(function(e) {
        amount = $('#'+id_source).val();
        if(amount == ""){
             $('#'+id_target).prop('disabled', "true");
             uIElementSwitch(id_target,true);
         }else if( amount <= max){
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

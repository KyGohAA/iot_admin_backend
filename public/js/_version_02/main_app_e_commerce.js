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

}

function updated_selected_product_detail(product_id,status_to_true) 
{    
    init_loading_overlay();
    $.get(getProductDetailUpdate, { id: product_id , status_to_true : status_to_true}, function(fdata) {
          init_hide_loading_overlay();
          set_alert_msg(fdata.status_msg);
          console.log('#' + product_id);
          $('#' + product_id).remove();
    }, "json");

}

function hide_step_by_step_no_shopping(step_no) {
    //$("span[id*=step]").removeClass('done');
    $('#step_' + (step_no)).fadeIn(1000).addClass('hide');
    $('#step_' + (step_no + 1)).fadeIn(1500).removeClass('hide');
}

function hide_step_by_step_no_shopping_back(step_no) {
    //$("span[id*=step]").removeClass('done');
    $('#step_' + (step_no + 1 )).fadeIn(1000).addClass('hide');
    $('#step_' + (step_no)).fadeIn(1500).removeClass('hide');
}


function preview_image()  
{
     var total_file=document.getElementById("upload_file").files.length;
     for(var i=0;i<total_file;i++)
     {
        id = Math.floor((Math.random() * 9999999) + 1);
       // $('#image_preview').append("<img style='heighh:200px; width:200px;' class='col-md-2' src='"+URL.createObjectURL(event.target.files[i])+"'>");
        $('#image_preview').append("<input name='product_photo' type='radio'  id='"+id+"' class='input-hidden'/>   <label class='img-fluid mx-auto d-block img-thumbnail'  for='"+id+"' > <img  class='img-fluid mx-auto d-block' src='"+URL.createObjectURL(event.target.files[i])+"'>  </label>");
        
     }
}

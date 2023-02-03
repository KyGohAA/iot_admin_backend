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
    url = url.substring(0, n != -1 ? n : url.length);
    var sidebar = $(".sidebar-menu").find("li");

    // var sidebar_li = sidebar.children();
    sidebar.each(function(){
        if ($(this).find("ul").length) {
            $(this).find("ul li").each(function(){
                var href = $(this).find("a").attr("href");
                if (href == url) {
                    $(this).addClass("active");
                    $(this).parentsUntil($("ul.sidebar-menu"), ".treeview-menu").show();
                    $(this).parentsUntil($("ul.sidebar-menu"), ".treeview").addClass("menu-open");
                }
            });
        } else {
        	var href = $(this).find("a").attr("href");
            if (href == url) {
                $(this).addClass("active");
            }            
        }
    });
    if ($(".latest-opening").length) {
        $(".latest-opening").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
        });
    }
    if ($(".input-daterange").length) {
        $(".input-daterange").datepicker({
            format: "dd-mm-yyyy",
        });
    }
    $("#export_by_pdf").on("click", function(){
        $(this).closest("form").attr("target", "_parent");
    });
    $("#export_by_html").on("click", function(){
        $(this).closest("form").attr("target", "_blank");
    });
    $("a").each(function(){
        $(this).attr("data-prefetch",true);
    });
    if ($("select").length) {
        init_select2($("select"));
    }
});

function init_loading_overlay() {
    $("#overlay").css("z-index","1000");
    $('#overlay').waitMe({
        //none, rotateplane, stretch, orbit, roundBounce, win8, 
        //win8_linear, ios, facebook, rotation, timer, pulse, 
        //progressBar, bouncePulse or img
        effect: 'bounce',
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

function init_select2(me) {
    if (typeof $(me).select2 !== 'undefined') {
        $(me).select2();
    }
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
        init_hide_loading_overlay();
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


function init_double(me) {
    var double = $(me).val() || 0;
    $(me).val(parseFloat(double).toFixed(decimalPoint));
}

function init_decimal_point(double) {
    return parseFloat(double || 0).toFixed(decimalPoint);
}

function init_billing_summary(me) {
    var table = $("#price_list");
    var sec_table = $("#detail_prices");
    var form_serialize = $(me).closest("form").serialize();
    table.find("tbody>tr").remove()
    $.get(utilityChargeEstimatedUrl, form_serialize, function(data){
        console.log(data);
        var total = 0;
        for (var i in data.table) {
            var tr = "<tr>";
                tr += "<td class='text-center'>"+data.table[i].meter_block+"</td>"
                tr += "<td class='text-center'>"+data.table[i].meter_usage+"</td>"
                tr += "<td class='text-center'>"+init_decimal_point(data.table[i].unit_price)+"</td>"
                tr += "<td class='text-center'>"+init_decimal_point(data.table[i].total)+"</td>"
                tr += "</tr>";
                table.find("tbody").append(tr);
                total += init_decimal_point(data.table[i].total);
        }
        var usage_kwh = sec_table.find(".usage_kwh");
        var usage_kwh_1 = usage_kwh.find("td:eq(1)");
        usage_kwh_1.html(data.meter.gst || 0);
        var usage_kwh_2 = usage_kwh.find("td:eq(2)");
        usage_kwh_2.html(data.meter.without_gst || 0);
        usage_kwh.find("td:eq(3)").html(parseFloat(usage_kwh_1.html()) + parseFloat(usage_kwh_2.html()));

        var usage_rm = sec_table.find(".usage_rm");
        var usage_rm_1 = usage_rm.find("td:eq(1)");
        usage_rm_1.html(init_decimal_point(data.amount.gst));
        var usage_rm_2 = usage_rm.find("td:eq(2)");
        usage_rm_2.html(init_decimal_point(data.amount.without_gst));
        var usage_rm_3 = usage_rm.find("td:eq(3)");
        usage_rm_3.html(init_decimal_point(parseFloat(usage_rm_1.html()) + parseFloat(usage_rm_2.html())));

        var icpt = sec_table.find(".icpt");
        var icpt_charge = icpt.find("td:eq(0)").find(".icpt_charge").html();
        var icpt_1 = icpt.find("td:eq(1)>.text");
        icpt_1.html(init_decimal_point(usage_kwh_1.html() * icpt_charge));
        var icpt_2 = icpt.find("td:eq(2)>.text");
        icpt_2.html(init_decimal_point(usage_kwh_2.html() * icpt_charge));
        icpt.find("td:eq(3)>.text").html(init_decimal_point(parseFloat(icpt_1.html()) + parseFloat(icpt_2.html())));

        var current_month_usage = sec_table.find(".current_month_usage");
        var current_month_usage_1 = current_month_usage.find("td:eq(1)");
        current_month_usage_1.html(init_decimal_point(parseFloat(usage_rm_1.html()) - parseFloat(icpt_1.html())));
        var current_month_usage_2 = current_month_usage.find("td:eq(2)");
        current_month_usage_2.html(init_decimal_point(parseFloat(usage_rm_2.html()) - parseFloat(icpt_2.html())));
        var current_month_usage_3 = current_month_usage.find("td:eq(3)");
        current_month_usage_3.html(init_decimal_point(parseFloat(current_month_usage_1.html()) + parseFloat(current_month_usage_2.html())));
        // var total_gst_amount = $(".total_gst_amount").html(parseFloat(current_month_usage_1.html()));
        
        var gst = sec_table.find(".gst");
        var gst_3 = gst.find("td:eq(3)");
        gst_3.html(init_decimal_point(data.gst_amount));
        
        var kwtbb = sec_table.find(".kwtbb");
        var kwtbb_3 = kwtbb.find("td:eq(3)");
        kwtbb_3.html(init_decimal_point(parseFloat(usage_rm_3.html()) * (1.6/100)));
        
        var late_payment_charge = sec_table.find(".late_payment_charge");
        var late_payment_charge_3 = late_payment_charge.find("td:eq(3)");
        late_payment_charge_3.html(init_decimal_point(0));
        
        var current_charge = sec_table.find(".current_charge");
        current_charge.find("td:eq(3)").html(init_decimal_point(parseFloat($("#over_due_amount").val()) + parseFloat(current_month_usage_3.html()) + parseFloat(gst_3.html()) + parseFloat(kwtbb_3.html()) + parseFloat(late_payment_charge_3.html())));

        table.find("tfoot .total").html(init_decimal_point(total));
    },"json");
}

function set_wishlist_cookie($value) {
    Cookies.set(wishlistLabel, $value);
}

function get_wishlist_cookie() {
    return Cookies.get(wishlistLabel);
}

function remove_wishlist_cookie() {
    Cookies.remove(wishlistLabel);;
}

function set_cart_cookie($value) {
    Cookies.set(cartLabel, $value);
}

function get_cart_cookie() {
    return Cookies.get(cartLabel);
}

function remove_cart_cookie() {
    Cookies.remove(cartLabel);;
}

function init_calculate(me, operator) {
    var segmented = $(me).closest(".segmented");
    var quantity = segmented.find("input[name*=product_quantity]").val();
    if(operator == "plus") {
        var quantity = parseFloat(quantity) + 1;
    } else {
        if(quantity > 0) {
            var quantity = parseFloat(quantity) - 1;
        }
    }
    segmented.find("input[name*=product_quantity]").val(quantity);
    segmented.find(".qty-display").html(quantity);
    var item = segmented.closest(".item");
    var product = item.find("input[name*=product_id]").val();
    var quantity = item.find("input[name*=product_quantity]").val();
    add_to_cart(product, quantity);
    init_cart_total();
}

function init_cart_total() {
    var total = 0;
    $(".card").each(function(){
        $(this).find(".item").each(function(){
            var quantity = $(this).find("input[name*=quantity]").val();
            var price = $(this).find(".price").html();
            total += parseFloat(quantity) * parseFloat(price);
        });
    });
    var block = $(".block");
    block.find(".total").html(init_decimal_point(total));
}

function add_to_wishlist_func() {
    var product = $("#id_vendor_product").val();
    add_to_wishlist(product);
}

function add_to_cart_func() {
    var product = $("#id_vendor_product").val();
    var quantity = parseFloat($("#vendor_product_quantity").val());
    var ProductList = null;
    add_to_cart(product, quantity);
}

function init_cart_check_list(me) {
    var checked = $(me).prop("checked");
    $("input[type=checkbox]").prop("checked", checked);
}

function init_cart_select_all() {
    var checked = true;
    $("input[name*=product_id]").each(function(){
        if(!$(this).prop("checked")) {
            checked=false;
        }
    });
    $("input[name=select_all]").prop("checked",checked);
}

function init_update_cart(me, operator) {
    var item = $(me).closest(".item");
    init_calculate($(me), operator);
}

function init_remove_item(me) {
    app.dialog.confirm(remove_item_message, function () {
        var item = $(me).closest(".item")
        var product = item.find("input[name*=product_id]").val();
        var progress = remove_from_cart(product);
        $.when(progress).done(function(){
            item.remove();
            init_cart_total();
        });
    });
}

function add_to_cart(product, quantity) {
    var ProductList = null;
    if (get_cart_cookie()) {
        var ProductList = JSON.parse(get_cart_cookie());
    }
    if (ProductList != null) {
        var added = false;
        for (var i = 0; i < ProductList.length; i++) {
            if (ProductList[i] != null) {
                if (ProductList[i].id_vendor_product == product) {
                    if (!quantity) {
                        delete(ProductList[i]);
                    } else {
                        ProductList[i].vendor_product_quantity = quantity;
                    }
                    added = true;
                };
            }
        };
        if (added == false && quantity) {
            ProductList.push(
                {"id_vendor_product":product, "vendor_product_quantity":quantity}
                );
        };
    } else {
        if (quantity) {
            var ProductList = new Array(
                {"id_vendor_product":product, "vendor_product_quantity":quantity}
                );
        }
    };
    if (ProductList != null) {
        set_cart_cookie(JSON.stringify(ProductList));
    }
}

function add_to_wishlist(product) {
    var ProductList = null;
    if (get_wishlist_cookie()) {
        var ProductList = JSON.parse(get_wishlist_cookie());
    }
    if (ProductList != null) {
        ProductList.push(product);
    } else {
        var ProductList = new Array(product);
    };
    set_wishlist_cookie(JSON.stringify(ProductList));
}

function remove_from_wishlist(product) {
    var ProductList = null;
    if (get_wishlist_cookie()) {
        var ProductList = JSON.parse(get_wishlist_cookie());
    }
    if (ProductList != null) {
        for (var i = 0; i < ProductList.length; i++) {
            if (ProductList[i] != null) {
                if (ProductList[i] == product) {
                    delete(ProductList[i]);
                };
            }
        };
    }
    if (ProductList != null) {
        set_wishlist_cookie(JSON.stringify(ProductList));
    }
}

function remove_from_cart(product) {
    var ProductList = null;
    if (get_cart_cookie()) {
        var ProductList = JSON.parse(get_cart_cookie());
    }
    if (ProductList != null) {
        for (var i = 0; i < ProductList.length; i++) {
            if (ProductList[i] != null) {
                if (ProductList[i].id_vendor_product == product) {
                    delete(ProductList[i]);
                };
            }
        };
    }
    if (ProductList != null) {
        set_cart_cookie(JSON.stringify(ProductList));
    }
}

function get_quantity_from_cart() {
    var product     =   $("#id_vendor_product").val();
    var label       =   $("#btn-qty-value");
    var quantity    =   $("#vendor_product_quantity");
    var qty_display =   $(".qty-display");
    var ProductList =   null;
    if (get_cart_cookie()) {
        var ProductList = JSON.parse(get_cart_cookie());
    }
    if (ProductList != null) {
        for (var i = 0; i < ProductList.length; i++) {
            if (ProductList[i] != null) {
                if (ProductList[i].id_vendor_product == product) {
                    label.html(ProductList[i].vendor_product_quantity);
                    quantity.val(ProductList[i].vendor_product_quantity);
                    qty_display.html(ProductList[i].vendor_product_quantity);
                };
            }
        };
    }
}

function init_daterange(me) {
    $(me).datepicker({
        format: "dd-mm-yyyy",
    });
}

function init_daterange_leaf_ui(me) {
    $(me).datepicker({
        format: "yyyy-mm-dd",
    });
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
        tr.find("a").remove();
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

function initMap() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: "roadmap"
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    map.setTilt(50);
        
    // Multiple markers location, latitude, and longitude
    var markers = [
        ['Peter Ooi', 40.671531, -73.963588],
        ['Kz Tan', 40.672587, -73.968146],
        ['LinSw', 40.665588, -73.965336]
    ];
                        
    // Info window content
    var infoWindowContent = [
        ['<div class="info_content">' +
        '<h4>Peter Ooi</h4><p>Last Login 31-12-2017</p><a target="_blank" href="https://www.google.com/maps?ll=50.006194,4.805145&z=9&t=m&hl=en-US&gl=US&mapclient=apiv3&cid=17468220328127719589">View on Google Maps</a>' + '</div>'],
        ['<div class="info_content">' +
        '<h4>Kz Tan</h4><p>Last Login 31-12-2017</p><a target="_blank" href="https://www.google.com/maps?ll=50.006194,4.805145&z=9&t=m&hl=en-US&gl=US&mapclient=apiv3&cid=17468220328127719589">View on Google Maps</a>' + '</div>'],
        ['<div class="info_content">' +
        '<h4>LinSw</h4><p>Last Login 31-12-2017</p><a target="_blank" href="https://www.google.com/maps?ll=50.006194,4.805145&z=9&t=m&hl=en-US&gl=US&mapclient=apiv3&cid=17468220328127719589">View on Google Maps</a>' + '</div>']
    ];
        
    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Place each marker on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });
        
        // Add info window to marker    
        google.maps.event.addListener(marker, "click", (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Center the map to fit all markers on the screen
        map.fitBounds(bounds);
    }

    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), "bounds_changed", function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
    
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
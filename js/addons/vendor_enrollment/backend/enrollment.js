//(function () {
$(document).ready(function() {
        $(document).on('click', '.vendor-plans-item', function () {
       // if(parseInt($("#purchase_plan_id").val()) != parseInt($(".cm-vendor-plans-selector-value").val())) {
            if($(this).find('.vendor-plan-price').text() != 'Free') {
                if($(this).attr("data-ca-addon-id")!=undefined) {
                    //
                } else {
                    var amount = $(this).find('.vendor-plan-price').text();
                    var currency = $("#currency").val();
                    var price = parseFloat(amount.replace(currency,""));cprice = parseFloat($("#current_plan_price").val());
                    if(price < cprice) { //when downgrade plan
                        $("#plan_days").val("");
                       var dplan = confirm("The plan you have selected will downgrade your current plan. Please verify your intent to downgrade. This plan will take effect on your next pay period.");
                       if(dplan) {
                            $("#plan_downgrade").val($(this).attr('data-ca-plan-id'));
                            $(".cm-update-company").click();
                            return true;
                       } else {
                            $("#plan_downgrade").val('0');
                            return false;
                       }
                    } else { //when upgrade plan
                        plan_cur_date = parseInt($("#plan_cur_date").val());
                        plan_exp_date = parseInt($("#plan_exp_date").val());
                        plan_days = Math.round((plan_exp_date - plan_cur_date)/86400);
                        $("#plan_days").val(plan_days);
                    }
                    if(parseInt($(".cm-vendor-plans-selector-value").attr("data-ca-default-plan")) == parseInt($(this).attr('data-ca-plan-id'))) {
                       return false;
                    }
                    $(".purchase_plan").text("Submit Payment");
                    //$(".change-card").click();
                    $(".cm-vendor-plans-selector").hide();
                    $("#ccdetails").removeClass();
                    $("#plan_downgrade").val(0);
                    
                    $("#amount").val(price);
                    //var tot=parseFloat($("#ttotal").text());
                    var plan_id = $(".cm-vendor-plans-selector-value").val();
                    var plan_name = $(this).find('.vendor-plan-header').text();
                    $("#content_upgrade").removeClass("hidden");
                    var del_prorate = 0;
                    html="<tr addonid="+plan_id+" amount="+price+"><td width='60%'></td><td>"+plan_name+"</td><td class='right'>"+currency+price+"</td><td class='right'><a href='#' onclick='javascript:delCart(this,"+plan_id+","+del_prorate+");'><i class='icon-trash'></i></a></td></tr>";
                    $("#ttotal").text(price);
                    $('#cart').html(html);
                    $("#tblcart tfoot").removeClass("hide");
                    purchaseAddon();
                }
            }
       // }
        });
        $(".cm-update-company").click(function(){
            $(".cm-vendor-plans-selector-value").val($("#curr_plan_id").val());
        });
        $("#plan").click(function() {
            $(".cm-vendor-plans-selector").show();
            $("#ccdetails").addClass("hidden");
            $("#content_addons_details").hide();
        });
        $(".purchase_plan").click(function() {
            $("#purchase_plan").val(1);
            $("#card_action").val('');
            if($("#card_number").val() == '' && $("#card_pay").val() != 1) {
                alert("Please enter card number");
                $("#card_number").focus();
                return false;
            }
            if($("#cvv2").val() == '' && $("#card_pay").val() != 1) {
                alert("Please enter cvv number");
                $("#cvv2").focus();
                return false;
            }
            localStorage.setItem("tblcart", $("#tblcart").html());
            localStorage.setItem("total", $("#total").val());
            localStorage.setItem("aamount", $("#aamount").val());
            localStorage.setItem("amount", $("#amount").val());
            localStorage.setItem("addon_id", $("#addon_id").val());
            localStorage.setItem("ptype", $("#ptype").val());
            localStorage.setItem("plan_id", $(".cm-vendor-plans-selector-value").val());
        });
        $("#chooseplan").click(function() {
            $(".cm-vendor-plans-selector").show();
            $("#ccdetails").addClass("hidden");
            $("#ptype").val("plan");
        });
        $("#upgrade").click(function() {
            $(".cm-vendor-plans-selector").show();
            $("#ccdetails").addClass("hidden");
            $("#tblcart").show();
            $("#ptype").val("addons");
            $("#content_addons_details").hide();
            $(".cm-vendor-plans-selector-value").val($("#curr_plan_id").val());
            $("#ttotal").text(0);
            $('#cart').html('');
            $("#tblcart tfoot").addClass("hide");
        });
        $(".buynow").click(function() {
            //$("#buy_now").val("1");
            //var amount = $(this).attr('price');
            var price =$(this).attr('price');//parseFloat(amount.replace("$",""));
            var name = $(this).attr('name');
            var id =$(this).attr("id");
            var tot=parseFloat($("#ttotal").text());
            var purchase=false;
            if($("#purchase_addon"+id).val()=="1") {
                purchase=true;
                alert("Addons already been purchase");
                return false;
               //$(this).attr("disabled","disabled");
            }
            $(".purchase_addon").show();
            if($(this).attr("details")==undefined) {
                $("#content_addons_details").hide();
            } 
            //else if($(this).attr("details") == 1) {
            var flag=false;
            $('#cart tr').each(function(index,tr) {
                if($(tr).attr("addonid") == id) {
                    flag=true;
                    return false;
                }
            }); if(flag) { alert("Add-ons already added into cart");return false; }
            //}
            
            if($(this).attr("disabled")!='disabled') {
                if($(this).attr("details")==undefined) {
                    //$(this).attr("disabled","disabled");
                }
                var amt = parseFloat(price).toFixed(2);
                var html=$('#cart').html();
                var currency = $("#currency").val();
                var prorate = parseFloat($("#prorate_charge"+id).val());
                prorate = Math.round(prorate * 10) / 10;
                var onetime_fees = 0;
                var frequency = $("#frequency"+id).val();
                if(frequency == 'One time') {
                    onetime_fees = 25;
                }
                var del_prorate=0;
                //if(prorate!=undefined) {del_prorate=1;}
                if(html!="") {
                    if(onetime_fees > 0) {
                         tot = tot + parseFloat(onetime_fees);
                         html=html+"<tr addonid="+id+" amount="+onetime_fees+"><td width='60%'></td><td>One time fees</td><td class='right'>"+currency+onetime_fees+"</td><td class='right'></td></tr>"
                    } else if(!purchase && prorate!=undefined && prorate > 0) {
                        // edate = parseInt($("#exp_date"+id).val());
                        // cdate = parseInt($("#cur_date"+id).val());
                        // days = Math.round((edate - cdate)/86400);
                        // prorate = prorate * days;
                        prorate = prorate.toFixed(2);
                        tot = parseFloat(tot) + parseFloat(prorate);
                        html=html+"<tr addonid="+id+" amount="+prorate+"><td width='60%'></td><td>"+name+" ("+currency+amt+" "+frequency+") Prorated Cost</td><td class='right'>"+currency+prorate+"</td><td class='right'><a href='#' onclick='javascript:delCart(this,"+id+","+del_prorate+");'><i class='icon-trash'></i></a></td></tr>";
                    } else {
                        tot = tot + parseFloat(amt);
                        html=html+"<tr addonid="+id+" amount="+amt+"><td width='60%'></td><td>"+name+"</td><td class='right'>"+currency+amt+"</td><td class='right'><a href='#' onclick='javascript:delCart(this,"+id+","+del_prorate+");'><i class='icon-trash'></i></a></td></tr>";
                    }
                } else {
                    if(onetime_fees > 0) {
                        tot = parseFloat(tot) + parseFloat(onetime_fees);
                        html=html+"<tr addonid="+id+" amount="+onetime_fees+"><td width='60%'></td><td>One time fees</td><td class='right'>"+currency+onetime_fees+"</td><td class='right'></td></tr>";
                    } else if(!purchase && prorate!=undefined && prorate > 0) {
                        // edate = parseInt($("#exp_date"+id).val());
                        // cdate = parseInt($("#cur_date"+id).val());
                        // days = Math.round((edate - cdate)/86400);
                        // prorate = prorate * days;
                        prorate = prorate.toFixed(2);
                        tot = parseFloat(tot) + prorate;
                        html=html+"<tr addonid="+id+" amount="+prorate+"><td width='60%'></td><td>"+name+" ("+currency+amt+" "+frequency+") Prorated Cost</td><td class='right'>"+currency+prorate+"</td><td class='right'><a href='#' onclick='javascript:delCart(this,"+id+","+del_prorate+");'><i class='icon-trash'></i></a></td></tr>";
                    } else {
                        tot = tot + parseFloat(amt);
                        html="<tr addonid="+id+" amount="+amt+"><td width='60%'></td><td>"+name+"</td><td class='right'>"+currency+amt+"</td><td class='right'><a href='#' onclick='javascript:delCart(this,"+id+","+del_prorate+");'><i class='icon-trash'></i></a></td></tr>";
                    }
                    //$('#cart').html(html);
                }
                $('#cart').html(html);
                tot = parseFloat(tot).toFixed(2);
                $("#total").val(tot);
                $("#ttotal").text(tot);
                $("#tblcart tfoot").removeClass("hide");
            }
        });
      
        $(".cancel").click(function() {
            //var warning = 'Pick a new plan or cancel your membership';
            var id =$(this).attr("id");
            var type =$(this).attr("type");
            if(type == 'plan') {
                if(confirm("Press Ok to Pick a new plan or click on Cancel to cancel your membership")) {
                    $("#chooseplan").click();
                    return false;
                } else {
                    if(confirm("Are you sure you would like to cancel your Plan and all the Add Ons associated with your Plan. This will close your store and remove all products the site. Press Ok to cancel plan or click on cancel to Stay enrolled")) {
                        //$("#cancel_plan").click();
                        //alert("Plan cancel successfully");
                        return true;
                    } else {
                        return false;
                        //console.log('enroll plan');
                    }
                }
            } else if(type == 'addons') {
                if(confirm("Are you sure you want to cancel this addon")) {
                    return true;
                } else {
                    return false;
                }
            }
        });
        $("#current_plan_addons").click(function(){
            $("#ccdetails").addClass("hidden");
            $(".cm-vendor-plans-selector-value").val($("#curr_plan_id").val());
        });
        $(".watch-video").click(function() {
            $("#details_img").addClass('hidden');
            $("#details_video").removeClass('hidden');
            $("#pvideo").play();
            $(".close-video").removeClass('hidden');
        });
        $(".close-video").click(function() {
            $("#details_img").removeClass('hidden');
            $("#details_video").addClass('hidden');
            $(".close-video").addClass('hidden');
            $("#pvideo").pause();
        });
       // $('.add-card').click(function() {
            //var id = $(this).attr('id');
            //console.log($("#selcard"+id));
            //$(this).find('[name="selcard"').click();
            //$("#selcard"+id).click();
            // if(id == "add_card" && $("#card_pay").val() == 1) {
            //     if($(".paneladd_card").attr("style").toString() == "display: none;") {
            //         $(".purchase_plan").show();
            //     } else {
            //         $(".purchase_plan").hide();
            //     }
            //   //  $('.panel'+id).toggle();

            // }
            // if(id == "add_card") {
            //     $('.panel'+id).toggle();
            // } else {
            //     $('.panel'+id).toggle();
            // }
        //});
        $("#add_card").click(function(){
            var id = $(this).attr('id');
            $('.panel'+id).toggle();
            if($("#card_pay").val() == 1) {
                if($(".paneladd_card").attr("style").toString() == "display: none;") {
                    $(".purchase_plan").show();
                } else {
                    $(".purchase_plan").hide();
                }
              //  $('.panel'+id).toggle();
            }
        });
        $('.change-card-btn').click(function() {
            //$('.change-card').hide();
            $('.list-card').toggle();
            $('.edit-card').hide();
            //$('#add_card').show();
        });
        $('.edit-btn').click(function() {
            var id = $(this).attr('id');
            //$('#payment_method').val($("#payment_method"+id).text());
            $('#card_name').val($(".name"+id).text());
            var exp_date = $(".expiry"+id).text().split("/");
            $('#exp_month').val(exp_date[0]);
            $('#exp_year').val(exp_date[1]);
            $("#e_address").val($(".address"+id).text());
            $("#e_city").val($(".city"+id).text());
            $("#e_country").val($(".country"+id).text());
            $('#e_country').find('option:selected').change();
            $("#e_state").val($(".state"+id).text());
            $("#e_zip").val($(".zip"+id).text());
            $("#c_type").val($("#card_type"+id).val());
            //$("#card_number_val").val($("#c_number"+id).val());
            $("#cust_id").val($("#customer_id"+id).val());
            $("#card_id").val(id);
            $(".edit-card").insertAfter("#other-added-card"+id);
            $(".edit-card").show();
        });
        $('.add-card-btn').click(function() {
            $("#card_action").val("Add");
            if($("#card_number").val() == '') {
                alert("Please enter card number");
                $("#card_number").focus();
                return false;
            }
            if($("#cvv2").val() == '') {
                alert("Please enter cvv number");
                $("#cvv2").focus();
                return false;
            }
            setPageValue();
        });
        $('.save-btn').click(function() {
            $("#card_action").val("Edit");
            if($("#payment_method").val() == '') {
                alert("Please enter card number");
                $("#payment_method").focus();
                return false;
            }
            if($("#e_cvv2").val() == '') {
                alert("Please enter cvv number");
                $("#e_cvv2").focus();
                return false;
            }
            setPageValue();
        });
        $('.remove-btn').click(function() {
            if(confirm('Are you sure you want to remove this payment method')) {
                $("#card_action").val("Remove");
                $("#card_id").val($(this).attr("id"));
                setPageValue();
            } else {
                return false;
            }
        });
        $('.default_method').click(function() {
            if(confirm("Are you sure you want to set this card as default")) {
                $("#card_action").val("Default");
                $("#card_id").val($(this).attr("id"));
                setPageValue();
            } else {
                return false;
            }
        });
        $('.cancel-btn').click(function() {
            $('.edit-card').hide();
            $("#card_action").val("Cancel");
            // $("#card_id").val($(this).attr("id"));
        });
//})();

    if($("#payment_page").val() == "1") {
        setTimeout(function() {
            if($("#selected_section").val()!='') {
                $("#upgrade").click();
                $("#content_upgrade .cm-vendor-plans-selector").hide();
                $("#content_addons_details").hide();
                $("#ccdetails").removeClass();
                $("#tblcart").html(localStorage.getItem("tblcart"));
                $("#total").val(localStorage.getItem("total"));
                $("#aamount").val(localStorage.getItem("aamount"));
                $("#addon_id").val(localStorage.getItem("addon_id"));
                $("#amount").val(localStorage.getItem("amount"));
                $("#ptype").val(localStorage.getItem("ptype"));
                $(".cm-vendor-plans-selector-value").val(localStorage.getItem("plan_id"));
                localStorage.setItem("tblcart", '');
                localStorage.setItem("total", '');
                localStorage.setItem("aamount", '');
                localStorage.setItem("addon_id", '');
                localStorage.setItem("amount", '');
                localStorage.setItem("ptype", '');
                localStorage.setItem("plan_id", '');
                if($(".change-card")!=undefined) {
                    $(".change-card-btn").click();
                }
            }
        }, 0);
        window.history.pushState({}, document.title, "/" + "vendor.php?dispatch=companies.update&company_id="+$("input[name=company_id]").val());
    }
    if($("#cancel").val() =="1") {
        setTimeout(function() {  
            $("#current_plan_addons").click();
        },0);
        window.history.pushState({}, document.title, "/" + "vendor.php?dispatch=companies.update&company_id="+$("input[name=company_id]").val());
    }
});
function setPageValue() {
    localStorage.setItem("tblcart", $("#tblcart").html());
    localStorage.setItem("total", $("#total").val());
    localStorage.setItem("aamount", $("#aamount").val());
    localStorage.setItem("addon_id", $("#addon_id").val());
    localStorage.setItem("amount", $("#amount").val());
    localStorage.setItem("ptype",$("#ptype").val());
    localStorage.setItem("plan_id",$(".cm-vendor-plans-selector-value").val());
}
function gotoAddon() {
    //$('.goto-addons').addClass("disabled");
    //$(".purchase_addon").removeClass("disabled")
    $("#goto_addons").addClass("active");
    $("#proceedcheckout").removeClass("active");
    
    $("#content_upgrade ul.cm-vendor-plans-selector").show();
    $("#ccdetails").addClass("hidden");
    $("#tblcart").show();
    $("#content_addons_details").hide();
    $(".cm-vendor-plans-selector-value").val($("#curr_plan_id").val());
    if($("#ptype").val() == "plan") {
        $("#ttotal").text(0);
        $('#cart').html('');
        $("#tblcart tfoot").addClass("hide");
        $("#upgrade").click();
    }
    $("#ptype").val("addons");
}
function addon_item(obj) {
    $("#content_upgrade .cm-vendor-plans-selector").hide();
    $("#content_addons_details").show();
    $("#details_img").attr("src",$(obj).find(".vendor-plan-header img").attr("src"));
    $("#details_name").text($(obj).find(".vendor-plan-header b").text());
    $("#details_desc").text($(obj).find("#long_desc").attr("value"));
    var price_period = $(obj).find(".vendor-plan-price-period").text();
    $("#details_price").text($(obj).find(".vendor-plan-price").text() + price_period);

    var video_url = $(obj).find("#product_video").val();
    if(video_url!='') {
        var video_data = '<video width="300" height="250" controls id="pvideo"><source src="/videos/addons/'+video_url+'"></video>';
        $("#details_video").html(video_data);
    } else {
        $("#details_video").html('');
    }
    var price = $(obj).find(".vendor-plan-price").text().trim().substr(1);
    $("#buynowbtn .buynow").attr("id",$(obj).attr("data-ca-addon-id"));
    $("#buynowbtn .buynow").attr("name",$(obj).find(".vendor-plan-header b").text());
    $("#buynowbtn .buynow").attr("price",price);
    $(".cm-vendor-addons-selector-value").val($(obj).attr("data-ca-addon-id"));
};
function purchaseAddon() {
    //$(".cm-vendor-plans-selector").hide();
    $("#proceedcheckout").addClass("active");
    $("#goto_addons").removeClass("active");
    $("#content_upgrade .cm-vendor-plans-selector").hide();
    //$(".change-card").click();
   // $("#tblcart").hide();
   $(".change-card").show();
   $(".list-card").hide();
    $("#ccdetails").removeClass();
    $("#content_addons_details").hide();
    $(".purchase_plan").text("Submit Payment");
     var ctotal=0;var addonid='';var aamount='';
     $("#cart").find('tr').each(function() {
         addonid = addonid+","+$(this).attr("addonid");
         aamount = aamount+","+$(this).attr("amount");
         ctotal = ctotal + parseFloat($(this).attr("amount"));
     });
     addonid = addonid.substr(1);
     aamount = aamount.substr(1);
     $("#aamount").val(aamount);
     $("#addon_id").val(addonid);
     $("#total").val(ctotal.toFixed(2));
     $("#ttotal").text(ctotal.toFixed(2));
     //$(".purchase_addon").addClass("disabled");
     //$(".goto-addons").removeClass("disabled");
   //  $("#purchase_addon").val(1);
}
function delCart(del,aid,del_prorate) {
    if(del_prorate == 1) {
        $(del).closest("tr").next().remove();
        //$(del).closest("tr").next().remove();
    }
    $(del).closest("tr").remove();
    $("#"+aid).removeAttr("disabled");
    //$(".cm-vendor-plans-selector").hide();
    if($("#payment_page").val() == "1") {
        $("#content_upgrade .cm-vendor-plans-selector").hide();
        $("#ccdetails").removeClass();
        $(".purchase_plan").text("Submit Payment");
        var ctotal=0;var addonid='';var aamount='';
        $("#cart").find('tr').each(function() {
            addonid = addonid+","+$(this).attr("addonid");
            aamount = aamount+","+$(this).attr("amount");
            ctotal = ctotal + parseFloat($(this).attr("amount"));
        });
        addonid = addonid.substr(1);
        aamount = aamount.substr(1);
        $("#aamount").val(aamount);
        $("#addon_id").val(addonid);
        $("#total").val(ctotal.toFixed(2));
        $("#ttotal").text(ctotal.toFixed(2));
    } else {
        purchaseAddon();
        //$(".purchase_addon").click();
    }
    if($("#tblcart tbody tr").length == 0) {
        $("#ttotal").text(0);
        $("#aamount").val('');
        $("#total").val('');
        $("#addon_id").val('');
        $(".purchase_addon").hide();
       // $("#content_upgrade .cm-vendor-plans-selector").show();
       // $("#ccdetails").addClass('hide');
    }
} 
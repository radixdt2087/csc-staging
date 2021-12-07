<div class="control-group {$no_hide_input_if_shared_product}">
    <label for="elm_price_price" class="control-label cm-required">{__("price")}({$currencies.$primary_currency.symbol nofilter}):</label>
    <div class="controls">
        <input type="text" name="product_data[price]" id="elm_price_price" size="10" value="{$product_data.price|default:"0.00"|fn_format_price:$primary_currency:null:false}" class="input-long cm-numeric" data-a-sep/>
        {include file="buttons/update_for_all.tpl" display=$show_update_for_all object_id="price" name="update_all_vendors[price]"}
        </div>
    </div>
</div>
    <div class="control-group">
            <label class="control-label" for="elm_override_commission">Override Sitewide Fee Settings: </label>
            <div class="controls">
                <input type="checkbox" name="product_data[override_commission]" id="elm_override_commission" value="yes" {if ($product_data.override_commission)== 'yes'} checked {/if} />
            </div>
    </div>

    <div class="control-group" id="pro_commission" style="display: none;">
        <div class="control-label" for="elm_pro_commission">WeSave Transaction Fee:</div>
        <div class="controls">
            <input type="hidden" name="vendor_commission" id="vendor_commission" value="{$vendor_commission.commission}">
            <input type="hidden" name="vendor_commission_fee" id="vendor_commission_fee" value="{$vendor_commission.fixed_commission}">
            <input type="text" name="product_data[product_commission]" id="elm_product_commission" onblur="javascript:if(this.value<{floatval($vendor_commission.commission)})alert('The product level WeSave Transaction Fee cannot be less than the site wide minimum WeSave Transaction Fee.  Please increase the amount of these fees or set them to your minimum rates.');" size="4" {if ($product_data.product_commission) != ""} value="{$product_data.product_commission|default:'$vendor_commission.commission'}" {else} value="{floatval($vendor_commission.commission)}" {/if} class="input-mini"> <span class="control-text">&nbsp;%&nbsp;+&nbsp;</span>
            <span class="control-text">{$currencies.$primary_currency.symbol nofilter} </span><input type="text" name="product_data[product_commission_fee]" id="elm_product_commission_fee"  {if ($product_data.product_commission_fee) != ""} value="{$product_data.product_commission_fee|default:'$vendor_commission.fixed_commission'|fn_format_price:$primary_currency:null:false}" {else} value="{$vendor_commission.fixed_commission}" {/if} class="input-mini" size="4" onblur="javascript:if(this.value<{$vendor_commission.fixed_commission})alert('Product commision fee cannot be less then Vendors commission fee.');"> 
            <p>Note : Your min Transaction fees are {floatval($vendor_commission.commission)}% and ${$vendor_commission.fixed_commission}</p>
         </div>
         
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        var commission = document.getElementById("vendor_commission").value;
        var commissionFee = document.getElementById("vendor_commission_fee").value;

        $('#elm_override_commission').change(function(){
            if(this.checked){
                $('#pro_commission').fadeIn('slow')
            }
            else{
                $('#pro_commission').fadeOut('slow');   
                $("#elm_override_commission").val('no');
            } 
        });
        $("#elm_override_commission").attr("checked") ? $('#pro_commission').fadeIn('slow') : $('#pro_commission').fadeOut('slow');

       // $("#elm_override_commission").attr("checked") ? $("#elm_override_commission").val('yes') : $("#elm_override_commission").val('no');
    });



</script>

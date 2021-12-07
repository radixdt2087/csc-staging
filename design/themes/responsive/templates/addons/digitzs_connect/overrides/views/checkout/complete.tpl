{assign var="profile_fields" value=$location|fn_get_profile_fields}
{$contact_fields = $profile_fields.C}
<div class="container-fluid order-confirmation">
    <div class="row-fluid ty-checkout-complete__order-success">
        <div class="span14 offset1">
            <div><h3 class="ty-mainbox-title">Order Placed, Thanks! </h3></div>
            <div class="confirm-msg"><p>Confirmation will be sent to your email.</p></div>
            <div class="confirm-info">
                <p>Click on an order number below for more details</p>
            {assign var=orderid value=$order_info.order_id}
            {if $order_info.child_ids}
                {assign var=orderid value=","|explode:$order_info.child_ids|@array_reverse}
            {/if}
                <table class="order-details-table">
                <tr class="table-header"><th>Order #</th><th>Total</th><th>Status</th></tr>
                {foreach from=$orderid item=oid}
                    {$orderInfo = $oid|fn_get_order_info}
                    <tr class="table-data"><td><a href="?dispatch=orders.details&order_id={$oid}">{$oid}</a></td><td>{include file="common/price.tpl" value="{$orderInfo.total}"}</td><td class="{include file="common/status.tpl" status=$orderInfo.status display="view"|lower}">{include file="common/status.tpl" status=$orderInfo.status display="view" }</td></tr>
                {/foreach}
            </table>
        </div>
        </div>
   </div>
{if $order_info && $settings.Checkout.allow_create_account_after_order == "Y" && !$auth.user_id}
<div class="ty-checkout-complete__create-account">
    <h3 class="ty-subheader">{__("create_account")}</h3>
    <div class="ty-login">
        <form name="order_register_form" action="{""|fn_url}" method="post">
            <input type="hidden" name="order_id" value="{$order_info.order_id}" />

            <div class="ty-control-group">
                <label for="password1" class="ty-control-group__label ty-login__filed-label cm-required cm-password">{__("password")}</label>
                <input type="password" id="password1" name="user_data[password1]" size="32" maxlength="32" value="" class="cm-autocomplete-off ty-login__input cm-focus" />
            </div>

            <div class="ty-control-group">
                <label for="password2" class="ty-control-group__label ty-login__filed-label cm-required cm-password">{__("confirm_password")}</label>
                <input type="password" id="password2" name="user_data[password2]" size="32" maxlength="32" value="" class="cm-autocomplete-off ty-login__input" />
            </div>

            <div class="buttons-container clearfix">
                <p>{include file="buttons/button.tpl" but_name="dispatch[checkout.create_profile]" but_text=__("create")}</p>
            </div>
        </form>
        </div>
    </div>
    <div class="ty-checkout-complete__login-info">
        {hook name="checkout:payment_instruction"}
            {if $order_info.payment_method.instructions}
                <div class="ty-login-info">
                    <h4 class="ty-subheader">{__("payment_instructions")}</h4>
                    <div class="ty-wysiwyg-content">
                        {$order_info.payment_method.instructions nofilter}
                    </div>
                </div>
            {/if}
        {/hook}
    </div>
{else}
    <div class="ty-checkout-complete__login-info ty-checkout-complete_width_full">
        {hook name="checkout:payment_instruction"}
            {if $order_info.payment_method.instructions}
                <h4 class="ty-subheader">{__("payment_instructions")}</h4>
                <div class="ty-wysiwyg-content">
                    <br>
                    {$order_info.payment_method.instructions nofilter}
                </div>
            {/if}
        {/hook}
    </div>
{/if}

    {* place any code you wish to display on this page right after the order has been placed *}
    {hook name="checkout:order_confirmation"}
    {/hook}

    <div class="ty-checkout-complete__buttons buttons-container {if !$order_info || !$settings.Checkout.allow_create_account_after_order == "Y" || $auth.user_id} ty-mt-s{/if}">
        {hook name="checkout:complete_button"}
            <div class="ty-checkout-complete__buttons-left">
                {if $order_info}
                    {if $order_info.child_ids}
                        {include file="buttons/button.tpl" but_meta="ty-btn__secondary" but_text=__("order_details") but_href="orders.search?period=A&order_id=`$order_info.child_ids`"}
                    {else}
                        {include file="buttons/button.tpl" but_text=__("order_details") but_meta="ty-btn__secondary" but_href="orders.details?order_id=`$order_info.order_id`"}                        
                    {/if}
                {/if}
                &nbsp;{include file="buttons/button.tpl" but_meta="ty-btn__secondary" but_text=__("view_orders") but_href="orders.search"} 
                &nbsp; {include file="buttons/continue_shopping.tpl" but_role="text" but_meta="ty-checkout-complete__button-vmid" but_href=$continue_url|fn_url}
            </div>
            {* <div class="ty-checkout-complete__buttons-right">
                {include file="buttons/continue_shopping.tpl" but_role="text" but_meta="ty-checkout-complete__button-vmid" but_href=$continue_url|fn_url}
            </div> *}
        {/hook}
    </div>
</div>

<div class="container-fluid span16  recently-viewed-product-section">
    <div class="ty-mainbox-simple-container clearfix recently-viewed-products span14 offset1">
    <h2 class="ty-mainbox-simple-title">Recently Viewed Product</h2>
        <div class="recently-viewed-products-details">
        {include 
        file="blocks/list_templates/grid_list.tpl"
        products=$recent_product_items 
        no_sorting="Y" 
        no_pagination=true
        show_name=true
        show_sku=false
        show_rating=false
        show_features=false
        show_prod_descr=false
        show_old_price=false
        show_price=true
        show_clean_price=false
        show_list_discount=false
        show_product_labels=false
        product_labels_mini=false
        show_discount_label=false
        show_shipping_label=false
        show_product_amount=true
        show_product_options=false
        show_qty=false
        show_min_qty=false
        show_product_edp=false
        show_add_to_cart=false
        show_list_buttons=false
        show_descr=false
        but_role="action"
        item_number=1
        show_product_labels=false
        show_discount_label=false
        show_shipping_label=false }
        </div>
    </div>
</div>
{* 
<div><h3>Top pics for you</h3></div>
{include file="blocks/list_templates/products_list.tpl" 
products=$popular_items 
no_sorting="Y" 
no_pagination=true
show_name=true 
show_sku=true 
show_rating=true 
show_features=true 
show_prod_descr=true 
show_old_price=true 
show_price=true 
show_clean_price=true 
show_list_discount=true 
show_product_labels=true
product_labels_mini=true
show_discount_label=true 
show_shipping_label=true
show_product_amount=true 
show_product_options=$_show_product_options 
show_qty=true 
show_min_qty=true 
show_product_edp=true 
show_add_to_cart=$_show_add_to_cart 
show_list_buttons=true 
show_descr=true 
but_role="action" 
item_number=$block.properties.item_number
show_product_labels=true
show_discount_label=true
show_shipping_label=true} *}
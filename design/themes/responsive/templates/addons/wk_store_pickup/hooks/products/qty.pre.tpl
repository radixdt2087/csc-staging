
{capture name="wk_store_pickup_point`$obj_prefix``$obj_id`"}
<div class="ty-product-block__note-wrapper cm-reload-{$obj_prefix}{$obj_id}" id="wk_select_store_pickup_container_{$obj_prefix}{$obj_id}">
    {if $product.enable_store_pickup == 'Y' && ($product.pickup_stores || !$details_page)}
        <div class="ty-product-block__note1 ty-product-block__note-inner">
            <label class="ty-control-group__label {if $product.store_pickup_only == 'Y'}cm-required{/if}" for="wk_store_pickup_store_id_{$obj_prefix}{$obj_id}" >{__("wk_store_pickup_point")}:</label>
            {$select_pickup_btn_text=__("wk_select_store_pickup_point")}
            {$but_meta="ty-btn ty-btn__primary"}
            {if $smarty.session.wk_store_pickup.$obj_id}
                <span class="wk_show_store_info"><a class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="wk_single_store_info{$obj_id}">{Fn_Get_Store_Pickup_name($smarty.session.wk_store_pickup.$obj_id)}<a/>  
                <a href="{"wk_store_pickup.remove_store&product_id=`$obj_id`"|fn_url}" class="cm-post" >&nbsp;<i title="{__("wk_remove_product_from_local_pickup")}" class="ty-icon-cancel-circle"></i></a>
                </span>
                {$select_pickup_btn_text=__("wk_change_store_pickup_point")}
                {$but_meta="ty-btn ty-btn__secondary"}
            {/if}
            <input type="hidden" name="product_data[{$obj_id}][wk_store_id]"  id="wk_store_pickup_store_id_{$obj_prefix}{$obj_id}" value="{$smarty.session.wk_store_pickup.$obj_id}">
             <a href="{"wk_store_pickup.search&product_id=`$product.product_id`"|fn_url}" class="{$but_meta}">{$select_pickup_btn_text}</a>
            {* {include file="buttons/button.tpl" but_text=$select_pickup_btn_text but_href="wk_store_pickup.search&product_id=`$product.product_id`" but_role="" but_meta=$but_meta} *}
            <p class="wk_local_pickup_info wk_store_pickup_only_product">{__("local_pickup_service_available_for_this_product")}{if $product.store_pickup_only == 'Y'}&nbsp;&nbsp;{__("wk_product_is_available_only_for_local_pickup")}{/if}</p>
        </div>
    {/if}
<!--wk_select_store_pickup_container_{$obj_prefix}{$obj_id}--></div>
{/capture}
{if !(!$show_product_options && $product.selected_options) && $cart_button_exists}
    {assign var="wk_store_pickup_point" value="wk_store_pickup_point`$obj_prefix``$obj_id`"}
    {$smarty.capture.$wk_store_pickup_point nofilter}
{/if}
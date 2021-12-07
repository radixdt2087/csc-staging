{assign var="wk_pickup_points" value=$product.extra.wk_store_pickup_points}
{if $wk_pickup_points && $product.extra.enabled_store_pickup}
    {assign var="store_id" value=$product.extra.wk_store_id|default:$product.wk_store_id}
    <div class="select_store">
        <label class="ty-control-group__label {if $product.extra.wk_store_pickup_only}cm-required{/if}" for="wk_select_pickup_point_store_id{$key}"><strong  >{__("wk_store_pickup_point")}:</strong></label>
        {assign var="unavailable_store_title" value=""}
        {assign var="is_store_available" value=true}
        {$wk_but_text=__("wk_view_store_pickup_point")}
        {$wk_but_meta="ty-btn ty-btn__primary"}
        <span class="ty-control-group__item">
            {$store_info=$wk_pickup_points.$store_id}
            {if $store_id && $store_info}
                {if $store_info.stock<$product.amount}
                    {assign var="unavailable_store_title" value=$store_info.title}
                    {assign var="is_store_available" value=false}
                    {__("wk_select_store_pickup_point")}
                    {assign var="store_id" value=''}
                {else}
                    {$wk_but_text=__("wk_change_store_pickup_point")}
                    {$wk_but_meta="ty-btn ty-btn__tertiary"}
                    {$store_info.title}&nbsp;<a href="{"wk_store_pickup.remove_store&cart_id=`$key`&is_cart"|fn_url}" class="cm-post" >&nbsp;<i title="{__("wk_remove_product_from_local_pickup")}" class="ty-icon-cancel-circle"></i></a>
                {/if}
            {else}
                {__("wk_select_store_pickup_point")}
            {/if}
            <input type="hidden" name="cart_products[{$key}][wk_store_id]" value="{$store_id|default:0}" id="wk_select_pickup_point_store_id{$key}" >
        </span>
        <span>
            <a href="{"wk_store_pickup.search&product_id=`$product.product_id`&checkout=N&cart_id=`$key`"|fn_url}" class="{$wk_but_meta}">{$wk_but_text}</a>
        </span>
        {if $product.extra.wk_store_pickup_only}
            <p class="wk_store_pickup_only_product">{__("wk_product_is_available_only_for_local_pickup")}</p>
        {/if}
        {if !$is_store_available}<p >{__("wk_selected_do_not_have_enough_stock_to_place_order",['[store]'=>$unavailable_store_title,'[product]'=>$product.product])}</p>{/if}
    </div>
{/if}
{hook name="checkout:shipping_rates"}
{assign var="is_store_product" value=false}
{foreach from=$cart.products item="product" key="_key" }
    {if $product.extra.enabled_store_pickup && $product.extra.wk_store_pickup_points}
        {assign var="is_store_product" value=true}
        {break}
    {/if}
{/foreach}
{if $is_store_product}
    <div class="wk_store_pickup_products">
        <div class="header_container">
        <h3 class="ty-shipping-options__wk_store_pickup_title">{__("wk_store_pickup_shipment_title")}</h3>
        <p class="ty-shipping-options__wk_store_pickup_info">{__("wk_store_pickup_shipment_customer_info")}</p>
        </div>
        <div class="ty-responsive-table-wrapper">
            <table class="ty-table ty-responsive-table ty-cart-content">
                <thead>
                    <tr>
                        <th>{__("product")}</th>
                        <th>{__("wk_store_pickup_points")}</th>
                    </tr>
                </thead>
              
                {foreach from=$cart.products item="product" key="_key"}
                    {if $product.extra.enabled_store_pickup && $product.extra.wk_store_pickup_points}
                        {assign var="store_id" value=$product.extra.wk_store_id}
                        {assign var="unavailable_store_title" value=""}
                        {assign var="is_store_available" value=true}
                        {$wk_but_text=__("wk_select_store_pickup_point")}
                        {$wk_but_meta="ty-btn ty-btn__primary"}
                        <tr class="">
                            <td data-th="{__("product")}" class="ty-cart-content__product-elem">
                                <a href="{"products.view&product_id=`$product.product_id`"|fn_url}" class="ty-order-products__a">
                                {if $product.product}
                                    {$product.product nofilter}
                                {else}
                                    {$product.product_id|fn_get_product_name}
                                {/if}
                                </a>
                                {include file="common/options_info.tpl" product_options=$cart_products.$_key.product_options no_block=true}
                            </td>
                            <td data-th="{__("wk_store_pickup_products")}" class="ty-cart-content__product-elem">
                                <div class="select_store">
                                    <label class="ty-control-label {if $product.extra.wk_store_pickup_only}cm-required{/if} hidden" for="wk_select_pickup_point{$_key}">{__("wk_store_pickup_point")}</label>
                                    <span class="ty-control-group__item">
                                        {if $store_id}
                                            {$wk_but_text=__("wk_change_store_pickup_point")}
                                            {$store_info=$product.extra.wk_store_pickup_points.$store_id}
                                            {if $store_info.stock<$product.amount}
                                                {assign var="unavailable_store_title" value=$store_info.title|default:Fn_Get_Store_Pickup_name($store_id)}
                                                {assign var="is_store_available" value=false}
                                                {assign var="store_id" value=''}
                                                {$wk_but_meta="ty-btn ty-btn__secondary"}
                                            {else}
                                                {$wk_but_meta="ty-btn ty-btn__tertiary"}
                                                {$store_info.title|default:Fn_Get_Store_Pickup_name($store_id)}&nbsp;<a href="{"wk_store_pickup.remove_store&cart_id=`$_key`"|fn_url}" class="cm-post" >&nbsp;<i title="{__("wk_remove_product_from_local_pickup")}" class="ty-icon-cancel-circle"></i></a>
                                            {/if}
                                        {/if}
                                    </span>
                                    <span>
                                        <a href="{"wk_store_pickup.search&product_id=`$product.product_id`&checkout=Y&cart_id=`$_key`"|fn_url}" class="{$wk_but_meta}">{$wk_but_text}</a>
                                    </span>
                                    <input type="hidden" name="cart_products[{$_key}][wk_store_id]" value="{$store_id}" id="wk_select_pickup_point{$_key}"/>
                                    {if $product.extra.wk_store_pickup_only}
                                    <p class="wk_store_pickup_only_product">{__("wk_product_is_available_only_for_local_pickup")}</p>
                                    {/if}
                                    {if !$is_store_available}<p >{__("wk_selected_do_not_have_enough_stock_to_place_order",['[store]'=>$unavailable_store_title,'[product]'=>$product.product])}</p>{/if}
                                </div>
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            </table>
        </div>
    </div>
{/if}
{if $product_groups}
    {if version_compare('4.9.3', $smarty.const.PRODUCT_VERSION) >= 0 || $addons.step_by_step_checkout.status == 'A'}
        <div id="shipping_rates_list">
            {foreach from=$product_groups key="group_key" item=group name="spg"}
                {$pickup_products_count=0}
                {foreach from=$group.products item="product" key="__key"}
                    {$store_id = $product.extra.wk_store_id}
                    {$store_info=$product.extra.wk_store_pickup_points.$store_id}
                    {if ($store_id && $store_info.stock > $product.amount) || $product.extra.wk_store_pickup_only}
                        {$pickup_products_count=$pickup_products_count+1}
                    {/if}
                {/foreach}
                {if count($group.products) == $pickup_products_count}
                    {continue}
                {/if}
                {* Group name *}
                {if !"ULTIMATE"|fn_allowed_for || $product_groups|count > 1}
                    <span class="ty-shipping-options__vendor-name">{$group.name}</span>
                {/if}

                {* Products list *}
                {if !"ULTIMATE"|fn_allowed_for || $product_groups|count > 1}
                    <ul class="ty-shipping-options__products">
                        
                        {foreach from=$group.products item="product" key="__key"}
                            {if !(($product.is_edp == 'Y' && $product.edp_shipping != 'Y') || $product.free_shipping == 'Y') && !$product.extra.wk_store_id && $product.shipping_no_required !== 'Y'}
                                <li class="ty-shipping-options__products-item">
                                    {if $product.product}
                                        {$product.product nofilter}
                                    {else}
                                        {$product.product_id|fn_get_product_name}
                                    {/if}
                                </li>
                            {/if}
                        {/foreach}
                    </ul>
                {/if}

                {* Shippings list *}
                {if $group.shippings && !$group.all_edp_free_shipping && !$group.shipping_no_required}

                    {foreach from=$group.shippings item="shipping"}

                        {if $cart.chosen_shipping.$group_key == $shipping.shipping_id}
                            {assign var="checked" value="checked=\"checked\""}
                            {assign var="strong_begin" value="<strong>"}
                            {assign var="strong_end" value="</strong>"}
                        {else}
                            {assign var="checked" value=""}
                            {assign var="strong_begin" value=""}
                            {assign var="strong_end" value=""}
                        {/if}

                        {if $shipping.delivery_time || $shipping.service_delivery_time}
                            {assign var="delivery_time" value="(`$shipping.service_delivery_time|default:$shipping.delivery_time`)"}
                        {else}
                            {assign var="delivery_time" value=""}
                        {/if}

                        {if $shipping.rate}
                            {capture assign="rate"}{include file="common/price.tpl" value=$shipping.rate}{/capture}
                            {if $shipping.inc_tax}
                                {assign var="rate" value="`$rate` ("}
                                {if $shipping.taxed_price && $shipping.taxed_price != $shipping.rate}
                                    {capture assign="tax"}{include file="common/price.tpl" value=$shipping.taxed_price class="ty-nowrap"}{/capture}
                                    {assign var="rate" value="`$rate``$tax` "}
                                {/if}
                                {assign var="inc_tax_lang" value=__('inc_tax')}
                                {assign var="rate" value="`$rate``$inc_tax_lang`)"}
                            {/if}
                        {elseif fn_is_lang_var_exists("free_shipping")}
                            {assign var="rate" value=__("free_shipping") }
                        {else}
                            {assign var="rate" value="" }
                        {/if}

                        {hook name="checkout:shipping_method"}
                            <div class="ty-shipping-options__method">
                                <input type="radio" class="ty-valign ty-shipping-options__checkbox" id="sh_{$group_key}_{$shipping.shipping_id}" name="shipping_ids[{$group_key}]" value="{$shipping.shipping_id}" onclick="fn_calculate_total_shipping_cost();" {$checked} />
                                <div class="ty-shipping-options__group">
                                    <label for="sh_{$group_key}_{$shipping.shipping_id}" class="ty-valign ty-shipping-options__title">
                                        <bdi>
                                            {if $shipping.image}
                                                <div class="ty-shipping-options__image">
                                                    {include file="common/image.tpl" obj_id=$shipping_id images=$shipping.image class="ty-shipping-options__image"}
                                                </div>
                                            {/if}

                                            {$shipping.shipping} {$delivery_time}
                                            {if $rate} {$rate nofilter}{/if}
                                    </bdi>
                                    </label>
                                </div>
                            </div>
                            {if $shipping.description}
                                <div class="ty-checkout__shipping-tips">
                                    <p>{$shipping.description nofilter}</p>
                                </div>
                            {/if}
                        {/hook}
                    {/foreach}

                    {if $smarty.foreach.spg.last && !$group.all_edp_free_shipping && !$group.shipping_no_required}
                        <p class="ty-shipping-options__total">{__("total")}:&nbsp;{include file="common/price.tpl" value=$cart.display_shipping_cost class="ty-price"}</p>
                    {/if}

                {else}
                    {if $group.all_free_shipping}
                        <p>{__("free_shipping")}</p>
                    {elseif $group.all_edp_free_shipping || $group.shipping_no_required }
                        <p>{__("no_shipping_required")}</p>
                    {else}
                        <p class="ty-error-text">
                            {__("text_no_shipping_methods")}
                        </p>
                    {/if}
                {/if}

            {foreachelse}
                <p>
                    {if !$cart.shipping_required}
                        {__("no_shipping_required")}
                    {elseif $cart.free_shipping}
                        {__("free_shipping")}
                    {/if}
                </p>
            {/foreach}
        <!--shipping_rates_list--></div>
    {else}
        <input type="hidden"
           name="additional_result_ids[]"
                value="litecheckout_final_section,litecheckout_step_payment,checkout*"
            />
            {foreach $product_groups as $group_key => $group}
                {$pickup_products_count=0}
                {foreach from=$group.products item="_product" key="__key"}
                    {$product = $cart.products.$__key}
                    {$store_id = $product.extra.wk_store_id}
                    {$store_info=$product.extra.wk_store_pickup_points.$store_id}
                    {if ($store_id && $store_info.stock >= $product.amount) || $product.extra.wk_store_pickup_only}
                        {$pickup_products_count=$pickup_products_count+1}
                    {/if}
                {/foreach}
                {if count($group.products) == $pickup_products_count}
                    {continue}
                {/if}
                {if $product_groups|count > 1}
                    <div class="litecheckout__group">
                        <div class="litecheckout__item">
                            <h2 class="litecheckout__step-title">
                                {__("lite_checkout.shipping_method_for", ["[group_name]" => $group.name])}
                            </h2>
                        </div>
                    </div>
                {/if}
                <div class="litecheckout__group">
                    {* Shippings list *}
                    {if $group.shippings && !$group.all_edp_free_shipping && !$group.shipping_no_required}
                        {foreach $group.shippings as $shipping}
                            {hook name="checkout:shipping_rate"}
                                {$delivery_time = ""}
                                {if $shipping.delivery_time || $shipping.service_delivery_time}
                                    {$delivery_time = "(`$shipping.service_delivery_time|default:$shipping.delivery_time`)"}
                                {/if}
                                {if $shipping.rate}
                                    {capture assign="rate"}{include file="common/price.tpl" value=$shipping.rate}{/capture}
                                    {if $shipping.inc_tax}
                                        {$rate = "`$rate` ("}
                                        {if $shipping.taxed_price && $shipping.taxed_price != $shipping.rate}
                                            {capture assign="tax"}{include file="common/price.tpl" value=$shipping.taxed_price class="ty-nowrap"}{/capture}
                                            {$rate = "`$rate``$tax` "}
                                        {/if}
                                        {$inc_tax_lang = __('inc_tax')}
                                        {$rate = "`$rate``$inc_tax_lang`)"}
                                    {/if}
                                {elseif fn_is_lang_var_exists("free")}
                                    {$rate = __("free")}
                                {else}
                                    {$rate = ""}
                                {/if}
                            {/hook}
                            <div class="litecheckout__shipping-method litecheckout__field litecheckout__field--xsmall">
                                <input
                                    type="radio"
                                    class="litecheckout__shipping-method__radio hidden"
                                    id="sh_{$group_key}_{$shipping.shipping_id}"
                                    name="shipping_ids[{$group_key}]"
                                    value="{$shipping.shipping_id}"
                                    onclick="fn_calculate_total_shipping_cost(); $.ceLiteCheckout('toggleAddress', {if $shipping.is_address_required == "Y"}true{else}false{/if});"
                                    data-ca-lite-checkout-element="shipping-method"
                                    data-ca-lite-checkout-is-address-required="{if $shipping.is_address_required == "Y"}true{else}false{/if}"
                                    {if $cart.chosen_shipping.$group_key == $shipping.shipping_id}checked{/if}
                                />

                                <label
                                    for="sh_{$group_key}_{$shipping.shipping_id}"
                                    class="litecheckout__shipping-method__wrapper js-litecheckout-activate"
                                    data-ca-activate="sd_{$group_key}_{$shipping.shipping_id}"
                                >
                                    {if $shipping.image}
                                        <div class="litecheckout__shipping-method__logo">
                                            {include file="common/image.tpl" obj_id=$shipping_id images=$shipping.image class="shipping-method__logo-image litecheckout__shipping-method__logo-image"}
                                        </div>
                                    {/if}
                                    <p class="litecheckout__shipping-method__title">{$shipping.shipping} — {$rate nofilter}</p>
                                    <p class="litecheckout__shipping-method__delivery-time">{$delivery_time}</p>
                                </label>
                            </div>
                        {/foreach}
                    {else}
                        <p class="litecheckout__shipping-method__text">
                            {if $group.all_free_shipping}
                                {__("free_shipping")}
                            {elseif $group.all_edp_free_shipping || $group.shipping_no_required }
                                {__("no_shipping_required")}
                            {else}
                                <span class="ty-error-text">
                                    {__("text_no_shipping_methods")}
                                </span>
                            {/if}
                        </p>
                    {/if}
                </div>

                <div class="litecheckout__group">
                    {foreach $group.shippings as $shipping}
                        {hook name="checkout:shipping_method"}
                        {/hook}
                    {/foreach}
                    <div class="litecheckout__item">
                        {foreach $group.shippings as $shipping}
                            {if $cart.chosen_shipping.$group_key == $shipping.shipping_id}
                                <div class="litecheckout__shipping-method__description">
                                    {$shipping.description nofilter}
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                </div>
            {/foreach}
    {/if}
{/if}
{/hook}
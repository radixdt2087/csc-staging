<div class="wk_back_button_container ty-right">
    <h2 class="ty-left">{__("wk_store_pickup_points_of_product",["[product]"=>fn_get_product_name($product_id)])}</h2>
    {include file="buttons/button.tpl" but_role="link" but_meta="ty-btn ty-btn__secondary " but_text=$wk_return_text but_icon="ty-icon-close icon-close" but_href=$wk_return_url}
</div>
<div class="store_pickup_location_container" id="content_wk_store_pickup_points1">
    <div class="pickup-points-container">
        <div class="search-tool-container">
        {$checkout = $smarty.request.checkout|default:$store_pickup_point_search.checkout}
        {$cart_id=$smarty.request.cart_id|default:$store_pickup_point_search.cart_id}
        {if $checkout=='Y' && $cart_id}
            {$cart_stock = $smarty.session.cart.products.$cart_id.amount}
            {$extra_url="&checkout=Y&cart_id=`$cart_id`"}
        {elseif $checkout=='N' && $cart_id}
            {$cart_stock = $smarty.session.cart.products.$cart_id.amount}
            {$extra_url="&checkout=N&cart_id=`$cart_id`"}
        {else}
            {$extra_url=''}
        {/if}
            <h3>{__("wk_search_store_pickup_points")}</h3>
            <form action="{""|fn_url}" method="get" name="store_pickup_point_form" class="">
                <div class="ty-control-group">
                    <input type="hidden" {if $checkout}name="checkout"{/if} value="{$checkout}"/>
                    <input type="hidden" {if $cart_id}name="cart_id"{/if} value="{$cart_id}"/>
                    <input type="hidden" name="wk_customer_lat" id="wk_customer_lat" value="{$smarty.session.wk_customer_lat}">
                    <input type="hidden" name="wk_customer_lng" id="wk_customer_lng" value="{$smarty.session.wk_customer_lng}">
                    <input type="hidden" name="product_id" value="{$product_id|default:$smarty.request.product_id}"/>
                    <div class="ty-control-group ty-input-append">
                        <span class="wk-select-your-location" title="{__("wk_use_my_current_location")}"><i class="wk-icon-pointer"></i></span>
                        <input type="text" size="20" class="ty-input-text" id="store_pickup_point_search" value="{$store_pickup_point_search.wk_address}" name="wk_address" placeholder="{__("wk_store_your_address")}"/>
                       
                    </div>
                    <div class="ty-control-group ty-input-append">
                        <label class="ty-control__label">{__("wk_store_search_range")}:</label>
                        <input type="range" size="5" name="wk_radius" placeholder="{__("wk_search_radius")}" class="cm-value-decimal" value="{$smarty.session.wk_radius|default:$addons.wk_store_pickup.wk_search_range}" min="0" max="{$addons.wk_store_pickup.wk_search_range_max|default:100}" onchange="Tygh.$('#wk_range_text').text($(this).val());" style="vertical-align:middle;"/>
                        <span id="wk_range_text">{$smarty.session.wk_radius|default:$addons.wk_store_pickup.wk_search_range}</span>
                        <select name="wk_range_unit">
                            <option value="km" {if $smarty.session.wk_range_unit == 'km' || (!$smarty.session.wk_range_unit && $addons.wk_store_pickup.wk_search_range_unit == 'km')}selected{/if}>{__("wk_unit_km")}</option>
                            <option value="miles" {if $smarty.session.wk_range_unit == 'miles' || (!$smarty.session.wk_range_unit && $addons.wk_store_pickup.wk_search_range_unit == 'miles')}selected{/if}>{__("wk_unit_miles")}</option>
                        </select>
                    </div>
                    <div class="ty-control-group ty-input-append">
                        <input type="text" size="20" class="ty-input-text" id="store_pickup_point_q_search" value="{$store_pickup_point_search.q}" name="q" placeholder="{__("wk_search_pickup_points_here")}"/>
                    </div>
                    <div class="ty-center ty-input-append">
                        {include file="buttons/button.tpl" but_name="dispatch[wk_store_pickup.search]" but_role="submit-link" but_text=__("search") but_meta="wk_store_pickup_point_searchbtn"}
                    </div>
                </div>
            </form>
        </div>
        
        {$config.tweaks.disable_dhtml = true}
        {* {include file="common/pagination.tpl" force_ajax=false id="wk_pagination_content"} *}
        <div class="stores-container">
            {foreach from=$store_pickup_points item=pickup_point key="store_id"}
                <div class="pickup-point-item {if $selected_store_id == $store_id}selected_store{/if}">
                    <h2 class="pickup-point-item__title">{$pickup_point.title}<span class="ty-float-right wk_store_distance_duration">{if $pickup_point.distance_found}{$pickup_point.distance} , {$pickup_point.duration}{/if}</span></h2>
                    <p class="pickup-point-item__address">
                    {if $pickup_point.address}{$pickup_point.address},{/if}
                    {if $pickup_point.city}{$pickup_point.city},{/if} 
                    {if $pickup_point.state}{$pickup_point.state},{/if}
                    {if $pickup_point.pincode}{$pickup_point.pincode},{/if}
                    {$pickup_point.country_title}</p>
                    <p class="pickup-point-item__phone"><strong>{__("phone")}:&nbsp;</strong><span><i class="ty-icon-phone"></i></span>{$pickup_point.phone}</p>
                    <div>
                        <strong>{__("wk_more_about_store")}:</strong>
                        <a onclick="" class="wk_show_hide_description" >{__("wk_show_description")}</a>
                        <div class="wk_store_description hidden ty-hidden si">   
                            {$pickup_point.description nofilter}
                        </div>
                    </div>
                    {$but_text=__("wk_select_store")}
                    {$but_meta="ty-btn__primary"}
                    {if $selected_store_id == $store_id}
                        {$but_text=__("wk_selected_store")}
                        {$but_meta="ty-btn__tertiary"}
                    {/if}
                    {if $addons.wk_store_pickup.show_store_inventory == 'Y'}<p><strong>{__("wk_available_stock")}:&nbsp;</strong>{$pickup_point.stock}</p>{/if}
                    <p class="select_pickup_btn ty-center">
                    {if !$cart_id ||($cart_stock && $cart_stock <= $pickup_point.stock)}
                        {include file="buttons/button.tpl" but_text=$but_text but_href="wk_store_pickup.select_store&product_id=`$product_id`&store_id=`$store_id``$extra_url`" but_role="link" but_meta="`$but_meta` cm-post"}
                    {/if}
                    <a href="" class="ty-btn ty-btn__secondary cm-wsp-map-view-location" data-ca-latitude="{$pickup_point.latitude}" data-ca-longitude="{$pickup_point.longitude}" data-ca-store-id="{$store_id}">{__("wk_view_on_map")}</a>
                    <a href="https://www.google.com/maps?saddr={if $smarty.session.wk_customer_lat && $smarty.session.wk_customer_lng}{$smarty.session.wk_customer_lat|cat:','|cat:$smarty.session.wk_customer_lng}{else}My+Location{/if}&daddr={$pickup_point.latitude},{$pickup_point.longitude}" class="ty-btn ty-btn__primary" target="_blank" title="{__("wk_get_route")}"><i class="ty-icon-direction"></i>{__("wk_get_route")}</a>
                    </p>
                </div>
            {/foreach}
            {if !$store_pickup_points}
                <div class="no-pickup-point-item" >
                    <p>{__("wk_no_store_pickup_points_found_change_search_criteria_to_show_pickup_points")}</p>
                </div>
            {/if}
        </div>
        {* {include file="common/pagination.tpl" force_ajax=false} *}
    </div>
    <div class="map-container" >
        {assign var="map_container" value="wk_sp_map_canvas"}
        <div class="ty-store-pickup__map-wrapper" id="{$map_container}"></div>
        {include file="addons/wk_store_pickup/views/wk_store_pickup/components/maps/google.tpl"}
    </div>
<!--content_wk_store_pickup_points1--></div>


<div class="wk_selected_store_pickup_point_info">
    {if !$wk_store_info}
        {$wk_store_info=Fn_Get_Store_Pickup_point($store_id)}
        {$wk_store_info=fn_get_single_store_distance_from_customer($wk_store_info)}
    {/if}
    <div class="hidden" id="wk_single_store_info{$content_id}" title="{__("wk_selected_store_info_title")}">
        <div class="pickup-point-item">
            <h2 class="pickup-point-item__title">{$wk_store_info.title}
            {if $wk_store_info.distance_found}<span class="ty-float-right wk_store_distance_duration">{$wk_store_info.distance} , {$wk_store_info.duration}</span>{/if}
            </h2>
            <p class="pickup-point-item__address">{if $wk_store_info.address}{$wk_store_info.address},{/if}{if $wk_store_info.city} {$wk_store_info.city},{/if} {if $wk_store_info.state}{$wk_store_info.state},{/if} {if $wk_store_info.pincode} {$wk_store_info.pincode},{/if} {$wk_store_info.country_title}</p>
            <p class="pickup-point-item__phone"><b>{__("phone")}:&nbsp;</b><span><i class="ty-icon-phone"></i></span>{$wk_store_info.phone}</p>
        </div>
        <div class="ty-wysiwyg">
            <p><b>{__("wk_more_about_store")}:</b></p>
            {$wk_store_info.description nofilter}
        </div>
        <div class="ty-center" style="margin:10px;">
            <a href="https://www.google.com/maps?saddr={if $wk_store_info.customer_lat && $wk_store_info.customer_lng}{$wk_store_info.customer_lat|cat:','|cat:$wk_store_info.customer_lng}{else}My+Location{/if}&daddr={$wk_store_info.latitude},{$wk_store_info.longitude}" class="ty-btn ty-btn__primary" target="_blank" title="{__("wk_get_route")}"><i class="ty-icon-direction"></i>{__("wk_get_route")}</a>
        </div>
    <!--wk_single_store_info{$content_id}--></div>
</div>
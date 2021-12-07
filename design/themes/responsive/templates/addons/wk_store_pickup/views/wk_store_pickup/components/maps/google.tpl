{script src="/js/addons/wk_store_pickup/google.js"}
<script type="text/javascript">
    (function(_, $) {
        _.wk_your_location = "{__("wk_you_are_here_at_map")}";
        _.is_store_pickup_page = true;
        _.wk_show_description = "{__("wk_show_description")}";
        _.wk_hide_description = "{__("wk_hide_description")}";
        {if $smarty.session.wk_customer_lat}
            var latitude = {$smarty.session.wk_customer_lat|doubleval},
            longitude = {$smarty.session.wk_customer_lng|doubleval};
        {else}
            var latitude = false,
            longitude = false;
        {/if}

        var options = {
            'latitude': latitude,
            'longitude': longitude,
            'map_container': '{$map_container}',
            'api_key':{if !empty($addons.wk_store_pickup.google_api_key)} '{$addons.wk_store_pickup.google_api_key|trim}' {else} false {/if},
            'language': '{$smarty.const.CART_LANGUAGE|Fn_Store_Pickup_Google_langs}',
            'storeData': [
            {foreach from=$store_pickup_points item="loc" name="st_loc_foreach" key="key"}
            {
                'store_id' : '{$loc.store_id}',
                'country' :  '{$loc.country|escape:javascript nofilter}',
                'latitude' : {$loc.latitude|doubleval},
                'longitude' : {$loc.longitude|doubleval},
                'name' :  '{$loc.title|escape:javascript nofilter}',
                'city' : '{$loc.city|escape:javascript nofilter}',
                'pincode': '{$loc.pincode|escape:javascript nofilter}',
                'country_title' : '{$loc.country_title|escape:javascript nofilter}'
            }
            {if !$smarty.foreach.st_loc_foreach.last},{/if}
            {/foreach}            
            ],
        };
       
        $.ceEvent('on', 'ce.commoninit', function(context) {
            if (context.find('#' + options.map_container).length) {
                $.ceWspMap('show', options);
            }
        });
    }(Tygh, Tygh.$));
</script>

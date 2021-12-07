{script src="/js/addons/wk_store_pickup/google.js"}
<script type="text/javascript">
    (function(_, $) {
        var latitude = {$smarty.const.WK_STORE_PICKUP_DEFAULT_LATITUDE|doubleval},
        longitude = {$smarty.const.WK_STORE_PICKUP_DEFAULT_LONGITUDE|doubleval};
        var options = {
            'latitude': latitude,
            'longitude': longitude,
            'map_container': '{$map_container}',
            'api_key':{if !empty($addons.wk_store_pickup.google_api_key)} '{$addons.wk_store_pickup.google_api_key|trim}' {else} false {/if},
            'language': '{$smarty.const.CART_LANGUAGE|Fn_Store_Pickup_Google_langs}',
            'storeData': [],
        };
        $.ceEvent('on', 'ce.commoninit', function(context) {
            if (context.find('#' + options.map_container).length) {
                $.ceWspMap('show', options);
            }
        });
    }(Tygh, Tygh.$));
</script>

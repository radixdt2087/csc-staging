<script>
$(document).ready(function(){
    $('#addon_option_wk_store_pickup_wk_search_range').addClass('cm-value-decimal');
    $('#addon_option_wk_store_pickup_wk_search_range_max').addClass('cm-value-decimal');
    $('label[for="addon_option_wk_store_pickup_wk_search_range"]').addClass('cm-required');
    $('label[for="addon_option_wk_store_pickup_wk_search_range_max"]').addClass('cm-required');
    $.ceEvent('on', 'ce.ajaxdone', function(){
        $('#addon_option_wk_store_pickup_wk_search_range').addClass('cm-value-decimal');
        $('#addon_option_wk_store_pickup_wk_search_range_max').addClass('cm-value-decimal');
        $('label[for="addon_option_wk_store_pickup_wk_search_range"]').addClass('cm-required');
        $('label[for="addon_option_wk_store_pickup_wk_search_range_max"]').addClass('cm-required');
    });
});
</script>
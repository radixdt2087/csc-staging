{capture name="track_order"}
{if $active_template == 'template_two'}
    {include file="addons/order_tracking_page/hooks/orders/components/template_two.tpl"}
{/if}
{/capture}
{$smarty.capture.track_order nofilter}




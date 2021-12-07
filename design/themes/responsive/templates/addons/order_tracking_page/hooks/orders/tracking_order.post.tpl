{if $active_template == 'template_one'}
    {include file="addons/order_tracking_page/hooks/orders/components/template_one.tpl"}
{elseif $active_template == 'template_three'}
    {include file="addons/order_tracking_page/hooks/orders/components/template_three.tpl"}
{elseif $active_template == 'template_four'}
    {include file="addons/order_tracking_page/hooks/orders/components/template_four.tpl"}
{/if}

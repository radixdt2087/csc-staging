{capture name="mainbox"}

{capture name="sidebar"}
    {include file="addons/sd_amazon_products/views/amazon_orders/components/amazon_orders_search_form.tpl"}
{/capture}

<form action="{""|fn_url}" method="post" target="_self" name="manage_amazon_orders_form">

{if $orders}
    <div class="table-responsive-wrapper">
        <table width="100%" class="table table-middle table-responsive">
            <thead>
                <tr>
                    <th  class="left">{include file="common/check_items.tpl"}</th>
                    <th>{__("sd_amz_amazon_order_id")}</th>
                    <th>{__("sd_amz_order_id")}</th>
                    <th>{__("sd_amz_purchase_date")}</th>
                    <th>{__("sd_amz_buyer_name")}</th>
                    <th>{__("payment_method")}</th>
                    <th>{__("order_total")}</th>
                    <th>{__("status")}</th>
                    <th></th>
                </tr>
            </thead>
            {foreach from=$orders item="order"}
                <tr>
                    <td data-th="{__("action")}">
                        <input type="checkbox" name="amazon_order_ids[]" value="{$order.AmazonOrderId}" class="checkbox cm-item" {if !$order.ready_for_import && !$order.update_status}disabled="disabled"{/if}/>
                    </td>
                    <td data-th="{__("sd_amz_amazon_order_id")}">{$order.AmazonOrderId}</td>
                    <td data-th="{__("sd_amz_order_id")}">
                        {if $order.cart_order_id}<a href="{"orders.details?order_id=`$order.cart_order_id`"|fn_url}">{/if}
                            {$order.cart_order_id}
                        {if $order.cart_order_id}</a>{/if}
                    </td>
                    <td data-th="{__("sd_amz_purchase_date")}">{$order.purchase_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"|default:"n/a"}</td>
                    <td data-th="{__("sd_amz_buyer_name")}">
                        {if $order.user_id}<a href="{"profiles.update?user_id=`$order.user_id`"|fn_url}">{/if}
                            {$order.BuyerName}
                        {if $order.user_id}</a>{/if}
                    </td>
                    <td data-th="{__("payment_method")}">
                        <span class="{if !$order.payment_id}red{/if}">{$order.PaymentMethod}</span>
                        {if !$order.payment_id && $order.PaymentMethod}{include file="common/tooltip.tpl" tooltip=__("sd_amz_default_payment_not_found")}{/if}
                    </td>
                    <td data-th="{__("order_total")}">{$order.OrderTotal.Amount}&nbsp;{$order.OrderTotal.CurrencyCode}</td>
                    <td data-th="{__("status")}">{$order.OrderStatus}</td>
                    <td>
                        {capture name="tools_list"}
                            {if $order.ready_for_import}
                                <li>{btn type="list" text=__("import") class="cm-confirm" href="amazon_orders.import?amazon_order_ids=`$order.AmazonOrderId`" method="POST"}</li>
                            {elseif $order.update_status}
                                <li>{btn type="list" text=__("update_status") class="cm-confirm" href="amazon_orders.import?amazon_order_ids=`$order.AmazonOrderId`" method="POST"}</li>
                            {/if}
                        {/capture}
                        {dropdown content=$smarty.capture.tools_list}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $orders}
            <li>{btn type="list" text=__("import") dispatch="dispatch[amazon_orders.import]" form="manage_amazon_orders_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{include file="common/mainbox.tpl" title=__("sd_amz_order_list") content=$smarty.capture.mainbox sidebar=$smarty.capture.sidebar sidebar_position="right" buttons=$smarty.capture.buttons content_id="manage_amazon_orders"}

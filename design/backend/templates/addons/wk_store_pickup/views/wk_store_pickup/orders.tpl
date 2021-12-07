{** wk_store_pickup_orders section **}
{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="store_pickup_orders_form" class="" enctype="multipart/form-data">
<input type="hidden" name="fake" value="1" />

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents_orders"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}
{include file="common/pagination.tpl" div_id="pagination_contents_orders" save_current_page=true save_current_url=true}
{if $wk_store_pickup_orders}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
    <thead>
    <tr>
        <th width="5%"><a class="cm-ajax" href="{"`$c_url`&sort_by=order_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("order")}{if $search.sort_by == "order_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=title&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_store_name")}{if $search.sort_by == "title"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="30%">
            {__("product")}
        </th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=timestamp&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("date")}{if $search.sort_by == "timestamp"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=customer&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("customer")}{if $search.sort_by == "customer"}{$c_icon nofilter}{/if}</a></th>
        <th width="10%" class="center"><a class="cm-ajax" href="{"`$c_url`&sort_by=amount&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("quantity")}{if $search.sort_by == "amount"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    </tr>
    </thead>
    {foreach from=$wk_store_pickup_orders item=wk_store_pickup_order}
    <tr class="1cm-row-status-{$wk_store_pickup_order.status|default:'A'|lower}">
        {assign var="allow_save" value=$wk_store_pickup_order|fn_allow_save_object:"wk_store_pickup_orders"}

        {if $allow_save}
            {assign var="no_hide_input" value="cm-no-hide-input"}
        {else}
            {assign var="no_hide_input" value=""}
        {/if}

        <td class="{$no_hide_input}" data-th="{__("order_id")}">
            <a href="{'orders.details&order_id='|cat:$wk_store_pickup_order.order_id|fn_url}">#{$wk_store_pickup_order.order_id}</a>
        </td>
        <td class="{$no_hide_input}" data-th="{__("wk_store_name")}">
            {if $wk_store_pickup_order.s_company_id == $wk_store_pickup_order.company_id || !$runtime.company_id}
            <a href="{'wk_store_pickup.update&store_id='|cat:$wk_store_pickup_order.store_id|fn_url}">
            {/if}
            {$wk_store_pickup_order.title}
            {if $wk_store_pickup_order.s_company_id == $wk_store_pickup_order.company_id || !$runtime.company_id}
            </a>
            {/if}
            {include file="views/companies/components/company_name.tpl" object=$wk_store_pickup_order}
        </td>
        <td class="{$no_hide_input}" data-th="{__("product")}">
            <a href="{"products.update&product_id=`$wk_store_pickup_order.product_id`"|fn_url}">{$wk_store_pickup_order.product}</a>
            {if $wk_store_pickup_order.product_options}<div class="options-info">{include file="common/options_info.tpl" product_options=$wk_store_pickup_order.product_options}</div>{/if}
        </td>
        <td class="{$no_hide_input}" data-th="{__("date")}">{$wk_store_pickup_order.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
         <td data-th="{__("customer")}">
            {if $wk_store_pickup_order.email}<a href="mailto:{$wk_store_pickup_order.email|escape:url}">@</a> {/if}
            {if $wk_store_pickup_order.user_id}<a href="{"profiles.update?user_id=`$wk_store_pickup_order.user_id`"|fn_url}">{/if}{$wk_store_pickup_order.firstname} {$wk_store_pickup_order.lastname} {if $wk_store_pickup_order.user_id}</a>{/if}
        </td>
        
        <td class="{$no_hide_input} center" data-th="{__("quantity")}">
            {$wk_store_pickup_order.amount|default:0}
        </td>
        <td class="{$no_hide_input}" data-th="{__("status")}">
            <select name="wk_orders_data[{$wk_store_pickup_order.id}][status]" class="input-hidden">
                <option value="H" {if $wk_store_pickup_order.status == 'H'}selected{/if}>{__("wk_on_hold")}</option>
                <option value="P" {if $wk_store_pickup_order.status == 'P'}selected{/if}>{__("wk_picked_up")}</option>
            </select>
        </td> 
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id="pagination_contents_orders"}
{capture name="buttons"}
    {if $wk_store_pickup_orders}
        {include file="buttons/save.tpl" but_name="dispatch[wk_store_pickup.m_update_orders]" but_role="action" but_target_form="store_pickup_orders_form" but_meta="cm-submit"}
    {/if}
{/capture}
</form>
{/capture}

{capture name="sidebar"}
    {hook name="wk_store_pickup_orders:manage_sidebar"}
        {include file="common/saved_search.tpl" dispatch="wk_store_pickup.orders" view_type="wk_store_pickup"}
        {include file="addons/wk_store_pickup/views/wk_store_pickup/components/store_orders_search_form.tpl" dispatch="wk_store_pickup.orders"}
    {/hook}
{/capture}

{include file="common/mainbox.tpl" title=__("wk_store_pickup_orders") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons select_languages=true sidebar=$smarty.capture.sidebar}

{** wk_store_pickup_orders section **}
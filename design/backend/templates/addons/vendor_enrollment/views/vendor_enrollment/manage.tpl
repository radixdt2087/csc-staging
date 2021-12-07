{capture name="mainbox"}

<form action="{""|fn_url}" method="post" target="_self" name="vendor_list_form" id="vendor_list_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}

{$c_url=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{$c_icon="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{$c_dummy="<i class=\"icon-dummy\"></i>"}
{$rev=$smarty.request.content_id|default:"pagination_contents"}

{$page_title="Vendor Plan/AddOn Charges"}
{$extra_status=$config.current_url|escape:"url"}
{$notify_vendor = fn_allowed_for("MULTIVENDOR")}
{$notify=true}
{$notify_department=true}
{if $plan_charges}
<div class="table-responsive-wrapper longtap-selection">
    <table width="100%" class="table table-middle table--relative table-responsive table-manage-orders">
    <thead data-ca-bulkedit-default-object="true" data-ca-bulkedit-component="defaultObject">
    <tr>
        <th width="5%" class="left mobile-hide">
            {* {include file="common/check_items.tpl" check_statuses=$order_status_descr}
            <input type="checkbox"
                   class="bulkedit-toggler hide"
                   data-ca-bulkedit-toggler="true"
                   data-ca-bulkedit-disable="[data-ca-bulkedit-default-object=true]"
                   data-ca-bulkedit-enable="[data-ca-bulkedit-expanded-object=true]"
            /> *}
        </th>
        <th width="12%"><a class="cm-ajax" href="{"`$c_url`&sort_by=id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>Transaction #{if $search.sort_by == "id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="18%"><a class="cm-ajax" href="{"`$c_url`&sort_by=plan_date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("date")}{if $search.sort_by == "plan_date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {* <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th> *}
        <th width="18%"><a class="cm-ajax" href="{"`$c_url`&sort_by=company_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("Company")}{if $search.sort_by == "company_id"}{$c_icon nofilter}{/if}</a></th>
        {* <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=invoice&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("Invoice")} (Digitzs) {if $search.sort_by == "invoice"}{$c_icon nofilter}{/if}</a></th>         *}         
         <th width="18%"><a class="cm-ajax" href="{"`$c_url`&sort_by=paiduntildate&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>Paid Until{if $search.sort_by == "paiduntildate"}{$c_icon nofilter}{/if}</a></th>
        {*<th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=type&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("Type")} {if $search.sort_by == "type"}{$c_icon nofilter}{/if}</a></th>*}
        <th width="12%">Zipcode</th>
        <th width="8%">Type</th>
        <th width="10%" class="right">Total</th>
        {* <th class="mobile-hide">&nbsp;</th> *}
    </tr>
    </thead>
    {foreach from=$plan_charges item="pcharge"}
    <tr class="cm-longtap-target"
        data-ca-longtap-action="setCheckBox"
        data-ca-longtap-target="input.cm-item"
        data-ca-id="{$pcharge.id}"
    >
        <td width="5%" class="left mobile-hide">
            {* <input type="checkbox" name="order_ids[]" value="{$o.order_id}" class="cm-item cm-item-status-{$o.status|lower} hide" />*}</td>
        <td width="12%">
        <bdi><a href="{"vendor_enrollment.vendor_details?id=`$pcharge.id`"|fn_url}" class="underlined"><bdi>#{$pcharge.id}</bdi></a></bdi></td>
        <td width="18%" class="nowrap" data-th="{__("date")}"> {$pcharge.plan_date|date_format:"`$settings.Appearance.date_format`"}</td>
        <td width="18%" data-th="{__("company")}">
            {include file="views/companies/components/company_name.tpl" object=$pcharge}
        </td>
        <td width="18%">
            {assign var='freq' value=""}
            {if $pcharge.frequency == 'Month'}
                {assign var='freq' value="+1 month"}
            {else if  $pcharge.frequency == 'Year'}
                {assign var='freq' value="+1 year"}
            {/if}
            {if $pcharge.type == 'addons'}
                <bdi>
                 {if $freq}
                 {$pcharge.exp_date|date_format:"`$settings.Appearance.date_format`"}
                {else} One time {/if}
                </bdi>
             {else}
                 <bdi>
                 {$pcharge.exp_date|date_format:"`$settings.Appearance.date_format`"} {* `$settings.Appearance.time_format`*}
                 </bdi> 
            {/if}
        </td>
        <td width="12%" {if $pcharge.zipcode}data-th="{__("zipcode")}"{/if}><bdi> {$pcharge.zipcode}</bdi></td> 
        <td width="8%" data-th="{__("type")}"> {$pcharge.type}</td> 
        <td width="10%" class="right" data-th="{__("price")}">
            {include file="common/price.tpl" value="{$pcharge.amount}"}
        </td>
        {* <td width="20%" {if $o.invoice}data-th="{__("invoice")}"{/if}><bdi>{$o.invoice}
        </bdi></td>   
       
        {if isset($o.plan_id)}
            <td width="15%" data-th="{__("type")}"><bdi>Plan</bdi></td>
        {else if isset($o.addon_id)}
            <td width="15%" data-th="{__("type")}"><bdi>Addon</bdi></td>
        {else}
            <td width="15%" data-th="{__("type")}"><bdi></bdi></td>
        {/if} 
        {hook name="orders:manage_data"}{/hook} *}
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl" div_id=$smarty.request.content_id}

{* {capture name="adv_buttons"}
    {hook name="orders:manage_tools"}
        {include file="common/tools.tpl" tool_href="order_management.new" prefix="bottom" hide_tools="true" title=__("add_order") icon="icon-plus"}
    {/hook}
{/capture} *}

</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        <li>{btn type="list" text=__("view_purchased_products") dispatch="dispatch[orders.products_range]" form="orders_list_form"}</li>
        <li class="divider"></li>
        <li class="mobile-hide">{btn type="list" text=__("export_selected") dispatch="dispatch[orders.export_range]" form="orders_list_form"}</li>
        <li class="mobile-hide">{btn type="delete_selected" dispatch="dispatch[orders.m_delete]" form="orders_list_form"}</li>
        {hook name="orders:list_tools"}
        {/hook}
    {/capture}
    {dropdown content=$smarty.capture.tools_list class="bulkedit-dropdown--legacy hide"}
{/capture}

{include file="common/mainbox.tpl"
    title=$page_title
    sidebar=$smarty.capture.sidebar
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
    adv_buttons=$smarty.capture.adv_buttons
    
}
{*content_id="manage_orders"
    select_storefront=true
    storefront_switcher_param_name="storefront_id"
    selected_storefront_id=$selected_storefront_id*}

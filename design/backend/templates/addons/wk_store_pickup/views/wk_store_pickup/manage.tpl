{** wk_store_pickup section **}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="store_pickup_points_form" class="" enctype="multipart/form-data">
<input type="hidden" name="fake" value="1" />

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents_points"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}
{include file="common/pagination.tpl" div_id="pagination_contents_points" save_current_page=true save_current_url=true}
{if $wk_store_pickup_points}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
    <thead>
    <tr>
        <th width="1%" class="left mobile-hide">
            {include file="common/check_items.tpl" class="cm-no-hide-input"}</th>
        <th width="8%"><a class="cm-ajax" href="{"`$c_url`&sort_by=store_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_store_id")}{if $search.sort_by == "store_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=title&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_store_name")}{if $search.sort_by == "title"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=address&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("address")}{if $search.sort_by == "address"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=pincode&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_pincode")}{if $search.sort_by == "pincode"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=city&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("city")}{if $search.sort_by == "city"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="15%" class="center">{__("products")}</th>
        <th width="15%" class="center">{__("orders")}</th>
        <th width="6%" class="mobile-hide">&nbsp;</th>
        <th width="9%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    </tr>
    </thead>
    {foreach from=$wk_store_pickup_points item=wk_store_pickup_point}
    <tr class="cm-row-status-{$wk_store_pickup_point.status|lower}">
        {assign var="allow_save" value=$wk_store_pickup_point|fn_allow_save_object:"wk_store_pickup_points"}

        {if $allow_save}
            {assign var="no_hide_input" value="cm-no-hide-input"}
        {else}
            {assign var="no_hide_input" value=""}
        {/if}

        <td class="left mobile-hide">
            <input type="checkbox" name="store_ids[]" value="{$wk_store_pickup_point.store_id}" class="cm-item {$no_hide_input}" /></td>
        <td data-th="{__("wk_store_id")}">
            <a href="{'wk_store_pickup.update&store_id='|cat:$wk_store_pickup_point.store_id|fn_url}">{$wk_store_pickup_point.store_id}</a>
        </td>
        <td data-th="{__("wk_store_name")}">
            {$wk_store_pickup_point.title}
            {include file="views/companies/components/company_name.tpl" object=$wk_store_pickup_point}
        </td>
        <td data-th="{__("address")}">
            {$wk_store_pickup_point.address|truncate:50}
        </td>
        <td data-th="{__("pincode")}">
            {$wk_store_pickup_point.pincode}
        </td>
        <td data-th="{__("city")}">
            {$wk_store_pickup_point.city}
        </td>
        <td class="center" data-th="{__("products")}">
            {include file="buttons/button.tpl" but_text=$wk_store_pickup_point.products but_meta="btn btn-primary" but_href="wk_store_pickup.products?store_id=`$wk_store_pickup_point.store_id`" but_role="link"}
        </td>
        <td class="center" data-th="{__("orders")}">
            {include file="buttons/button.tpl" but_text=$wk_store_pickup_point.orders but_meta="btn btn-primary" but_href="wk_store_pickup.orders?store_id=`$wk_store_pickup_point.store_id`" but_role="link"}
        </td>
        <td class="mobile-hide">
            {capture name="tools_list"}
                <li>{btn type="list" text=__("edit") href="wk_store_pickup.update?store_id=`$wk_store_pickup_point.store_id`"}</li>
                <li>{btn type="list" text=__("add_products") href="wk_store_pickup.store_product?store_id=`$wk_store_pickup_point.store_id`"}</li>
                {if $allow_save}
                    <li>{btn type="list" text=__("delete") href="wk_store_pickup.delete?store_id=`$wk_store_pickup_point.store_id`" class="cm-confirm cm-post"}</li>
                {/if}
            {/capture}
            <div class="hidden-tools">
                {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>
        <td width="9%" class="right nowrap" data-th="{__("status")}">
          {include file="common/select_popup.tpl" popup_additional_class="dropleft" id=$wk_store_pickup_point.store_id status=$wk_store_pickup_point.status hidden=false object_id_name="store_id" table="wk_store_pickup_points"}
        </td>
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id="pagination_contents_points"}
{capture name="buttons"}
    {capture name="tools_list"}
        {if $wk_store_pickup_points && $allow_save}
            <li>{btn type="delete_selected" dispatch="dispatch[wk_store_pickup.m_delete]" form="store_pickup_points_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list class="mobile-hide"}
{/capture}
{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="wk_store_pickup.add" prefix="top" hide_tools="true" title=__("wk_add_new_pickup_point") icon="icon-plus"}
{/capture}
</form>

{/capture}

{capture name="sidebar"}
    {hook name="wk_store_pickup_points:manage_sidebar"}
        {include file="common/saved_search.tpl" dispatch="wk_store_pickup.manage" view_type="wk_store_pickup"}
        {include file="addons/wk_store_pickup/views/wk_store_pickup/components/store_search_form.tpl" dispatch="wk_store_pickup.manage"}
    {/hook}
{/capture}

{include file="common/mainbox.tpl" title=__("wk_store_pickup_points") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons select_languages=true sidebar=$smarty.capture.sidebar}

{** wk_store_pickup section **}

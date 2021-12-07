{capture name="mainbox"}
{assign 'items_status' ['A'=>'Active', 'D'=>'Disabled']}
<form action="{""|fn_url}" method="post" name="addons_form" id="addons_form">
<input type="hidden" name="fake" value="1" />

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}

{$c_url=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{$c_icon="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{$c_dummy="<i class=\"icon-dummy\"></i>"}
{$rev=$smarty.request.content_id|default:"pagination_contents"}

{$return_url=$config.current_url|escape:"url"}
{if $addons_data}
<div class="table-responsive-wrapper {if "MULTIVENDOR"|fn_allowed_for}longtap-selection{/if}">
    <table width="100%" class="table table-middle table--relative table-responsive">
    <thead {if "MULTIVENDOR"|fn_allowed_for}data-ca-bulkedit-default-object="true"{/if}>
    <tr>
        <th width="1%" class="left mobile-hide">
            {* {include file="common/check_items.tpl" check_statuses=$c_statuses}

            {if "MULTIVENDOR"|fn_allowed_for}
             <input type="checkbox"
                   class="bulkedit-toggler hide"
                   data-ca-bulkedit-toggler="true"
                   data-ca-bulkedit-disable="[data-ca-bulkedit-default-object=true]" 
                   data-ca-bulkedit-enable="[data-ca-bulkedit-expanded-object=true]"
            />
            {/if} *}
        </th>
        <th width="18%"><a class="cm-ajax" href="{"`$c_url`&sort_by=id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("id")}{if $search.sort_by == "id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=company&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}{if $search.sort_by == "company"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {* {if "ULTIMATE"|fn_allowed_for}
            <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=storefront&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("storefront_url")}{if $search.sort_by == "storefront"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        {/if} *}
        {* <th width="16%"><a class="cm-ajax nowrap" href="{"`$c_url`&sort_by=date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("registered")}{if $search.sort_by == "date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th> *}
        
        <th width="4%" class="nowrap">&nbsp;</th>
        <th width="7%" class="nowrap"><a class="nowrap cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>        
        <th width="25%" class="nowrap right"><a class="cm-ajax" href="{"`$c_url`&sort_by=price&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("price")}{if $search.sort_by == "price"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>

    </tr>
    </thead>
    {foreach from=$addons_data item=addon}
    <tr class="cm-row-status-{$addon.status|lower} {if "MULTIVENDOR"|fn_allowed_for}cm-longtap-target{/if}"
        {if "MULTIVENDOR"|fn_allowed_for}
            data-ct-addon-id="{$addon.id}"
            data-ca-longtap-action="setCheckBox"
            data-ca-longtap-target="input.cm-item"
            data-ca-id="{$addon.id}"
            data-ca-bulkedit-dispatch-parameter="addon_ids[]"
        {/if}
    >
        <td width="1%" class="left mobile-hide">
            {* <input type="checkbox"
                   name="company_ids[]"
                   value="{$addon.company_id}"
                   class="
                       cm-item cm-item-status-{$company.status|lower}
                       {if fn_allowed_for("MULTIVENDOR")}hide{/if}
                   "
            /> *}
        </td>
        <td width="18%" class="row-status" data-th="{__("id")}"><a href="{"vendor_addons.update?id=`$addon.id`"|fn_url}">&nbsp;<span>{$addon.id}</span>&nbsp;</a></td>
        <td width="25%" class="row-status wrap" data-th="{__("name")}">{$addon.name}</td>        
        
        {* {if "ULTIMATE"|fn_allowed_for}
            {$storefront_href = "http://`$company.storefront`"}
            {if $company.storefront_status === "StorefrontStatuses::CLOSED"|enum && $company.store_access_key}
                {$storefront_href = $storefront_href|fn_link_attach:"store_access_key=`$company.store_access_key`"}
            {/if}
            <td width="25%" data-th="{__("storefront")}" id="storefront_url_{$company.company_id}"><a href="{$storefront_href}">{$company.storefront|puny_decode}</a><!--storefront_url_{$company.company_id}--></td>
        {/if}
        <td width="16%" class="row-status" data-th="{__("registered")}">{$company.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td> *}
        
        <td width="4%" class="nowrap" data-th="{__("tools")}">
          {*   {capture name="tools_items"}
            {hook name="companies:list_extra_links"}
                <li>{btn type="list" href="products.manage?company_id=`$company.company_id`" text=__("view_vendor_products")}</li>
                {if "MULTIVENDOR"|fn_allowed_for}
                    <li>{btn type="list" href="profiles.manage?user_type={"UserTypes::VENDOR"|enum}&company_id=`$company.company_id`" text=__("view_vendor_admins")}</li>
                {else}
                    <li>{btn type="list" href="profiles.manage?company_id=`$company.company_id`" text=__("view_vendor_users")}</li>
                {/if}
                <li>{btn type="list" href="orders.manage?company_id=`$company.company_id`" text=__("view_vendor_orders")}</li>
                {if !"ULTIMATE"|fn_allowed_for && !$runtime.company_id}
                    <li>{btn type="list" href="companies.merge?company_id=`$company.company_id`" text=__("merge")}</li>
                {/if}
                {if !$runtime.company_id && fn_check_view_permissions("companies.update", "POST")}
                    <li>{btn type="list" href="companies.update?company_id=`$company.company_id`" text=__("edit")}</li>
                    <li class="divider"></li>
                    {if $runtime.simple_ultimate}
                        <li class="disabled"><a>{__("delete")}</a></li>
                    {else}
                        <li>{btn type="list" class="cm-confirm" href="companies.delete?company_id=`$company.company_id`&redirect_url=`$return_current_url`" text=__("delete") method="POST"}</li>
                    {/if}
                {/if} 
            {/hook}
            {/capture}
            <div class="hidden-tools">
                {dropdown content=$smarty.capture.tools_items}
            </div>*}
        </td>
        <td width="7%"
                class="nowrap"
                data-th="{__("status")}"
        >
        {include file="common/select_popup.tpl"
            id=$addon.id
            status=$addon.status
            items_status=$items_status
            object_id_name="id"
            hide_for_vendor=false
            update_controller="vendor_addons"
            notify=false
            notify_text=__("notify_vendor")
            status_target_id="pagination_contents"
            extra="&return_url=`$return_url`"
        }
        </td>
        <td width="20%" class="nowrap right" data-th="{__("price")}">{include file="common/price.tpl" value=$addon.price}</td>
            {* {include file="views/companies/components/status_on_manage.tpl"
                id=$addon.id
                status=$addon.status
                items_status="addon"|fn_get_predefined_statuses:$addon.status
                company=$addon
                text_wrap=true
            } *}
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id=$smarty.request.content_id}

</form>
{/capture}
{* {capture name="buttons"}
    {capture name="tools_items"}
            {if !$runtime.company_id
                && fn_allowed_for("ULTIMATE")
                && fn_check_view_permissions("companies.update", "POST")
            }
                <li>{btn type="delete_selected" dispatch="dispatch[companies.m_delete]" form="companies_form"}</li>
            {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_items class="mobile-hide"}
{/capture} *}

{if fn_allowed_for("MULTIVENDOR")}
    {$add_vendor_text = "Add addon"}
{else}
    {$add_vendor_text = __("add_storefront")}
{/if}

{capture name="adv_buttons"}
        {* {if $is_companies_limit_reached}
            {$promo_popup_title = __("ultimate_or_storefront_license_required", ["[product]" => $smarty.const.PRODUCT_NAME])}

            {include file="common/tools.tpl"
                tool_override_meta="btn cm-dialog-opener cm-dialog-auto-height"
                tool_href="functionality_restrictions.ultimate_or_storefront_license_required"
                prefix="top"
                hide_tools=true
                title=$add_vendor_text
                icon="icon-plus"
                meta_data="data-ca-dialog-title='{$promo_popup_title}'"}
        {else}
            {include file="common/tools.tpl"
                tool_href="vendor_addons.add"
                prefix="top"
                hide_tools=true
                title=$add_vendor_text
                icon="icon-plus"
            }
        {/if} *}
        {include file="common/tools.tpl"
            tool_href="vendor_addons.add"
            prefix="top"
            hide_tools=true
            title=$add_vendor_text
            icon="icon-plus"
        }
{/capture}

{capture name="sidebar"}
    
{/capture}

{capture name="page_title"}
    {if fn_allowed_for("MULTIVENDOR")}
        {__("vendor_enrollment.addons")}
    {else}
        {__("storefronts")}
    {/if}
{/capture}

{include file="common/mainbox.tpl" 
title=$smarty.capture.page_title 
content=$smarty.capture.mainbox 
buttons=$smarty.capture.buttons 
adv_buttons=$smarty.capture.adv_buttons 
}

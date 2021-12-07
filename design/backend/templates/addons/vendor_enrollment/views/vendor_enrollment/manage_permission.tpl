{capture name="mainbox"}
{assign 'items_status' ['A'=>'Active', 'D'=>'Disabled']}
<form action="{""|fn_url}" method="post" name="permission_form" id="permission_form">
<input type="hidden" name="fake" value="1" />

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{$c_url=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{$c_icon="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{$c_dummy="<i class=\"icon-dummy\"></i>"}
{$return_url=$config.current_url|escape:"url"}
{if $permission_list}
<div class="table-responsive-wrapper {if "MULTIVENDOR"|fn_allowed_for}longtap-selection{/if}">
    <table width="100%" class="table table-middle table--relative table-responsive">
    <thead {if "MULTIVENDOR"|fn_allowed_for}data-ca-bulkedit-default-object="true"{/if}>
    <tr>
        <th width="1%" class="left mobile-hide">
        </th>
        <th width="18%"><a class="cm-ajax" href="{"`$c_url`&sort_by=id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("id")}{if $search.sort_by == "id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="4%" class="nowrap">&nbsp;</th>
        <th width="25%" class="nowrap"><a class="nowrap cm-ajax" href="{"`$c_url`&sort_by=modules&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">Modules {if $search.sort_by == "modules"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="25%" class="nowrap"><a class="cm-ajax" href="{"`$c_url`&sort_by=tabs&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("tabs")}{if $search.sort_by == "tabs"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="7%" class="nowrap right"><a class="nowrap cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>        
    </tr>
    </thead>
    {foreach from=$permission_list item=permission}
    <tr class="cm-row-status-{$permission.status|lower} {if "MULTIVENDOR"|fn_allowed_for}cm-longtap-target{/if}"
        {if "MULTIVENDOR"|fn_allowed_for}
            data-ct-permission-id="{$permission.id}"
            data-ca-longtap-action="setCheckBox"
            data-ca-longtap-target="input.cm-item"
            data-ca-id="{$permission.id}"
            data-ca-bulkedit-dispatch-parameter="permission_ids[]"
        {/if}
    >
        <td width="1%" class="left mobile-hide"></td>
        <td width="18%" class="row-status" data-th="{__("id")}"><a href="{"vendor_enrollment.update?id=`$permission.id`"|fn_url}">&nbsp;<span>{$permission.id}</span>&nbsp;</a></td>
        <td width="20%" class="row-status wrap" data-th="{__("name")}">{$permission.name}</td>
        <td width="4%" class="nowrap" data-th="{__("tools")}">
        </td>
        <td width="25%" class="nowrap" data-th="{__("modules")}">{$permission.modules}</td>
        <td width="25%" class="nowrap" data-th="{__("tabs")}">{$permission.tabs}</td>
        <td width="7%"
                class="nowrap right"
                data-th="{__("status")}"
        >
        {include file="common/select_popup.tpl"
            id=$permission.id
            status=$permission.status
            items_status=$items_status
            object_id_name="id"
            hide_for_vendor=false
            update_controller="vendor_enrollment"
            notify=false
            notify_text=__("notify_vendor")
            status_target_id="pagination_contents"
            extra="&return_url=`$return_url`"
        }
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl"}
</form>
{/capture}
{if fn_allowed_for("MULTIVENDOR")}
    {$add_vendor_text = "Add"}
{else}
    {$add_vendor_text = __("add_storefront")}
{/if}

{capture name="adv_buttons"}
        {include file="common/tools.tpl"
            tool_href="vendor_enrollment.add"
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
        {__("vendor_enrollment.permission")}
    {else}
        {__("storefronts")}
    {/if}
{/capture}

{include file="common/mainbox.tpl" title=$smarty.capture.page_title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}

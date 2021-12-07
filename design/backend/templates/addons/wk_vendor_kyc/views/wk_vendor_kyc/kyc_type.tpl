
{capture name="wk_kyc"}
     {capture name="sidebar"}
        {include file="common/saved_search.tpl" dispatch="wk_vendor_kyc.kyc_type" view_type="wk_vendor_kyc"}
        {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/kyc_type_search_form.tpl" dispatch="wk_vendor_kyc.kyc_type"}
    {/capture}
<form action="{""|fn_url}" method="post" target="_self" name="wk_vendor_kyc_list_form">
{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}
{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}
<div style="margin-top:22px;">
{if $wk_vendor_kyc}
<div class="table-responsive-wrapper">
    <table width="100%" class="table table-middle table-responsive">
    <thead>
        <tr>
            <th width="1%" class="left">
                {include file="common/check_items.tpl"}</th>
            <th width="30%" class="left"><a class="cm-ajax" href="{"`$c_url`&sort_by=kyc_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("kyc_id")}{if $search.sort_by == "kyc_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="40%"><a class="cm-ajax" href="{"`$c_url`&sort_by=kyc_type&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("kyc_type")}{if $search.sort_by == "kyc_type"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=is_required&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("required")}{if $search.sort_by == "is_required"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="10%">&nbsp</th>
            <th width="7%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>

        </tr>
    </thead>
    {foreach from=$wk_vendor_kyc item="kyc_data"}
    <tr class="cm-row-status-{$kyc_data.status|lower}" data-ct-company-id="{$kyc_data.kyc_id}">
        <td class="left mobile-hide">
            <input type="checkbox" name="kyc_type_ids[]" value="{$kyc_data.kyc_id}" class="cm-item" /></td>
        <td data-th='{__("kyc_id")}' class="left">
            {$kyc_data.kyc_id}
        </td>
        <td data-th='{__("kyc_type")}'>
           {$kyc_data.description}
        </td> 
        <td data-th='{__("required")}'>
           {if $kyc_data.is_required =='Y'}
           {__('yes')}
           {else}
             {__('no')}
           {/if}
        </td> 
        <td class="nowrap">
            <div class="hidden-tools">
                {capture name="tools_list"}
                    <li>{btn type="list" text=__("edit") href="wk_vendor_kyc.update_kyc_type?kyc_id=`$kyc_data.kyc_id`"}</li>
                    <li>{btn type="list" text=__("delete") href="wk_vendor_kyc.kyc_type_delete?kyc_id=`$kyc_data.kyc_id`" class="cm-post"}</li>
                {/capture}
                {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>  
         <td data-th='{__("status")}' class="right nowrap">
            {include file="common/select_popup.tpl" id=$kyc_data.kyc_id status=$kyc_data.status hidden="" object_id_name="kyc_id" table="wk_vendor_kyc_type" popup_additional_class="`$popup_additional_class` dropleft" non_editable=$non_editable}
        </td>
    </tr>
    {/foreach}
</table>
</div>

{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
</div>
{include file="common/pagination.tpl" div_id=$smarty.request.content_id}
</form>
{/capture}
{capture name="add_kyc_type"}
    {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/add_kyc_type.tpl"}
{/capture}
{capture name="adv_buttons"}
     {include file="common/popupbox.tpl" id="add_kyc_type" text=__("add_kyc_type") title=__("add_kyc_type") content=$smarty.capture.add_kyc_type act="general" link_class="cm-dialog-auto-size" icon="icon-plus" link_text=""}
{/capture}

{capture name="buttons"}
    {capture name="tools_items"}
        {hook name="companies:manage_tools_list"}
            <li>{btn type="delete_selected" dispatch="dispatch[wk_vendor_kyc.m_kyc_type_delete]" form="wk_vendor_kyc_list_form"}</li>  
        {/hook}
    {/capture}
    {dropdown content=$smarty.capture.tools_items}
{/capture}
{include file="common/mainbox.tpl" title={__('wk_vendor_kyc_types')} content=$smarty.capture.wk_kyc  buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar adv_buttons=$smarty.capture.adv_buttons select_languages=true}
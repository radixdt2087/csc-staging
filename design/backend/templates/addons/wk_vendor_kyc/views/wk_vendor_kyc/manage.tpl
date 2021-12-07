<style type="text/css">
    .of_atatus{
        list-style: none;
    }
</style>
{capture name="wk_vendor_kyc_manage"}
     {capture name="sidebar"}
        {include file="common/saved_search.tpl" dispatch="wk_vendor_kyc.manage" view_type="kyc_search"}
        {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/kyc_search_form.tpl" dispatch="wk_vendor_kyc.manage"}
    {/capture}
<form action="{""|fn_url}" method="post" target="_self" name="wk_vendor_kyc_data_manage_form">
{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}
{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}
{if $kyc_data}
<div class="table-responsive-wrapper" style="margin-top:23px;">
    <table width="100%" class="table table-middle table-responsive">
    <thead>
        <tr>
            <th width="1%" class="left">
                {include file="common/check_items.tpl"}</th>
            <th width="10%" class="left"><a class="cm-ajax" href="{"`$c_url`&sort_by=kyc_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("kyc_id")}{if $search.sort_by == "kyc_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
             <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=company_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("company_id")}{if $search.sort_by == "company_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=kyc_type&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("kyc_type")}{if $search.sort_by == "kyc_type"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=kyc_id_number&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("kyc_id_number")}{if $search.sort_by == "kyc_id_number"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=kyc_name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("kyc_name")}{if $search.sort_by == "kyc_name"}{$c_icon nofilter}{/if}</a></th>
           
            <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=upload_date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("upload_date")}{if $search.sort_by == "upload_date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="5%">&nbsp</th>
            <th width="8%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
            <th width="8%">&nbsp</th>
        </tr>
    </thead>
    {foreach from=$kyc_data item="kyc"}
     {assign var="kyc_type_description" value=$kyc.kyc_type|fn_wk_vendor_kyc_get_kyc_type_description}
    <tr {if empty($kyc_type_description)}class="cm-row-status-d"{/if}>
        <td class="left mobile-hide">
            <input type="checkbox" name="kyc_data_ids[]" value="{$kyc.kyc_id}" class="cm-item" /></td>
        <td data-th='{__("kyc_id")}' class="left row-status">
        <a href="{"wk_vendor_kyc.upload_kyc?kyc_id=`$kyc.kyc_id`"|fn_url}">{__('kyc')}#{$kyc.kyc_id}</a>
            {if !empty($kyc.reason)}{include file="common/tooltip.tpl" tooltip=$kyc.reason}{/if}
        </td>
         <td data-th='{__("company_id")}' class="row-status">
           {$kyc.company_id}
        </td>  
        <td data-th='{__("kyc_type")}' class="row-status">
            {assign var="kyc_type_description" value=$kyc.kyc_type|fn_wk_vendor_kyc_get_kyc_type_description}
           {$kyc_type_description}
            
        </td>  
        <td data-th='{__("kyc_id_number")}' class="row-status">
           {$kyc.kyc_id_number}
        </td>
        <td data-th='{__("kyc_name")}' class="row-status">
           {$kyc.kyc_name}
        </td>
        <td data-th='{__("upload_date")}' class="row-status">
           {$kyc.upload_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
        </td>  
        <td class="nowrap">
            <div class="hidden-tools">
                {capture name="tools_list"}
                   
                    <li>{btn type="list" text=__("download_kyc") href="wk_vendor_kyc.download?kyc_id=`$kyc.kyc_id`" class="cm-confirm cm-post"}</li>
                    <li>{btn type="list" text=__("delete") href="wk_vendor_kyc.delete?kyc_id=`$kyc.kyc_id`" class="cm-confirm cm-post"}</li>
                {/capture}
                {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>  
        <td data-th='{__("status")}' class="row-status">
            {if $kyc.status eq 'N'}
                 <li class="of_atatus">{__('new')}</li>
            {elseif $kyc.status eq 'A'}
                <li class="of_atatus">{__('accepted')}</li>
            {else}
                <li class="of_atatus">{__('rejected')}</li>
            {/if}
        </td>

        <td>
            {if $runtime.company_id ==0}
                {capture name="accepted"}
                {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/approval_popup.tpl" kyc_id=$kyc.kyc_id company_id=$kyc.company_id name="approval_data[`$kyc.kyc_id`]"}
                <div class="buttons-container">
                    {include file="buttons/save_cancel.tpl" but_text=__("accept") but_name="dispatch[wk_vendor_kyc.accept.approve.`$kyc.kyc_id`]" cancel_action="close"}
                </div>
                {/capture}
            
                {capture name="rejected"}
                    {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/approval_popup.tpl" name="approval_data[`$kyc.kyc_id`]" kyc_id=$kyc.kyc_id company_id=$kyc.company_id}
                    <div class="buttons-container">
                        {include file="buttons/save_cancel.tpl" but_text=__("reject") but_name="dispatch[wk_vendor_kyc.reject.disapprove.`$kyc.kyc_id`]" cancel_action="close"}
                    </div>
                {/capture}
            
                {if $kyc.status == "A" || $kyc.status =='N'}
                    {include file="common/popupbox.tpl" id="reject_`$kyc.kyc_id`" text="{__("rejected")}" content=$smarty.capture.rejected link_text=" " act="edit" icon="icon-thumbs-down"}
                {/if}
                
                {if $kyc.status == "R" || $kyc.status =='N'}
                    {include file="common/popupbox.tpl" id="accepted_`$kyc.kyc_id`" text="{__("accepted")}"  content=$smarty.capture.accepted link_text=" " act="edit" icon="icon-thumbs-up"}
                {/if}
            {/if}
        </td>
        
    </tr>
    {/foreach}
</table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id=$smarty.request.content_id}
{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="wk_vendor_kyc.upload_kyc" prefix="top" title=__("upload_kyc") hide_tools=true icon="icon-plus"}
{/capture}
{if $runtime.company_id ==0}
    {capture name="accept_selected"}
        {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/reason_container.tpl" type="accept"}
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_text=__("accept") but_name="dispatch[wk_vendor_kyc.m_accept]" cancel_action="close" but_meta="cm-process-items"}
        </div>
    {/capture}
    {include file="common/popupbox.tpl" id="accept_selected" text=__("accept_selected") content=$smarty.capture.accept_selected link_text=__("accept_selected")}    

    {capture name="reject_selected"}
        {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/reason_container.tpl" type="reject"}
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_text=__("reject") but_name="dispatch[wk_vendor_kyc.m_reject]" cancel_action="close" but_meta="cm-process-items"}
        </div>
    {/capture}
        {include file="common/popupbox.tpl" id="reject_selected" text=__("reject_selected") content=$smarty.capture.reject_selected link_text=__("reject_selected")}
    {capture name="buttons"}
        {capture name="tools_items"}
            {hook name="companies:manage_tools_list"}
                <li>{btn type="delete_selected" dispatch="dispatch[wk_vendor_kyc.m_delete]" form="wk_vendor_kyc_data_manage_form"}</li>  
            {/hook}
        {/capture}
        {dropdown content=$smarty.capture.tools_items}
        {include file="buttons/button.tpl" but_role="submit-link" but_target_id="content_accept_selected" but_text=__("accept_selected") but_meta="cm-process-items cm-dialog-opener" but_target_form="manage_products_form"}
        {include file="buttons/button.tpl" but_role="submit-link" but_target_id="content_reject_selected" but_text=__("reject_selected") but_meta="cm-process-items cm-dialog-opener" but_target_form="manage_products_form"}
    {/capture}
{/if}
</form>
{/capture}

{include file="common/mainbox.tpl" title={__('wk_vendor_kyc_manage')} content=$smarty.capture.wk_vendor_kyc_manage  buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar adv_buttons=$smarty.capture.adv_buttons select_languages=true}
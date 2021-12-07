{** listing_management section **}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="wk_vendor_subdomain_manage_form" class=" cm-hide-inputs" enctype="multipart/form-data">
<input type="hidden" name="fake" value="1" />
{include file="common/pagination.tpl" save_current_page=true save_current_url=true}
{assign var="return_current_url" value=$config.current_url|escape:url}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}
{if $subdomainList}
<table class="table table-middle">
<thead>
<tr>
    {if !$runtime.company_id}   
    <th width="1%" class="left">{include file="common/check_items.tpl" class="cm-no-hide-input"}</th>
    {/if}
    <th>{__("vendor_id")}</th>
    <th>{__("vendor_name")}</th>
    <th>{__("subdomain")}</th>
    <th>&nbsp;</th>
    <th class="right">{__("status")}</th>
</tr>
</thead>
{foreach from=$subdomainList item=data}
<tr class="cm-row-status-{$data.status|lower}">
    {assign var="allow_save" value=$data|fn_allow_save_object:"wk_vendor_subdomain"}
    {if $allow_save}
        {assign var="no_hide_input" value="cm-no-hide-input"}
    {else}
        {assign var="no_hide_input" value=""}
    {/if}
     {if !$runtime.company_id}   
    <td class="left">
        <input type="checkbox" name="company_ids[]" value="{$data.company_id}" class="cm-item {$no_hide_input}" />
    </td>
    {/if}
    <td class="left">
        <a class="row-status" href="{"companies.update&company_id=`$data.company_id`"|fn_url}">{$data.company_id}</a>
    </td>
    <td class="left">
        <a class="row-status" href="{"companies.update&company_id=`$data.company_id`"|fn_url}">{fn_get_company_name($data.company_id)}</a>   
    </td>
    <td class="row-status ">
        {$data.subdomain}
    </td> 
    <td class="no-wrap" >
        {capture name="tools_list"}
            <li>
            {include file="common/popupbox.tpl"
                    href="wk_vendor_subdomain.update?company_id={$data.company_id}"
                    link_text=__("edit")
                    act="edit"
                    text=__("update_subdomain")
                    id="vendor_subdomain_{$data.company_id}"
                    content=""
             }
            </li>
            {if !$runtime.company_id}   
                <li>{btn type="list" text=__("delete") class="cm-confirm" href="wk_vendor_subdomain.delete?company_id=`$data.company_id`" method="post"}</li>
            {/if}
        {/capture}
        <div class="hidden-tools">
            {dropdown content=$smarty.capture.tools_list}
        </div>
    </td>    
    
    <td class="right">
        {if !$runtime.company_id}
            {include file="common/select_popup.tpl" id=$data.company_id status=$data.status hidden=false object_id_name="company_id" table="wk_vendor_subdomain" popup_additional_class="`$no_hide_input` dropleft" update_controller="wk_vendor_subdomain"}
        {else}
            {if $data.status == 'A'}{__("active")}{else}{__("disabled")}{/if}
        {/if}
    </td>
    </td>
</tr>
{/foreach}
</table>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl"}

{capture name="buttons"}
{if !$runtime.company_id}   
    {if $subdomainList}
        {capture name="tools_list"}
                <li>
                <a data-ca-dispatch="dispatch[wk_vendor_subdomain.m_delete]" data-ca-target-form="wk_vendor_subdomain_manage_form" class="cm-submit cm-process-items cm-post cm-confirm">{__("delete_selected")}</a>
        {/capture}
        {dropdown content=$smarty.capture.tools_list}
    {/if}
{/if}    
{/capture}
{capture name="adv_buttons"}
    {if !$subdomainList || !$runtime.company_id}
        {include file="common/popupbox.tpl"
            act="create"
            text=__("update_subdomain")
            title=__("add_subdomain_title")
            id="vendor_subdomain"
            icon="icon-plus"
            content=""
        }
    {/if}
{/capture}
</form>
{/capture}
{capture name="sidebar"}   
    {if !$runtime.company_id}       
        {include file="addons/wk_vendor_subdomain/views/wk_vendor_subdomain/vendor_subdomain_search_form.tpl" dispatch="wk_vendor_subdomain.manage"}
    {/if}
{/capture}
{include file="common/mainbox.tpl" title=__("manage_vendor_subdomain")  content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar select_languages=true}

{include file="addons/wk_vendor_subdomain/views/wk_vendor_subdomain/components/create_new_subdomain.tpl"}



{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="companies_form" id="companies_form">
<input type="hidden" name="fake" value="1" />

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}

{assign var="return_url" value=$config.current_url|escape:"url"}

{if $companies}
<div class="table-responsive-wrapper">
<table width="100%" class="table table-middle table-responsive">
<thead>
<tr>
    <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("id")}{if $search.sort_by == "id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=company&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}{if $search.sort_by == "company"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    {if "MULTIVENDOR"|fn_allowed_for}
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=email&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("email")}{if $search.sort_by == "email"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    {/if}
    <th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=upload_kyc_request_time&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("upload_kyc_request_time")}{if $search.sort_by == "upload_kyc_request_time"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>

    <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=wk_upload_exp&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("kyc_upload_status")}{if $search.sort_by == "wk_upload_exp"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="5%" class="nowrap">&nbsp;</th>
</tr>
</thead>
{foreach from=$companies item=company}
<tr class="cm-row-status-{$company.status|lower}" data-ct-company-id="{$company.company_id}">
    <td data-th='{__("id")}' class="row-status"><a href="{"companies.update?company_id=`$company.company_id`"|fn_url}">&nbsp;<span>{$company.company_id}</span>&nbsp;</a></td>
    <td data-th='{__("name")}' class="row-status"><a href="{"companies.update?company_id=`$company.company_id`"|fn_url}">{$company.company}</a></td>
    {if "MULTIVENDOR"|fn_allowed_for}
        <td data-th='{__("email")}' class="row-status"><a href="mailto:{$company.email}">{$company.email}</a></td>
    {/if}
    <td data-th='{__("upload_kyc_request_time")}' class="row-status">{$company.upload_kyc_request_time|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
    {assign var=wk_vendor_kyc_exist value=fn_wk_vendor_kyc_check_kyc_exist($company.company_id)}
    <td data-th='{__("kyc_upload_status")}'>{if $wk_vendor_kyc_exist && $company.wk_upload_exp eq 0}
        <span class="row-status normal badge">{$wk_vendor_kyc_exist}</span>&nbsp;{__('kyc_found')}
        {else if $company.wk_upload_exp eq 1}
             <span class="label label-important"><i class="ui-icon ui-icon-closethick"></i></span>&nbsp;{__('upload_expire')}
        {/if}
    </td>
    <td class="nowrap">
        {capture name="tools_items"}
            <li>{btn type="list" href="wk_vendor_kyc.sendagain?company_id=`$company.company_id`" text=__("sendagain")}</li>
        {/capture}
        <div class="hidden-tools">
            {dropdown content=$smarty.capture.tools_items}
        </div>
    </td>

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
{capture name="sidebar"}
    {include file="common/saved_search.tpl" dispatch="companies.manage" view_type="companies"}
    {include file="addons/wk_vendor_kyc/views/wk_vendor_kyc/components/companies_search_form.tpl" dispatch="wk_vendor_kyc.kyc_request_log"}
{/capture}
{include file="common/mainbox.tpl" title=__("kyc_request_log") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}

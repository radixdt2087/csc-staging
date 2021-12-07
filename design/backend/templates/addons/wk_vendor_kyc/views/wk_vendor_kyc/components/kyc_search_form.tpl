
{if $in_popup}
    <div class="adv-search">
    <div class="group">
{else}
    <div class="sidebar-row">
    <h6>{__("search")}</h6>
{/if}
<form action="{""|fn_url}" name="orders_search_form" method="get" class="{$form_meta}">
{capture name="simple_search"}

{if $smarty.request.redirect_url}
<input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
{/if}
{if $selected_section != ""}
<input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
{/if}

{$extra nofilter}

<div class="sidebar-field">
    <label for="kyc_id">{__("kyc_id")}</label>
    <input type="text" name="kyc_id" id="kyc_id" value="{$search.kyc_id}" size="30" />
</div>
<div class="sidebar-field">
    <label for="company_id">{__("company_id")}</label>
    <input type="text" name="company_id" id="company_id" value="{$search.company_id}" size="30"/>
</div>
<div class="sidebar-field">
    <label for="kyc_type">{__("kyc_type")}</label>
    <input type="text" name="kyc_type" id="kyc_type" value="{$search.kyc_type}" size="30" />
</div>
<div class="sidebar-field">
    <label for="kyc_name">{__("kyc_name")}</label>
    <input type="text" name="kyc_name" id="kyc_name" value="{$search.kyc_name}" size="30"/>
</div>
<div class="sidebar-field">
    <label for="kyc_status">{__("kyc_status")}</label>
    <select name="kyc_status" id="kyc_status">
        <option value="">--</option>
        <option value="N" {if $search.kyc_status == "N"}selected="selected"{/if}>{__("new")}</option>
        <option value="A" {if $search.kyc_status == "A"}selected="selected"{/if}>{__("accepted")}</option>
        <option value="R" {if $search.kyc_status == "R"}selected="selected"{/if}>{__("rejected")}</option>
    </select>
</div>
{/capture}
{include file="common/advanced_search.tpl" simple_search=$smarty.capture.simple_search dispatch=$dispatch  no_adv_link=no_adv_link}
</form>
{if $in_popup}
    <div class="adv-search">
    <div class="group">
{else}
    <div class="sidebar-row">
    <h6>{__("search")}</h6>
{/if}

<form name="companies_search_form" action="{""|fn_url}" method="get" class="{$form_meta}">
{capture name="simple_search"}

{if $smarty.request.redirect_url}
    <input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
{/if}

{if $selected_section != ""}
    <input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
{/if}

{if $search.user_type}
    <input type="hidden" name="user_type" value="{$search.user_type}" />
{/if}

{if $company_id}
    <input type="hidden" name="company_id" value="{$company_id}" />
{/if}

{$extra nofilter}

{hook name="companies:search_form_main"}

<div class="sidebar-field">
    <label for="elm_name">{__("name")}</label>
    <input type="text" name="company" id="elm_name" value="{$search.company}" />
</div>

<div class="sidebar-field">
    <label for="elm_email">{__("email")}</label>
    <input type="text" name="email" id="elm_email" value="{$search.email}" />
</div>

{/hook}

{/capture}

{include file="common/advanced_search.tpl" simple_search=$smarty.capture.simple_search  dispatch=$dispatch view_type="companies"  no_adv_link=no_adv_link}

</form>


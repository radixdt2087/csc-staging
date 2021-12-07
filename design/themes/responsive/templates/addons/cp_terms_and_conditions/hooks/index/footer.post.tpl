{if $addons.cp_terms_and_conditions.terms_page_id}
    {assign var="page_title" value=$addons.cp_terms_and_conditions.terms_page_id|fn_cp_terms_and_conditions_get_page_title}
{else}
    {assign var="page_title" value=__("cp_terms_n_conditions_name")}
{/if}
<div class="hidden" id="content_cp_terms_and_conditions_page" title="{$page_title}">
</div>

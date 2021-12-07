{if !$suffix}
    {assign var="suffix" value=""|uniqid}
{/if}

<div class="ty-control-group">
    {include file="addons/cp_terms_and_conditions/common/terms_and_conditions.tpl" suffix=$suffix}
</div>
{hook name="terms_and_conditions:register_post"}{/hook}
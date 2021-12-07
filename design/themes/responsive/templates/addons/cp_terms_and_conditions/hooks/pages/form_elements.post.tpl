{if $page.cp_use_terms_and_conditions == "Y"}
    {$last_element = end($page.form.elements)}
    {if $element_id == $last_element.element_id}
        {if !$suffix}
            {assign var="suffix" value=""|uniqid}
        {/if}
        <div class="ty-control-group">
            {include file="addons/cp_terms_and_conditions/common/terms_and_conditions.tpl" suffix=$suffix}
        </div>
    {/if}
{/if}

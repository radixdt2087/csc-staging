<div class="sidebar-row">
    <form action="{""|fn_url}" method="get" name="search_form">
    <h6>{__("search")}</h6>
        {capture name="simple_search"}
            <input type="hidden" name="selected_section" value="">

            <div class="sidebar-field">
                <label>{__("sd_amz_import_product_from")}</label>
                <select name="marketplace" id="marketplace">
                    {foreach from=$marketplace_list key="code" item="marketplace"}
                        {$marketplace_is_active = "is_active__`$code`"}
                        {if $addons.sd_amazon_products.$marketplace_is_active == "Y"}
                            <option {if $search.code == $code}selected="selected"{/if} value="{$code}">{$marketplace}</option>
                        {/if}
                    {/foreach}
                </select>
            </div>
            
            {include file="common/period_selector.tpl" period=$search.period|default:"D"  form_name="search_form" display="form"}
        {/capture}

        {include file="common/advanced_search.tpl" no_adv_link=true simple_search=$smarty.capture.simple_search not_saved=true dispatch="amazon_orders.manage"}
    </form>
</div>
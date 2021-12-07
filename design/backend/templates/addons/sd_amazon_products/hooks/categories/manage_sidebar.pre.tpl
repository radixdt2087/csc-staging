{capture name="buttons"}
    {capture name="tools_list"}
        <li>{btn type="list" text=__("bulk_category_addition") href="categories.m_add"}</li>
        {if $categories_tree}
            {if !$hide_inputs}
            <li class="divider"></li>
            <li>{btn type="dialog" class="cm-process-items" text=__("edit_selected") target_id="content_select_fields_to_edit" form="category_tree_form"}</li>
            {/if}
            <li>{btn type="delete_selected" dispatch="dispatch[categories.m_delete]" form="category_tree_form" data=["data-ca-confirm-text" => "{__("bulk_category_deletion_side_effects")}"]}</li>
            {assign var="active_marketplaces" value=['us'=>'is_active__us', 'uk'=>'is_active__uk', 'jp'=>'is_active__jp', 'de'=>'is_active__de']}

            {include file="addons/sd_amazon_products/common/multiple_export_import_button.tpl" active_marketplaces=$active_marketplaces mode="m_export_product" action="categories" form_name="category_tree_form" title=__("sd_amz_export_product_on")}
            {include file="addons/sd_amazon_products/common/multiple_export_import_button.tpl" active_marketplaces=$active_marketplaces mode="m_import_product" action="categories" form_name="category_tree_form" title=__("sd_amz_import_product_from")}
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}

    {if $categories_tree}
        {include file="buttons/save.tpl" but_name="dispatch[categories.m_update]" but_role="submit-button" but_target_form="category_tree_form"}
    {/if}
{/capture}

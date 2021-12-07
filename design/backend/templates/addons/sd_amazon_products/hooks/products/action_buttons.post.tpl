{if $products}
    {assign var="active_marketplaces" value=['us'=>'is_active__us', 'uk'=>'is_active__uk', 'jp'=>'is_active__jp', 'de'=>'is_active__de']}

    {include file="addons/sd_amazon_products/common/multiple_export_import_button.tpl" active_marketplaces=$active_marketplaces mode="m_export_product" action="products" form_name="manage_products_form" title=__("sd_amz_export_product_on")}
    {include file="addons/sd_amazon_products/common/multiple_export_import_button.tpl" active_marketplaces=$active_marketplaces mode="m_import_product" action="products" form_name="manage_products_form" title=__("sd_amz_import_product_from")}
{/if}

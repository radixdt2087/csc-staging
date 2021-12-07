{assign var="active_marketplaces" value=['us'=>'is_active__us', 'uk'=>'is_active__uk', 'jp'=>'is_active__jp', 'de'=>'is_active__de']}
{assign var="return_current_url" value=$config.current_url|escape:url}

{include file="addons/sd_amazon_products/common/single_export_import_button.tpl" active_marketplaces=$active_marketplaces return_current_url=$return_current_url mode="export_product" action="products" target="product_id" title=__("sd_amz_export_product_on")}
{include file="addons/sd_amazon_products/common/single_export_import_button.tpl" active_marketplaces=$active_marketplaces return_current_url=$return_current_url mode="import_product" action="products" target="product_id" title=__("sd_amz_import_product_from")}
{if !$runtime.company_id && $product.is_shared_product == "Y"}
    {assign var="show_update_for_all" value=true}
{else}
    {assign var="show_update_for_all" value=false}
{/if}
<td>
    {include file="buttons/update_for_all.tpl" display=$show_update_for_all object_id="youtube_link_`$product.product_id`" name="update_all_vendors[youtube_link][`$product.product_id`]"}
    <input type="text" name="products_data[{$product.product_id}][youtube_link]" size="15" value="{$product.youtube_link}" class="input-hidden input-small cm-no-hide-input">
</td>
<td class="center" data-th="{__("sd_amazon")}">
    <input type="hidden" value="N" name="products_data[{$product.product_id}][amz_synchronization]"/>
    <input type="checkbox" name="products_data[{$product.product_id}][amz_synchronization]" {if $product.amz_synchronization == 'Y'} checked="checked"{/if} value="Y" />
</td>

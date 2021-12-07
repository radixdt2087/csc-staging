{if $addons.ab__deal_of_the_day.amount_of_promos_in_prods_lists && $product.promotions}
    <div class="ab-dotd-promos">
        {$steps_amount = min($addons.ab__deal_of_the_day.amount_of_promos_in_prods_lists, count($product.promotions))}

        {for $i = 0; $i < $steps_amount; $i++}
            <div class="ab-dotd-category-promo" data-ca-promotion-id="{$product.promotions|key}"></div>
            {assign var="tmp" value=$product.promotions|next}
        {/for}
    </div>
{/if}
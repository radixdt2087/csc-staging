{if $cart_products && $addons.cp_checkout_by_vendor.show_cart_on_complete == "Y"}
    <div class="ty-checkout-complete__buttons-right ty-ml-s">
        {if $payment_methods}
            {include file="buttons/proceed_to_checkout.tpl"}
        {/if}
    </div>
{/if}

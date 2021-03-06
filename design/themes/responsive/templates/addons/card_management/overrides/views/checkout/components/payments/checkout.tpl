<style>
.span12.main-content-grid {
    background: #fff;
    padding: 32px;
    margin-top: 32px;
}
.litecheckout__shipping-method__radio:checked + .payment-method-label .litecheckout__shipping-method__wrapper {
    border: solid 1px;
    border-color: #4fbe31;
    box-shadow: 0 0 0 4px rgb(79 190 49 / 20%), 0 0 0 1px #4fbe31;
    background: url(/images/tick.svg) 0px 0px no-repeat #fff;
    width: 20px;
    height: 20px;
}
.different-payment-methods {
    height: auto;
    flex-direction: row;
}
.payment-method-label .litecheckout__shipping-method__wrapper {
    width: 20px;
    height: 20px;
}
.payment-method-text p {
    text-align: left;
    margin-left: 20px;
}
</style>
<div class="litecheckout__group">
    {if $cart.payment_id}
    {foreach $payment_methods as $payment}
        <div class="litecheckout__shipping-method litecheckout__field litecheckout__field--xsmall different-payment-methods">

            <input type="radio"
                   name="selected_payment_method"
                   id="radio_{$payment.payment_id}"
                   data-ca-target-form="litecheckout_payments_form"
                   data-ca-url="checkout.checkout"
                   data-ca-result-ids="litecheckout_final_section,litecheckout_step_payment,shipping_rates_list,litecheckout_terms,checkout*"
                   class="litecheckout__shipping-method__radio cm-select-payment hidden"
                   value="{$payment.payment_id}"
                   {if $payment.payment_id == $cart.payment_id}checked{/if}
            />
            <div class="payment-method-label">
            <label id="payments_{$payment.payment_id}"
                   class="litecheckout__shipping-method__wrapper js-litecheckout-toggle"
                   for="radio_{$payment.payment_id}"
                   data-ca-toggling="payments_form_wrapper_{$payment.payment_id}"
                   data-ca-hide-all-in=".litecheckout__payment-methods"
            >
                {if $payment.image}
                    <div class="litecheckout__shipping-method__logo">
                        {include file="common/image.tpl" obj_id=$payment.payment_id images=$payment.image class="litecheckout__shipping-method__logo-image"}
                    </div>
                {/if}
            </label>
            </div>
            <div class="payment-method-text">
            <p class="litecheckout__shipping-method__title">{$payment.payment}</p>
            <p class="litecheckout__shipping-method__delivery-time">{$payment.description}</p>
        </div>
        </div>
    {/foreach}
    {else}
        <div class="litecheckout__item">
            <p>
                {__("text_no_payments_required")}
            </p>
        </div>
    {/if}
</div>

<div class="litecheckout__group litecheckout__payment-methods">
    {foreach $payment_methods as $payment}
        {if $payment.payment_id != $cart.payment_id}
            {continue}
        {/if}
        <div class="litecheckout__group litecheckout__payment-method"
             data-ca-toggling-by="payments_form_wrapper_{$payment.payment_id}"
             data-ca-hideble="true"
        >
            <input type="hidden" name="payment_id" value="{$payment_id}"/>
            <input type="hidden" name="result_ids" value="{$result_ids}"/>
            <input type="hidden" name="dispatch" value="checkout.place_order"/>
            <input type="hidden" name="customer_notes" value=""/>

            {if $order_id}
                <input type="hidden" name="order_id" value="{$order_id}"/>
            {/if}

            <input type="hidden" name="payment_id" value="{$payment.payment_id}"/>

            {if $payment.template}
                {capture name="payment_template"}
                    {include file=$payment.template card_id=$payment.payment_id}
                {/capture}
            {/if}
            
            {if $payment.instructions}
                <div class="litecheckout__item litecheckout__payment-instructions">
                    {$payment.instructions nofilter}
                </div>
            {/if}

            {if $payment.template && $smarty.capture.payment_template|trim != ""}
                {$smarty.capture.payment_template nofilter}
            {/if}
        </div>
        {if $iframe_mode}
            <div class="ty-payment-method-iframe__box">
                <iframe width="100%" height="700" id="order_iframe_{$smarty.const.TIME}"
                        src="{"checkout.process_payment"|fn_checkout_url:$smarty.const.AREA}"
                        style="border: 0px" frameBorder="0"
                ></iframe>
                {if $is_terms_and_conditions_agreement_required}
                    <div id="payment_method_iframe_{$payment.payment_id}"
                         class="ty-payment-method-iframe"
                    >
                        <div class="ty-payment-method-iframe__label">
                            <div class="ty-payment-method-iframe__text">{__("checkout_terms_n_conditions_alert")}</div>
                        </div>
                    </div>
                {/if}
            </div>
        {/if}
    {/foreach}
</div>

{if "DEVELOPMENT"|defined && $smarty.const.DEVELOPMENT && $auth.act_as_user}
    <div class="litecheckout__group">
        <div class="litecheckout__item">
            <label>
                <input type="checkbox" id="skip_payment" name="skip_payment" value="Y"
                       class="checkbox"
                />
                {__("skip_payment")}
            </label>
        </div>
    </div>
{/if}

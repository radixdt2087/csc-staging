{foreach from=$payments item="payment"}
    {if $payment.company_id}
        <span class="hidden" id="cp_cbv_payment_name_{$payment.payment_id}">({$payment.company_id|fn_get_company_name})</span>
    {/if}
{/foreach}
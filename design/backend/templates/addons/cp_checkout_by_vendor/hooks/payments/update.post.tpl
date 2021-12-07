{if $payment.company_id > 0}
    {assign var="company_id" value=$payment.company_id}
{else}
    {assign var="company_id" value=$runtime.company_id}
{/if}
<input type="hidden" value="{$company_id}" name="payment_data[company_id]">
{if $auth.user_type == 'A' && !$runtime.company_id && $company_id > 0}
<div class="control-group">
    <label class="control-label">{__("vendor")}</label>
    <div class="controls">
        <label class="checkbox">
            {$payment.company_id|fn_get_company_name}
        </label>
    </div>
</div>
{/if}

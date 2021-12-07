{if $auth.user_type == 'A' && !$runtime.company_id}
<div class="control-group">
    <label class="control-label" for="allow_create_payment">{__("cp_allow_create_payment")}</label>
    <div class="controls">
        <label class="checkbox">
            <input type="hidden" name="company_data[allow_create_payment]" value="N" />
            <input type="checkbox" name="company_data[allow_create_payment]" id="allow_create_payment" {if $company_data.allow_create_payment == "Y"}checked="checked"{/if} value="Y" class="" />
        </label>
    </div>
</div>
{/if}
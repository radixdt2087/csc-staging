{if $runtime.controller == 'profiles'}
    {if $runtime.mode == 'add'}
    <div class="ty-account-benefits registration-form-right">
        {__("text_profile_benefits")}
    </div>

    {elseif $runtime.mode == 'update'}
        <div class="ty-account-detail registration-form-right">
            {__("text_profile_details")}
        </div>
    {/if}
{/if}

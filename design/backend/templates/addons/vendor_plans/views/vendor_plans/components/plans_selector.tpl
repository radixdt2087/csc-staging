<ul class="vendor-plans inline cm-vendor-plans-selector">
    <input type="hidden" name="{$name}" class="cm-vendor-plans-selector-value" value="{$current_plan_id}" data-ca-default-plan="{$current_plan_id}" />
    {foreach from=$plans item=plan}
        {$current = $plan.plan_id == $current_plan_id}
        <li class="vendor-plans-item {if $plan.avail_errors}disabled{/if} {if $current}active {/if}" data-ca-plan-id="{$plan.plan_id}">
            {if $current}
                <div class="vendor-plans-status">
                   {if $company_data.plan_status} Cancel {else} {__("vendor_plans.current_plan")} {/if}
                </div>
            <input type="hidden" name="current_plan_price" id="current_plan_price" value="{$plan.price}" />
            <input type="hidden" id="current_plan_periodicity" value="{$plan.periodicity}" />
            {* <input type="hidden" name="exp_date" id="exp_date" value="{strtotime("+1 month",$payment_details.plan_date)}" /> *}
            {elseif $plan.avail_errors}
                <p class="vendor-plans-status not-available">{__("vendor_plans.not_available")}</p>
            {elseif $company_data.downgrade_plan_id && $company_data.downgrade_plan_id == $plan.plan_id}
                <div class="vendor-plans-status">
                    Starts ({$plan_exp_date|date_format:"`$settings.Appearance.date_format`"})
                </div>
            {/if}
            <div class="vendor-plan-content{if $current} vendor-plan-current{/if}">
                {if !$plan.avail_errors && $plan.description}
                    <p class="vendor-plan-descr">{$plan.description nofilter}</p>
                {/if}
                <h3 class="vendor-plan-header">{$plan.plan}</h3>
                {if $plan.avail_errors}
                    <ul class="unstyled">
                        {foreach from=$plan.avail_errors item=error}
                            <li>
                                <span class="text-error">{$error nofilter}</span>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
                
                {strip}
                <span class="vendor-plan-price">
                    {if floatval($plan.price)}
                        {include file="common/price.tpl" value=$plan.price}
                    {else}
                        {__('free')}
                    {/if}
                </span>
                {if $plan.periodicity != 'onetime'}
                    <span class="vendor-plan-price-period">/&nbsp;{__("vendor_plans.{$plan.periodicity}")}</span>
                {/if}
                {/strip}
                
                <div class="vendor-plan-params">
                    <p>
                        {if $plan.products_limit}
                            {__("vendor_plans.products_limit_value", ["[products]" => $plan.products_limit])}
                        {else}
                            {__("vendor_plans.products_limit_unlimited")}
                        {/if}
                    </p>
                    <p>
                        {if floatval($plan.revenue_limit)}
                            {capture name="revenue"}
                                {include file="common/price.tpl" value=$plan.revenue_limit}
                            {/capture}
                            {__("vendor_plans.revenue_up_to_value", ["[revenue]" => $smarty.capture.revenue])}
                        {else}
                            {__("vendor_plans.revenue_up_to_unlimited")}
                        {/if}
                    </p>
                    {if $plan.vendor_store}
                        <p>{__("vendor_plans.vendor_store")}</p>
                    {/if}
                    <p>

                        {$commissionRound = $plan->commissionRound()}
                        {capture name="fee_value"}
                            {if $commissionRound > 0}
                                {$commissionRound}%
                            {/if}
                            
                            {if $plan->fixed_commission > 0.0}
                                {if $commissionRound > 0} + {/if}
                                {include file="common/price.tpl" value=$plan->fixed_commission}
                            {/if}
                        {/capture}

                        {if ($plan->fixed_commission > 0.0) || ($commissionRound > 0)}
                            {__("vendor_plans.transaction_fee_value", [
                                "[value]" => "{$smarty.capture.fee_value nofilter}"
                            ])}
                        {/if}
                    </p>
                </div>
                
            </div>
        </li>
    {/foreach}
</ul>

<script type="text/javascript">
(function(_, $){
    $(document).ready(function(){
            $(document).on('click', '.cm-vendor-plans-selector > li[data-ca-plan-id]:not(.disabled)', function() {
            var card_act = $("#card_action").val();
            if($("#plan_downgrade").val() == 0 && card_act!='Remove' && card_act!='Edit' && card_act!='Add' && card_act!='Default') {
                var container = $('.cm-vendor-plans-selector');
                container.find('li').removeClass('active');
                var plan_id = $(this).addClass('active').data('caPlanId');
                var input = container.find('input.cm-vendor-plans-selector-value');
                input.val(plan_id);

                // Submit buttons
                var buttons = $('.cm-submit[data-ca-target-form="company_update_form"]');
                if (plan_id != input.data('caDefaultPlan')) {
                        /*buttons.addClass('cm-confirm');
                        buttons.data('ca-confirm-text', "{__("vendor_plans.plan_will_be_change_text")|escape:javascript}");*/
                    
                } else {
                    buttons.removeClass('cm-confirm');
                }
            }
           });
        
    });
}(Tygh, Tygh.$));
</script>

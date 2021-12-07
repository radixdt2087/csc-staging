{assign var="plan_price" value=""}
{assign var="payment_frequency" value=""}
{foreach from=$vendor_plans item=cplan}
    {$current = $cplan.plan_id == $company_data.plan_id}
    {if $current}
        {assign var="plan_price" value=$cplan.price}
        {assign var="payment_frequency" value=$cplan.periodicity}
        {break}
    {/if}
{/foreach}
<input type="hidden" id="cancel" value="{$smarty.get.cancel}"/>
<table width="100%" class="table table-middle table--relative table-responsive">
    <thead>
    <tr><th>Name</th><th>Price</th><th>Action</th></tr>
    </thead>
    <tbody>
    <tr><td width="40%"><span class="strong">Plan</span>: {$company_data.plan}</td><td width="20%">{include file="common/price.tpl" value="{$plan_price}"} /
    {if $payment_frequency!='onetime'}{__("vendor_plans.{$payment_frequency}")} {else} One time {/if}</td><td>{if $payment_frequency!='onetime'}
    <a class="btn btn-primary btn-primary cancel" type="plan" href="{"vendor_enrollment.cancel_plan?id=`$company_data.plan_id`"|fn_url}">Cancel</a>{/if} {*&nbsp; <a class="btn btn-primary btn-primary change_billing" type="plan" href="#">Change Billing</a>*}
    </td></tr>
    <tr><td colspan="3" class="strong">Purchased Add-ons</td></tr>
    {foreach from=$current_plan_addons item=plan_addons}
        <tr><td width="40%">{$plan_addons.name}</td><td width="20%">{include file="common/price.tpl" value="{$plan_addons.price}"} /{$plan_addons.payment_frequency}</td><td>{if $plan_addons.payment_frequency!='One time'}<a class="btn btn-primary btn-primary cancel" type="addons" href="{"vendor_enrollment.cancel_addons?id=`$plan_addons.id`"|fn_url}">Cancel</a>{/if}  {*&nbsp; <a class="btn btn-primary btn-primary change_billing" type="plan" href="#">Change Billing</a> *}</td></tr>
    {/foreach}
    </tbody>
</table>
<div>
    <a class="btn btn-primary" href="{"vendor_enrollment.manage&company_id=`$smarty.get.company_id`"|fn_url}">View Billing history</a>
</div>
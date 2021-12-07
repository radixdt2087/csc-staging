<div>
<table width="100%" id="tblcart"><tbody id="cart"></tbody><tfoot class="hide"><tr><td width="60%">&nbsp;</td><td><b>Total</b></td><td class="right">{$currencies.$primary_currency.symbol}<span id="ttotal">0</span></td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>
  <div class="cm-j-tabs cm-track tabs">
    <ul class="nav nav-tabs"><li id="proceedcheckout" class="cm-js"><a class="purchase_addon" onclick="javascript:purchaseAddon();">Proceed to checkout</a></li><li id="goto_addons" class="cm-js active"><a class="goto-addons" onclick="javascript:gotoAddon();">Goto Addons</a></li></ul>
</div>
{* <a class="btn btn-primary purchase_addon" onclick="javascript:purchaseAddon();" > Proceed to checkout </a> &nbsp; <a class="btn btn-primary goto-addons" onclick="javascript:gotoAddon();">Goto Addons</a> *}
</td></tr></tfoot>
</table>
</div>
<input type="hidden" name="currency" id="currency" value="{$currencies.$primary_currency.symbol}"/>
<ul class="vendor-plans inline cm-vendor-plans-selector">
    <input type="hidden" name="company_data[addon_id]" class="cm-vendor-addons-selector-value" value="" data-ca-default-plan="1" />
         <input type="hidden" name="addon_id" id="addon_id" value=""/>
         <input type="hidden" name="buy_now" id="buy_now" value=""/>
         <input type="hidden" name="plan_downgrade" id="plan_downgrade" value="0"/>
         <input type="hidden" name="upgrade_plan" id="upgrade_plan" value="0"/>
         {assign var='exp_date' value = ''}
         {if $plan_details}
            {if $plan_details.periodicity == 'month'}
                {assign var='exp_date' value = strtotime("+1 month",$plan_details.plan_date)}
            {else if $plan_details.periodicity == 'year'}
                {assign var='exp_date' value = strtotime("+1 year",$plan_details.plan_date)}
            {/if}
         {/if}
        <input type="hidden" name="plan_exp_date" id="plan_exp_date" value={$exp_date} />
        <input type="hidden" id="plan_cur_date" value="{$smarty.now}"/>
        <input type="hidden" id="plan_days" name="plan_days" value=""/>
         {foreach from=$addon_data item=item key=key name=name}
            <li class="vendor-plans-item" data-ca-addon-id="{$item.id}">
                <div id="addons_item" onclick="javascript:addon_item(this);" data-ca-addon-id="{$item.id}">
                {foreach from=$addon_details item=addon}{if $addon.addon_id==$item.id}
                    <input type="hidden" id="purchase_addon{$addon.addon_id}" value="1"/>
                    <div class="vendor-plans-status">{if $addon.status == 'Cancel'} Cancel {else} Purchased {/if}</div>{break}{/if}{/foreach}
                    <div class="vendor-plan-content">
                    {if $item.product_img}
                    <div class="vendor-plan-header"><img src="/images/addons/{$item.product_img}" width="60" alt='loading...'/></div>
                    {/if}
                    <p class="vendor-plan-header"><b>{$item.name}</b></p>
                    <div class="vendor-plan-descr">{$item.short_desc|truncate:100}</div>
                    <div class="hidden" id="long_desc" value="{$item.long_desc}"></div>
                    {strip}
                    <span class="vendor-plan-price">
                        {include file="common/price.tpl" value="{$item.price}"}
                    </span>
                    {if $item.payment_frequency}
                        <span class="vendor-plan-price-period">/ {$item.payment_frequency}</span>
                        <input type="hidden" id="frequency{$item.id}" value="{$item.payment_frequency}"/>
                    {/if}
                    {if $item.prorate_charge == 'Yes' && $item.payment_frequency!='One time'}
                        <input type="hidden" id="prorate_charge{$item.id}" value="{$item.price/$plan_details.days|string_format:"%.2f"}"/>
                        <p class="vendor-plan-header">Pro-rated on the first charge</p>
                        <input type="hidden" id="exp_date{$item.id}" value="{$exp_date}"/>
                        <input type="hidden" id="cur_date{$item.id}" value="{$smarty.now}"/>
                    {/if}
                    </div>
                    <input type="hidden" id="product_video" value="{$item.product_video}"/>
                    {/strip}
               </div>
               <div>
                <p><a class="btn btn-primary btn-primary buynow" price="{$item.price}" id="{$item.id}" name="{$item.name}" {if $status} disabled='' {/if}> Buy it now</a></p>
               </div>
        </li>
         {/foreach}
</ul>
<div id="content_addons_details" style="display:none">{include file="addons/vendor_enrollment/views/addons_details.tpl" addon_data=$addon_data}</div>
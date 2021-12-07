{capture name="mainbox"}
<div id="manage_affiliates">
	{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
	{assign var="cols" value="5"}

	<form name="frm_charities_manage" method="post" id="frm_charities_manage">
	{include file="common/pagination.tpl"}
	<table class="table{* sortable*} charity_manage"  width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr>
			<th class="left">
				<a href="{"charities.manage"|fn_url}&sorting=name:{if $sort_by=="name"}{$colation|default:"ASC"}{else}DESC{/if}">
					{__("ch_affiliate_name")}
				</a>
			</th>
			<th class="left">
				<a href="{"charities.manage"|fn_url}&sorting=short_name:{if $sort_by=="short_name"}{$colation|default:"ASC"}{else}ASC{/if}">
					{__("ch_short_name")}
				</a>
			</th>
			<th class="left">
				<a href="{"charities.manage"|fn_url}&sorting=status:{if $sort_by=="status"}{$colation|default:"ASC"}{else}ASC{/if}">
					{__("status")}
				</a>
			</th>
			<th class="left">
				<a href="{"charities.manage"|fn_url}&sorting=timestamp:{if $sort_by=="timestamp"}{$colation|default:"DESC"}{else}DESC{/if}">
					{__("ch_created")}
				</a>
			</th>
			<th class="left">
				<a href="{"charities.manage"|fn_url}&sorting=total_earned:{if $sort_by=="total_earned"}{$colation|default:"DESC"}{else}DESC{/if}">
					{__("ch_total_earned")}
				</a>
			</th>
			<th class="left">
				<a href="{"charities.manage"|fn_url}&sorting=total_paid:{if $sort_by=="total_paid"}{$colation|default:"DESC"}{else}DESC{/if}">
					{__("ch_total_paid")}
				</a>
			</th>
			<th class="left">{__("actions")}</th>
		</tr>
		{foreach from=$affiliates item="affiliate" key="affiliate_id"}
		<input type="hidden" name="affiliates[{$affiliate_id}][affiliate_id]" value="{$affiliate_id}" />
		<input type="hidden" name="affiliates[{$affiliate_id}][name]" value="{$affiliate.name}" />
		<tr>
			<td class="left">
				<a href="{"charities.detail.$affiliate_id"|fn_url}">{$affiliate.name}</a>
			</td>
			<td class="left">
				<input type="text" class="input-medium" name="affiliates[{$affiliate_id}][short_name]" value="{$affiliate.short_name}" />
			</td>
			<td class="left">
				<select id="status" name="affiliates[{$affiliate_id}][status]" class="ch-input-small">
					{foreach from=$affiliate_status_menu key="s_status" item="s_name"}
						<option value="{$s_status}" {if $affiliate.status == $s_status}selected="selected"{/if} >{$s_name}</option>
					{/foreach}
				</select>
			</td>
			<td class="left">
				{$affiliate.timestamp|date_format:"`$settings.Appearance.date_format`"}
			</td>
			<td class="left">
				<span>$</span>
				{if $addons.charities.edit_totals == "Y"}
					<input type="text" class="input-small" name="affiliates[{$affiliate_id}][total_earned]" value="{$affiliate.total_earned}" />
				{else}
					<input type="hidden" name="affiliates[{$affiliate_id}][total_earned]" value="{$affiliate.total_earned}" />
					{$affiliate.total_earned|fn_format_price}
				{/if}
			</td>
			<td class="left">
				<span>$</span>{$affiliate.total_paid}
			</td>
			<td class="left">
				<a href="{"charities.tracking&amp;affiliate_id=`$affiliate_id`&amp;from=1/1/2008"|fn_url}">{__("ch_affiliate_tracking_detail")}</a>
{*
				<br/>
				<a href="{"charities.products&amp;affiliate_id=`$affiliate_id`"|fn_url}">{__("ch_affiliate_products")}</a>
				<br/>
				<a href="{"charities.mark_paid.`$affiliate_id`"|fn_url}"				>{__("ch_mark_as_paid")}</a>
*}
				<br/>
				<a href="{"charities.delete_affiliate.`$affiliate_id`"|fn_url}" onclick="return confirm('{__("ch_delete_affiliate")}')" >{__("delete")}</a>
			</td>
		</tr>
		{/foreach}
		
		{** Totals row **}
		<tr>
			<td><strong>{__("ch_all_affiliates")}</strong></td>
			<td colspan="3">&nbsp;</td>
			<td class="left">
				<span>{$total_commissions|fn_format_price_by_currency:$smarty.const.CART_PRIMARY_CURRENCY}</span>
			</td>
			<td class="left">
				<span>{$total_paid|fn_format_price_by_currency:$smarty.const.CART_PRIMARY_CURRENCY}</span>
			</td>
			<td>&nbsp;</td>
		</tr>
		
		{** Blank row for new input **}
{*		<tr><td colspan="{$cols}">&nbsp;</td></tr>
		<tr><td colspan="{$cols}" class="left">{include file="common/subheader.tpl" title=__("new")|ucwords}</td></tr>
		<tr>
			<td class="left">
				<input type="text" class="input-medium" name="affiliates[0][name]" value="" />
			</td>
			<td class="left">
				<input type="text" class="input-medium" name="affiliates[0][short_name]" value="" />
			</td>
			<input type="hidden" name="affiliates[0][total_earned]" value="0" />
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
*}
	</table>
	{include file="common/pagination.tpl"}
	<br/>
	<div>
		{include file="buttons/button.tpl" 
			but_text=__("save") 
			but_role="submit" 
			but_name="dispatch[charities.manage]" 
		}
	</div>
	</form>
</div>
{/capture}

{capture name="sidebar"}
<div class="sidebar-charities-container">
	<div>
		<p>	<a href="{"charities.tracking"|fn_url}">{__("ch_commission_tracking")}</a> </p>
		<p> <a href="{"charities.payments"|fn_url}">{__("ch_payments")}</a> </p> 
		<div>
			<h5>{__("ch_global_settings")}</h5>
			<form name="ch_defaults_form" id="ch_defaults_form" method="post" >
			<div class="control-group">
				<label for="commission" class="control-label">{__("ch_commission_rate")}:</label>
				<div class="controls">
					<input id="commission" type="text" name="global[commission]" class="input-micro" value="{$global.commission}" /> {__("ch_rate_help")}
				</div>	
			</div>		
			{if $addons.charities.use_default != 'Y' }
				<input type="hidden" name="global[default][affiliate_id]" value="0" />
			{else}	
			<div class="control-group">
				<label for="default" class="control-label">{__("ch_default_charity")}:</label>
				<div class="controls">
					<select id="default" class="ch-input-small" name="global[default][affiliate_id]" >
					{foreach from=$affiliate_menu key="affiliate_id" item="aff_name"}
						<option value="{$affiliate_id}" {if $global.default.affiliate_id == $affiliate_id}selected="selected"{/if}>{$aff_name}</option>
					{/foreach}
					</select>
				</div>	
			</div>	
			{/if} {* use_default *}
			
			{if $addons.charities.use_override != 'Y' }
				<input type="hidden" name="global[override][affiliate_id]" value="0" />
			{else}		
			<div class="control-group">
				<label for="override" class="control-label">{__("ch_override_charity")}:</label>
				<div class="controls">
					<select id="override" class="ch-input-small" name="global[override][affiliate_id]" >
					{foreach from=$affiliate_menu key="affiliate_id" item="aff_name"}
						<option value="{$affiliate_id}" {if $global.override.affiliate_id == $affiliate_id}selected="selected"{/if}>{$aff_name}</option>
					{/foreach}
					</select>
				</div>	
			</div>
			{/if} {* use_override *}			
			<div>
				{include file="buttons/button.tpl" 
					but_text=__("ch_save_globals") 
					but_role="submit" 
					but_name="dispatch[charities.manage]" 
				}
			</div>
			<form>
		</div>
{*
		<p> <a href="{"charities.products"|fn_url}">{__("ch_commission_products")}</a> </p>
*}
	</div>
</div>
{/capture}

{capture name="adv_buttons"}
	{include file="common/tools.tpl" tool_href="charities.detail" prefix="top" hide_tools=true title=__("ch_add_affiliate") icon="icon-plus"}
{/capture}

{include file="common/mainbox.tpl" 
	title=__("ch_manage_affiliates")|ucwords 
	sidebar=$smarty.capture.sidebar
	content=$smarty.capture.mainbox 
	adv_buttons=$smarty.capture.adv_buttons
	content_id="manage_affiliates"
}

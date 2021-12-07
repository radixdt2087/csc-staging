{capture name="mainbox"}
	{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
	{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"} 
	{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}
	{assign var="cols" value="6"}

	
	<table class="table table-middle"  width="100%">
		<thead>
		<tr>
			<th class="left">
				{__("ch_affiliate")}
			</th>			
			<th class="left">
				{__("order_id")|ucwords}
			</th>
			<th class="left">
				{__("ch_order_date")|ucwords}
			</th>
			<th class="left">
				{__("status")|ucwords}
			</th>
			<th class="left">
				{__("ch_order_price")|ucwords}
			</th>
			<th class="left">
				{__("ch_order_commission")|ucwords}
			</th>
		</tr>
		</thead>
		
	{foreach from=$tracking item="tr"}
		<tr>
			<td class="left">
				<a href="{"charities.detail.`$tr.affiliate_id`"|fn_url}">
					{if $tr_search.output != 'csv'}
						    {$affiliate_menu[$tr.affiliate_id]}     {* short_name *}
					{else}
						{$affiliates[$tr.affiliate_id].name}     {* full name *}
					{/if}
					</a>
			</td>
			<td class="left">
				<a href="{"orders.details&amp;order_id=`$tr.order_id`"|fn_url}">
					{$tr.order_id}
				</a>
			</td>
			<td class="left">
				{$tr.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
			</td>
			<td class="left">
				{$tr_status_menu[$tr.status]}
			</td>
			<td class="left">
				{include file="common/price.tpl" value=$tr.order_subtotal}
			</td> 
			<td class="left">
				{include file="common/price.tpl" value=$tr.commission}
			</td> 
		</tr>
	{/foreach}
	{if $tracking}
	    <tr>
	        <td class="left">{__("ch_totals")}</td>
	        <td class="center" colspan="2">{$totals.orders_count}</td>
	        <td class="center" colspan="2">{include file="common/price.tpl" value=$totals.orders_total}</td>
	        <td class="center">{include file="common/price.tpl" value=$totals.total_earned}</td>
	    </tr>
	{/if}
	</table>
{/capture}

{capture name="sidebar"}
<div class="sidebar-charities-container">
	<div>
		<p>	<a href="{"charities.manage"|fn_url}">{__("ch_manage_affiliates")}</a> </p>
{*		<p>	<a href="{"charities.tracking"|fn_url}">{__("ch_commission_tracking")}</a> </p> 
*}
		<p> <a href="{"charities.payments"|fn_url}">{__("ch_payments")}</a> </p> 

	</div>
	<div>
		{include file="common/subheader.tpl" title=__("ch_search_tracking")|ucwords}
		<form name="frm_charities_tracking" method="post" id="frm_charities_tracking" action="{""|fn_url}" >
		<input type="hidden" name="posted" value="1" />
		<fieldset>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_affiliate")}
				</div>
				<div class="control sidebar-charities-input">
					<select name="affiliate_id" class="input-small">
						<option value="">{__("ch_all_affiliates")|ucwords}</option>
					{foreach from=$affiliate_menu key="affiliate_id" item="name"}
						<option value="{$affiliate_id}" {if $tr_search.affiliate_id == $affiliate_id}selected="selected"{/if} >{$name}</option>
					{/foreach}
					</select>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("order_id")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="order_id" value="{$tr_search.order_id}" 
						type="text" class="input-small sidebar-charities-input" 
					/>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_date_from")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="from" value="{$tr_search.from}" 
						type="text" class="input-small sidebar-charities-input" 
					/>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_date_to")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="to" value="{$tr_search.to}" 
						type="text" class="input-small sidebar-charities-input" 
					/>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("status")}
				</div>
				<div class="control sidebar-charities-input">
					<select name="status" class="input-small">
						<option value="">{__("ch_all_status")|ucwords}</option>
					{foreach from=$tr_status_menu key="status" item="name"}
						<option value="{$status}" {if $tr_search.status == $status}selected="selected"{/if} >{$name}</option>
					{/foreach}
					</select>
				</div>
			</div>
{*
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("product_id")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="product_id" value="{$tr_search.product_id}" 
						type="text" class="input-small sidebar-charities-input" 
					/>
				</div>
			</div>
*}
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_output")}
				</div>
				<div class="control sidebar-charities-input">
					<select name="output" class="input-small">
						<option value="screen" selected="selected">{__("ch_output_screen")}</option>
						<option value="csv" >{__("ch_output_csv")}</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_sort_by")}
				</div>
				<div class="control sidebar-charities-input">
					<select name="sort_by" class="input-small">
						{foreach from=$tr_sort_menu key="k" item="name"}
							<option value="{$k}" {if $k == $tr_search.sort_by}selected="selected"{/if}>{$name}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_subtotal_only")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="subtotal_only" value="1" 
						{if $tr_search.subtotal_only }checked="checked"{/if}
						type="checkbox" 
					/>
				</div>
			</div>
		</fieldset>
		<div>
			{include file="buttons/button.tpl" 
				but_text=__("search") 
				but_role="submit" 
				but_name="dispatch[charities.tracking]" 
			}
		</div>
		</form>
	</div>
	<form id="fake" method="get" action="{""|fn_url}">
	{if $tr_search.posted && $tracking && !$tr_search.pay_key}
		<div class="buttons-container wrap">
    	    {assign var="params" value=$tr_search|http_build_query}
			{assign var="but_href" value="?dispatch=charities.mark_paid&`$params`"}
    	    {include file="buttons/button.tpl" 
				but_text=__("ch_mark_as_paid")
				but_role="action"
				but_href=$but_href
    	   }
 		</div>
   {elseif $tr_search.pay_key}
		<div class="buttons-container wrap">
   	     	{include file="buttons/button.tpl" 
				but_text=__("ch_show_payments")
			 	but_role="action"
                but_href="?dispatch=charities.payments&amp;pay_key=`$tr_search.pay_key`" 
			}

		</div>
	{/if}
	</form>
	<div>
		{if $total_commissions}
			<hr/>
			{include file="common/subheader.tpl" title=__("ch_total_commissions") }
			<ul class="no-decorate">
				{foreach from=$total_commissions key="affiliate_id" item="v"}
				<li>{$v.name}&nbsp;-&nbsp;{include file="common/price.tpl" value=$v.commission}</li>
				{/foreach}
			</ul>
		{/if}
	</div>
</div>
{/capture}

{include file="common/mainbox.tpl" 
	title=__("ch_commission_tracking")|ucwords 
	sidebar=$smarty.capture.sidebar
	content=$smarty.capture.mainbox 
}

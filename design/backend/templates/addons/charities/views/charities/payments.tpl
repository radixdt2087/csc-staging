{capture name="mainbox"}
	{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
	{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"} 
	{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}
	{assign var="cols" value="5"}

	
	<table class="table table-middle"  width="100%">
		<thead>
		<tr>
			<th class="left">
				{__("ch_affiliate")}
			</th>			
			<th class="left">
				{__("ch_payment_date")|ucwords}
			</th>
			<th class="left">
			    {__("ch_total_orders")}
			</th>
			<th class="left">
                 {__("ch_orders_total_amount")}
            </th>
			<th class="left">
				{__("ch_payment_amount")|ucwords}
			</th>
		</tr>
		</thead>
		
	    {foreach from=$payments item="pd"}
	    <tr>
	        <td class="left">
	            {if $pay_search.output != 'csv'}
                    <a href="{"charities.tracking&amp;affiliate_id=`$pd.affiliate_id`&amp;pay_key=`$pd.pay_key`"|fn_url}">
                        {$affiliate_menu[$pd.affiliate_id]}      {* short_name *}
                    </a>
	            {else}
	                {$affiliates[$pd.affiliate_id].name}     {* full name *}
                {/if}
	        </td>
	        <td class="left">
	            {$pd.pay_key|date_format:"`$settings.Appearance.date_format`"}
	        </td>
	        <td class="left">
				<a href="{"charities.tracking&amp;pay_key=`$pd.pay_key`&amp;affiliate_id=`$pd.affiliate_id`&amp;posted=1"|fn_url}">
               	 {$pd.orders_count}
				</a>
            </td>
            <td class="left">
                {include file="common/price.tpl" value=$pd.orders_total}
            </td>
	        <td>
	            {include file="common/price.tpl" value=$pd.commission}
	        </td>
	    </tr>
	    {/foreach}
		<tr>
			<td class="left">{__("ch_totals")}</td>
			<td>&nbsp;</td>
			<td class="left">{$totals.orders_count}</td>
			<td class="left">{include file="common/price.tpl" value=$totals.orders_total}</td>
			<td class="left">{include file="common/price.tpl" value=$totals.total_paid}</td>
		</tr>
	</table>
{/capture}

{capture name="sidebar"}
<div class="sidebar-charities-container">
	<div>
		<p>	<a href="{"charities.manage"|fn_url}">{__("ch_manage_affiliates")}</a> </p>
		<p>	<a href="{"charities.tracking"|fn_url}">{__("ch_commission_tracking")}</a> </p> 
{*		<p> <a href="{"charities.payments"|fn_url}">{__("ch_payments")}</a> </p> 
*}
	</div>
	<div>
		{include file="common/subheader.tpl" title=__("ch_search_payments")|ucwords}
		<form name="frm_payments_search" method="post" id="frm_payments_search" action="{""|fn_url}" >
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
						<option value="{$affiliate_id}" {if $pay_search.affiliate_id == $affiliate_id}selected="selected"{/if} >{$name}</option>
					{/foreach}
					</select>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_date_from")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="from" value="{$pay_search.from}" 
						type="text" class="input-small sidebar-charities-input" 
					/>
				</div>
			</div>
			<div class="control-group">
				<div class="sidebar-charities-label">
					{__("ch_date_to")}
				</div>
				<div class="control sidebar-charities-input">
					<input name="to" value="{$pay_search.to}" 
						type="text" class="input-small sidebar-charities-input" 
					/>
				</div>
			</div>
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
		</fieldset>
		<div>
			{include file="buttons/button.tpl" 
				but_text=__("search") 
				but_role="submit" 
				but_name="dispatch[charities.payments]" 
			}
		</div>
		</form>
	</div>
</div>
{/capture}

{include file="common/mainbox.tpl" 
	title=__("ch_payments")|ucwords 
	sidebar=$smarty.capture.sidebar
	content=$smarty.capture.mainbox 
}

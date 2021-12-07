{if $order_info.charities}
	{assign var="ch_show_rate" value=$ch_show_rate|default:true}
	<div class="well orders-right-paine form-horizontal">
		<div class="control-group">
			<div class="control-label">{__("ch_order_commission")}</div>
			<div class="control-label">
				<strong>{$order_info.charities.affiliate.short_name}</strong> {if $ch_show_rate} ({$order_info.charities.commission_rate}){/if}: {include file="common/price.tpl" value=$order_info.charities.commission}
			</div>
		</div>
	</div>
{/if}
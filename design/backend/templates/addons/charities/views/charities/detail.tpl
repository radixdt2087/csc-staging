{capture name="mainbox"}
	{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
	{assign var="cols" value="4"}

	<form action="{""|fn_url}" name="frm_charities_detail" method="post" id="frm_charities_detail" enctype="multipart/form-data">
	<input type="hidden" name="affiliate[affiliate_id]" value="{$affiliate.affiliate_id}" />
	<div class="charities_detail">
		<fieldset>
			<div class="control-group ch-control-group">
				<label for="name" class="control-label cm-required">{__("ch_affiliate_name")}:</label>
				<div class="controls">
					<input type="text" id="name" name="affiliate[name]" class="input-slarge" value="{$affiliate.name}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="short_name" class="control-label">{__("ch_short_name")}:</label>				
				<div class="controls">
					<input type="text" id="short_name" name="affiliate[short_name]" class="input-slarge" value="{$affiliate.short_name}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<input type="hidden" name="affiliate[stored_password]" value="{$affiliate.password}" />
				<label for="password" class="control-label">{__("password")}:</label>				
				<div class="controls">
					<input type="password" id="password" name="affiliate[password]" class="input-slarge" value="{$affiliate.password}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="status" class="control-label">{__("status")}:</label>				
				<div class="controls">
					<select id="status" name="affiliate[status]" class="ch-input-small">
						{foreach from=$affiliate_status_menu key="s_status" item="s_name"}
							<option value="{$s_status}" {if $affiliate.status == $s_status}selected="selected"{/if} >{$s_name}</option>
						{/foreach}
					</select>
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="rate" class="control-label">{__("ch_commission_rate")}:</label>
				<div class="controls">
					<input type="text" id="rate" name="affiliate[rate]" class="ch-input-small" value="{$affiliate.rate|default:$global.commission}" /> {__("ch_rate_help")}
				</div>
			</div>
			<div class="control-group ch-control-group">
				<label for="mission_statement" class="control-label">{__("ch_mission_statement")}:</label>
				<div class="controls">
					<input id="mission_statement" type="text" name="affiliate[mission_statement]" class="input-wide" value="{$affiliate.mission_statement}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="description"  class="control-label">{__("ch_description")}:</label>
				<div class="controls">
					<textarea id="description" name="affiliate[description]" 
						class="cm-wysiwyg input-wide" >{$affiliate.description nofilter}</textarea>
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="contact_name"  class="control-label">{__("ch_contact_name")}:</label>
				<div class="controls">
					<input id="contact_name" type="text" name="affiliate[contact_name]" class="input-slarge" value="{$affiliate.contact_name}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="street" class="control-label">{__("ch_address")}:</label>
				<div class="controls">
					<input id="street" type="text" name="affiliate[street]" class="input-slarge" value="{$affiliate.street}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="street2"  class="control-label">{__("ch_address2")}:</label>
				<div class="controls">
					<input id="street2" type="text" name="affiliate[street2]" class="input-medium" value="{$affiliate.street2}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="city" class="control-label">{__("city")}:</label>
				<div class="controls">
					<input id="city" type="text" name="affiliate[city]" class="input-medium" value="{$affiliate.city}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="state"  class="control-label">{__("state")}:</label>
				<div class="controls">
					<input id="state" maxlength="2" type="text" name="affiliate[state]" class="input-micro" value="{$affiliate.state}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="country"  class="control-label">{__("country")}:</label>
				<div class="controls">
					<input id="country" maxlength="2" type="text" name="affiliate[country]" class="input-micro" value="{$affiliate.country|default:"US"}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="zip_code"  class="control-label">{__("zip_postal_code")}:</label>
				<div class="controls">
					<input id="zip_code" type="text" name="affiliate[zipcode]" class="input-medium" value="{$affiliate.zipcode}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="phone"  class="control-label">{__("phone")}:</label>
				<div class="controls">
					<input id="phone" type="text" name="affiliate[phone]" class="input-medium" value="{$affiliate.phone}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="pp_email"  class="control-label">{__("ch_pp_email")}:</label>
				<div class="controls">
					<input id="pp_email" type="text" name="affiliate[pp_email]" class="input-slarge" value="{$affiliate.pp_email}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="url"  class="control-label">{__("ch_url")}:</label>
				<div class="controls">
					<input id="url" type="text" name="affiliate[url]" class="ch-input-small" value="{$affiliate.url}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="tax_id"  class="control-label">{__("ch_tax_id")}:</label>
				<div class="controls">
					<input id="tax_id" type="text" name="affiliate[tax_id]" class="input-medium" value="{$affiliate.tax_id}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="notes"  class="control-label">{__("notes")}:</label>
				<div class="controls">
					<textarea id="notes" name="affiliate[notes]" class="input-wide">{$affiliate.notes}</textarea>
				</div>				
			</div>
			
			{** Fields added using this hook should be named in the 'extra' array.  
			  * I.e. affiliate[extra][my_new_field]
			 **}
			 {hook name="charities:extra_profile"}{/hook}
			 
			<div class="control-group ch-control-group">
				<label for="seo_name"  class="control-label">{__("ch_friendly_url_name")}:</label>
				<div class="controls">
					<span class="ch_url">{""|fn_url:'C'}{__("ch_charity")|strtolower}-</span>
					<input id="seo_name" type="text" name="affiliate[seo_name]" class="ch-input-small left" value="{$affiliate.seo_name|default:$affiliate.short_name}" />
				</div>				
			</div>
			<div class="control-group ch-control-group">
				<label for="total_earned"  class="control-label">{__("ch_total_earned")}:</label>
				<div class="controls">
				{if $addons.charities.edit_totals != 'N'}
					<input id="total_earned" type="text" name="affiliate[total_earned]" class="ch-input-small" value="{$affiliate.total_earned}" />
				{else}
					<input id="total_earned" type="hidden" name="affiliate[total_earned]" class="input-medium" value="{$affiliate.total_earned}" />
					{include file="common/price.tpl" value=$affiliate.total_earned}
				{/if}
				</div>				
			</div>
			<!-- TB: ch_images -->
			{include file="addons/charities/common/ch_images.tpl" name="affiliate" detail_only=true}
		</fieldset>
	</div>
	<br/>
	<div>
		{include file="buttons/button.tpl" 
			but_text=__("save") 
			but_role="submit" 
			but_name="dispatch[charities.detail]" 
		}
	</div>
	</form>
	<form action="{""|fn_url}" method="get" id="dummy_form">
	</form>
{/capture}

{capture name="sidebar"}
<div class="sidebar-charities-container">
	<div>
		<p>	<a href="{"charities.manage"|fn_url}">{__("ch_manage_affiliates")}</a> </p>
		<p>	<a href="{"charities.tracking"|fn_url}">{__("ch_commission_tracking")}</a> </p>
{*		<p> <a href="{"charities.products"|fn_url}">{__("ch_commission_products")}</a> </p> 
*}
	</div>
</div>
{/capture}

{if $affiliate.affiliate_id}
	{capture name="buttons"}
		{capture name="tools_items"}
			<li>{btn type="list" 
				dispatch="dispatch[charities.delete_affiliate.`$affiliate.affiliate_id`]" 
				text={__("ch_delete_affiliate")}
				form="dummy_form"
				}
			</li>
		{/capture}
		<div class="hidden-tools">
			{dropdown content=$smarty.capture.tools_items}
		</div>
	{/capture}
{/if}

{include file="common/mainbox.tpl" 
	title=__("ch_affiliate_detail")|ucwords 
	sidebar=$smarty.capture.sidebar
	content=$smarty.capture.mainbox 
	buttons=$smarty.capture.buttons
	content_id="charities_detail"
}

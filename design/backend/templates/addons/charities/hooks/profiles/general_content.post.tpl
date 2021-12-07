<!-- hook: charities: profiles/general_content.post.tpl -->
<div class="control-group">
    <label for="affiliate_id" class="control-label">{__("ch_select_charity")}:</label>
    <div class="controls">
		<select name="user_data[charities][affiliate_id]" id="affiliate_id">
			<option value="0">{__("ch_select_charity")}</option>
		{foreach from=$affiliate_menu key="affiliate_id" item="v"}
			<option value="{$affiliate_id}" {if $user_data.charities && $user_data.charities.affiliate_id == $affiliate_id}selected="selected"{/if} >{$v}</option>
		{/foreach}
		</select>
    </div>
</div>
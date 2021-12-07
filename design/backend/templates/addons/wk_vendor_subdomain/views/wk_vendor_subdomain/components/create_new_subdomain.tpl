<div class="hidden"  id="content_vendor_subdomain" title="{__("add_subdomain_title")}">
    <form action="{""|fn_url}" class="form-horizontal form-edit" method="post" name="add_subdomain_form" >
	<div style="padding:10px;">
        <div class="control-group">
		    <label class="control-label cm-required" for="elm_vendor">{__('vendor')}</label>
		    <div class="controls">
                {$companyIds = db_get_fields("SELECT company_id FROM ?:wk_vendor_subdomain")}
                {assign var="vendors" value=fn_get_short_companies()}
                <select name="company_id" id="elm_vendor">
                    <option value="">--</option>
                    {foreach from=$vendors key="seller_id" item="seller"}
                    {if $seller_id && !in_array($seller_id,$companyIds)}
                    <option value="{$seller_id}">{$seller}</option>
                    {/if}
                    {/foreach}
                </select>
            </div>
        </div>
		<div class="control-group">
			<label class="control-label cm-required" for="elm_subdomain">{__('subdomain')}</label>
			<div class="controls">
			<span>{if $smarty.const.HTTPS}{"https://"}{else}{"http://"}{/if}</span>&nbsp;&nbsp;<input  id="elm_subdomain" type="text"  name="subdomain" value=""/>&nbsp;<span>.{$config.current_host}</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="elm_status">{__('status')}</label>
			<div class="controls">
			<select name="status" id="elm_status">
                <option value="A">{__('active')}</option>
                <option value="D">{__('disabled')}</option>
            </select>
			</div>
		</div>
        <div class="buttons-container buttons-container-picker">
			{assign var="but_label" value={__("add_subdomain")}}
            {include file="buttons/button.tpl"  but_text=__("close") but_role="close"   but_target_form="add_subdomain_form"  but_meta="btn cm-dialog-closer"}
			{include file="buttons/button.tpl"  but_text=$but_label but_role="submit" but_name="dispatch[wk_vendor_subdomain.create_new]"  but_target_form="add_subdomain_form" but_id="add_form_submit" but_meta="btn btn-primary cm-submit "}

		</div>
    </div>
    </form>


<!--content_{$id}--></div>

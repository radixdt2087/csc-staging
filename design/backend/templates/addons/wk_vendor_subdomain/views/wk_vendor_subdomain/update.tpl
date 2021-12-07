{$id = "vendor_subdomain_{$company_id}"}
<div class="hidden"  id="content_{$id}">
    <form action="{""|fn_url}" class="form-horizontal form-edit" method="post" name="update_subdomain_form_{$company_id}" >
	<div style="padding:10px;">
		<input type="hidden" name="company_id" value="{$company_id}" />
		{if !$company_id}
            <div class="control-group">
			<label class="control-label cm-required" for="elm_company_data_{$company_id}">{__('vendor')}</label>
			<div class="controls">
            {assign var="vendors" value=fn_get_short_companies()}
            <select name="company_id" id="elm_company_data_{$company_id}">
                <option value="">--</option>
                {foreach from=$vendors key="seller_id" item="seller"}
                {if $seller_id}
                <option value="{$seller_id}">{$seller}</option>
                {/if}
                {/foreach}
            </select>
            </div>
        {else}
        <div class="control-group">
			<label class="control-label cm-required" for="elm_company_name_{$company_id}">{__('vendor_name')}</label>
			<div class="controls">
			<input  id="elm_company_name_{$company_id}" type="text"  name="company_name" value="{fn_get_company_name($company_id)}" readonly/>
			</div>
		</div>
        {/if}
		<div class="control-group">
			<label class="control-label cm-required" for="elm_subdomain_{$company_id}">{__('subdomain')}</label>
			<div class="controls">
            <span>{if $smarty.const.HTTPS}{"https://"}{else}{"http://"}{/if}</span>&nbsp;&nbsp;<input  id="elm_subdomain_{$company_id}" type="text"  name="subdomain" value="{$data.subdomain}"/>&nbsp;<span>.{$config.current_host}</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="elm_status_{$company_id}">{__('status')}</label>
			<div class="controls">
			<select name="status" id="elm_status_{$company_id}">
                <option value="A" {if $data.status == 'A'}selected{/if}>{__('active')}</option>
                <option value="D" {if $data.status == 'D'}selected{/if}>{__('disabled')}</option>
            </select>
			</div>
		</div>

        <div class="buttons-container buttons-container-picker">
			{assign var="but_label" value={__("update")}}
            {include file="buttons/button.tpl"  but_text=__("close") but_role="close"   but_target_form="add_subdomain_form_0"  but_meta="btn cm-dialog-closer"}
			{include file="buttons/button.tpl"  but_text=$but_label but_role="submit" but_name="dispatch[wk_vendor_subdomain.create_new]"  but_target_form="update_subdomain_form_`$company_id`" but_id="update_form_submit" but_meta="btn btn-primary cm-submit "}
		</div>
    </div>
    </form>
<!--content_{$id}--></div>

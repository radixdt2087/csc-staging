{if fn_is_vendor_subdomain_allowed($smarty.request.company_id)}
{include file="common/subheader.tpl" title=__("vendor_subdomain")}
<div class="control-group">
    <label class="control-label" for="elm_company_subdomain">{__("subdomain")}:</label>
    <div class="controls">
    	<span>{if $smarty.const.HTTPS}{"https://"}{else}{"http://"}{/if}</span>&nbsp;&nbsp;<input type="text" class="input-" name="company_data[subdomain]" id="elm_company_subdomain" size="32" value="{$subdomain}" {if $subdomain} readonly {/if}/>&nbsp;<span>.{$config.current_host}</span>{if $subdomain}&nbsp;&nbsp;&nbsp;&nbsp;{if $sub_domain_status=='A'}<span class="label btn-info o-status-s">{__("active")}</span>{else}<span class="label btn-info o-status-f">{__("disabled")}</span>{/if}{/if}&nbsp;&nbsp;&nbsp;&nbsp;<a id="edit_subdomain">{__("edit")}</a>
    </div>
</div>
{/if}
<script>
$('#edit_subdomain').on('click',function(){
    if($('#elm_company_subdomain').prop('readonly')){
        $('#elm_company_subdomain').prop('readonly',false);
    }else{
        $('#elm_company_subdomain').prop('readonly',true);
    }
});
</script>

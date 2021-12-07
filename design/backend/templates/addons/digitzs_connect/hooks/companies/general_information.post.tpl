 {if $permission == 'access'}
{include file="common/subheader.tpl" title="Digitzs Vendor details"}
{if isset($cdata.digitzs_connect_account_id) && $cdata.digitzs_connect_account_id!=''}
<div class="control-group">
    <label class="control-label" for="merchant_id">Merchant Id</label>
    <div class="controls">
        <input type="text" disabled id="merchant_id"  name="company_data[connect]" size="32" value="{$cdata.digitzs_connect_account_id}" >
    </div>
</div>
{else}
<div class="control-group">
    <label class="control-label" for="digitzs_account">Digitzs Account</label>
    <div class="controls">
        <input type="button" id="connect" class="cm-js btn btn-primary cm-submit btn-primary" value="Connect"/>
    </div>
</div>
{/if}
<input type="hidden" value="{$smarty.get.m}" id="mdata"/>
{/if}
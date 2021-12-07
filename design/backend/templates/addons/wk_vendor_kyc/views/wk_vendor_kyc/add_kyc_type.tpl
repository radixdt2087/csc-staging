<form  action="{""|fn_url}" method="post" name="add_kyc_type_form" class="form-horizontal form-edit  cm-disable-empty-files" enctype="multipart/form-data" id="add_kyc_type_form">

<div style="margin:0 auto;display:table;padding:10px 80px 0px 0px;margin-top:5%; background:#EFEFEF;">
    <input type="hidden" name="wk_vendor_kyc[kyc_id]" value="{$wk_vendor_kyc_type_data.kyc_id}" />
        <div class="control-group">
            <label class="control-label cm-required" for="elm_kyc_type">{__("kyc_type")}</label>
            <div class="controls">
                <input type="text" name="wk_vendor_kyc[kyc_type]" id="elm_kyc_type" value="{if $wk_vendor_kyc_type_data.description}{$wk_vendor_kyc_type_data.description}{/if}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label " for="elm_is_required">{__("is_required")}</label>
            <div class="controls">
                <input type="checkbox" name="wk_vendor_kyc[is_required]" id="elm_is_required" value="Y" {if $wk_vendor_kyc_type_data.is_required =='Y'}checked='checked'{/if}/>
            </div>
        </div>
     <div class="control-group">
         <div class="controls display_none right" id="debit_but">
            {include file="buttons/button.tpl" but_meta="cm-tab-tools" but_text=__("add") but_role="submit-link" but_target_form="add_kyc_type_form" but_name="dispatch[wk_vendor_kyc.add_kyc_type]" save=true}
        </div>  
    </div> 
</div>   
</form>



{capture name="edit_kyc_type"}
{include file="common/subheader.tpl" title=__("information") target="#add_update_kyc_type"}
<div id="add_update_kyc_type">
    <form  action="{""|fn_url}" method="post" name="edit_kyc_type_form" class="form-horizontal form-edit  cm-disable-empty-files" enctype="multipart/form-data" id="edit_kyc_type_form">
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
    </form>
</div>
{/capture}

{capture name="buttons"}
    {include file="buttons/save.tpl" but_name="dispatch[wk_vendor_kyc.add_kyc_type]" but_role="submit-link" but_target_form="edit_kyc_type_form"}
{/capture}
{include file="common/mainbox.tpl" title={__('edit_kyc_type')} content=$smarty.capture.edit_kyc_type  buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar adv_buttons=$smarty.capture.adv_buttons select_languages=true}


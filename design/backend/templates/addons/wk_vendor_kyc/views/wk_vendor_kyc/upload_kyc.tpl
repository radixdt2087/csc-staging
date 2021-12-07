
{if $kyc_data.kyc_id}
    {assign var="id" value=$kyc_data.kyc_id}    
{else}
    {assign var="id" value="0"}
{/if}
{capture name="wk_upload_kyc"}
<form action="{""|fn_url}" method="post" class="form-horizontal form-edit  {$hide_inputs}" name="wk_upload_kyc_form" enctype="multipart/form-data">
{include file="common/subheader.tpl" title=__("information") target="#upload_information"}
<div id="upload_information">
    <input type="hidden" name="kyc_id" value="{$id}" />
    <input type="hidden" name="fake" value="1" />
    <input type="hidden" name="object_id" value="{$object_id}" />
    <input type="hidden" name="object_type" value="{$object_type}" />
    <input type="hidden" name="kyc_id" value="{$wk_vendor_kyc_data.kyc_id}" />
    {if $description_string}
        <div class="control-group control-group1">
            <div class="controls">
                <p style="color:red"><b>{__("note")} : </b>{__("upload_mandatory_kyc")}</p>
                <ul style="color:red">
                    {foreach from =$description_string item=mandatory_type}
                        <li>{$mandatory_type}</li>
                    {/foreach}
                </ul>
            </div>  
        </div>
    {/if}
    {include file="views/companies/components/company_field.tpl"
        name="upload_kyc_data[company_id]"
        id="product_data_company_id"
        selected=$wk_vendor_kyc_data.company_id
        tooltip=$companies_tooltip
        js_action=$js_action
    }
    <div class="control-group control-group1">
        <label class="control-label" for="elm_company_kyc_type">{__("kyc_type")}:{include file="common/tooltip.tpl" tooltip={__("kyc_type_tooltip")}}</label>
        <div class="controls">
            <select id="elm_company_kyc_type" name="upload_kyc_data[kyc_type]" class="span4">
            {foreach from =$wk_vendor_kyc_types item=kyc_type}
                <option value="{$kyc_type.kyc_id}">{$kyc_type.description}</option>
            {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group ">
        <label class="control-label cm-required" for="elm_company_kyc_name">{__("kyc_name")}:{include file="common/tooltip.tpl" tooltip={__("kyc_name_tooltip")}}</label>
        <div class="controls">
            <input id="elm_company_kyc_name" name="upload_kyc_data[kyc_name]" value="{$wk_vendor_kyc_data.kyc_name}" class="input-large"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_company_kyc_id">{__("kyc_id_uan")}:{include file="common/tooltip.tpl" tooltip={__("kyc_id_uan_tooltip")}}</label>
        <div class="controls">
            <input id="elm_company_kyc_id" name="upload_kyc_data[kyc_id]" value="{$wk_vendor_kyc_data.kyc_id_number}" class="input-large"/>
        </div>
    </div>
    <div class="control-group">
        <label for="type_{"kyc_attach_file[`$id`]"|md5}" class="control-label {if !$wk_vendor_kyc_data}cm-required{/if}">{__("kyc_file")}:</label>
        <div class="controls" id="box_new_kyc">
            {if $wk_vendor_kyc_data.kyc_file}
                <div class="text-type-value">
                    {foreach from =$wk_vendor_kyc_data.kyc_file key= file_id item=kyc_type}
                     <div><a class="icon-remove-sign cm-tooltip cm-confirm cm-post" href="{"wk_vendor_kyc.delete_file?file_id=`$file_id`&kyc_id=`$wk_vendor_kyc_data.kyc_id`"|fn_url}" title="{__("delete")}"></a>
                     <a href="#">{$kyc_type}</a> </div>
                    {/foreach}
                </div>
            {/if}
            {include file="common/fileuploader.tpl" var_name="kyc_attach_file[`$id`]" multiupload=true}
        </div>
    </div>
</div>
</form>
{/capture}
{capture name="buttons"}
{if $wk_vendor_kyc_types}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[wk_vendor_kyc.upload_kyc]" but_target_form="wk_upload_kyc_form" save=$wk_vendor_kyc_data.kyc_id}
{/if}
{/capture}
{include file="common/mainbox.tpl" title={__('upload_kyc')} content=$smarty.capture.wk_upload_kyc  buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar adv_buttons=$smarty.capture.adv_buttons select_languages=true}

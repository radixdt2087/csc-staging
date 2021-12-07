{capture name="mainbox"}
{capture name="tabsbox"}
{if $label_setting.id}
{assign var=id value=$label_setting.id}
{else}
{assign var=id value=0}
{/if}
<form class="form-horizontal form-edit {if !fn_check_view_permissions("wk_order_tracking.update", "POST")}cm-hide-inputs{/if} {if !$id}cm-comet cm-disable-check-changes{/if}" action="{""|fn_url}" method="post" id="wk_otp_update_form" enctype="multipart/form-data">
        {** General info section **}
        {include file="common/subheader.tpl" title=__("otp_general_info") target="#general_info"}
        <div id="general_info">
        <input type="text" value="{$id}" class="hidden" name="id">
        <div class="control-group">
            <label for="wk_elm_title" class="control-label cm-required">{__("otp_state")}{include file="common/tooltip.tpl" tooltip="{__('otp_state_title_tooltip')}"}:</label>
            <div class="controls">
                <input type="text" id="wk_elm_title" maxlength="30" onkeydown="alphaOnly();" name="label_setting[title]" id="wk_elm_title" size="5" value="{$label_setting.title}" class="input-large" />
            </div>
        </div>
        <div class="control-group">
            <label for="wk_elm_position" class="control-label cm-required">{__("position")}{include file="common/tooltip.tpl" tooltip="{__('otp_position_tooltip')}"}:</label>
            <div class="controls">
                <input type="number" name="label_setting[position]" id="wk_elm_position" size="5" value="{$label_setting.position}" class="input-large" />
            </div>
        </div>
        <div class="control-group">
            <label for="wk_elm_description" class="control-label">{__("description")}{include file="common/tooltip.tpl" tooltip="{__('otp_description_tooltip')}"}:</label>
            <div class="controls">
                <textarea id="wk_elm_description" name="label_setting[description]" cols="55" rows="2" class="input-large">{$label_setting.description}</textarea>
            </div>
        </div>
	
	<div class="control-group">
            <label for="wk_elm_state_ad" class="control-label">{__("active/disabled")}{include file="common/tooltip.tpl" tooltip="{__('otp_state_ad_tooltip')}"}:</label>
            <div class="controls">
                <input type="radio" id="wk_elm_state_ad1" name="label_setting[state_ad]" value="1">{__('check_active')}</input>

                <input type="radio" id="wk_elm_state_ad2" name="label_setting[state_ad]" value="2" >{__('check_disabled')}</input>
            </div>
        </div>

        <div class="control-group">
            <label for="wk_elm_statuses" class="control-label cm-required cm-failed-label">{__("selected_status")}{include file="common/tooltip.tpl" tooltip="{__('otp_selected_status_tooltip')}"}:</label>           
            <input type="hidden" name="label_setting[select_status][{$id}]"  value="" class="cm-failed-field"/>
            <div class="controls">
                <select id="wk_elm_statuses"
                            
                            class="cm-object-selector"
                            name="label_setting[select_status][]"
                            data-ca-enable-images="false"
                            data-ca-image-width="30"
                            data-ca-image-height="30"
                            data-ca-enable-search="false"
                            data-ca-placeholder="-{__("none")}-"
                            data-ca-allow-clear="true" style="width:100%;">
                        {foreach from=$statuses item="status"}
                            <option value="{$status.id}"{if $status.selected} selected="selected"{/if}>{$status.name}</option>
                        {/foreach}
                </select>
            </div>
        </div>
        </div>
        {** /General info section **}
        {** Icons info section **}
        {include file="common/subheader.tpl" title=__("otp_icons_section") target="#icons_section"}
        <div id="icons_section">
        <div class="control-group">
            <label for="wk_elm_icon_at_activate" class="control-label cm-required"></label>
            <div class="controls">
                {include file="common/attach_images.tpl" image_name="wk_otp_activated_icon" image_object_type="wk_otp_activated_icon" image_pair=$label_setting.activate_icon no_detailed=true hide_titles=false hide_alt=true icon_text="{__('icon_at_activate_text')}" icon_title="{__('icon_at_activate_title')}"}
            </div>
        </div>
        <div class="control-group">
            <label for="wk_elm_icon_at_deactivate" class="control-label cm-required"></label>
            <div class="controls">
                {include file="common/attach_images.tpl" image_name="wk_otp_deactivated_icon" image_object_type="wk_otp_deactivated_icon" image_pair=$label_setting.deactivate_icon no_detailed=true hide_titles=false hide_alt=true icon_text="{__('icon_at_deactivate_text')}" icon_title="{__('icon_at_deactivate_title')}"}
            </div>
        </div>
    	{** /Icons info section **}
</form>
{/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name="companies" active_tab=$smarty.request.selected_section track=true}
{/capture}
{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[wk_order_tracking.update.add]" but_target_form="wk_otp_update_form" but_meta="cm-comet" save=$id}
{/capture}
{if $_REQUEST['id']}
    {include file="common/mainbox.tpl" title=__('label_update') content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar}
{else}
    {include file="common/mainbox.tpl" title=__('label_add') content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar}
{/if}

<script>
var i='{$label_setting.statusad}';

if(i=='2'){
$('#wk_elm_state_ad2').prop("checked",true);
}
else{
$('#wk_elm_state_ad1').prop("checked",true);
}
function alphaOnly(){
    var title = document.getElementById('wk_elm_title').value;
    var letters = '/^[a-zA-Z]+$/';
    for(var i=0;i<=title.length;i++){
        if(!isNaN(title[i]) && title[i]!=' '){
          return(document.getElementById('wk_elm_title').style.border = "1px solid red");
        }
    }
    document.getElementById('wk_elm_title').style.border = "";
}
</script>


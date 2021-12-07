<style type="text/css">
  .control-group1{
    margin-left: 130px;
  }
</style>
{assign var=wk_vendor_status value=fn_get_predefined_statuses('companies')}
<div class="control-group control-group1">
  <label class="control-label">{__("vendor_status_from")}:{include file="common/tooltip.tpl" tooltip={__("vendor_status_from_tooltip")}}</label>
    <div class="controls">
        <select name="wk_vendor_kyc[vendor_status_from]" id="vendor_status">
            {foreach from=$wk_vendor_status key=status item=vendor_status}
                <option value="{$status}" {if ($wk_vendor_kyc_settings_data.vendor_status_from)==$status}selected{/if}>{$vendor_status}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="control-group control-group1">
  <label class="control-label">{__("upload_kyc_within_timeframe")}:{include file="common/tooltip.tpl" tooltip={__("upload_kyc_within_timeframetooltip")}}</label>
  <label style="float:left;margin-left:50px;">{__("days")}</label>
  <label style="float:left;margin-left:55px;">{__("hour")}</label>
  <label style="float:left;margin-left:55px;">{__("minitues")}</label>
  <div class="controls" style="clear:left;">
      <select class="input-small" name="wk_vendor_kyc[days]">
          {section name="days" loop=51}
              <option value="{$smarty.section.days.index}" {if $wk_vendor_kyc_settings_data.days == $smarty.section.days.index}selected="selected"{/if}>
              {$smarty.section.days.index}
              </option>
          {/section}
      </select>
      <select class="input-small" name="wk_vendor_kyc[hours]">
              {section name="hours" loop=24}
                  <option value="{$smarty.section.hours.index}" {if $wk_vendor_kyc_settings_data.hours == $smarty.section.hours.index}selected="selected"{/if}>
                  {$smarty.section.hours.index}
                  </option>
              {/section}
      </select>
      <select class="input-small" name="wk_vendor_kyc[minute]">
              {section name="minute" loop=60}
                  <option value="{$smarty.section.minute.index}" {if $wk_vendor_kyc_settings_data.minute ==$smarty.section.minute.index}selected="selected"{/if}>
                  {$smarty.section.minute.index}
                  </option>
              {/section}
      </select>
  </div>
</div>
{assign var=wk_vendor_status value=fn_get_predefined_statuses('companies')}
<div class="control-group control-group1">
  <label class="control-label">{__("vendor_status")}:{include file="common/tooltip.tpl" tooltip={__("vendor_status_tooltip")}}</label>
    <div class="controls">
        <select name="wk_vendor_kyc[vendor_status]" id="vendor_status">
            {foreach from=$wk_vendor_status key=status item=vendor_status}
                <option value="{$status}" {if ($wk_vendor_kyc_settings_data.vendor_status)==$status}selected{/if}>{$vendor_status}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="control-group control-group1">  
    <label class="control-label">{__("vendor_kyc_upload_notify_to")}{include file="common/tooltip.tpl" tooltip={__("vendor_kyc_upload_notify_to_notify_tooltip")}}:</label>
    <div class="controls">
      <label class="checkbox inline" for="elm_id_admin"><input type="checkbox" id="elm_id_admin" name="wk_vendor_kyc[ch_admin]" value="y" {if ($wk_vendor_kyc_settings_data.ch_admin)=="y"}checked="checked"
       {/if}/>{__("admin")}</label><br>
    </div>
</div>



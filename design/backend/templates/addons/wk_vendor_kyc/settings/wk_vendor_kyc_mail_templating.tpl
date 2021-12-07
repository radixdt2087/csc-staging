<style type="text/css">
  .control-group1{
    margin-left: 130px;
  }
</style>
<div class="control-group control-group1">
      <label class="control-label for="elm_review_mail_text_guide"">{__("user_guide")}:{include file="common/tooltip.tpl" tooltip={__("user_guide_tooltip")}}</label>
          <div class="controls">            
              <textarea readonly id="elm_review_mail_text_guide" name="product_data[g_review_mail_text_guide]" rows="10" cols="55" class="input-large">{__("wk_vendor_kyc_user_guide")}
                 </textarea>
          </div>
      </div>  
<div class="control-group1">
 {include file="common/subheader.tpl" title=__("wk_vendor_upload_kyc_request") target="#wk_vendor_upload_kyc_request"}
</div>
<div id="wk_vendor_upload_kyc_request">
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_request_sub")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_request_sub_tooltip")}}</label>  
      <div class="controls">
        <input name="wk_vendor_kyc[upload_mail_sub]" value="{$wk_vendor_kyc_settings_data.upload_mail_sub}"{if $wk_vendor_kyc_settings_data.upload_mail_sub}{/if}/>
      </div>
    </div> 
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_request_mail_text")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_request_mail_text_tooltip")}}</label>  
      <div class="controls">
        <textarea cols="55" rows="20" name="wk_vendor_kyc[upload_mail_text]" class="cm-wysiwyg">{if !empty($wk_vendor_kyc_settings_data.upload_mail_text)}{$wk_vendor_kyc_settings_data.upload_mail_text}{/if}
        </textarea>
        <p>{include file="buttons/button.tpl" but_text=__("preview") but_name="dispatch[wk_vendor_kyc.preview_html_upload_request]" but_meta="cm-new-window"}</p>
      </div>
    </div> 
</div>

<div class="control-group1">
 {include file="common/subheader.tpl" title=__("wk_vendor_upload_kyc_notify_admin") target="#wk_vendor_upload_kyc_notify_admin"}
</div>
<div id="wk_vendor_upload_kyc_notify_admin">
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_admin_notify_sub")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_admin_notify_sub_tooltip")}}</label>  
      <div class="controls">
        <input name="wk_vendor_kyc[notify_admin_mail_sub]" value="{$wk_vendor_kyc_settings_data.notify_admin_mail_sub}"{if $wk_vendor_kyc_settings_data.notify_admin_mail_sub}{/if}/>
      </div>
    </div> 
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_admin_notify_mail_text")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_admin_notify_mail_text_tooltip")}}</label>  
      <div class="controls">
        <textarea cols="55" rows="20" name="wk_vendor_kyc[admin_notify_mail_text]" class="cm-wysiwyg">{if !empty($wk_vendor_kyc_settings_data.admin_notify_mail_text)}{$wk_vendor_kyc_settings_data.admin_notify_mail_text}{/if}
        </textarea>
        <p>{include file="buttons/button.tpl" but_text=__("preview") but_name="dispatch[wk_vendor_kyc.preview_html_admin_notify]" but_meta="cm-new-window"}</p>
      </div>
    </div> 
</div>

<div class="control-group1">
 {include file="common/subheader.tpl" title=__("wk_vendor_upload_kyc_accept") target="#wk_vendor_upload_kyc_accept"}
</div>
<div id="wk_vendor_upload_kyc_accept">
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_accept_sub")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_accept_sub_tooltip")}}</label>  
      <div class="controls">
        <input name="wk_vendor_kyc[accept_mail_sub]" value="{$wk_vendor_kyc_settings_data.accept_mail_sub}"{if $wk_vendor_kyc_settings_data.accept_mail_sub}{/if}/>
      </div>
    </div> 
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_accept_mail_text")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_accept_mail_text_tooltip")}}</label>  
      <div class="controls">
        <textarea cols="55" rows="20" name="wk_vendor_kyc[accept_mail_text]" class="cm-wysiwyg">{if !empty($wk_vendor_kyc_settings_data.accept_mail_text)}{$wk_vendor_kyc_settings_data.accept_mail_text}{/if}
        </textarea>
        <p>{include file="buttons/button.tpl" but_text=__("preview") but_name="dispatch[wk_vendor_kyc.preview_html_accept_kyc]" but_meta="cm-new-window"}</p>
      </div>
    </div> 
</div>
<div class="control-group1">
 {include file="common/subheader.tpl" title=__("wk_vendor_upload_kyc_reject") target="#wk_vendor_upload_kyc_reject"}
</div>
<div id="wk_vendor_upload_kyc_reject">
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_reject_sub")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_reject_sub_tooltip")}}</label>  
      <div class="controls">
        <input name="wk_vendor_kyc[reject_mail_sub]" value="{$wk_vendor_kyc_settings_data.reject_mail_sub}"{if $wk_vendor_kyc_settings_data.reject_mail_sub}{/if}/>
      </div>
    </div> 
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_upload_kyc_reject_mail_text")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_upload_kyc_reject_mail_text_tooltip")}}</label>  
      <div class="controls">
        <textarea cols="55" rows="20" name="wk_vendor_kyc[reject_mail_text]" class="cm-wysiwyg">{if !empty($wk_vendor_kyc_settings_data.reject_mail_text)}{$wk_vendor_kyc_settings_data.reject_mail_text}{/if}
        </textarea>
        <p>{include file="buttons/button.tpl" but_text=__("preview") but_name="dispatch[wk_vendor_kyc.preview_html_reject_kyc]" but_meta="cm-new-window"}</p>
      </div>
    </div> 
</div>

<div class="control-group1">
 {include file="common/subheader.tpl" title=__("wk_vendor_kyc_validity_period_expire") target="#wk_vendor_kyc_validity_period_expire"}
</div>
<div id="wk_vendor_kyc_validity_period_expire">
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_kyc_validity_period_expire_sub")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_kyc_validity_period_expire_sub_tooltip")}}</label>  
      <div class="controls">
        <input name="wk_vendor_kyc[exp_mail_sub]" value="{$wk_vendor_kyc_settings_data.exp_mail_sub}"{if $wk_vendor_kyc_settings_data.exp_mail_sub}{/if}/>
      </div>
    </div> 
    <div class="control-group control-group1">
      <label class="control-label">{__("wk_vendor_kyc_validity_period_expire_mail_text")}:{include file="common/tooltip.tpl" tooltip={__("wk_vendor_kyc_validity_period_expire_mail_text_tooltip")}}</label>  
      <div class="controls">
        <textarea cols="55" rows="20" name="wk_vendor_kyc[exp_mail_text]" class="cm-wysiwyg">{if !empty($wk_vendor_kyc_settings_data.exp_mail_text)}{$wk_vendor_kyc_settings_data.exp_mail_text}{/if}
        </textarea>
        <p>{include file="buttons/button.tpl" but_text=__("preview") but_name="dispatch[wk_vendor_kyc.preview_html_exp]" but_meta="cm-new-window"}</p>
      </div>
    </div> 
</div>
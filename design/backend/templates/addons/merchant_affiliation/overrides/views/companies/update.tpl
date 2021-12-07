<style>
/* Tooltip container */
.customTooltip {
  position: relative;
  display: inline-block;
}

/* Tooltip text */
.customTooltip .tooltiptext {
    visibility: hidden;
    width: 500px;
    background-color: black;
    color: #fff;
    text-align: left;
    padding: 5px 5px;  
    border-radius: 6px;
 
    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1000;
}

/* Show the tooltip text when you mouse over the tooltip container */
.customTooltip:hover .tooltiptext {
  visibility: visible;
}

.tabcontent {
    display:none;
}
.windowios{
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
}
</style>
{if $company_data.company_id}
    {$id=$company_data.company_id}
{else}
    {$id=0}
{/if}
{$is_allowed_to_update_companies = fn_check_view_permissions("companies.update", "POST")}

{* Show approve and disapprove button instead of status dropdown *}
{if $company_data.status == "VendorStatuses::NEW_ACCOUNT"|enum}
    {$show_approve = true}
    {$status_display = "text"}
{/if}

{include file="views/profiles/components/profiles_scripts.tpl"}

{capture name="mainbox"}

{capture name="tabsbox"}
{** /Item menu section **}

<form class="form-horizontal form-edit {$form_class} {if !$is_allowed_to_update_companies}cm-hide-inputs{/if} {if !$id && "ULTIMATE"|fn_allowed_for}cm-ajax cm-comet cm-disable-check-changes{/if}" action="{""|fn_url}" method="post" id="company_update_form" enctype="multipart/form-data"> {* company update form *}
{* class=""*}
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="company_id" value="{$id}" />

{** Start POPUP **}
<div class="detailed tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'general':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'general':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add the product please select a link below</h4>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-detailed" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-detailed" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<div class="addons tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'addons':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'addons':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add SEO please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-addons" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-addons" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<div class="description tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'description':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'description':'document'}
    
    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add description please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-description" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-description" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<div class="logos tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'logos':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'logos':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add logo please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-logos" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-logos" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>

</div>

<div class="plan tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'plan':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'plan':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to choose plan please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-plan" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-plan" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<div class="terms_and_conditions tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'terms_and_conditions':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'terms_and_conditions':'document'}

    {if $videoLink != null || $documentLink != null}
    <h4>For guidance on how to add Terms & Conditions please select a link below</h4>
    <p>To watch a video click to video link.</p>
    <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-terms_and_conditions" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-terms_and_conditions" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<div class="ab__motivation_block tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'ab__motivation_block':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'ab__motivation_block':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add motivation block please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-ab__motivation_block" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-ab__motivation_block" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<div class="discussion tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'discussion':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'discussion':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add reviews please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-discussion" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-discussion" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>

</div>

<div class="digitzs_connect tabcontent">
    {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'digitzs_connect':'video'}
    {assign var='documentLink' value='vendor'|fn_my_changes_get_upload_product_vendor_details:'digitzs_connect':'document'}

    {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to add digitzs please select a link below</h4>
        <p>To watch a video click to video link.</p>
        <p>To review our documention click document link.</p>
    {/if}

    {if $videoLink != null}<a data-ca-target-id="popup-digitzs_connect" class="cm-dialog-opener cm-dialog-auto-size">Video</a> {/if} {if $videoLink != null && $documentLink != null} & {/if} {if $documentLink != null}<a href="#" onclick="window.open('{$documentLink[0]['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;">Document</a>{/if}

    <div class="product-options hidden" id="popup-digitzs_connect" title="Video Information">
        <div style="width: 1000px; height: 500px; overflow: hidden;">
            <video controls="" style="width: 100%; height: 500px;"> 
                <source src="{$videoLink[0]['url']}" type="video/mp4">
            </video>
        </div>
    </div>
</div>

{** End Start POPUP **}

{** General info section **}
<div id="content_detailed" class="hidden"> {* content detailed *}
<fieldset>

{if "ULTIMATE"|fn_allowed_for && !$id && !$runtime.company_id}
    {include file="common/subheader.tpl" title=__("use_existing_store")}

    <div class="control-group">
        <label class="control-label" for="elm_company_exists_store">{__("storefront")}:</label>
        <div class="controls">
            <input type="hidden" name="company_data[clone_from]" id="elm_company_exists_store" value="" onchange="fn_switch_store_settings(this);" />
            {include file="common/ajax_select_object.tpl" data_url="companies.get_companies_list?show_all=Y&default_label=none" text=__("none") result_elm="elm_company_exists_store" id="exists_store_selector"}
        </div>
    </div>

    <div id="clone_settings_container" class="hidden">

    {split data=$clone_schema size=ceil(sizeof($clone_schema) / 2) assign="splitted_objects" vertical_delimition=false preverse_keys=true}
    <div class="control-group">
        <div class="controls">
            <table cellpadding="10">
            <tr valign="top">
                {foreach from=$splitted_objects item="s_object"}
                    <td>
                    <ul class="unstyled">
                        {foreach from=$s_object key="object" item="object_data"}
                            <li>
                                {if $object_data}
                                    {assign var="label" value="clone_`$object`"}
                                    <label class="checkbox">

                                        <input type="checkbox" name="company_data[clone][{$object}]" {if $object_data.checked_by_default}checked="checked"{/if} class="cm-item-s cm-dependence-{$object}" value="Y" {if $object_data.dependence}onchange="fn_check_dependence('{$object_data.dependence}', this.checked)"{/if} />
                                    {__($label)}{if $object_data.tooltip}{include file="common/tooltip.tpl" tooltip=__($object_data.tooltip)}{/if}{if $object_data.checked_by_default}&nbsp;<span class="muted">({__("recommended")})</span>{/if}</label>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                    </td>
                {/foreach}
            </tr></table>
            <p>
            {include file="common/check_items.tpl" check_target="s" style="links"}
            </p>
        </div>
    </div>
    </div>
{/if}

{include file="common/subheader.tpl" title=__("information")}

{hook name="companies:general_information"}


{if $smarty.request.company_id}

    <div class="control-group">
        <label for="" class="control-label">LPO Name: </label>
        <div class="controls">
            {** <p><b>{ucfirst($lpoDetails['firstName'])} {ucfirst($lpoDetails['lastName'])}</b></p> **}
            <p><b>{ucfirst($lpoDetails['loyaltyProgram'])}</b></p>
        </div>
    </div>

    <div class="control-group">
        <label for="" class="control-label">Company ID {$lpoDetails['firstName']} {$lpoDetails['lastName']}</label>
        <div class="controls">
            <input type="text" class="input-large user-success clickTocopy" style="cursor:auto;" value="http://{$config.http_host}/index.php?dispatch=companies.products&company_id={$smarty.request.company_id}" readonly>

        </div>
    </div>
{/if}

{if "ULTIMATE"|fn_allowed_for}
<div class="control-group">
    <label for="elm_company_name" class="control-label cm-required">{__("name")}:</label>
    <div class="controls">
        <input type="text" name="company_data[company]" id="elm_company_name" size="32" value="{$company_data.company}" class="input-large" />
    </div>
</div>

{hook name="companies:storefronts"}
<div class="control-group">
    <label for="elm_company_storefront" class="control-label cm-required">{__("storefront_url")}:</label>
    <div class="controls">
        {if $runtime.company_id}
            http://{$company_data.storefront|puny_decode}
        {else}
            <input type="text" name="company_data[storefront]" id="elm_company_storefront" size="32" value="{$company_data.storefront|puny_decode}" class="input-large" />
        {/if}
        <p class="muted description">{__("ttc_storefront_url")}</p>
    </div>
</div>
{/hook}

{hook name="companies:storefronts_design"}

{if $id}
{include file="views/storefronts/components/status.tpl"
    id=$id
    status=$company_data.storefront_status
    input_name="company_data[storefront_status]"
}

{include file="views/storefronts/components/access_key.tpl"
    id=$id
    access_key=$company_data.store_access_key
    input_name="company_data[store_access_key]"
}

{include file="views/storefronts/components/access_only_for_authorized_customers.tpl"
    id=$id
    is_accessible_for_authorized_customers_only=$company_data.is_accessible_for_authorized_customers_only
    input_name="company_data[is_accessible_for_authorized_customers_only]"
}

{include file="common/subheader.tpl" title=__("design")}
{/if}

{include file="views/storefronts/components/theme.tpl"
    id=$id
    theme_url="themes.manage?switch_company_id={$id}"
    theme=$theme
    current_theme=$current_theme
    current_style=$current_style
    input_name="company_data[theme_name]"
}

{if $id}
    {include file="common/subheader.tpl"
        title=__("localization")
    }

    {include file="views/storefronts/components/languages.tpl"
        id=$storefront_id
        all_languages=$all_languages
    }

    {include file="views/storefronts/components/currencies.tpl"
        id=$storefront_id
        all_currencies=$all_currencies
    }
{/if}
{/hook}

{/if}

{if "MULTIVENDOR"|fn_allowed_for}
    {include file="views/profiles/components/profile_fields.tpl" section="C" default_data_name="company_data" profile_data=$company_data include=["company"] nothing_extra=true}
    {if !$runtime.company_id}
        {include file="common/select_status.tpl"
            input_name="company_data[status]"
            id="company_data"
            obj=$company_data
            items_status="companies"|fn_get_predefined_statuses:$company_data.status
            display=$status_display
        }
    {else}
        <div class="control-group">
            <label class="control-label">{__("status")}:</label>
            <div class="controls">
                <label class="radio">
                    <input type="radio" checked="checked" id="elm_company_status"/>
                    {if $company_data.status === "ObjectStatuses::ACTIVE"|enum}
                        {__("active")}
                    {elseif $company_data.status === "ObjectStatuses::PENDING"|enum}
                        {__("pending")}
                    {elseif $company_data.status === "ObjectStatuses::NEW_OBJECT"|enum}
                        {__("new")}
                    {elseif $company_data.status === "ObjectStatuses::DISABLED"|enum}
                        {__("disabled")}
                    {/if}
                </label>
            </div>
        </div>
    {/if}

    <div class="control-group">
        <label class="control-label" for="elm_company_language">{__("language")}:</label>
        <div class="controls">
        <select name="company_data[lang_code]" id="elm_company_language">
            {foreach from=$languages item="language" key="lang_code"}
                <option value="{$lang_code}" {if $lang_code == $company_data.lang_code}selected="selected"{/if}>{$language.name}</option>
            {/foreach}
        </select>
        </div>
    </div>
{/if}


{if !$id}
    {literal}
    <script type="text/javascript">
    function fn_switch_store_settings(elm)
    {
        jelm = Tygh.$(elm);
        var close = true;
        if (jelm.val() != 'all' && jelm.val() != '' && jelm.val() != 0) {
            close = false;
        }

        Tygh.$('#clone_settings_container').toggleBy(close);
    }

    function fn_check_dependence(object, enabled)
    {
        if (enabled) {
            Tygh.$('.cm-dependence-' + object).prop('checked', true).prop('readonly', true).on('click', function(e) {
                return false
            });
        } else {
            Tygh.$('.cm-dependence-' + object).prop('readonly', false).off('click');
        }
    }
    </script>
    {/literal}

    {if !"ULTIMATE"|fn_allowed_for}
        <div class="control-group">
            <label class="control-label" for="elm_company_vendor_admin">{__("create_administrator_account")}:</label>
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox" name="company_data[is_create_vendor_admin]" id="elm_company_vendor_admin" checked="checked" value="Y" />
                </label>
            </div>
        </div>
    {/if}
{/if}


{if "MULTIVENDOR"|fn_allowed_for}
    {$excluded_fields=["company", "company_description", "accept_terms", "admin_firstname", "admin_lastname"]}
    {hook name="companies:contact_information"}
    {include file="views/profiles/components/profile_fields.tpl" section="C" default_data_name="company_data" profile_data=$company_data exclude=$excluded_fields nothing_extra=true}
    {/hook}

    {hook name="companies:shipping_address"}
    {/hook}
{/if}

{if "ULTIMATE"|fn_allowed_for}
    {include file="common/subheader.tpl" title="{__("settings")}: {__("company")}" }

    {component
        name="settings.settings_section"
        subsection=$company_settings
        section="Company"
        html_id_prefix="field_"
        html_name="update"
    }{/component}
{/if}

{/hook}

</fieldset>
</div> {* /content detailed *}
{** /General info section **}



{** Company description section **}
<div id="content_description" class="hidden"> {* content description *}
<fieldset>
{hook name="companies:description"}
<div class="control-group">
    <label class="control-label" for="elm_company_description">{__("description")}:</label>
    <div class="controls">
        <textarea id="elm_company_description" name="company_data[company_description]" cols="55" rows="8" class="cm-wysiwyg input-large">{$company_data.company_description}</textarea>
    </div>
</div>
{/hook}
</fieldset>
</div> {* /content description *}
{** /Company description section **}


{if "MULTIVENDOR"|fn_allowed_for}
    {** Company logos section **}
    <div id="content_logos" class="hidden">
        {hook name="companies:logos"}
        {include file="views/companies/components/logos_list.tpl" logos=$company_data.logos company_id=$id}
        {/hook}
    </div>
    {** /Company logos section **}
{/if}


{if "ULTIMATE"|fn_allowed_for}
{** Company regions settings section **}
<div id="content_regions" class="hidden">
    <fieldset>
        <div class="control-group">
            <div class="controls">
            <input type="hidden" name="company_data[redirect_customer]" value="N" checked="checked"/>
            <label class="checkbox"><input type="checkbox" name="company_data[redirect_customer]" id="sw_company_redirect" {if $company_data.redirect_customer == "Y"}checked="checked"{/if} value="Y" class="cm-switch-availability cm-switch-inverse" />{__("redirect_customer_from_storefront")}</label>
            </div>
        </div>

        <div class="control-group" id="company_redirect">
            <label class="control-label" for="elm_company_entry_page">{__("entry_page")}</label>
            <div class="controls">
            <select name="company_data[entry_page]" id="elm_company_entry_page" {if $company_data.redirect_customer == "Y"}disabled="disabled"{/if}>
                <option value="none" {if $company_data.entry_page == "none"}selected="selected"{/if}>{__("none")}</option>
                <option value="index" {if $company_data.entry_page == "index"}selected="selected"{/if}>{__("index")}</option>
                <option value="all_pages" {if $company_data.entry_page == "all_pages"}selected="selected"{/if}>{__("all_pages")}</option>
            </select>
            </div>
        </div>

        {include file="common/double_selectboxes.tpl"
            title=__("countries")
            first_name="company_data[countries_list]"
            first_data=$company_data.countries_list
            second_name="all_countries"
            second_data=$countries_list}
    </fieldset>
</div>
{** /Company regions settings section **}

{/if}

{if "MULTIVENDOR"|fn_allowed_for && !$runtime.company_id}
{** Shipping methods section **}
<div id="content_shipping_methods" class="hidden">
    {hook name="companies:shipping_methods"}
        {if $shippings}
        <input type="hidden" name="company_data[shippings]" value="" />
        <div class="table-responsive-wrapper">
            <table width="100%" class="table table-middle table--relative table-responsive">
            <thead>
            <tr>
                <th width="50%">{__("shipping_methods")}</th>
                <th class="center">{__("available_for_vendor")}</th>
            </tr>
            </thead>
            {foreach from=$shippings item="shipping" key="shipping_id"}
            <tr>
                <td data-th="{__("shipping_methods")}"><a href="{"shippings.update?shipping_id=`$shipping_id`"|fn_url}">{$shipping.shipping}{if $shipping.status == "D"} ({__("disabled")|lower}){/if}</a></td>
                <td class="center" data-th="{__("available_for_vendor")}">
                    <input type="checkbox" {if !$id || $shipping_id|in_array:$company_data.shippings_ids} checked="checked"{/if} name="company_data[shippings][]" value="{$shipping_id}">
                </td>
            </tr>
            {/foreach}
            </table>
        </div>
        {else}
            <p class="no-items">{__("no_data")}</p>
        {/if}
    {/hook}
</div>
{** /Shipping methods section **}
{/if}

<div id="content_addons" class="hidden">
    {hook name="companies:detailed_content"}{/hook}
</div>

{hook name="companies:tabs_content"}{/hook}

</form> {* /product update form *}

{hook name="companies:tabs_extra"}{/hook}

{/capture}
{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name="companies" active_tab=$smarty.request.selected_section track=true}

{/capture}

{capture name="sidebar"}
    {hook name="companies:update_sidebar"}
{if $id}
<div class="sidebar-row">
    <h6>{__("menu")}</h6>
    <ul class="nav nav-list">
        {hook name="companies:sidebar_links"}
        <li><a href="{"products.manage?company_id=`$id`"|fn_url}">{__("view_vendor_products")}</a></li>
        {if "ULTIMATE"|fn_allowed_for && $runtime.company_id}
            <li><a href="{"categories.manage?company_id=`$id`"|fn_url}">{__("view_vendor_categories")}</a></li>
        {/if}
        {if "MULTIVENDOR"|fn_allowed_for}
            <li><a href="{"profiles.manage?user_type={"UserTypes::VENDOR"|enum}&company_id=`$id`"|fn_url}">{__("view_vendor_admins")}</a></li>
        {else}
            <li><a href="{"profiles.manage?company_id=`$id`"|fn_url}">{__("view_vendor_users")}</a></li>
        {/if}
        <li><a href="{"orders.manage?company_id=`$id`"|fn_url}">{__("view_vendor_orders")}</a></li>
        {/hook}
    </ul>
</div>
{if "MULTIVENDOR"|fn_allowed_for}
<div class="sidebar-row sidebar-vendor-statistics">
    <h6>{__("vendors_statistics")}</h6>
    <ul class="unstyled">
        {hook name="companies:accounting_sidebar_links"}
            <li class="vendor-statistics">
                <a href="{"companies.balance?vendor=`$id`&selected_section=withdrawals"|fn_url}">
                    {include file="common/price.tpl" value=$company_data.balance}</a>
                <span>{__("balance")}</span>
            </li>
            <li class="vendor-statistics">
                <a href="{"orders.manage?company_id=`$id`"|fn_url}">{$company_data.orders_count}</a>
                <span>{__("orders")}</span>
            </li>
            <li class="vendor-statistics">
                <a href="{"orders.manage?company_id=`$id`&is_search=Y&period=C&time_from=`$time_from`&time_to=`$time_to`"|fn_url}">
                    {include file="common/price.tpl" value=$company_data.sales}</a>
                <span>{__("sales")}</span>
            </li>
            <li class="vendor-statistics">
                <a href="{"companies.balance?vendor=`$id`"|fn_url}">
                    {include file="common/price.tpl" value=$company_data.income}</a>
                <span>{__("income")}</span>
            </li>
            <li class="vendor-statistics">
                <a href="{"products.manage?company_id=`$id`&status=A&product_type[]=P"|fn_url}">{$company_data.products_count}</a>
                <span>{__("active_products")}</span>
            </li>
            {if $settings.General.inventory_tracking !== "YesNo::NO"|enum}
                <li class="vendor-statistics">
                    <a href="{"products.manage?company_id=`$id`&amount_from=&amount_to=0&tracking[0]={"ProductTracking::TRACK"|enum}"|fn_url}">
                        {$company_data.out_of_stock}
                    </a>
                    <span>{__("out_of_stock_products")}</span>
                </li>
            {/if}
        {/hook}
    </ul>
</div>
{/if}
{/if}
    {/hook}
{/capture}



{** Form submit section **}
{capture name="buttons"}
    {if $id}
        {capture name="tools_list"}
        {hook name="companies:tools_list"}
            {if $show_approve}
                <li>{btn type="list" text=__("save") class="cm-update-company" dispatch="dispatch[companies.update]" form="company_update_form" method="POST"}</li>
            {/if}
            <li>{btn type="list" text=__("delete") class="cm-confirm" href="companies.delete?company_id=$id" method="POST"}</li>
        {/hook}
        {/capture}
        {dropdown content=$smarty.capture.tools_list}
        {if $show_approve}
            {if $settings.Vendors.allow_approve_vendors_in_two_steps == "YesNo::YES"|enum}
                {$approve_status = "VendorStatuses::PENDING"|enum}
            {else}
                {$approve_status = "VendorStatuses::ACTIVE"|enum}
            {/if}

            {include file="buttons/approve_disapprove.tpl"
                id=$id
                dispatch="companies.update_status"
                header_view=true
                approve_status=$approve_status
            }
        {else}
            {include file="buttons/save_cancel.tpl" but_name="dispatch[companies.update]" but_target_form="company_update_form" save=$id but_meta="cm-update-company"}
        {/if}
    {else}
        {if $is_companies_limit_reached}
            {include file="buttons/save_cancel.tpl" but_meta="btn cm-promo-popup"}
        {else}
            {include file="buttons/save_cancel.tpl" but_name="dispatch[companies.add]" but_target_form="company_update_form" but_meta="cm-comet"}
        {/if}
    {/if}
{/capture}
{** /Form submit section **}

{capture name="page_title"}
{if $id}
    {$company_data.company}
{elseif fn_allowed_for("MULTIVENDOR")}
    {__("new_vendor")}
{else}
    {__("add_storefront")}
{/if}
{/capture}

<script>
  document.querySelector('.clickTocopy').onclick = function() {
    this.select();
    document.execCommand('copy');
    alert('Copied');
  }
</script>

<script type="text/javascript">
$("#detailed, #addons, #description, #logos, #plan, #terms_and_conditions, #ab__motivation_block, #discussion, #digitzs_connect").click(function(){
    var id = $(this).attr('id');
    openPage(id,this);
});
function openPage(pageName,elmnt) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  document.getElementsByClassName(pageName)[0].style.display = "block";
}

$('label[for="first_name"]')[0].innerHTML = '<div class="customTooltip">First Name <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"first_name"}</span></div>';

$('label[for="last_name"]')[0].innerHTML = '<div class="customTooltip">Last Name <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"last_name"}</span></div>';

$('label[for="personal_email"]')[0].innerHTML = '<div class="customTooltip">Personal Email <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"personal_email"}</span></div>';

$('label[for="day_phone"]')[0].innerHTML = '<div class="customTooltip">Day Phone <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"day_phone"}</span></div>(xxx) xxx-xxxx ';

$('label[for="evening_phone"]')[0].innerHTML = '<div class="customTooltip">Evening Phone <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"evening_phone"}</span></div>(xxx) xxx-xxxx ';

$('label[for="birth_date"]')[0].innerHTML = '<div class="customTooltip">Birth Date (MM-DD-YYYY)(18+) <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"birth_date"}</span></div>';
 
$('label[for="social_security"]')[0].innerHTML = '<div class="customTooltip">Social Security <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"social_security"}</span></div>';

$('label[for="personal_address_line1"]')[0].innerHTML = '<div class="customTooltip">Personal Address <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"personal_address_line1"}</span></div>';

$('label[for="personal_city"]')[0].innerHTML = '<div class="customTooltip">Personal City <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"personal_city"}</span></div>';

$('label[for="personal_country"]')[0].innerHTML = '<div class="customTooltip">Personal Country <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"personal_country"}</span></div>';

$('label[for="personal_state"]')[0].innerHTML = '<div class="customTooltip">Personal State <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"personal_state"}</span></div>';

$('label[for="personal_zip"]')[0].innerHTML = '<div class="customTooltip">Personal Zipcode <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"personal_zip"}</span></div>';

$('label[for="same_as_above"]')[0].innerHTML = '<div class="customTooltip">Same as Personal Address <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"same_as_above"}</span></div>';

$('label[for="business_name"]')[0].innerHTML = '<div class="customTooltip">Business Name <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"business_name"}</span></div>';

$('label[for="url"]')[0].innerHTML = '<div class="customTooltip">Website URL <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"url"}</span></div>';

$('label[for="ein"]')[0].innerHTML = '<div class="customTooltip">EIN Number <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"ein"}</span></div>';

$('label[for="business_address_line1"]')[0].innerHTML = '<div class="customTooltip">Business Address <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"business_address_line1"}</span></div>';

$('label[for="business_city"]')[0].innerHTML = '<div class="customTooltip">Business City <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"business_city"}</span></div>';

$('label[for="business_country"]')[0].innerHTML = '<div class="customTooltip">Business Country <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"business_country"}</span></div>';

$('label[for="business_state"]')[0].innerHTML = '<div class="customTooltip">Business State <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"business_state"}</span></div>';

$('label[for="business_zipcode"]')[0].innerHTML = '<div class="customTooltip">Business Zipcode <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"business_zipcode"}</span></div>';

$('label[for="bank_name"]')[0].innerHTML = '<div class="customTooltip">Bank name <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"bank_name"}</span></div>';

$('label[for="account_ownership"]')[0].innerHTML = '<div class="customTooltip">Account ownership <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"account_ownership"}</span></div>';

$('label[for="account_type"]')[0].innerHTML = '<div class="customTooltip">Bank Account type <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"account_type"}</span></div>';

$('label[for="account_name"]')[0].innerHTML = '<div class="customTooltip">Account Name <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"account_name"}</span></div>';

$('label[for="account_number"]')[0].innerHTML = '<div class="customTooltip">Account No. <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"account_number"}</span></div>';

$('label[for="routing_number"]')[0].innerHTML = '<div class="customTooltip">Routing No. <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"routing_number"}</span></div>';

$('label[for="merchant_agreement"]')[0].innerHTML = '<div class="customTooltip">Merchant Agreement <i class="help-center__show-help-center--icon icon-question-sign"></i><span class="tooltiptext">{assign var="var1" value="Digitzs"|fn_my_changes_get_label_details_digitzs:"merchant_agreement"}</span></div>';


</script>


{include file="common/mainbox.tpl"
    title=$smarty.capture.page_title
    select_languages=(bool) $id
    content=$smarty.capture.mainbox
    sidebar=$smarty.capture.sidebar
    buttons=$smarty.capture.buttons
}
{assign var='disp' value=''}
{if $auth.user_type!='V' && $field.field_name == 'password' && $smarty.get.dispatch !='companies.add' }
{assign var='disp' value='hide'}
{elseif $auth.user_type == 'V' && $field.field_name == 'password' && $smarty.get.dispatch !='companies.add' }
{assign var='disp' value='hide'}
{elseif $auth.user_type == 'A' && $field.field_name == 'password' && $smarty.get.dispatch =='companies.add' }
{assign var='disp' value='hide'}
{/if}

<div class="control-group profile-field-{$field.field_name} {$disp}"> 
     <label
        for={$element_id}
        class="control-label cm-profile-field {if $required == "Y" && $disp==''}cm-required{/if}{if $field.field_type == "ProfileFieldTypes::PHONE"|enum || ($field.autocomplete_type == "phone-full")} cm-mask-phone-label {/if}{if $field.field_type == "Z"} cm-zipcode{/if}{if $field.field_type == "E"} cm-email{/if} {if $field.field_type == "Z"}{if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}{/if}"
    >{$field.description}:</label>

    <div class="controls">

    {if $field.field_type == "ProfileFieldTypes::STATE"|enum}
        {$_country = $settings.Checkout.default_country}
        {$_state = $value}

        <select class="cm-state {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}" id={$element_id} name="{$data_name}[{$data_id}]" {$disabled_param nofilter}>
            <option value="">- {__("select_state")} -</option>
            {if $states && $states.$_country}
                {foreach from=$states.$_country item=state}
                    <option {if $_state == $state.code}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                {/foreach}
            {/if}
        </select>
        <input type="text" id="elm_{$field.field_id}_d" name="{$data_name}[{$data_id}]" size="32" maxlength="64" value="{$_state}" disabled="disabled" class="cm-state {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if} input-large hidden cm-skip-avail-switch" />

    {elseif $field.field_type == "ProfileFieldTypes::COUNTRY"|enum}
        {assign var="_country" value=$value|default:$settings.Checkout.default_country}
        <select id={$element_id} class="cm-country {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}" name="{$data_name}[{$data_id}]" {$disabled_param nofilter}>
            {hook name="profiles:country_selectbox_items"}
            <option value="">- {__("select_country")} -</option>
            {foreach from=$countries item="country" key="code"}
            <option {if $_country == $code}selected="selected"{/if} value="{$code}">{$country}</option>
            {/foreach}
            {/hook}
        </select>

    {elseif $field.field_type == "ProfileFieldTypes::CHECKBOX"|enum}
        <input type="hidden" name="{$data_name}[{$data_id}]" value="N" {$disabled_param nofilter} />
        <label class="checkbox">
        <input type="checkbox" id={$element_id} name="{$data_name}[{$data_id}]" value="Y" {if $value == "Y"}checked="checked"{/if} {$disabled_param nofilter} /></label>

    {elseif $field.field_type == "ProfileFieldTypes::TEXT_AREA"|enum}
        <textarea class="input-large" id={$element_id} name="{$data_name}[{$data_id}]" cols="32" rows="3" {$disabled_param nofilter}>{$value}</textarea>

    {elseif $field.field_type == "ProfileFieldTypes::DATE"|enum}
        {include file="common/calendar.tpl" date_id="elm_`$field.field_id`" date_name="`$data_name`[`$data_id`]" date_val=$value extra=$disabled_param}

    {elseif $field.field_type == "ProfileFieldTypes::SELECT_BOX"|enum}
        <select id={$element_id} name="{$data_name}[{$data_id}]" {$disabled_param nofilter}>
            {if $required != "Y"}
            <option value="">--</option>
            {/if}
            {foreach from=$field.values key=k item=v}
            <option {if $value == $k}selected="selected"{/if} value="{$k}">{$v}</option>
            {/foreach}
        </select>

    {elseif $field.field_type == "ProfileFieldTypes::RADIO"|enum}
        <div class="select-field">
        {foreach from=$field.values key=k item=v name="rfe"}
        <input class="radio" type="radio" id="{$element_id}_{$k}" name="{$data_name}[{$data_id}]" value="{$k}" {if (!$value && $smarty.foreach.rfe.first) || $value == $k}checked="checked"{/if} {$disabled_param nofilter} /><label for="{$element_id}_{$k}">{$v}</label>
        {/foreach}
        </div>

    {elseif $field.field_type == "ProfileFieldTypes::ADDRESS_TYPE"|enum}
        <input class="radio valign {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" type="radio" id="{$element_id}_residential" name="{$data_name}[{$data_id}]" value="residential" {if !$value || $value == "residential"}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{__("address_residential")}</span>
        <input class="radio valign {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" type="radio" id="{$element_id}_commercial" name="{$data_name}[{$data_id}]" value="commercial" {if $value == "commercial"}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{__("address_commercial")}</span>
    {elseif $field.field_type == "ProfileFieldTypes::FILE"|enum}
        {if isset($value.file_name)}
            <div class="text-type-value" data-file-id="{$hash_name}">
                <i id="{$hash_name}" title="{__("remove_this_item")}" class="icon-remove-sign cm-tooltip hand cm-file-remove {if $field.required == "YesNo::YES"|enum}cm-file-required{/if}"></i>
                <span><a href="{$value.link|default:""}">{$value.file_name}</a></span>
            </div>
        {/if}
        {include file="common/fileuploader.tpl"
            var_name=$var_name
            label_id="elm_{$id_prefix}{$field.field_id}"
            hidden_name="{$data_name}[{$data_id}]"
            hidden_value=$value.file_name|default:""
            prefix=$id_prefix
            disabled_param=$disabled_param
            max_upload_filesize=$config.tweaks.profile_field_max_upload_filesize
        }

    {else}  {* Simple input (or another types of input) *}
    
    <input
            {if $auth.user_type=='V' && $field.field_name == 'password'} type= "password" {else} type= "text" {/if}
            id="{$id_prefix}elm_{$field.field_id}"            
            {if $auth.user_type=='V' && $field.field_name == 'password'} name="{$data_name}[{$field.field_name}]" {else} name="{$data_name}[{$data_id}]" {/if}
            size="32"
            value="{$value}"
            class="input-large {if ($field.autocomplete_type == "phone-full") || ($field.field_type == "ProfileFieldTypes::PHONE"|enum)} cm-mask-phone{/if}"
            {$disabled_param nofilter}
        /> 
        
    {/if}
    </div>
</div>
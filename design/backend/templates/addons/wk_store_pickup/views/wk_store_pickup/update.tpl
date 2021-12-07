{if $store_pickup_point.store_id}
    {assign var="id" value=$store_pickup_point.store_id}
{else}
    {assign var="id" value="0"}
{/if}

{assign var="allow_save" value=$store_pickup_point|fn_allow_save_object:"wk_store_pickup"}
{$show_save_btn = $allow_save scope = root}

{include file="addons/wk_store_pickup/pickers/map.tpl"}
{include file="views/profiles/components/profiles_scripts.tpl"}

{capture name="mainbox"}

{capture name="tabsbox"}

    <form action="{""|fn_url}" method="post" enctype="multipart/form-data" class="form-horizontal form-edit{if !$allow_save} cm-hide-inputs{/if}" name="store_locations_form{$suffix}">
        <input type="hidden" name="store_id" value="{$id}" />
        <div id="content_detailed">
            <fieldset>
                <div class="control-group">
                    <label for="elm_name" class="cm-required control-label">{__("wk_store_name")}:</label>
                    <div class="controls">
                        <input type="text" id="elm_name" name="store_pickup_point[title]" value="{$store_pickup_point.title}">
                    </div>
                </div>
                {if ("ULTIMATE"|fn_allowed_for || "MULTIVENDOR"|fn_allowed_for) && !$runtime.company_id}
                <div class="control-group">
                        {if $id}
                            <label class="control-label">{if "ULTIMATE"|fn_allowed_for}{__("store")}{else}{__("vendor")}{/if}:</label>
                            <div class="controls">
                                <p>{$store_pickup_point.company_id|fn_get_company_name}</p>
                            </div>
                        {else}
                            {if "ULTIMATE"|fn_allowed_for}
                                {assign var="companies_tooltip" value=__("text_ult_product_store_field_tooltip")}
                            {/if}
                            {include file="views/companies/components/company_field.tpl"
                                name="store_pickup_point[company_id]"
                                id="product_data_company_id"
                                selected=$store_pickup_point.company_id
                                tooltip=$companies_tooltip
                            }
                        {/if}
                </div>
                {/if}
                <div class="control-group">
                    <label for="elm_description" class="cm-required control-label">{__("description")}:</label>
                    <div class="controls">
                        <textarea class="cm-wysiwyg input-large" id="elm_description" name="store_pickup_point[description]">{$store_pickup_point.description}</textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="elm_phone">{__("phone")}:</label>
                    <div class="controls">
                        <input type="text" name="store_pickup_point[phone]" id="elm_phone" value="{$store_pickup_point.phone}" size="3">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label cm-required">{__("coordinates")} ({__("latitude_short")} &times; {__("longitude_short")}):</label>
                    <label class="control-label cm-required hidden" for="elm_latitude">{__("latitude")}</label>
                    <label class="control-label cm-required hidden" for="elm_longitude">{__("longitude")}</label>
                    <div class="controls">
                        <input type="hidden" id="elm_place_id_hidden" name="store_pickup_point[place_id]" value="{$store_pickup_point.place_id}"/>
                        <input type="hidden" id="elm_latitude_hidden" value="{$store_pickup_point.latitude}" />
                        <input type="hidden" id="elm_longitude_hidden" value="{$store_pickup_point.longitude}" />
                        <input type="text" name="store_pickup_point[latitude]" id="elm_latitude" value="{$store_pickup_point.latitude}" class="input-small">
                        &times;
                        <input type="text" name="store_pickup_point[longitude]" id="elm_longitude" value="{$store_pickup_point.longitude}" class="input-small">

                        {include file="buttons/button.tpl" but_text=__("select") but_role="action" but_meta="btn-primary cm-map-dialog"}
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="elm_address">{__("address")}:</label>
                    <div class="controls">
                        <input type="text" id="elm_address" name="store_pickup_point[address]" class="input-large" value="{$store_pickup_point.address}">
                    </div>
                </div>
                <div class="control-group">
                    <label for="elm_pincode" class="cm-required control-label">{__("wk_pincode")}:</label>
                    <div class="controls">
                        <input type="text" id="elm_pincode" name="store_pickup_point[pincode]" value="{$store_pickup_point.pincode}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="elm_city">{__("city")}:</label>
                    <div class="controls">
                        <input type="text" name="store_pickup_point[city]" id="elm_city" value="{$store_pickup_point.city}">
                    </div>
                </div>
                {$_country = $store_pickup_point.country|default:$settings.General.default_country}
                {$_state = $store_pickup_point.state|default:$settings.General.default_state}
                <div class="control-group">
                    <label class="control-label" for="elm_country">{__("country")}:</label>
                    <div class="controls">
                        <select id="elm_country" name="store_pickup_point[country]" class="select  cm-country cm-location-billing">
                            <option value="">- {__("select_country")} -</option>
                            {foreach from=$countries item="country" key="code"}
                                <option {if $_country == $code}selected="selected"{/if} value="{$code}" title="{$country}">{$country}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="elm_state">{__("state")}:</label>
                    <div class="controls">
                        <select id="elm_state" class="cm-state cm-location-billing" name="store_pickup_point[state]">
                            <option value="">- {__("select_state")} -</option>
                            {if $states && $states.$_country}
                                {foreach from=$states.$_country item=state}
                                    <option {if $_state == $state.code}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                                {/foreach}
                            {/if}
                        </select>
                        <input type="text" id="elm_state_d" name="store_pickup_point[state]" size="32" maxlength="64" value="{$_state}" class="cm-state cm-location-billing ty-input-text {if $states.$_country}hidden{/if}" {if $states.$_country}disabled{/if}/>
                    </div>
                </div>
               
                {hook name="wk_store_pickup:detailed_content"}
                {/hook}

                {include file="common/select_status.tpl" input_name="store_pickup_point[status]" id="elm_status" obj_id=$store_pickup_point.location_id obj=$store_pickup_point}

            </fieldset>
        </div>

        <div id="content_addons">
            {hook name="wk_store_pickup:addons_content"}
            {/hook}
        </div>

        {hook name="wk_store_pickup:tabs_content"}
        {/hook}

        {capture name="buttons"}
            {if !$id}
                {include file="buttons/save_cancel.tpl" but_name="dispatch[wk_store_pickup.update]" but_role="submit-link" but_target_form="store_locations_form{$suffix}"}
            {else}
                {if !$show_save_btn}
                    {assign var="hide_first_button" value=true}
                    {assign var="hide_second_button" value=true}
                {/if}
                {include file="buttons/save_cancel.tpl" but_name="dispatch[wk_store_pickup.update]" hide_first_button=$hide_first_button hide_second_button=$hide_second_button but_role="submit-link" but_target_form="store_locations_form{$suffix}" save=$id}
            {/if}
        {/capture}

    </form>

    {if $id}
        {hook name="wk_store_pickup:tabs_extra"}
        {/hook}
    {/if}

{/capture}

{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox track=true}
{/capture}

{if $id}
    {$title_start = __('wk_store_pickup_editing_pickup_point')}
    {$title_end = $store_pickup_point.title}
{else}
    {$title = __("wk_store_pickup_add_pickup_point")}
{/if}

{include file="common/mainbox.tpl" title_start=$title_start title_end=$title_end title=$title content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons}
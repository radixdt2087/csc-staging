{if $addons_data[0].id}
    {$id=$addons_data[0].id}
{else}
    {$id=0}
{/if}
{$status = $obj.status|default:""}
{$items_status = $items_status|default:($status|fn_get_product_statuses:$hidden)}
{$non_editable = $non_editable_status|default:false}
{capture name="mainbox"}

{capture name="tabsbox"}
{** /Item menu section **}

<form class="form-horizontal form-edit {$form_class} {if !$id && "ULTIMATE"|fn_allowed_for}cm-ajax cm-comet cm-disable-check-changes{/if}" action="{""|fn_url}" method="post" id="addon_update_form" enctype="multipart/form-data"> {* addons update form *}
{* class=""*}
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="id" value="{$id}" />

{** General info section **}
<div id="content_detailed"> 
<fieldset>

{include file="common/subheader.tpl" title=__("information")}

{hook name="vendor_addons:general_information"}
{if "MULTIVENDOR"|fn_allowed_for}
    <div class="control-group">
        <label class="control-label cm-required" for="elm_addon_name">{__("name")}:</label>
        <div class="controls">
        <input type="text" name="addon_data[name]" id="elm_addon_name" value="{$addons_data[0].name}"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_addon_short_desc">Short Description:</label>
        <div class="controls">
            <textarea id="elm_addon_short_desc" name="addon_data[short_desc]" cols="30" rows="4" >{$addons_data[0].short_desc}</textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_addon_long_desc">Long Description:</label>
        <div class="controls">
            <textarea id="elm_addon_long_desc" name="addon_data[long_desc]" cols="55" rows="8" class="input-large">{$addons_data[0].long_desc}</textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">{__("status")}:</label>
        <div class="controls">
            {foreach from=$items_status item="status_name" key="status_id" name="status_cycle"}
                <label class="radio inline" for="{$id}_{$obj_id|default:0}_{$status_id|lower}">
                    <input type="radio"
                        name="addon_data[status]"
                        class="addon__status-switcher"
                        id="elm_addon_status_{$addons_data|default:0}_{$status_id|lower}"
                        {if $addons_data[0].status === $status_id || (!$addons_data[0].status && $smarty.foreach.status_cycle.first)}checked="checked"{/if}
                        value="{$status_id}"
                    />
                    {$status_name}
                </label>
            {/foreach}
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">{__("image")}:</label>
        <div class="controls">
        {include file="common/fileuploader.tpl"
            var_name='addons_data'
            label_id="elm_addons_data_file"
            hidden_name="addons_data"
            hidden_value=$addons_data[0].product_img|default:""
            prefix='addons_data'
            disabled_param=false
            max_upload_filesize=$config.tweaks.profile_field_max_upload_filesize
         }
        </div>
    </div>
    {if $addons_data[0].product_img}
    <div class="control-group">
        <div class="controls">
        <img src="/images/addons/{$addons_data[0].product_img}" width="80px"/>
        </div>
    </div>
    {/if}
    <div class="control-group">
        <label class="control-label">Video:</label>
        <div class="controls">
        {include file="common/fileuploader.tpl"
            var_name='addons_data_video'
            label_id="elm_addons_data_file"
            hidden_name="addons_data_video"
            hidden_value=$addons_data[0].product_video|default:""
            prefix='addons_data'
            disabled_param=false
            max_upload_filesize=$config.tweaks.profile_field_max_upload_filesize
         }
        </div>
    </div>
    {if $addons_data[0].product_video}
    <div class="control-group">
        <div class="controls">
         <video width="100" height="100" controls>
            <source src="/videos/addons/{$addons_data[0].product_video}">
         </video>
        </div>
    </div>
    {/if}
    <div class="control-group">
        <label class="control-label cm-required" for="elm_price">{__("price")} ({$currencies.$primary_currency.symbol nofilter}) :</label>
        <div class="controls">
            <input type="text" name="addon_data[price]" id="elm_price" size="10" value="{$addons_data[0].price}" class="input-long cm-numeric" data-a-sep />
        </div>
    </div>
     <div class="control-group">
        <label class="control-label cm-required" for="payment_frequency">Payment Frequency</label>
        <div class="controls">
            <select name="addon_data[payment_frequency]" id="payment_frequency">
            <option value="">--Select--</option>
            <option value="Month" {if $addons_data[0].payment_frequency == 'Month'} selected {/if}>per month</option>
            <option value="Year" {if $addons_data[0].payment_frequency == 'Year'} selected {/if}>per year</option>
            <option value="One time" {if $addons_data[0].payment_frequency == 'One time'} selected {/if}>one time</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="allow_package">Allow in Package</label>
        <div class="controls">
            <input type="checkbox" name="addon_data[allow_package]" id="allow_package" value="Yes" {if $addons_data[0].allow_package == 'Yes'}checked{/if} />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="prorate_charge">Pro-rate first charge</label>
        <div class="controls">
            <input type="checkbox" name="addon_data[prorate_charge]" id="prorate_charge" value="Yes" {if $addons_data[0].prorate_charge == 'Yes'}checked{/if} />
        </div>
    </div>
    
{/if}
{/hook}
</fieldset>
</div>
</form>

{/capture}
{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name="addons" active_tab=$smarty.request.selected_section track=true}
{/capture}

{* {capture name="sidebar"}
    {hook name="vendor_addons:update_sidebar"}
{if $id}
<div class="sidebar-row">
    <h6>{__("menu")}</h6>
</div>

{/if}
    {/hook}
{/capture} *}

{capture name="buttons"}
    {if $id}
        {*{capture name="tools_list"}
        {if $show_approve}
            <li>{btn type="list" text=__("save") class="cm-update-addon" dispatch="dispatch[vendor_addons.update]" form="addons_update_form" method="POST"}</li>
        {/if}
        <li>{btn type="list" text=__("delete") class="cm-confirm" href="vendor_addons.delete?addon_id=$id" method="POST"}</li>
        {/capture}
        {dropdown content=$smarty.capture.tools_list}*}
        {if $show_approve}
        {else}
            {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_addons.update]" but_target_form="addon_update_form" save=$id but_meta="cm-update-addon"}
        {/if}
    {else}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_addons.add]" but_target_form="addon_update_form" but_meta="cm-comet"}        
    {/if}
{/capture}
{** /Form submit section **}

{capture name="page_title"}
{if $id}
{elseif fn_allowed_for("MULTIVENDOR")}
    New Addons
{else}
    {__("add_storefront")}
{/if}
{/capture}

{include file="common/mainbox.tpl"
    title=$smarty.capture.page_title
    select_languages=(bool) $id
    content=$smarty.capture.mainbox
    sidebar=$smarty.capture.sidebar
    buttons=$smarty.capture.buttons
}

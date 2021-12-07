{if $permission_data.id}
    {$id=$permission_data.id}
{else}
    {$id=0}
{/if}
{$status = $obj.status|default:""}
{$items_status = $items_status|default:($status|fn_get_product_statuses:$hidden)}
{$non_editable = $non_editable_status|default:false}
{capture name="mainbox"}
{capture name="tabsbox"}
<form class="form-horizontal form-edit {$form_class} {if !$id && "ULTIMATE"|fn_allowed_for}cm-ajax cm-comet cm-disable-check-changes{/if}" action="{""|fn_url}" method="post" id="permission_update_form" enctype="multipart/form-data">
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="id" value="{$id}" />
<div id="content_detailed">
<fieldset>
{if "MULTIVENDOR"|fn_allowed_for}
    <div class="control-group">
        <label class="control-label cm-required" for="name">{__("name")}:</label>
        <div class="controls">
        <input type="text" name="vendor_permission[name]" id="name" value="{$permission_data.name}"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label cm-required" for="module">Modules:</label>
        <div class="controls">
        <select name="vendor_permission[module]" id="module">
        <option value=''>--Select--</option>
        <option value='products' {if $permission_data.modules == 'products'}selected{/if}>Products</option>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label cm-required" for="name">Tabs:</label>
        <div class="controls">
        <select name="vendor_permission[tabs]" id="tabs">
        <option value=''>--Select--</option>
        <option value='youtube' {if $permission_data.tabs=='youtube'}selected{/if}>Youtube</option>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label cm-required" for="addons">Addons:</label>
        <div class="controls">
        <select name="vendor_permission[addons]" id="addons">
        <option value=''>--Select--</option>
        {foreach from=$addons_list item=item}
            <option value='{$item.id}' {if $permission_data.addons == $item.id}selected{/if}>{$item.name}</option>
        {/foreach}
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label cm-required" for="vendors">Vendors:</label>
        <div class="controls">
        <select name="vendor_permission[vendors]" id="vendors">
        <option value=''>--Select--</option>
        {foreach from=$companies item=item key=key}
            <option value='{$key}' {if $permission_data.vendors == $key}selected{/if}>{$item}</option>
        {/foreach}
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">{__("status")}:</label>
        <div class="controls">
            {foreach from=$items_status item="status_name" key="status_id" name="status_cycle"}
                <label class="radio inline" for="{$id}_{$obj_id|default:0}_{$status_id|lower}">
                    <input type="radio"
                        name="vendor_permission[status]"
                        class="addon__status-switcher"
                        id="elm_addon_status_{$permission_data|default:0}_{$status_id|lower}"
                        {if $permission_data.status === $status_id || (!$permission_data.status && $smarty.foreach.status_cycle.first)}checked="checked"{/if}
                        value="{$status_id}"
                    />
                    {$status_name}
                </label>
            {/foreach}
        </div>
    </div>
{/if}
</fieldset>
</div>
</form>

{/capture}
{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name="addons" active_tab=$smarty.request.selected_section track=true}
{/capture}
{capture name="buttons"}
    {if $id}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_enrollment.update]" but_target_form="permission_update_form" save=$id but_meta="cm-update-addon"}
    {else}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_enrollment.add]" but_target_form="permission_update_form" but_meta="cm-comet"}
    {/if}
{/capture}
{** /Form submit section **}

{capture name="page_title"}
{if $id}
    Vendor Addons Permission
{else if fn_allowed_for("MULTIVENDOR")}
    New Permission
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

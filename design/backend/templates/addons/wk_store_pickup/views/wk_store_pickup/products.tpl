{** wk_store_pickup_products section **}
{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="store_pickup_products_form" class="" enctype="multipart/form-data">
<input type="hidden" name="fake" value="1" />

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
<input type="hidden" name="store_id" value="{$smarty.request.store_id|default:0}"/>
{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents_products"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}
{include file="common/pagination.tpl" div_id="pagination_contents_products" save_current_page=true save_current_url=true}
{if $wk_store_pickup_products}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
    <thead>
    <tr>
        <th width="1%" class="left mobile-hide">
            {include file="common/check_items.tpl" class="cm-no-hide-input"}</th>
        <th width="10%"><a class="cm-ajax" href="{"`$c_url`&sort_by=store_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_store_id")}{if $search.sort_by == "store_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=title&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_store_name")}{if $search.sort_by == "title"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="40%">
            <a class="cm-ajax" href="{"`$c_url`&sort_by=product&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("product")}{if $search.sort_by == "product"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a>
        </th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=stock&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("wk_store_stock")}{if $search.sort_by == "stock"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
        <th width="6%" class="mobile-hide">&nbsp;</th>
    </tr>
    </thead>
    {foreach from=$wk_store_pickup_products item=wk_store_pickup_product}
    <tr class="1cm-row-status-{$wk_store_pickup_product.status|default:'A'|lower}">
        {assign var="allow_save" value=$wk_store_pickup_product|fn_allow_save_object:"wk_store_pickup_products"}

        {if $allow_save}
            {assign var="no_hide_input" value="cm-no-hide-input"}
        {else}
            {assign var="no_hide_input" value=""}
        {/if}
        <td class="left mobile-hide">
            <input type="checkbox" name="ids[]" value="{$wk_store_pickup_product.id}" class="cm-item {$no_hide_input}" /></td>

        <td class="{$no_hide_input}" data-th="{__("wk_store_id")}">
            <a href="{'wk_store_pickup.update&store_id='|cat:$wk_store_pickup_product.store_id|fn_url}">{$wk_store_pickup_product.store_id}</a>
        </td>
        <td class="{$no_hide_input}" data-th="{__("wk_store_name")}">
            {$wk_store_pickup_product.title}
            {include file="views/companies/components/company_name.tpl" object=$wk_store_pickup_product}
        </td>
        <td class="{$no_hide_input}" data-th="{__("product")}">
            <a href="{"products.update&product_id=`$wk_store_pickup_product.product_id`"|fn_url}">{fn_get_product_name($wk_store_pickup_product.product_id)}</a>
        </td>
        <td class="{$no_hide_input}" data-th="{__("wk_store_stock")}">
              <input type="hidden" name="store_pickup_data[{$wk_store_pickup_product.id}][store_id]" value="{$wk_store_pickup_product.store_id}" class="input-mini input-hidden"/>
              <input type="hidden" name="store_pickup_data[{$wk_store_pickup_product.id}][product_id]" size="6" value="{$wk_store_pickup_product.product_id}" class="input-mini input-hidden"/>
               <input type="hidden" name="store_pickup_data[{$wk_store_pickup_product.id}][id]" size="6" value="{$wk_store_pickup_product.id}" class="input-mini input-hidden"/>
              <input type="text" name="store_pickup_data[{$wk_store_pickup_product.id}][stock]" size="6" value="{$wk_store_pickup_product.stock|default:0}" class="input-mini input-hidden"/>
        </td>
        <td class="{$no_hide_input}" data-th="{__("status")}">
            <select name="store_pickup_data[{$wk_store_pickup_product.id}][status]" class="input-small input-hidden">
                <option value="A" {if $wk_store_pickup_product.status == 'A'}selected{/if}>{__("active")}</option>
                <option value="D" {if $wk_store_pickup_product.status == 'D'}selected{/if}>{__("disabled")}</option>
            </select>
        </td> 
        <td class="mobile-hide">
            {capture name="tools_list"}
                {if $allow_save}
                    <li>{btn type="list" text=__("delete") href="wk_store_pickup.delete_product?id=`$wk_store_pickup_product.id`" class="cm-confirm cm-post"}</li>
                {/if}
            {/capture}
            <div class="hidden-tools">
                {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>
    </tr>
    {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
{include file="common/pagination.tpl" div_id="pagination_contents_products"}
{capture name="buttons"}
{if $wk_store_pickup_products}
    {capture name="tools_list"}
        <li>{btn type="delete_selected" dispatch="dispatch[wk_store_pickup.m_delete_product]" form="store_pickup_products_form" method="post"}</li>
    {/capture}
    {dropdown content=$smarty.capture.tools_list class="mobile-hide"}

    {include file="buttons/save.tpl" but_name="dispatch[wk_store_pickup.m_update_products]" but_role="action" but_target_form="store_pickup_products_form" but_meta="cm-submit"}
{/if}
{/capture}
</form>
{/capture}

{capture name="sidebar"}
    {hook name="wk_store_pickup_products:manage_sidebar"}
        {include file="common/saved_search.tpl" dispatch="wk_store_pickup.products" view_type="wk_store_pickup"}
        {include file="addons/wk_store_pickup/views/wk_store_pickup/components/store_products_search_form.tpl" dispatch="wk_store_pickup.products"}
    {/hook}
{/capture}

{include file="common/mainbox.tpl" title=__("wk_store_pickup_products") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons select_languages=true sidebar=$smarty.capture.sidebar}

{** wk_store_pickup_products section **}
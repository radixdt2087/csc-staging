   
{if $in_popup}
    <div class="adv-search">
    <div class="group">
{else}
    <div class="sidebar-row">
    <h6>{__("search")}</h6>
{/if}
<form name="vendor_subdomain_search_form" action="{""|fn_url}" method="get" class="{$form_meta}">

    {if $smarty.request.redirect_url}
    <input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
    {/if}

    {if $search.user_type}
    <input type="hidden" name="user_type" value="{$search.user_type}" />
    {/if}

    {if $put_request_vars}
    {foreach from=$smarty.request key="k" item="v"}
    {if $v && $k != "callback"}
    <input type="hidden" name="{$k}" value="{$v}" />
    {/if}
    {/foreach}
    {/if}

    {capture name="simple_search"}
    {$extra nofilter}
        <div class="sidebar-field">
            <label for="elm_subdomain_data">{__("subdomain")}:</label>
            <input type="text" name="subdomain" id="elm_subdomain_data" value="{$search.subdomain}" />
        </div>
        {if !$runtime.company_id}
            <div class="sidebar-field">
                <label for="elm_company_data">{__("vendor")}:</label>
                {assign var="vendors" value=fn_get_short_companies()}
                <select name="company_id" id="elm_company_data">
                    <option value="">--</option>
                    {foreach from=$vendors key="seller_id" item="seller"}
                    {if $seller_id}
                    <option value="{$seller_id}" {if $search.company_id == $seller_id}selected="selected"{/if}>{$seller}</option>
                    {/if}
                    {/foreach}
                </select>
            </div>
        {/if}

        <div class="sidebar-field">
            <label for="elm_status">{__("status")}</label>
            <div class="break">
                <select name="status" id="elm_status" >
                    <option value="">{__("all")}</option>
                        <option {if $search.status == 'A'}selected="selected"{/if} value="A">{__("active")}</option>
                        <option {if $search.status == 'D'}selected="selected"{/if} value="D">{__("disabled")}</option>
                </select>
            </div>
        </div>
    {/capture}
    {include file="common/advanced_search.tpl" simple_search=$smarty.capture.simple_search no_adv_link=true dispatch=$dispatch view_type="vendor_subdomain" in_popup=$in_popup}
</form>

{if $in_popup}
</div></div>
{else}
</div><hr>
{/if}
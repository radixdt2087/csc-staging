{if $in_popup}
    <div class="adv-search">
    <div class="group">
{else}
    <div class="sidebar-row">
    <h6>{__("search")}</h6>
{/if}

<form name="orders_search_form" action="{""|fn_url}" method="get" class="{$form_meta}">
    <input type="hidden" name="store_id" value="{$search.store_id|default:$smarty.request.store_id}" />        
    
    {if $smarty.request.redirect_url}
        <input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
    {/if}

    {if $selected_section != ""}
        <input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
    {/if}

    {if $put_request_vars}
        {array_to_fields data=$smarty.request skip=["callback"]}
    {/if}

    {$extra nofilter}

    {capture name="simple_search"}
        <div class="sidebar-field">
            <label for="elm_q">{__("find_results_with")}</label>
            <div class="break">
                <input type="text" name="q" id="elm_q" value="{$search.q}" />
            </div>
        </div>

        <div class="sidebar-field">
            <label for="elm_type">{__("status")}</label>
            <div class="controls">
                <select name="status" id="elm_type">
                    <option value="">{__("all")}</option>
                     <option value="A" {if $search.status == 'A'}selected{/if}>{__("active")}</option>
                    <option value="D" {if $search.status == 'D'}selected{/if}>{__("disabled")}</option>
                </select>
            </div>
        </div>
    {/capture}

    {include file="common/advanced_search.tpl" no_adv_link=true simple_search=$smarty.capture.simple_search dispatch=$dispatch view_type="templates"}

</form>

{if $in_popup}
    </div></div>
{else}
    </div><hr>
{/if}
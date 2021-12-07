{capture name="mainbox"}
    <form action="{""|fn_url}" method="post" name="otp_manage_form" id="wk_elm_otp_labels">
        {assign var="return_current_url" value=$config.current_url|escape:url}
        {assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
        {if $labels}
	
            <table class="table table-middle table--relative table-responsive products-table" width="100%">
                <thead data-ca-bulkedit-default-object="true">
                <tr>
                    <th width="5%" class="center">{include file="common/check_items.tpl"}</th>
                    <th width="10%">{__("position")}</th>    
                    <th width="40%">{__("otp_state")}</th>    
                    <th width="10%">{__("selected_status")}</th>
		{if "ULTIMATE"|fn_allowed_for && $runtime.company_id eq '0' }
		    <th width="30%" class="center">{__("storefront_name")}</th> 
		{/if}  
                    <th width="15%"></th>
		    <th width="9%" class="right">{__("status")}</th> 
                </tr>
                </thead>
                <tbody>
                {foreach from=$labels item=label}
                    <tr class="cm-row-status cm-row-status-{$label.status|lower}">
                        <td class="center">
                            <input type="checkbox" name="label_ids[]" value="{$label.id}" class="checkbox cm-item" />
                        </td>
                        <td class="row-status" data-th="{__("position")}">{$label.position}</td>
                        <td class="row-status" data-th="{__("otp_state")}">
                        {if fn_check_permissions('wk_order_tracking', 'update', 'admin')}
                            <a class="row-status" href="{"wk_order_tracking.update?id=`$label.id`"|fn_url}">{$label.title|truncate:100 nofilter}</a>
                        {else}
                            {$label.title|truncate:100 nofilter}
                        {/if}
                        <br/>
                        <small>{$label.description}</small>
                        </td>
                        <td class="row-status" data-th="{__("selected_status")}">
                            <div>
                                {foreach from=$label.statuses item=status}
                                    <span class="otp-selected-status">{$status}</span>
                                {/foreach}
                            </div>
                        </td>
			{if "ULTIMATE"|fn_allowed_for && $runtime.company_id eq '0' }
			<td class="row-status center" data-th="{__("storefront_id")}">
                            <div>
                                {$label.storefront_name}
                            </div>
                        </td>
			{/if}
                        <td  class="nowrap">
                            <div class="hidden-tools">
                                {capture name="tools_list"}
                                    <li>
                                        {btn type="list" text=__("edit") href="wk_order_tracking.update?id=`$label.id`"}
                                    </li>
                                    <li>
                                        {btn type="list" text=__("delete") class="cm-confirm cm-post" href="wk_order_tracking.delete?id=`$label.id`"}
                                    </li>
                                {/capture}
                                {dropdown content=$smarty.capture.tools_list}
                            </div>
                        </td>
			<td class="row-status right" data-th="{__("status")}">
                            <div>
                                {if $label.statusad eq '1'}
			{__("check_active")}
				{else}
			{__("check_disabled")}
				{/if}
                            </div>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
            </table>
	
        {else}
            <p class="no-items">{__("no_items")}</p>
        {/if}
    </form>
{/capture}

{capture name="buttons"}
    {include file="common/tools.tpl" tool_href="wk_order_tracking.update" prefix="top" title=__("otp_add_label") hide_tools=true icon="icon-plus"}
    {capture name="tools_list"}
        {if $labels}
            <li>
                {btn type="delete_selected" dispatch="dispatch[wk_order_tracking.m_delete]" form="wk_elm_otp_labels"}
            </li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{include file="common/mainbox.tpl" title=__("order_tracking_page") content=$smarty.capture.mainbox  sidebar=$smarty.capture.sidebar sidebar_position="left" adv_buttons=$smarty.capture.adv_buttons buttons=$smarty.capture.buttons select_languages=true content_id="manage_tickets"}

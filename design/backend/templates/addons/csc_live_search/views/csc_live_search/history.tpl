{capture name="mainbox"}
	{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="search_history_`$runtime.action``$search.user_id`"}
    {assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
    {assign var="rev" value="search_history_`$runtime.action``$search.user_id`"}
    {assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
    {assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}    
    <form action="{""|fn_url}" method="post" name="history_form" class="form-horizontal form-edit cm-check-changes" enctype="multipart/form-data">        
        {include file="addons/`$addon_base_name`/views/`$addon_base_name`/history/`$runtime.action`.tpl"}
	</form>
    {include file="common/pagination.tpl" div_id="search_history_`$runtime.action``$search.user_id`"}   
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        <li>{btn type="list" class="cm-confirm cm-post" text=__("delete_selected") dispatch="dispatch[csc_live_search.m_delete_history]" form="history_form" }</li>
        <li>{btn type="list" class="cm-confirm cm-post" text=__("cleanup_history") href="csc_live_search.delete_all"}</li>        
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}
{capture name="sidebar"}
	{include file="addons/`$addon_base_name`/components/submenu.tpl"}
    {include file="addons/`$addon_base_name`/views/`$addon_base_name`/components/sales_reports_search_form.tpl" period=$search.period search=$search dispatch="`$runtime.controller`.`$runtime.mode`.`$runtime.action`"}       
{/capture}

{if $in_popup}
    {$smarty.capture.mainbox nofilter}
{else}
    {include file="common/mainbox.tpl" title=__("cls.history_`$runtime.action`") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons   mainbox_content_wrapper_class="csc-settings" sidebar=$smarty.capture.sidebar}
{/if}

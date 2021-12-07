{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="reports_form" class="{if ""|fn_check_form_permissions} cm-hide-inputs{/if}">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}

{if $reports}
    <div class="table-responsive-wrapper">
        <table class="table table-middle table-responsive">
            <thead>
                <tr>
                    <th width="1%" class="mobile-hide">{include file="common/check_items.tpl"}</th>
                    <th width="25%">{__("sd_amz_submission_id")}</th>
                    <th width="20%">{__("sd_amz_region")}</th>
                    <th width="23%"><a class="cm-ajax" href="{"`$c_url`&sort_by=datetime&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("sd_amz_submit_date")}{if $search.sort_by == "datetime"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
                    <th width="12%">{__("messages")}</th>
                    <th width="12%">{__("type")}</th>

                    {hook name="amazon_reports:manage_header"}{/hook}

                    <th width="10%">&nbsp;</th>
                </tr>
            </thead>

            {foreach from=$reports item=report}

                {assign var="allow_save" value=$reports|fn_allow_save_object:'amazon_reports'}

                {if $allow_save}
                    {assign var="link_text" value=__("view")}
                    {assign var="additional_class" value="cm-no-hide-input"}
                    {assign var="status_display" value=""}
                {else}
                    {assign var="link_text" value=__("view")}
                    {assign var="additional_class" value="cm-hide-inputs"}
                    {assign var="status_display" value="text"}
                {/if}

                <tr class="cm-row-status">
                    <td class="mobile-hide">
                        <input name="report_ids[]" type="checkbox" value="{$report.report_id}" class="cm-item" />
                    </td>
                    <td data-th="{__("sd_amz_submission_id")}">
                        {if $report.sync_type == 1}
                            {include file="common/popupbox.tpl" id="group_export_{$report.feed_id}" act=$act|default:"edit" link_text=$report.feed_id href="amazon_reports.view?region=`$report.region`&feed_id=`$report.feed_id`&sync_type=1"}
                        {elseif $report.sync_type == 2}
                            {include file="common/popupbox.tpl" id="group_import_{$report.feed_id}" act=$act|default:"edit" link_text=$report.feed_id href="amazon_reports.view?region=`$report.region`&feed_id=`$report.feed_id`&sync_type=2"}
                        {/if}
                    </td>
                    <td data-th="{__("sd_amz_region")}">
                        {__("sd_amz_`$report.region`")}
                    </td>
                    <td data-th="{__("sd_amz_submit_date")}">
                        {$report.datetime|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
                    </td>
                    <td data-th="{__("messages")}">
                        {$report.quantity}
                    </td>
                    <td data-th="{__("type")}">
                        {if $report.sync_type == 1}
                            {__("export")}
                        {elseif $report.sync_type == 2}
                            {__("import")}
                        {/if}
                    </td>

                    {hook name="amazon_reports:manage_data"}{/hook}

                    <td class="right" data-th="{__("action")}">
                        <div class="hidden-tools">
                            {capture name="tools_list"}
                                {hook name="amazon_reports:list_extra_links"}
                                <li>
                                {if $report.sync_type == 1}
                                    {include file="common/popupbox.tpl" id="group_export_{$report.feed_id}" act=$act|default:"edit" link_text=$link_text text="$report.feed_id" href="amazon_reports.view?region=`$report.region`&feed_id=`$report.feed_id`&sync_type=1"}
                                {elseif $report.sync_type == 2}
                                    {include file="common/popupbox.tpl" id="group_import_{$report.feed_id}" act=$act|default:"edit" link_text=$link_text text="$report.feed_id" href="amazon_reports.view?region=`$report.region`&feed_id=`$report.feed_id`&sync_type=2"}
                                {/if}
                                </li>
                                {if $allow_save}
                                    <li>{btn type="list" text=__("delete") class="cm-confirm" href="amazon_reports.delete?report_id=`$report.report_id`" method="POST"}</li>
                                {/if}
                                {/hook}
                            {/capture}
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

{include file="common/pagination.tpl"}

{capture name="buttons"}
    {capture name="tools_list"}
        {hook name="amazon_reports:manage_tools_list"}
            {if $reports}
                <li>{btn type="delete_selected" dispatch="dispatch[amazon_reports.m_delete]" form="reports_form"}</li>
            {/if}
        {/hook}
    {/capture}
    {dropdown content=$smarty.capture.tools_list class="mobile-hide"}
{/capture}

{capture name="sidebar"}
    {include file="addons/sd_amazon_products/views/amazon_reports/components/amazon_reports_search_form.tpl" period=$search.period}
{/capture}

</form>
{/capture}

{include file="common/mainbox.tpl" title=__("sd_amz_reports") content=$smarty.capture.mainbox sidebar=$smarty.capture.sidebar buttons=$smarty.capture.buttons tools=$smarty.capture.tools}

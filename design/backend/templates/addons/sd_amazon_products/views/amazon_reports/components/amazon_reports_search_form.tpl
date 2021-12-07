<div class="sidebar-row">
    <form action="{""|fn_url}" method="get" name="report_form_{$report.report_id}">
    <h6>{__("search")}</h6>
        {capture name="simple_search"}
            <input type="hidden" name="selected_section" value="">

            <div class="sidebar-field">
                <label for="tag_status_identifier">{__("type")}</label>
                <select name="sync_type" id="type_identifier">
                    <option value="">{__("all")}</option>
                    <option value="2"{if $search.sync_type == 2} selected="selected"{/if}>{__("import")}</option>
                    <option value="1"{if $search.sync_type == 1} selected="selected"{/if}>{__("export")}</option>
                </select>
            </div>
            
            {include file="common/period_selector.tpl" period=$period display="form"}
        {/capture}

        {include file="common/advanced_search.tpl" no_adv_link=true simple_search=$smarty.capture.simple_search not_saved=true dispatch="amazon_reports.manage"}
    </form>
</div>
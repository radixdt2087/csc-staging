<div class="amazon-export-summary table-responsive-wrapper">
    {if $export_result.count_fail >= 0 && $export_result.count_submitted >= 1}
        <div class="alert alert-info">
            {__("sd_amz_export_info_msg", ["[url]" => $export_result.report_url, "[id]" => $export_result.submission_id])}
        </div>
    {/if}

    {if !empty($export_result.correct_products)}
        <div class="alert alert-success">
            {__("sd_amz_export_success_msg")}
        </div>
        <table width="100%" class="table table-bordered table-middle table-striped table-responsive">
        <thead>
            <tr>
                <th width="12%">{__('link')}</th>
                <th width="88%">{__('title')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$export_result.correct_products key=id item=product name="correct_products"}
                <tr {if $smarty.foreach.correct_products.first} class="no-border"{/if}>
                    <td width="12%" data-th="{__("link")}"><strong><a href="{"products.update?product_id=`$id`&selected_section=amazon"|fn_url}" target="_blank" class="pseudo-a">{$id}</a></strong></td>
                    <td width="88%" data-th="{__("title")}">{$product}</td>
                </tr>
            {/foreach}
        </tbody>
        </table>
    {/if}

    {if !empty($export_result.broken_products)}
        <div class="alert alert-error">
            {__("sd_amz_export_failed_export_msg")}
        </div>
        <table width="100%" class="table table-bordered table-middle table-striped table-responsive">
        <thead>
            <tr>
                <th width="12%">{__('link')}</th>
                <th width="88%">{__('title')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$export_result.broken_products key=id item=product name="broken_products"}
                <tr {if $smarty.foreach.broken_products.first} class="no-border"{/if}>
                    <td width="12%" class="text-error" data-th="{__("link")}"><strong><a href="{"products.update?product_id=`$id`&selected_section=amazon"|fn_url}" target="_blank" class="pseudo-a">{$id}</a></strong></td>
                    <td width="88%" class="text-error" data-th="{__("title")}">{$product}</td>
                </tr>
            {/foreach}
        </tbody>
        </table>
    {/if}

    <table width="100%" class="table table-no-hover table-responsive">
        <tr class="no-border">
            <td width="60%"><strong>{__('sd_amz_count_product_successfully_exported')}</strong></td>
            <td align="right">{$export_result.count_submitted}&nbsp;{__('items')}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_count_product_fail_exported')}</strong></td>
            <td align="right">{$export_result.count_fail}&nbsp;{__('items')}</td>
        </tr>
    </table>
    <div>
        <a class="btn cm-notification-close pull-right">{__("close")}</a>
    </div>
</div>

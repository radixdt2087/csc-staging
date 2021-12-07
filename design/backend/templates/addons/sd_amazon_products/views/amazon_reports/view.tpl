<div class="table-responsive-wrapper" id="content_group_export_{$feed_id}">
    {if !empty($report.ProcessingReport.Result)}
        <h4 class="text-notice">{__('messages')} [{__('status')|lcfirst}: {$report.ProcessingReport.StatusCode}]</h4>
        <table width="100%" class="table table-no-hover table-responsive">
            {if $report.ProcessingReport.Result.ResultCode == "Error"}
                <tr class="no-border">
                    <td class="text"><strong title="{__('exception_error_code')}">Message: {$report.ProcessingReport.Result.MessageID}</strong>: {$report.ProcessingReport.Result.ResultDescription}</td>
                </tr>
            {else}
                {foreach from=$report.ProcessingReport.Result item=notice name="notices"}
                    {$product_id = $notice.AdditionalInfo.SKU|fn_get_product_id_by_sku}
                    <tr {if $smarty.foreach.notices.first} class="no-border"{/if}>
                        <td class="text-{$notice.ResultCode|lcfirst}"><strong title="{__('exception_error_code')} {$notice.ResultMessageCode}">Message: {$notice.MessageID}</strong>{if $notice.AdditionalInfo.SKU} - SKU: <a href="{"products.update?product_id=`$product_id`"|fn_url}" target="_blank" class="pseudo-a">{$notice.AdditionalInfo.SKU}</a> {/if} - {$notice.ResultDescription}</td>
                    </tr>
                {/foreach}
            {/if}
        </table>
    {else}
        <div class="alert alert-success">
            <p>{__("sd_amz_export_success_msg")}</p>
        </div>
    {/if}
    <table width="100%" class="table table-no-hover table-responsive">
        <tr class="no-border">
            <td width="60%"><strong>{__('sd_amz_count_product_successfully_processed')}</strong></td>
            <td align="right">{$report.ProcessingReport.ProcessingSummary.MessagesProcessed}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_count_product_successfully_exported')}</strong></td>
            <td align="right">{$report.ProcessingReport.ProcessingSummary.MessagesSuccessful}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_count_product_fail_exported')}</strong></td>
            <td align="right">{$report.ProcessingReport.ProcessingSummary.MessagesWithError}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_count_product_fail_warning')}</strong></td>
            <td align="right">{$report.ProcessingReport.ProcessingSummary.MessagesWithWarning}</td>
        </tr>
    </table>
<!--content_group_export_{$feed_id}--></div>

<div id="content_group_import_{$feed_id}">
    {if !empty($report.import_product_list)}
        {assign var="import_product_list" value=$report.import_product_list|unserialize}

        <div class="alert alert-success">
            {__("sd_amz_import_success_msg")}
        </div>
        <table width="100%" class="table table-bordered table-middle table-striped table-responsive">
        <thead>
            <tr>
                <th width="12%">{__('link')}</th>
                <th width="52%">{__('title')}</th>
                <th width="36%">{__('sd_amz_imported')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$import_product_list.correct_products key=id item=product name="correct_products"}
                <tr {if $smarty.foreach.correct_products.first} class="no-border"{/if}>
                    <td width="12%" data-th="{__("link")}"><strong><a href="{"products.update?product_id=`$id`"|fn_url}" target="_blank" class="pseudo-a">{$id}</a></strong></td>
                    <td width="52%" data-th="{__("title")}">{$product}</td>
                    <td width="36%" data-th="{__("sd_amz_imported")}">{$import_product_list.imported_attrs[$id]}</td>
                </tr>
            {/foreach}
        </tbody>
        </table>
    {/if}

    {if !empty($import_product_list.broken_products)}
        <div class="alert alert-error">
            {__("sd_amz_export_failed_import_msg")}
        </div>
        <table width="100%" class="table table-bordered table-middle table-striped table-responsive">
        <thead>
            <tr>
                <th width="12%">{__('link')}</th>
                <th width="88%">{__('title')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$import_product_list.broken_products key=id item=product name="broken_products"}
                <tr {if $smarty.foreach.broken_products.first} class="no-border"{/if}>
                    <td width="12%" class="text-error" data-th="{__("link")}"><strong><a href="{"products.update?product_id=`$id`"|fn_url}" target="_blank" class="pseudo-a">{$id}</a></strong></td>
                    <td width="88%" class="text-error" data-th="{__("title")}">{$product}</td>
                </tr>
            {/foreach}
        </tbody>
        </table>
    {/if}

    <table width="100%" class="table table-no-hover table-responsive">
        <tr class="no-border">
            <td width="60%"><strong>{__('sd_amz_import_count_processed')}</strong></td>
            <td align="right">{$import_product_list.count_success}&nbsp;{__('items')}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_import_count_skip')}</strong></td>
            <td align="right">{$import_product_list.count_skip}&nbsp;{__('items')}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_import_count_fail')}</strong></td>
            <td align="right">{$import_product_list.count_fail}&nbsp;{__('items')}</td>
        </tr>
    </table>
<!--content_group_import_{$feed_id}--></div>

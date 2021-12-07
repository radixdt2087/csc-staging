<div class="amazon-export-summary table-responsive-wrapper">
    {if $import_result.count_fail >= 0 && $import_result.count_success >= 1}
        <div class="alert alert-info">
            {__("sd_amz_import_info_msg", ["[url]" => $import_result.report_url, "[id]" => $import_result.submission_id])}
        </div>
    {/if}

    {if !empty($import_result.correct_products)}
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
            {foreach from=$import_result.correct_products key=id item=product name="correct_products"}
                <tr {if $smarty.foreach.correct_products.first} class="no-border"{/if}>
                    <td width="12%" data-th="{__("link")}"><strong><a href="{"products.update?product_id=`$id`"|fn_url}" target="_blank" class="pseudo-a">{$id}</a></strong></td>
                    <td width="52%" data-th="{__("title")}">{$product}</td>
                    <td width="36%" data-th="{__("sd_amz_imported")}">{$import_result.imported_attrs[$id]}</td>
                </tr>
            {/foreach}
        </tbody>
        </table>
    {/if}

    {if !empty($import_result.broken_products)}
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
            {foreach from=$import_result.broken_products key=id item=product name="broken_products"}
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
            <td align="right">{$import_result.count_success}&nbsp;{__('items')}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_import_count_skip')}</strong></td>
            <td align="right">{$import_result.count_skip}&nbsp;{__('items')}</td>
        </tr>
        <tr>
            <td width="60%"><strong>{__('sd_amz_import_count_fail')}</strong></td>
            <td align="right">{$import_result.count_fail}&nbsp;{__('items')}</td>
        </tr>
    </table>
    <div>
        <a class="btn cm-notification-close pull-right">{__("close")}</a>
    </div>
</div>

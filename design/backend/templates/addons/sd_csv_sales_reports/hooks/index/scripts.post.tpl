{if $sd_check_sales_report_page}
    <script type="text/javascript">
    (function(_, $) {
        $.ceEvent('on', 'ce.commoninit', function() {
            var url, id;
            $('li[id^=table_]').each(function(index, element) {
                id = element.id.split('_');
                id = id[1];
                if (id) {
                    url = $('#table_' + id + ' a').attr('href');
                    url = url.replace('sales_reports.view', 'sales_reports.export');
                    if (!$('#content_table_' + id).is('#csv_export_link_' + id)) {
                        $('#csv_export_link_' + id).remove();
                        $('#content_table_' + id).prepend("<a id='csv_export_link_" + id + "' href=" + url + ">{__("sd_csv_sales_reports.export_report")}</a>");
                    }
                }
            });
        });
    }(Tygh, Tygh.$));
    </script>
{/if}
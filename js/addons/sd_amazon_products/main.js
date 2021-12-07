(function(_, $) {

    var products_prefix = 'addon_option_sd_amazon_products_',
        amz_market_lang = ['us', 'uk', 'jp', 'de'];

    $(_.doc).ready(function() {
        // Disable marketplace ID field
        $('[id^="' + products_prefix + 'marketplace_id__"]').attr('disabled', 'disabled');

        $.each(amz_market_lang, function(key, code) {
            // Flag for each marketplace
            $("#sd_amazon_products_" + code + "_section a").prepend('<i class="flag flag-' + code + '"></i>');

            // Language variables
            $("label[for='" + products_prefix + "is_active__" + code + "']").prepend(_.tr('is_active'));
            $("label[for='" + products_prefix + "seller_id__" + code + "']").prepend(_.tr('seller_id'));
            $("label[for='" + products_prefix + "marketplace_id__" + code + "']").prepend(_.tr('marketplace_id'));
            $("label[for='" + products_prefix + "aws_key_id__" + code + "']").prepend(_.tr('aws_key_id'));
            $("label[for='" + products_prefix + "secret_key__" + code + "']").prepend(_.tr('secret_key'));
        });
        
        $(_.doc).on('change', 'input[id^="' + products_prefix + 'is_active__"]', function (e) {
            if ($(this).is(":checked")) {
                var result = $(this).attr('id').match('^(.*)_(.*)$');
                var lang_code = result[2];

                var merchantId = $.trim($('#' + products_prefix + 'seller_id__' + lang_code).val());
                var marketPlaceId = $.trim($('#' + products_prefix + 'marketplace_id__' + lang_code).val());
                var awsKeyId = $.trim($('#' + products_prefix + 'aws_key_id__' + lang_code).val());
                var awsSecretKey = $.trim($('#' + products_prefix + 'secret_key__' + lang_code).val());

                if (!merchantId.length) {
                    $.ceNotification('show', {
                        type: 'E',
                        message_state: 'I',
                        title: _.tr('error'),
                        message: _.tr('sd_amz_merch_missing')
                    });

                    $(this).prop('checked', false);
                    return;
                }

                if (!awsKeyId.length) {
                    $.ceNotification('show', {
                        type: 'E',
                        message_state: 'I',
                        title: _.tr('error'),
                        message: _.tr('sd_amz_key_id_missing')
                    });

                    $(this).prop('checked', false);
                    return;
                }

                if (!awsSecretKey.length) {
                    $.ceNotification('show', {
                        type: 'E',
                        message_state: 'I',
                        title: _.tr('error'),
                        message: _.tr('sd_amz_secret_missing')
                    });

                    $(this).prop('checked', false);
                    return;
                }
            }
        });

        $(_.doc).on('click', 'a[id^="' + products_prefix + 'api_check__"]', function (e) {
            var result = $(this).attr('id').match('^(.*)_(.*)$');
            var lang_code = result[2];

            var merchantId = $.trim($('#' + products_prefix + 'seller_id__' + lang_code).val());
            var marketPlaceId = $.trim($('#' + products_prefix + 'marketplace_id__' + lang_code).val());
            var awsKeyId = $.trim($('#' + products_prefix + 'aws_key_id__' + lang_code).val());
            var awsSecretKey = $.trim($('#' + products_prefix + 'secret_key__' + lang_code).val());
            var marketPlaceRegion = lang_code;

            if (!$('input[id^="' + products_prefix + 'is_active__' + lang_code + '"]').is(":checked")) {
                $.ceNotification('show', {
                    type: 'E',
                    message_state: 'I',
                    title: _.tr('error'),
                    message: _.tr('sd_amz_activate_secton')
                });

                return;
            }

            $.ceAjax('request', fn_url('amazon.api_check'), {
                method: 'post',
                data: {
                    'merchantId': merchantId,
                    'marketPlaceId': marketPlaceId,
                    'awsKeyId': awsKeyId,
                    'awsSecretKey': encodeURIComponent(awsSecretKey),
                    'marketPlaceRegion': marketPlaceRegion
                }
            });
        });

        $(_.doc).on('click', '.cm-notification-content-extended .cm-notification-close', function() {
            location.reload(true);
        });
    });

}(Tygh, Tygh.$));

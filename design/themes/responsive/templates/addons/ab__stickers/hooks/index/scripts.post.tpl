{strip}
<script>
    (function (_, $) {
        $.extend(_, {
            ab__stickers: {
                timeouts: { },
                runtime: {
                    controller_mode: '{$runtime.controller}.{$runtime.mode}',
                    caching: Boolean({fn_ab__stickers_sticker_is_cache_allowed()}),
                },
            },
        });
    })(Tygh, Tygh.$);
</script>
{script src="js/addons/ab__stickers/func.js"}
{/strip}
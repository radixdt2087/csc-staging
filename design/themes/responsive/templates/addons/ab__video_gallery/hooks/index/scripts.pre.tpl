{if $runtime.controller == "products" && $runtime.mode == "view"}
    <script>
        (function (_, $) {
            var wrapper = $('.ab-vg-stickers-wrapper');
            if (wrapper.length) {
                var prev_w_size = 0;

                $(window).on('resize', function () {
                    if (prev_w_size !== window.innerWidth) {
                        prev_w_size = window.innerWidth;

                        wrapper.css('max-height', $('.ty-product-img a.cm-image-previewer').first().outerHeight() + 'px');
                    }
                });
            }
        })(Tygh, Tygh.$);
    </script>
{/if}
{script src="js/lib/owlcarousel/owl.carousel.min.js"}
<script>
(function(_, $) {
    $.ceEvent('on', 'ce.commoninit', function(context) {
        var elm = context.find('#scroll_list_{$block.block_id}');

        var item = {$block.properties.item_quantity|default:3},
            itemsDesktop = 3,
            itemsDesktopSmall = 3;
            itemsTablet = 2;

        if (item > 3) {
            itemsDesktop = item;
            itemsDesktopSmall = item - 1;
            itemsTablet = item - 2;
        } else if (item == 1) {
            itemsDesktop = itemsDesktopSmall = itemsTablet = 1;
        } else {
            itemsDesktop = item;
            itemsDesktopSmall = itemsTablet = item - 1;
        }

        {if $block.properties.outside_navigation == "YesNo::YES"|enum}
        function outsideNav () {
            if(this.options.items >= this.itemsAmount){
                $("#owl_outside_nav_{$block.block_id}").hide();
            } else {
                $("#owl_outside_nav_{$block.block_id}").show();
            }
        }
        {/if}

        if (elm.length) {
            elm.owlCarousel({
                direction: '{$language_direction}',
                items: item,
                itemsDesktop: [1199, itemsDesktop],
                itemsDesktopSmall: [979, itemsDesktopSmall],
                itemsTablet: [768, itemsTablet],
                itemsMobile: [479, 1],
                {if $block.properties.scroll_per_page == "YesNo::YES"|enum}
                scrollPerPage: true,
                {/if}
                {if $block.properties.not_scroll_automatically == "YesNo::YES"|enum}
                autoPlay: false,
                {else}
                autoPlay: '{$block.properties.pause_delay * 1000|default:0}',
                {/if}
                slideSpeed: {$block.properties.speed|default:400},
                stopOnHover: true,
                {if $block.properties.outside_navigation == "N"}
                navigation: true,
                navigationText: ['<i class="ty-icon-left-open-thin"></i>', '<i class="ty-icon-right-open-thin"></i>'],
                {/if}
                pagination: false,
            {if $block.properties.outside_navigation == "YesNo::YES"|enum}
                afterInit: outsideNav,
                afterUpdate : outsideNav
            });

              $('{$prev_selector}').click(function(){
                elm.trigger('owl.prev');
              });
              $('{$next_selector}').click(function(){
                elm.trigger('owl.next');
              });

            {else}
            });
            {/if}

        }
    });
}(Tygh, Tygh.$));
</script>
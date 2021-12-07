{script src="js/tygh/exceptions.js"}
<div class="ty-product-bigpicture">

    {if $product}
        {$obj_id = $product.product_id}

        {if $addons.sd_youtube.show_player_controls == "N"}
            {$player_controls = "&amp;controls=0"}
        {/if}

        {$redirect_url = "youtube_gallery.view"}

        {include file="common/product_data.tpl" product=$product but_role="big" but_text=__("add_to_cart") redirect_url="youtube_gallery.view"}

        <div class="ty-product-bigpicture__left">
            <div class="ty-product-bigpicture__left-wrapper">
                {if !$hide_title}
                    <a href="{"products.view&product_id=`$product.product_id`"|fn_url}"><h1 class="ty-product-block-title" {live_edit name="product:product:{$product.product_id}"}>{$product.product nofilter}</h1></a>
                {/if}

                {if !$no_images}
                    <div class="ty-product-bigpicture__img  cm-reload-{$product.product_id} ty-product-bigpicture__as-thumbs" >
                        <iframe class="sd-max-full-width"
                            src="https://www.youtube-nocookie.com/embed/{$product.youtube_link}?rel=0{$player_controls}"
                            width="640"
                            height="360"
                            frameborder="0"
                            allowfullscreen
                        ></iframe>
                    <!--product_images_{$product.product_id}_update--></div>
                {/if}
            </div>
        </div>

        <div class="ty-product-bigpicture__right">
            {$form_open = "form_open_{$obj_id}"}
            {$smarty.capture.$form_open nofilter}

            {$old_price = "old_price_{$obj_id}"}
            {$price = "price_{$obj_id}"}
            {$clean_price = "clean_price_{$obj_id}"}
            {$list_discount = "list_discount_{$obj_id}"}
            {$discount_label = "discount_label_{$obj_id}"}

            <div class="{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}prices-container {/if}price-wrap">
                {if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
                    <div class="ty-product-bigpicture__prices">
                        {if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price nofilter}{/if}
                {/if}

                {if $smarty.capture.$price|trim}
                    <div class="ty-product-block__price-actual">
                        {$smarty.capture.$price nofilter}
                    </div>
                {/if}

                {if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
                        {$smarty.capture.$clean_price nofilter}
                        {$smarty.capture.$list_discount nofilter}

                        {$discount_label = "discount_label_`$obj_prefix`{$obj_id}"}
                        {$smarty.capture.$discount_label nofilter}
                    </div>
                {/if}
            </div>

            <div class="ty-product-bigpicture__sidebar-bottom">

                {if $capture_options_vs_qty}{capture name="product_options"}{$smarty.capture.product_options nofilter}{/if}
                <div class="ty-product-block__option">
                    {$product_options = "product_options_{$obj_id}"}
                    {$smarty.capture.$product_options nofilter}
                </div>
                {if $capture_options_vs_qty}{/capture}{/if}

                {if $capture_options_vs_qty}{capture name="product_options"}{$smarty.capture.product_options nofilter}{/if}
                <div class="ty-product-block__advanced-option clearfix">
                    {$advanced_options = "advanced_options_{$obj_id}"}
                    {$smarty.capture.$advanced_options nofilter}
                </div>
                {if $capture_options_vs_qty}{/capture}{/if}

                <div class="ty-product-block__sku">
                    {$sku = "sku_{$obj_id}"}
                    {$smarty.capture.$sku nofilter}
                </div>

                {if $capture_options_vs_qty}{capture name="product_options"}{$smarty.capture.product_options nofilter}{/if}
                <div class="ty-product-block__field-group">
                    {$product_amount = "product_amount_{$obj_id}"}
                    {$smarty.capture.$product_amount nofilter}

                    {$qty = "qty_{$obj_id}"}
                    {$smarty.capture.$qty nofilter}

                    {$min_qty = "min_qty_{$obj_id}"}
                    {$smarty.capture.$min_qty nofilter}
                </div>
                {if $capture_options_vs_qty}{/capture}{/if}

                {$product_edp = "product_edp_{$obj_id}"}
                {$smarty.capture.$product_edp nofilter}

                {hook name="products:promo_text"}
                    {if $product.promo_text}
                        <div class="ty-product-block__note">
                            {$product.promo_text nofilter}
                        </div>
                    {/if}
                {/hook}

                {if $show_descr}
                {$prod_descr = "prod_descr_{$obj_id}"}
                    <h3 class="ty-product-block__description-title">{__("description")}</h3>
                    <div class="ty-product-block__description">{$smarty.capture.$prod_descr nofilter}</div>
                {/if}

                {if $capture_buttons}{capture name="buttons"}{/if}
                <div class="ty-product-block__button">
                    {if $show_details_button}
                        {include file="buttons/button.tpl" but_href="products.view?product_id=`$product.product_id`" but_text=__("view_details") but_role="submit"}
                    {/if}

                    {$add_to_cart = "add_to_cart_{$obj_id}"}
                    {$smarty.capture.$add_to_cart nofilter}

                    {$list_buttons = "list_buttons_{$obj_id}"}
                    {$smarty.capture.$list_buttons nofilter}
                </div>
                {if $capture_buttons}{/capture}{/if}
            </div>

            {$form_close = "form_close_{$obj_id}"}
            {$smarty.capture.$form_close nofilter}

            {hook name="products:product_detail_bottom"}
            {/hook}

            {if $show_product_tabs}
            {include file="views/tabs/components/product_popup_tabs.tpl"}
            {$smarty.capture.popupsbox_content nofilter}
            {/if}
        </div>
        <div class="clearfix"></div>
    {/if}

    {if $smarty.capture.hide_form_changed == "Y"}
        {$hide_form = $smarty.capture.orig_val_hide_form}
    {/if}

    {if $show_product_tabs}

        {include file="views/tabs/components/product_tabs.tpl"}

        {if $blocks.$tabs_block_id.properties.wrapper}
            {include file=$blocks.$tabs_block_id.properties.wrapper content=$smarty.capture.tabsbox_content title=$blocks.$tabs_block_id.description}
        {else}
            {$smarty.capture.tabsbox_content nofilter}
        {/if}

    {/if}
</div>

{capture name="mainbox_title"}{$details_page = true}{/capture}

{** block-description:tmpl_scroller **}
{if $settings.Appearance.enable_quick_view == "Y" && $block.properties.enable_quick_view == "Y"}
    {$quick_nav_ids = $items|fn_fields_from_multi_level:"product_id":"product_id"}    
    {$show_quick_view = true}
{/if}

{if $block.properties.hide_add_to_cart_button == "Y"}
    {$show_add_to_cart=false}
{else}
    {$show_add_to_cart=true}
{/if}
{if $block.properties.show_price == "Y"}
    {$hide_price=false}
{else}
    {$hide_price=true}
{/if}

{$show_trunc_name=true}
{$show_old_price=true}
{$show_price=true}
{$show_rating=true}
{$show_clean_price=true}
{$show_list_discount=true}
{$but_role="action"}
{$show_product_labels=true}
{$show_discount_label=true}
{$show_shipping_label=true}

{* FIXME: Don't move this file *}
{script src="js/tygh/product_image_gallery.js"}

{$obj_prefix="`$block.block_id`000"}
{$block.properties.outside_navigation = "N"}
{$block_id = $block.block_id}
{$block.block_id = "{$block.block_id}_{uniqid()}"}
{assign var="name" value="{$productdata[0]->Name}"}
{if $productdata}
 <div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list grid-list ty-scroller-advanced">
    {foreach from=$productdata item="data" name="productloop"}
    {$buy_url = "{$data->BuyUrl}"|replace:'{subid}':"{$auth.user_id}" }
    <div class="ty-scroller-list__item">
        <div class="merchant-name">{if $data@index > 0} {$name} {/if}</div>
        <div class="ty-grid-list__image">
            <a href = "{if $data@index == 0} {"companies.products?company_id=`$aff_merchant_details.company_id`"|fn_url} {else} {"products.view&product_id=238655&id=`$data->Id`"|fn_url} {/if}">
                <img src="{$data->Image}" loading="lazy" height="180" width="150"/>
            </a>
        </div>
        {if $data@index > 0}
            <a href="{"products.view&product_id=238655&id=`$data->Id`"|fn_url}">
                <div class="ty-grid-list__item-name">
                <bdi>{$data->Name|replace:'�':''|truncate:40:""}</bdi>
                </div>
            </a>
        {else}
            <div class="ty-grid-list__item-name"></div>
        {/if}
        <div class="ty-grid-list__price {if $data->Price == 0}ty-grid-list__no-price{/if}">{if $data->Price > 0}{$data->Price|format_price:$currencies.$secondary_currency:$span_id:$class:true:$live_editor_name:$live_editor_phrase nofilter}{/if}</div>
        {*if $block_id == 2367*}
          <div class="ty-grid-list__item-location">
            <a url="{$buy_url}" class="buy_now">Shop Direct</a>
            {if $data@index == 0}
            <a href="{"companies.products?company_id=`$aff_merchant_details.company_id`"|fn_url}">View More</a>
            {else}
            <a href="{"products.view&product_id=238655&id=`$data->Id`"|fn_url}">View More</a>
            {/if}
          </div>
        {*else if $data->IsUniversalLink == 1}
            <div class="ty-grid-list__item-location">
            <a url="{$buy_url}" class="buy_now">Shop Direct</a>
            <a href="{"companies.products?company_id=`$aff_merchant_details.company_id`"|fn_url}">View More</a>
          </div>
        {/if*}
    </div>
    {/foreach}
 </div>
 {else}
 <div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list grid-list ty-scroller-advanced">
    {foreach from=$items item="product" name="for_products"}
        {hook name="products:product_scroller_advanced_list"}
        <div class="ty-scroller-list__item">
            {hook name="products:product_scroller_advanced_list_item"}
            {if $product}
                {$obj_id=$product.product_id}
                {$obj_id_prefix="`$obj_prefix``$product.product_id`"}
                {include file="common/product_data.tpl" product=$product}

                <div class="ty-grid-list__item ty-quick-view-button__wrapper ty-left
                {if $show_quick_view} ty-grid-list__item--overlay{/if}">
                    {$form_open="form_open_`$obj_id`"}
                    {$smarty.capture.$form_open nofilter}

                    <div class="ty-grid-list__image">
                        {$product_labels="product_labels_`$obj_prefix``$obj_id`"}
                        {$smarty.capture.$product_labels nofilter}

                        {include file="views/products/components/product_icon.tpl" product=$product show_gallery=true}
                    </div>

                    {$rating="rating_$obj_id"}
                    {if $smarty.capture.$rating}
                        <div class="grid-list__rating">
                            {$smarty.capture.$rating nofilter}
                        </div>
                    {/if}

                    <div class="ty-grid-list__item-name">
                        {if $item_number == "Y"}
                            <span class="item-number">{$cur_number}.&nbsp;</span>
                            {math equation="num + 1" num=$cur_number assign="cur_number"}
                        {/if}

                        {$name="name_$obj_id"}
                        <bdi>{$smarty.capture.$name nofilter}</bdi>
                    </div>

                    {if !$hide_price}
                        <div class="ty-grid-list__price {if $product.price == 0}ty-grid-list__no-price{/if}">
                            {$old_price="old_price_`$obj_id`"}
                            {if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price nofilter}{/if}

                            {$price="price_`$obj_id`"}
                            {$smarty.capture.$price nofilter}

                            {$clean_price="clean_price_`$obj_id`"}
                            {$smarty.capture.$clean_price nofilter}

                            {$list_discount="list_discount_`$obj_id`"}
                            {$smarty.capture.$list_discount nofilter}
                        </div>
                    {/if}

                    {capture name="product_multicolumns_list_control_data_wrapper"}
                        <div class="ty-grid-list__control">
                            {capture name="product_multicolumns_list_control_data"}
                                {if $show_quick_view}
                                    {include file="views/products/components/quick_view_link.tpl" quick_nav_ids=$quick_nav_ids}
                                {/if}

                                {if $show_add_to_cart}
                                    <div class="button-container">
                                        {$add_to_cart = "add_to_cart_`$obj_id`"}
                                        {$smarty.capture.$add_to_cart nofilter}
                                    </div>
                                {/if}
                            {/capture}
                            {$smarty.capture.product_multicolumns_list_control_data nofilter}
                        </div>
                    {/capture}


                    {if $smarty.capture.product_multicolumns_list_control_data|trim}
                        {$smarty.capture.product_multicolumns_list_control_data_wrapper nofilter}
                    {/if}

                    {$form_close="form_close_`$obj_id`"}
                    {$smarty.capture.$form_close nofilter}
                </div>
            {/if}
            {/hook}
        </div>
        {/hook}
    {/foreach}
</div>
{/if}
{include file="common/scroller_init.tpl"}


{if $products}

    {script src="js/tygh/exceptions.js"}

    {if !$no_pagination}
        {include file="common/pagination.tpl"}
    {/if}

    {if !$no_sorting}
        {*include file="views/products/components/sorting.tpl"*}
    {/if}

    {if !$show_empty}
        {split data=$products size=$columns|default:"2" assign="splitted_products"}
    {else}
        {split data=$products size=$columns|default:"2" assign="splitted_products" skip_complete=true}
    {/if}

    {math equation="100 / x" x=$columns|default:"2" assign="cell_width"}
    {if $item_number == "Y"}
        {assign var="cur_number" value=1}
    {/if}

    {* FIXME: Don't move this file *}
    {script src="js/tygh/product_image_gallery.js"}

    {if $settings.Appearance.enable_quick_view == 'Y'}
        {$quick_nav_ids = $products|fn_fields_from_multi_level:"product_id":"product_id"}
    {/if}
    {if $search.search_cid}
    <a class="back-btn ty-btn">Back</a>
    {/if}
    <div class="grid-list">
        {strip}
            {foreach from=$splitted_products item="sproducts" name="sprod"}
                {foreach from=$sproducts item="product" name="sproducts"}
                    <div class="ty-column{$columns}">
                        {if $product}
                            {assign var="obj_id" value=$product.product_id}
                            {assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
                            {include file="common/product_data.tpl" product=$product}
                            {if $product.merchantId}
                            <div class="ty-grid-list__item ty-quick-view-button__wrapper {if $product.merchant }aff-vendors{else}aff-products{/if}">
                                {$buy_url = "{$product.BuyUrl}"|replace:'{subid}':"{$auth.user_id}" }
                                <div class="ty-scroller-list__item">
                                    <div class="merchant-name">
                                        {if !$product.merchant}
                                         <a url="{$buy_url}" class="buy_now">{$product.merchantName}</a>
                                        {/if}
                                    </div>
                                    <a class="product-affiliate-redirect" href="{if $product.merchant} {"companies.products?company_id=`$product.company_id`"|fn_url} {else} {"products.view&product_id=`$product.product_id`&id=`$product.product_code`"|fn_url} {/if}">
                                        <div class="ty-grid-list__image">
                                            <img src="{$product.img}" width="240px" loading="lazy" />
                                        </div>
                                        <div class="ty-grid-list__item-name">
                                        <bdi>{$product.product}</bdi>
                                        </div>
                                        {if !$product.merchant}
                                        <div class="ty-grid-list__price ">{include file="common/price.tpl" value=$product.price}</div>
                                        {/if}
                                    </a>
                                    {* <div class="ty-grid-list__item-location">
                                        <a url="{$buy_url}" class="buy_now">Shop Direct</a>
                                        {if $product.company_id}
                                            <a href="{"companies.products?company_id=`$product.company_id`"|fn_url}">View More</a>
                                        {else}
                                            <a href="{"products.view&product_id=`$product.product_id`&id=`$product.product_code`"|fn_url}">View More</a>
                                        {/if}
                                    </div> *}
                                </div>
                            </div>
                            {else}
                            <div class="ty-grid-list__item ty-quick-view-button__wrapper 
                                {if $settings.Appearance.enable_quick_view == 'Y' || $show_features} ty-grid-list__item--overlay{/if} cs-products">
                                {assign var="form_open" value="form_open_`$obj_id`"}
                                {$smarty.capture.$form_open nofilter}
                                {if $search.dispatch == 'products.search'}
                                <div class="merchant-name"> <a href= "{"companies.products?company_id=`$product.company_id`"|fn_url}">{$product.company_name}</a></div>
                                {/if}
                                {hook name="products:product_multicolumns_list"}
                                
                                        <div class="ty-grid-list__image">
                                            {include file="views/products/components/product_icon.tpl" product=$product show_gallery=true}
                                        </div>
                                        <div class="ty-grid-list__item-name">
                                            {if $item_number == "Y"}
                                                <span class="item-number">{$cur_number}.&nbsp;</span>
                                                {math equation="num + 1" num=$cur_number assign="cur_number"}
                                            {/if}
                                            {assign var="name" value="name_$obj_id"}
                                            <bdi>{$smarty.capture.$name nofilter}</bdi>
                                        </div>

                                        {assign var="rating" value="rating_$obj_id"}
                                        {if $smarty.capture.$rating}
                                            <div class="grid-list__rating">
                                                {$smarty.capture.$rating nofilter}
                                            </div>
                                        {/if}
                                            <div class="ty-grid-list__price {if $product.price == 0}ty-grid-list__no-price{/if}">
                                                {assign var="old_price" value="old_price_`$obj_id`"}
                                                {if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price nofilter}{/if}

                                                {assign var="price" value="price_`$obj_id`"}
                                                {$smarty.capture.$price nofilter}

                                                {assign var="clean_price" value="clean_price_`$obj_id`"}
                                                {$smarty.capture.$clean_price nofilter}

                                                {assign var="list_discount" value="list_discount_`$obj_id`"}
                                                {$smarty.capture.$list_discount nofilter}
                                            </div>
                                      
                                        {capture name="product_multicolumns_list_control_data_wrapper"}
                                            <div class="ty-grid-list__control">
                                                {capture name="product_multicolumns_list_control_data"}
                                                    {hook name="products:product_multicolumns_list_control"}
                                                        {if $settings.Appearance.enable_quick_view == 'Y'}
                                                            {include file="views/products/components/quick_view_link.tpl" quick_nav_ids=$quick_nav_ids}
                                                        {/if}

                                                        {if $show_add_to_cart}
                                                            <div class="button-container">
                                                                {$add_to_cart = "add_to_cart_`$obj_id`"}
                                                                {$smarty.capture.$add_to_cart nofilter}
                                                            </div>
                                                        {/if}
                                                    {/hook}
                                                {/capture}
                                                {$smarty.capture.product_multicolumns_list_control_data nofilter}
                                            </div>
                                        {/capture}

                                        {if $smarty.capture.product_multicolumns_list_control_data|trim}
                                            {$smarty.capture.product_multicolumns_list_control_data_wrapper nofilter}
                                        {/if}
                                {/hook}
                                {assign var="form_close" value="form_close_`$obj_id`"}
                                {$smarty.capture.$form_close nofilter}
                            </div>
                            {/if}
                        {/if}
                    </div>
                {/foreach}
                {if $show_empty && $smarty.foreach.sprod.last}
                    {assign var="iteration" value=$smarty.foreach.sproducts.iteration}
                    {capture name="iteration"}{$iteration}{/capture}
                    {hook name="products:products_multicolumns_extra"}
                    {/hook}
                    {assign var="iteration" value=$smarty.capture.iteration}
                    {if $iteration % $columns != 0}
                        {math assign="empty_count" equation="c - it%c" it=$iteration c=$columns}
                        {section loop=$empty_count name="empty_rows"}
                            <div class="ty-column{$columns}">
                                <div class="ty-product-empty">
                                    <span class="ty-product-empty__text">{__("empty")}</span>
                                </div>
                            </div>
                        {/section}
                    {/if}
                {/if}
            {/foreach}
        {/strip}
    </div>
    {if !$no_pagination}
        {include file="common/pagination.tpl"}
    {/if}

{/if}

{capture name="mainbox_title"}{$title}{/capture}
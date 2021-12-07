{assign var="dropdown_id" value=$block.snapping_id}
{assign var="r_url" value=$config.current_url|escape:url}
{hook name="checkout:cart_content"}
    <div class="ty-dropdown-box" id="cart_status_{$dropdown_id}">
        <div id="sw_dropdown_{$dropdown_id}" class="ty-dropdown-box__title cm-combination">
        <a href="{"checkout.cart"|fn_url}" class="ac-title ty-hand">
            {hook name="checkout:dropdown_title"}
                {if $smarty.session.cart.amount}
                    <i class="ut2-icon-outline-cart filled"><span class="ty-minicart-count">{$smarty.session.cart.amount}</span></i><span>{__("cart")}<i class="ut2-icon-outline-expand_more"></i></span>
                {else}
                    <i class="ut2-icon-outline-cart empty"><span class="ty-minicart-count ty-hand empty">0</span></i><span>{__("cart")}<i class="ut2-icon-outline-expand_more"></i></span>
                {/if}
            {/hook}
        </a>
        </div>
        <div id="dropdown_{$dropdown_id}" class="cm-popup-box ty-dropdown-box__content ty-dropdown-box__content--cart hidden">
            {hook name="checkout:minicart"}
                <div class="cm-cart-content {if $block.properties.products_links_type == "thumb"}cm-cart-content-thumb{/if} {if $block.properties.display_delete_icons == "YesNo::YES"|enum}cm-cart-content-delete{/if}">
                        <div class="ty-cart-items">
                            {if $smarty.session.cart.amount}
                                <ul class="ty-cart-items__list">
                                    {hook name="index:cart_status"}
                                        {assign var="_cart_products" value=$smarty.session.cart.products|array_reverse:true}
                                        {foreach from=$_cart_products key="key" item="product" name="cart_products"}
                                            {hook name="checkout:minicart_product"}
                                            {if !$product.extra.parent}
                                                <li class="ty-cart-items__list-item">
                                                    {hook name="checkout:minicart_product_info"}
                                                    {if $block.properties.products_links_type == "thumb"}
                                                        <div class="ty-cart-items__list-item-image">
                                                            {include file="common/image.tpl" image_width="40" image_height="40" images=$product.main_pair no_ids=true lazy_load=false}
                                                        </div>
                                                    {/if}
                                                    <div class="ty-cart-items__list-item-desc">
                                                        <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$product.product|default:fn_get_product_name($product.product_id) nofilter}</a>
                                                    <p>
                                                        <span>{$product.amount}</span><span>&nbsp;x&nbsp;</span>{include file="common/price.tpl" value=$product.display_price span_id="price_`$key`_`$dropdown_id`" class="none"}
                                                    </p>
                                                    </div>
                                                    {if $block.properties.display_delete_icons == "YesNo::YES"|enum}
                                                        <div class="ty-cart-items__list-item-tools cm-cart-item-delete">
                                                            {if (!$runtime.checkout || $force_items_deletion) && !$product.extra.exclude_from_calculate}
                                                                {include file="buttons/button.tpl" but_href="checkout.delete.from_status?cart_id=`$key`&redirect_url=`$r_url`" but_meta="cm-ajax cm-ajax-full-render" but_target_id="cart_status*" but_role="delete" but_name="delete_cart_item"}
                                                            {/if}
                                                        </div>
                                                    {/if}
                                                    {/hook}
                                                </li>
                                            {/if}
                                            {/hook}
                                        {/foreach}
                                    {/hook}
                                </ul>
                            {else}
                                <div class="ty-cart-items__empty ty-center">{__("cart_is_empty")}</div>
                            {/if}
                            
                            {hook name="checkout:cart_subtotal"}
                            {if $smarty.session.cart.amount > 1 || $product.extra.buy_together}
                            <p class="b-top">{__("total_items")}:&nbsp;<span class="ty-float-right">{$smarty.session.cart.amount}&nbsp;{__("items")} {__("for")}&nbsp;&nbsp;<strong>{include file="common/price.tpl" value=$smarty.session.cart.display_subtotal}</strong></span><br>&nbsp;</p>
                            {/if}
                            {/hook}
                            
                        </div>

                    {if $block.properties.display_bottom_buttons == "YesNo::YES"|enum}
                        <div class="cm-cart-buttons ty-cart-content__buttons buttons-container{if $smarty.session.cart.amount} full-cart{else} hidden{/if}">
                            <div class="ty-float-left">
                                <a href="{"checkout.cart"|fn_url}" rel="nofollow" class="ty-btn ty-btn__secondary">{__("view_cart")}</a>
                            </div>
                            {if $settings.Checkout.checkout_redirect != "YesNo::YES"|enum}
                                <div class="ty-float-right">
                                    {include file="buttons/proceed_to_checkout.tpl" but_text=__("checkout")}
                                </div>
                            {/if}
                        </div>
                    {/if}

                </div>
            {/hook}
        </div>
    <!--cart_status_{$dropdown_id}--></div>
{/hook}
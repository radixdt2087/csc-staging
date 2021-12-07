{ab__hide_content bot_type="ALL"}
{if !$hide_form
    && $addons.call_requests.buy_now_with_one_click == "YesNo::YES"|enum
    && ($auth.user_id
        || $settings.Checkout.allow_anonymous_shopping == "allow_shopping"
    ) && $show_buy_now|default:true
}
    {$is_not_required_option = true}

    {foreach $product.product_options as $option}
        {if $option.required === "YesNo::YES"|enum}
            {$is_not_required_option = false}
            {break}
        {/if}
    {/foreach}

    {if $show_product_options || ($is_not_required_option || $details_page)}
        {if $details_page && $settings.abt__ut2.addons.call_requests.item_button == "N" || $settings.abt__ut2.addons.call_requests.item_button == "YesNo::YES"|enum}
            {include file="common/popupbox.tpl"
                href="call_requests.request?product_id={$product.product_id}&obj_prefix={$obj_prefix}"
                link_text=__("call_requests.buy_now_with_one_click")
                text=__("call_requests.buy_now_with_one_click")
                id="call_request_{$obj_prefix}{$product.product_id}"
                link_meta="ty-btn ty-cr-product-button cm-dialog-destroy-on-close"
                content=""
                dialog_additional_attrs=["data-ca-product-id" => $obj_id, "data-ca-dialog-purpose" => "call_request"]
            }
        {/if}
    {else}
        {include file="buttons/button.tpl" 
            but_text=__("call_requests.buy_now_with_one_click")
            but_href="products.view?product_id=`$product.product_id`"
            but_role="text"
            but_id="call_request_{$obj_prefix}{$product.product_id}"
            but_meta="btn ty-btn ty-btn__text ty-cr-product-button"
        }
    {/if}
{/if}
{/ab__hide_content}
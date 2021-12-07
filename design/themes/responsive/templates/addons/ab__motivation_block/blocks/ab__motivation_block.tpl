{if $ab__motivation_items}
    {hook name="ab__motivation_block:block"}
    {assign var="id" value=$ab__mb_id|default:"ab__mb_id_`$block.block_id`"}
    <div class="ab__motivation_block ab__{$addons.ab__motivation_block.template_variant}{if $ab__motivation_items} loaded{/if}" data-ca-product-id="{$product.product_id}" data-ca-result-id="{$id}">
        <div id="{$id}">
            {if $ab__motivation_items}
                <div class="ab__mb_items {$addons.ab__motivation_block.appearance_type_styles}{if $addons.ab__motivation_block.bg_color !="#ffffff"} colored{/if}{if $runtime.customization_mode.live_editor} ab-mb-live-editor{/if}"{if $addons.ab__motivation_block.bg_color !="#ffffff"} style="border-color: {$addons.ab__motivation_block.bg_color};"{/if}>
                    {include file="addons/ab__motivation_block/blocks/components/`$addons.ab__motivation_block.template_variant`.tpl"}
                </div>
            {/if}
        <!--{$id}--></div>
    </div>
    {/hook}
{/if}
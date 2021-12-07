{*big video block*}
{if !$product_video_block_id}
    {$product_video_block_id = $block.block_id}
{/if}
<div id="product_video_play">
    {if $play_video_product_data}
        {include file="addons/sd_youtube/views/youtube_gallery/component/play_video_block.tpl" product=$play_video_product_data show_sku=true show_rating=true show_old_price=true show_price=true show_list_discount=true show_clean_price=true details_page=true show_discount_label=true show_product_amount=true show_product_options=true hide_form=$smarty.capture.val_hide_form 	min_qty=true
	show_edp=true
	show_add_to_cart=true
	show_list_buttons=true
	but_role="action"
	capture_buttons=$smarty.capture.val_capture_buttons
	capture_options_vs_qty=$smarty.capture.val_capture_options_vs_qty
	separate_buttons=$smarty.capture.val_separate_buttons
	show_add_to_cart=true
	show_list_buttons=true
	but_role="action"
	block_width=true
	no_ajax=$smarty.capture.val_no_ajax
	show_product_tabs=true play_video=true}
    {/if}
<!--product_video_play--></div>

<div id="youtube_gallery_products_{$block.block_id}" class="sd-youtube-gallery">

{if $products}

    {if $category_data.product_columns}
        {assign var="product_columns" value=$category_data.product_columns}
    {else}
        {assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
    {/if}

    {include file="blocks/product_list_templates/products_multicolumns.tpl" columns=$product_columns video_gallery=true hide_layouts = true}

{elseif !$subcategories || $show_no_products_block}
    <p class="ty-no-items cm-pagination-container">{__("text_no_videos")}</p>
{else}
    <div class="cm-pagination-container"></div>
{/if}
<!--youtube_gallery_products_{$block.block_id}--></div>


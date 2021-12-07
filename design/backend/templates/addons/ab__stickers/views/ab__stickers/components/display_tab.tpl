<p class="muted" style="margin-bottom: 15px;">{__("ab__stickers.output_settings.tooltip")}</p>
<div class="control-group">
<label class="control-label" for="ab__stickers_display_on_detailed_pages">{__("product_details")}:</label>
<div class="controls">
<select name="sticker_data[display_on_detailed_pages]" id="ab__stickers_display_on_detailed_pages">
{foreach ["not_display", "small_size", "full_size"] as $display_size}
<option value="{$display_size}"{if $sticker_data.display_on_detailed_pages|default:"full_size" == $display_size} selected{/if}>{__("ab__stickers.output_settings.{$display_size}")}</option>
{/foreach}
</select>
</div>
</div>
{$product_templates_schema = fn_ab__stickers_get_templates_list()}
{$prohibited_list_positions = fn_ab__stickers_get_prohibited_list_positions()}
{$exclude_templates = [
"blocks/products/products_text_links.tpl",
"addons/product_variations/blocks/products/variations_list.tpl"
]}
{$small_icons_templates = [
"blocks/products/products_scroller.tpl",
"blocks/products/products_small_items.tpl",
"blocks/products/products_links_thumb.tpl"
]}
{$do_not_show_templates = [
"blocks/products/short_list.tpl"
]}
{foreach $product_templates_schema as $product_template}
{if $product_template@key|in_array:$exclude_templates}{continue}{/if}
{$default = "full_size"}
{if $product_template@key|in_array:$small_icons_templates}
{$default = "small_size"}
{/if}
{if $product_template@key|in_array:$do_not_show_templates}
{$default = "not_display"}
{/if}
<div class="control-group">
<label class="control-label" for="ab__stickers_display_on_{$product_template.name}">{$product_template.name}:</label>
<div class="controls">
<select name="sticker_data[display_on_lists][{$product_template@key}]" id="ab__stickers_display_on_{$product_template.name}" style="vertical-align:top">
{foreach ["not_display", "small_size", "full_size"] as $display_size}
<option value="{$display_size}"{if $sticker_data.display_on_lists.{$product_template@key}|default:$default == {$display_size}} selected{/if}>{__("ab__stickers.output_settings.{$display_size}")}</option>
{/foreach}
</select>
{if $prohibited_list_positions[$product_template@key]}
<ul style="list-style-type:none;display:inline-block;margin:0 0 15px 10px">
<li><b style="color:red">{__("warning")}</b>. {__("ab__stickers.list_has_prohibited_positions")}:</li>
{foreach $prohibited_list_positions[$product_template@key] as $prohibited_postion}
<li>{__("ab__stickers.output_position.`$prohibited_postion`")}</li>
{/foreach}
<li>{__("ab__stickers.list_has_prohibited_positions.docs")}</li>
</ul>
{/if}
</div>
</div>
{/foreach}
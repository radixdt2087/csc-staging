{$ab__vg_videos = $product.product_id|fn_ab__vg_get_videos}
{$total_count = ($product.image_pairs|count + $ab__vg_videos|count + 1)}
{$is_vertical = (($runtime.mode != 'quick_view') && ($addons.ab__video_gallery.vertical == "Y"))}

{assign var="th_size" value=$addons.ab__video_gallery.th_size|default:35}
{if $is_vertical}
{if $total_count == 0 || $total_count == 1}{$gal_width = 0}{elseif $total_count > 6 && $settings.Appearance.thumbnails_gallery == "N"}{$gal_width = ($th_size * 2 + 18)}{else}{$gal_width = ($th_size + 12)}{/if}
{else}
    {$gal_width = $th_size + 10}
{/if}

{$left_or_right = "left"}
{if $language_direction == "rtl"}
    {$left_or_right = "right"}
{/if}

{if $details_page && $total_count > 1}
    <!-- This wrapper was added by ab__video_gallery add-on -->
    <div class="ab-vg-stickers-wrapper" style="{if $is_vertical}{$left_or_right}{else}bottom{/if}:{$gal_width}px;{if $is_vertical}bottom{else}{$left_or_right}{/if}:0">
{/if}
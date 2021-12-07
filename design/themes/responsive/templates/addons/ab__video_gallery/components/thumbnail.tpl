{if $video.icon_type == 'icon' && $video.icon}
    {include file="common/image.tpl" images=$video.icon image_width=$width image_height=$height show_detailed_link=false}
{else}
    {$image = [
        'image_path' => "https://img.youtube.com/vi/{$video.youtube_id}/hqdefault.jpg",
        'image_x' => $width|default:0,
        'image_y' => $height|default:0,
        'alt' => $video.title|strip_tags,
        'relative_path' => "",
        'absolute_path' => ""
    ]}
    {include file="common/image.tpl" images=$image image_width=$image.image_x image_height=$image.image_y show_detailed_link=false}
{/if}
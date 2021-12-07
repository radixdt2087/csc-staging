{$image = [
    'image_path' => $icon_image|default:"`$images_dir`/addons/ab__video_gallery/youtube_logo.png",
    'image_x' => $icon_width|default:30,
    'image_y' => $icon_height|default:30,
    'alt' => $icon_alt|default:'',
    'relative_path' => '',
    'absolute_path' => ''
]}
{include file="common/image.tpl" images=$image class=$icon_class|default:"" image_width=$image.image_x image_height=$image.image_y show_detailed_link=false}
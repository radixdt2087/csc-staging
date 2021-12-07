{** block-description:ab__video_gallery_title_product **}

{$tmb_width = 360}
{$tmb_height = 240}

{$product_id = $product.product_id}
{if $product.master_product_id > 0}
    {$product_id = $product.master_product_id}
{/if}

{$ab__vg_videos = $product_id|fn_ab__vg_get_videos}

{if $ab__vg_videos}
<div id="content_ab__video_gallery" class="ab__video_gallery-block">
    <div class="ab__vg-videos">
        {foreach $ab__vg_videos as $video}
            <div class="ab__vg-video">
                <a class="cm-dialog-opener" data-ca-target-id="ab__vg_video_{$video.video_id}" rel="nofollow">
                    <span class="ab__vg-video_thumb">
                        {include file="addons/ab__video_gallery/components/thumbnail.tpl" video=$video width=$tmb_width height=$tmb_height}
                    </span>
                </a>
                <div class="ab__vg-video_title"><p><strong>{$video.title}</strong></p></div>
                <div class="ab__vg-video_description ty-wysiwyg-content">{$video.description nofilter}</div>
            </div>
        {/foreach}
    </div>
</div>
{/if}
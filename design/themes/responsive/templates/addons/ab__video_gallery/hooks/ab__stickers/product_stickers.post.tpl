{$ab__vg_videos = $product.product_id|fn_ab__vg_get_videos}
{$total_count = ($product.image_pairs|count + $ab__vg_videos|count + 1)}

{if $details_page && $total_count > 1}
    <!-- This wrapper was added by ab__video_gallery add-on -->
    </div>
{/if}
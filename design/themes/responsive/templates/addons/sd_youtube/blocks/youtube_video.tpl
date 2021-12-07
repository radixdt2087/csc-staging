{if !empty($items.youtube_link)}
    {if !$video_block_id}
        {$video_block_id = $block.block_id}
    {/if}

    {script src="js/addons/sd_youtube/func.js"}

    {if !empty($items.video_width)}
        {$video_width = "`$items.video_width`px"}
    {else}
        {$video_width = "100%"}
    {/if}
    {if ($items.visibility == "Tygh\Enum\VisibilityValues::AUTHORIZED"|constant && !empty($auth.user_id)) || ($items.visibility == "Tygh\Enum\VisibilityValues::UNAUTHORIZED"|constant && empty($auth.user_id)) || $items.visibility == "Tygh\Enum\VisibilityValues::ALL"|constant}
        <div class="sd-aspect-ratio-wrapper" {if !empty($items.video_width)}style="max-width: {$video_width}"{/if}>
            <div class="sd-aspect-ratio single-youtube-video">
                <iframe
                    class="sd-max-full-width"
                    style="max-width: {$video_width}"
                    {strip}
                    src="https://www.youtube-nocookie.com/embed/
                        {$items.youtube_link}?rel=0&amp;
                        {if $addons.sd_youtube.show_player_controls == "YesNo::NO"|enum}controls=0{/if}
                    {/strip}
                    frameborder="0"
                    allowfullscreen >
                </iframe>
            </div>
        </div>
    {/if}
{/if}

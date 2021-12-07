{** block-description:discussion_title_home_page **}

{assign var="discussion" value=0|fn_get_discussion:"E":true:$block.properties}

{if $discussion && $discussion.type != "D" && $discussion.posts}

{assign var="obj_prefix" value="`$block.block_id`000"}

{if $block.properties.outside_navigation == "Y"}
    <div class="owl-theme ty-owl-controls">
        <div class="owl-controls clickable owl-controls-outside" id="owl_outside_nav_{$block.block_id}">
            <div class="owl-buttons">
                <div id="owl_prev_{$obj_prefix}" class="owl-prev"><i class="ty-icon-left-open-thin"></i></div>
                <div id="owl_next_{$obj_prefix}" class="owl-next"><i class="ty-icon-right-open-thin"></i></div>
            </div>
        </div>
    </div>
{/if}

<div class="ty-mb-l">
    <div class="ty-scroller-discussion-list">
        <div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list">

        {foreach from=$discussion.posts item=post}
            <div class="ty-discussion-post__content ty-mb-l">

                {hook name="discussion:items_list_row"}

                <div class="ty-discussion-post {cycle values=", ty-discussion-post_even"}" id="post_{$post.post_id}">
                    {if $discussion.type == "Addons\\Discussion\\DiscussionTypes::TYPE_COMMUNICATION"|enum
                        || $discussion.type == "Addons\\Discussion\\DiscussionTypes::TYPE_COMMUNICATION_AND_RATING"|enum
                    }
                        <div class="ty-discussion-post__message {if  $settings.abt__ut2.addons.discussion.highlight_administrator === "Y" && $post.user_type === "A"}auth-admin{/if}">
                            <div class="ty-discussion-post__message-author">
                                <div class="ty-discussion-post__author">
                                    <div class="ty-discussion-post__avatar">
                                        {* if admin *}
                                        {if $settings.abt__ut2.addons.discussion.highlight_administrator === "Y" && $post.user_type === "A"}
                                            <i class="ut2-icon-outline-headset_mic"></i>
                                        {else}
                                            {mb_substr($post.name, 0, 1)}
                                        {/if}
                                    </div>
                                    <p><b>{$post.name}</b><br/>
                                    <span class="ty-discussion-post__date">{$post.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span></p>
                                </div>
                                {* if not Admin *}
                                {if $post.user_type !== "A"}
                                    <div class="ty-discussion-post__rating-stars">
                                        {if $discussion.type == "Addons\\Discussion\\DiscussionTypes::TYPE_RATING"|enum
                                            || $discussion.type == "Addons\\Discussion\\DiscussionTypes::TYPE_COMMUNICATION_AND_RATING"|enum
                                            && $post.rating_value > 0
                                        }
                                            <div class="clearfix ty-discussion-post__rating">
                                                {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$post.rating_value|fn_get_discussion_rating}
                                            </div>
                                        {/if}
                                    </div>
                                {/if}
                            </div>
                            <div class="ty-discussion-post__message-text">
                            {if mb_strlen($post.message) > 300}
                                    <div class="clipped">
                                        <p>{$post.message|escape|nl2br nofilter}</p>
                                    </div>
                                    <a class="ut2-more-btn" href="javascript:void(0);" onclick="$(this).prev().toggleClass('view');$(this).toggleClass('open');"><i class="ut2-icon-outline-expand_more"></i><span class="see-more">{__("abt__ut2.discussion.see_more")}</span><span class="see-less">{__("abt__ut2.discussion.see_less")}</span></a>
                                {else}
                                    <p>{$post.message|escape|nl2br nofilter}</p>                                 
                            {/if}
                            </div>
                        </div>
                    {/if}
                </div>

                {/hook}

            </div>
        {/foreach}

        </div>
    </div>
</div>

{include file="common/scroller_init_with_quantity.tpl" prev_selector="#owl_prev_`$obj_prefix`" next_selector="#owl_next_`$obj_prefix`" itemsDesktop=1 itemsDesktopSmall=2 itemsTablet=3 itemsTabletSmall=3}

{/if}

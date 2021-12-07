{* block-description:abt__ut2_advanced_subcategories_menu *}
{if $settings.abt__device == "mobile"}
    <a href="javascript:void(0);" rel="nofollow" class="ut2-btn-close" onclick="$(this).parent().prev().removeClass('open');$(this).parent().addClass('hidden');"><i class="ut2-icon-baseline-close"></i></a>
{/if}

{if $abt__ut2_subcategories}
    {$level = 0}
    <ul class="ut2-subcategories clearfix">

        {* parents *}
        {if $abt__ut2_subcategories.parents}
            {foreach $abt__ut2_subcategories.parents as $id => $category}
                <li class="ut2-item level-{$level}">
                    <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                        <span {live_edit name="category:category:{$category.category_id}"}>{$category.category}</span>
                    </a>
                </li>
                {$level = $level + 1}
            {/foreach}
        {/if}

        {* build children *}
        {capture name="subcategories"}
            {if $abt__ut2_subcategories.subcategories}
                {* increase level if tree *}
                {if $level > 0 || $abt__ut2_subcategories.siblings}
                    {$_l = $level + 1}
                {else}
                    {$_l = $level}
                {/if}

                {foreach $abt__ut2_subcategories.subcategories as $id => $category}
                    <li class="ut2-item level-{$_l}">
                        <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                            <span {live_edit name="category:category:{$category.category_id}"}>{$category.category}</span>
                        </a>
                    </li>
                {/foreach}
            {/if}
        {/capture}

        {* siblings *}
        {if $abt__ut2_subcategories.siblings}
            {foreach $abt__ut2_subcategories.siblings as $id => $category}
                {if $category.category_id == $_REQUEST.category_id}
                    <li class="ut2-item level-{$level} ut2-current-item">
                        <span {live_edit name="category:category:{$category.category_id}"}>{$category.category}</span>
                    </li>

                    {* print children after current *}
                    {$smarty.capture.subcategories nofilter}
                {else}
                    <li class="ut2-item level-{$level}">
                        <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                            <span {live_edit name="category:category:{$category.category_id}"}>{$category.category}</span>
                        </a>
                    </li>
                {/if}
            {/foreach}
        {elseif $abt__ut2_subcategories.current_category}
            <li class="ut2-item level-{$level} ut2-current-item">
                <span {live_edit name="category:category:{$abt__ut2_subcategories.current_category.category_id}"}>{$abt__ut2_subcategories.current_category.category}</span>
            </li>

            {* print children after current *}
            {$smarty.capture.subcategories nofilter}
        {else}
            {* print children if no current *}
            {$smarty.capture.subcategories nofilter}
        {/if}
    </ul>
{/if}
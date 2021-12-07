{if $filter.field_type == "YesNo::YES"|enum && !$filter.variants && !$filter.selected_variants}
    <span class="hidden">&nbsp;</span>
{elseif $filter.field_type == "YesNo::YES"|enum}
    {assign var="filter_uid" value="`$block.block_id`_`$filter.filter_id`"}
    {assign var="cookie_name_show_filter" value="content_`$filter_uid`"}
    {if $filter.display == "N"}
        {* default behaviour of cm-combination *}
        {assign var="collapse" value=true}
        {if $smarty.cookies.$cookie_name_show_filter}
            {assign var="collapse" value=false}
        {/if}
    {else}
        {* reverse behaviour of cm-combination *}
        {assign var="collapse" value=false}
        {if $smarty.cookies.$cookie_name_show_filter}
            {assign var="collapse" value=true}
        {/if}
    {/if}

    {$reset_url = ""}
    {if $filter.selected_variants || $filter.selected_range}
        {$reset_url = $filter_base_url}
        {$fh = $smarty.request.features_hash|fn_delete_filter_from_hash:$filter.filter_id}
        {if $fh}
            {$reset_url = $filter_base_url|fn_link_attach:"features_hash=$fh"}
        {/if}
    {/if}

    {if ($addons.sd_labels && $addons.sd_labels.status == 'A') && !$filter.variants.Y.disabled && $product_label_filter_fields[$filter.field_type]}
        <div class="ty-product-filters__block ty-label-filter-block">
            {include file="addons/sd_labels/components/product_filter_variants.tpl" filter_uid=$filter_uid filter=$filter collapse=$collapse}
        </div>
    {else}
        <div class="ty-product-filters__block">
            <div id="sw_content_{$filter_uid}" class="ty-product-filters__switch cm-combination-filter_{$filter_uid}{if !$collapse} open{/if} cm-save-state {if $filter.display == "Y"}cm-ss-reverse{/if}">
                <span class="ty-product-filters__title">{$filter.filter}{if $filter.selected_variants} ({$filter.selected_variants|sizeof}){/if}{if $reset_url}<a class="cm-ajax cm-ajax-full-render cm-history" href="{$reset_url|fn_url}" data-ca-event="ce.filtersinit" data-ca-target-id="{$ajax_div_ids}" data-ca-scroll=".ty-mainbox-title"><i class="ty-icon-cancel-circle"></i></a>{/if}</span>
                <i class="ty-product-filters__switch-down ty-icon-down-open"></i>
                <i class="ty-product-filters__switch-right ty-icon-up-open"></i>
            </div>

            {if $filter.slider}
                {if $filter.feature_type == "ProductFeatures::DATE"|enum}
                    {include file="blocks/product_filters/components/product_filter_datepicker.tpl" filter_uid=$filter_uid filter=$filter}
                {else}
                    {include file="blocks/product_filters/components/product_filter_slider.tpl" filter_uid=$filter_uid filter=$filter}
                {/if}
            {else}
                {include file="blocks/product_filters/components/product_filter_variants.tpl" filter_uid=$filter_uid filter=$filter collapse=$collapse}
            {/if}
        </div>
    {/if}
{/if}

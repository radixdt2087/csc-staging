{assign var="_title" value=$category_data.category|default:__("vendor_products")}

{assign var="products_search" value="Y"}

{hook name="companies:products"}
<style>
    .ty-no-items a{
        color: #3b7dd1;
        padding: 10px 20px;
    }
</style>
<div id="products_search_{$block.block_id}">
<div class="row-fluid">
    <div class="span8">
        <div><h4>Search Product from this vendor.</h4></div>
        <div class="ty-search-block">
            <form action="{""|fn_url}" name="search_form" method="get">
                <input type="hidden" name="match" value="all" />
                <input type="hidden" name="subcats" value="Y" />
                <input type="hidden" name="pcode_from_q" value="Y" />
                <input type="hidden" name="pshort" value="Y" />
                <input type="hidden" name="pfull" value="Y" />
                <input type="hidden" name="pname" value="Y" />
                <input type="hidden" name="pkeywords" value="Y" />
                <input type="hidden" name="search_performed" value="Y" />
                <input type="hidden" name="search_cid" value="{$affiliate_merchant}" id="search_cid" />
                {hook name="search:additional_fields"}{/hook}
                {strip}
                    {if $settings.General.search_objects}
                        {assign var="search_title" value=__("search")}
                    {else}
                        {assign var="search_title" value=__("search_products")}
                    {/if}
                    <div class="search-textbox">
                        <input type="text" name="q" value="{$search.q}" id="search_input{$smarty.capture.search_input_id}" title="{$search_title}" class="ty-search-block__input vendor-search cm-hint" />
                    </div>
                    {if $settings.General.search_objects}
                        {include file="buttons/magnifier.tpl" but_name="search.results" alt=__("search")}
                    {else}
                        {include file="buttons/magnifier.tpl" but_name="products.search" alt=__("search")}
                    {/if}
                {/strip}
                {capture name="search_input_id"}{$block.snapping_id}{/capture}
            </form>
        </div>
    </div>
</div>

    {if $subcategories or $category_data.description || $category_data.main_pair}
        {math equation="ceil(n/c)" assign="rows" n=$subcategories|count c=$columns|default:"2"}
        {split data=$subcategories size=$rows assign="splitted_subcategories"}

    {if $category_data.description && $category_data.description != ""}
        <div class="ty-wysiwyg-content ty-mb-s">{$category_data.description nofilter}</div>
    {/if}

        {if $subcategories}
            <ul class="subcategories clearfix">
            {foreach from=$splitted_subcategories item="ssubcateg"}
                {foreach from=$ssubcateg item=category name="ssubcateg"}
                    {if $category}
                        <li class="ty-subcategories__item">
                            <a href="{"companies.products?category_id=`$category.category_id`&company_id=$company_id"|fn_url}">
                            {if $category.main_pair}
                                {include file="common/image.tpl"
                                    show_detailed_link=false
                                    images=$category.main_pair
                                    no_ids=true
                                    image_id="category_image"
                                    image_width=$settings.Thumbnails.category_lists_thumbnail_width
                                    image_height=$settings.Thumbnails.category_lists_thumbnail_height
                                    class="ty-subcategories-img"
                                }
                            {/if}
                            {$category.category}
                            </a>
                        </li>
                    {/if}
                {/foreach}
            {/foreach}
            </ul>
        {/if}
    {/if}
            {if $products}
                {if $affiliate_merchant}
                    {assign var="title_extra" value="{__("products_found")}: `$search.total_items`"}
                    {assign var="layouts" value=""|fn_get_products_views:false:0}
                    {if $category_data.product_columns}
                        {assign var="product_columns" value=$category_data.product_columns}
                    {else}
                        {assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
                    {/if}

                    {if $layouts.$selected_layout.template}
                        {include file="`$layouts.$selected_layout.template`" columns=$product_columns show_qty=true}
                    {/if}
                   
                {else}
                    {assign var="title_extra" value="{__("products_found")}: `$search.total_items`"}
                    {assign var="layouts" value=""|fn_get_products_views:false:0}
                    {if $category_data.product_columns}
                        {assign var="product_columns" value=$category_data.product_columns}
                    {else}
                        {assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
                    {/if}

                    {if $layouts.$selected_layout.template}
                        {include file="`$layouts.$selected_layout.template`" columns=$product_columns show_qty=true}
                    {/if}
                {/if}
			{elseif !$subcategories}
				{hook name="products:search_results_no_matching_found"}
                    {if $affiliate_merchant}
                    <p class="ty-no-items">
                        <a url="{$companydata.url|replace:'{subid}':{$auth.user_id}}" class="buy_now">Click on the link to go direct to the site.</a>
                    </p>
                    {else}
                        {* <p class="ty-no-items">{__("text_no_matching_products_found")}</p> *}
                    {/if}
				{/hook}
			{/if}

<!--products_search_{$block.block_id}--></div>

{/hook}

{hook name="products:search_results_mainbox_title"}
{capture name="mainbox_title"}<span class="ty-mainbox-title__left">{$_title}</span><span class="ty-mainbox-title__right" id="products_search_total_found_{$block.block_id}">{$title_extra nofilter}<!--products_search_total_found_{$block.block_id}--></span>{/capture}
{/hook}
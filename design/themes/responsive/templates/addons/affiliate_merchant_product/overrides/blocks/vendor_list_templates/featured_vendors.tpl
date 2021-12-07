{** block-description:vendor_logos_and_product_count **}

{$show_location = $block.properties.show_location|default:"N" == "Y"}
{$show_rating = $block.properties.show_rating|default:"N" == "Y"}
{$show_vendor_rating = $block.properties.show_vendor_rating|default:"N" == "Y"}
{$show_products_count = $block.properties.show_products_count|default:"N" == "Y"}

{$columns=$block.properties.number_of_columns}
{$obj_prefix="`$block.block_id`000"}
{if $items && $search.total_items == 0}
    {split data=$items size=$columns|default:"5" assign="splitted_companies"}
    {math equation="100 / x" x=$columns|default:"5" assign="cell_width"}
    <div class="grid-list ty-grid-vendors">
        {strip}
            {foreach from=$splitted_companies item="scompanies" name="scomp"}
                {foreach from=$scompanies item="company" name="scompanies"}
                    {if $company}
                    <div class="ty-column{$columns}">
                        {if $company}
                            {if $company.logos}
                                {$show_logo = true}
                            {else}
                                {$show_logo = false}
                            {/if}
                            {$obj_id=$company.company_id}
                            {$obj_id_prefix="`$obj_prefix``$company.company_id`"}
                            {include file="common/company_data.tpl" company=$company show_links=true show_logo=$show_logo show_location=$show_location}
                            <div class="ty-grid-list__item">
                                {hook name="companies:featured_vendors"}
                                        <div class="ty-grid-list__company-logo">
											{if $company.affiliate_merchant}
                                            {$ca = fn_affiliate_merchant_product_init_templater($company.company_id)}
											<a class="ty-company-image-wrapper" href="{"companies.products?company_id=`$company.company_id`"|fn_url}">
												<img class="ty-pict  ty-company-image   cm-image" id="det_img_14" src={$ca} loading="lazy" width="242" height="121"/>
											</a>
											{else}
												{$logo="logo_`$obj_id`"}
												{$smarty.capture.$logo nofilter}
											{/if}
                                        </div>
                                        {$location="location_`$obj_id`"}
                                        {if $show_location && $smarty.capture.$location|trim}
                                        <div class="ty-grid-list__item-location">
                                            {if $company.affiliate_merchant}
                                                {if $company.affiliate_product_count == 1}
                                                    {$className = "shop-direct-products"}
                                                {else}
                                                    {$className = ""}
                                                {/if}
                                                <a url="{$company.url|replace:'{subid}':{$auth.user_id}}" class="buy_now {$className}">Shop Direct</a>
                                                {if $company.affiliate_product_count > 1}
                                                    <a href="{"companies.products?company_id=`$company.company_id`"|fn_url}">View More</a>
                                                {/if}
                                            {/if}
                                            <!-- <a url="{"companies.products?company_id=`$company.company_id`"|fn_url}" class="buy_now company-location">
                                                <bdi>BUY NOW</bdi>
                                            </a> -->
                                        </div>
                                        {/if}

                                        {$rating="rating_`$obj_id`"}
                                        {if $smarty.capture.$rating && $show_rating}
                                            <div class="grid-list__rating">
                                                {$smarty.capture.$rating nofilter}
                                            </div>
                                        {/if}
                                       
                                        <div class="ty-grid-list__group">
                                            {$vendor_rating="vendor_rating_`$obj_id`"}
                                            {if $smarty.capture.$vendor_rating && $show_vendor_rating}
                                                <div class="ty-grid-list__vendor_rating">
                                                    {$smarty.capture.$vendor_rating nofilter}
                                                </div>
                                            {/if}

                                            <div class="ty-grid-list__total-products">
                                                {$products_count="products_count_`$obj_id`"}
                                                {if $smarty.capture.$products_count && $show_products_count}
                                                    {$smarty.capture.$products_count nofilter}
                                                {/if}
                                            </div>
                                        </div>                                      
                                {/hook}
                            </div>
                        {/if}
                    </div>
                    {/if}
                {/foreach}
            {/foreach}
        {/strip}
    </div>
{/if}

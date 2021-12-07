{if $product.promotions}
    {$promotion = $product.promotions|key|fn_ab__dotd_get_cached_promotion_data}
    <div class="ab__deal_of_the_day">
		<div{if $promotion.to_date && $promotion.to_date > $smarty.now} class="col1"{/if}>
	        <div class="action-title">{$promotion.name}</div>
	                    
            <div class="actions-link">
		        {if $product.promotions|count > 1}
                    <span>
                        <i class="cm-tooltip ty-icon-help-circle" title="{__('ab__dotd.all_promotions.title')}"></i>
                        <a class="also-in-promos-link cm-external-click" data-ca-scroll="content_ab__deal_of_the_day" data-ca-external-click-id="ab__deal_of_the_day">{__('ab__dotd.all_promotions')}</a>
                    </span>
		        {/if}
	            {if $promotion.status == 'A'}
	                <span>
	                    {if $promotion.short_description|strip_tags|fn_string_not_empty}<i class="cm-tooltip ty-icon-help-circle" title="{$promotion.short_description}"></i>{/if}
	                    <a class="details-promo-link" href="{"promotions.view?promotion_id=`$promotion.promotion_id`"|fn_url}" title="" target="_blank">{__('ab__dotd.detailed')}</a>
	                </span>
	            {/if}
            </div>
        </div>
        
        {if $promotion.to_date && $promotion.to_date > $smarty.now}
        <div class="col2">
            <span class="time-left">{__('ab__dotd_time_left')}:</span>
            {include file="addons/ab__deal_of_the_day/components/init_countdown.tpl"}
        </div>
        {/if}
    </div>
{/if}
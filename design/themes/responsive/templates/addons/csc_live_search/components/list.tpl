{strip}
{assign var=cls value= $addons.csc_live_search}
{if $cls.show_wish=="Y" && $addons.wishlist.status=='A'}
	{assign var=wishlist_products value= $smarty.session.wishlist|fn_csc_ls_get_wishlist_products}
{/if}
{foreach from=$products item="product" name="products"} 
<li class="csc_template-small__item clearfix">      
    <div class="csc_template-small__item-img" style="width:{$cls.image_width}px">
    {if $product.merchant_id}
        <a href="{"products.view?product_id=`$product.product_id`&id=`$product.product_code`"|fn_url}">{include file="common/image.tpl" image_width=$cls.image_width image_height=$cls.image_height images=$product.main_pair}</a>
    {else}
       <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{include file="common/image.tpl" image_width=$cls.image_width image_height=$cls.image_height images=$product.main_pair}</a>
    {/if}
    </div>
    <div class="csc_template-p-description">
        {if $product.category}
            <div class="cs-label-block">
             <a href="{"categories.view&category_id=`$product.main_category`"|fn_url}" class="lvs-category-label" style="
             {if $cls.show_category_gradient=="Y"}
             	background:linear-gradient(to bottom, #fff -5px, {$product.label_color} 100%);
             {else}
             	background:{$product.label_color};
             {/if}
             ">{$product.category}</a>
         </div>
         {/if}
        {if $product.merchant_id}
            <a class="csc_no_line" href="{"products.view?product_id=`$product.product_id`&id=`$product.product_code`"|fn_url}">{$product.product nofilter} </a>
        {else}
         <a class="csc_no_line" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$product.product nofilter} </a>
        {/if}
        <div class="clearfix>"></div>
        {if $product.merchant_id}
             <a href="{"products.view?product_id=`$product.product_id`&id=`$product.product_code`"|fn_url}" class="lspa">
        {else}
         <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="lspa">
         {/if}
         	{if $cls.show_product_code=="Y"}
            	<div class="csc_product_code">{__('product_code')}: {$product.product_code}</div>
            {/if}
            {if $cls.show_price=="Y" || ($cls.show_price=="A" && $auth.user_id)}
				<div class="csc_template-item-price">                    
					<span class="csc_price{if !$product.price|floatval && !$product.zero_price_action} hidden{/if}" id="line_discounted_price_{$obj_prefix}{$obj_id}">{include file="common/price.tpl" value=$product.price}</span>               
				{if $settings.Appearance.show_prices_taxed_clean == "Y" && $product.taxed_price}
					&nbsp;           	    
					{if $product.clean_price != $product.taxed_price && $product.included_tax}
						<span class="csc_price tax">({include file="common/price.tpl" value=$product.taxed_price} {__("inc_tax")})</span>
					{elseif $product.clean_price != $product.taxed_price && !$product.included_tax}
						<span class="csc_price tax">({__("including_tax")})</span>
					{/if}
				{/if}          
				</div>
			{/if}			
          </a>         
          {assign var= redirect_url value = $live_search.current_url|escape:url}    
          {if ($cls.show_cart=="Y" || ($cls.show_cart=="A" && $auth.user_id)) && ($settings.General.allow_anonymous_shopping == "allow_shopping" || $auth.user_id) &&
          	(
                (
                 $product.zero_price_action != "R" || 
                 $product.price > 0
                )
                && 
                (
                    $settings.General.inventory_tracking != "Y"
                    || $settings.General.allow_negative_amount == "Y"
                    || (
                        $product.amount > 0
                        && $product.amount >= $product.min_qty
                    )
                    || $product.tracking == "ProductTracking::DO_NOT_TRACK"|enum
                    || $product.is_edp == "Y"
                    || $product.out_of_stock_actions == "OutOfStockActions::BUY_IN_ADVANCE"|enum
                  )
                
            )
          }  
          {if $product.has_options}
          	<a id="button_cart_{$product.product_id}_ls" 
               href="{"products.view?product_id=`$product.product_id`"|fn_url}" 
               class="lspb"               
               title="{__('select_options')}"><i class="ls-icon ls-icon-cog-alt"></i></a>
          {else}                 
           <a id="button_cart_{$product.product_id}_ls" 
               href="{"checkout.add..`$product.product_id`?product_data[`$product.product_id`][amount]=1&from_ls=Y&redirect_url=`$redirect_url`"|fn_url}" 
               class="cm-ajax cm-ajax-full-render cm-post lspb" 
               data-ca-target-id="cart_status*,account_info*{if $redirect_url|strpos:'checkout.cart' || $redirect_url|strpos:'checkout.checkout'},tygh_main_container{/if}" 
               title="{__('add_to_cart')}"><i class="ls-icon ls-icon-basket-3"></i></a>
               
           {/if}
          {/if}         
          {if $cls.show_wish=="Y" && $addons.wishlist.status=='A'}
               <a class="cm-ajax cm-ajax-full-render lspw delete {if !$product.product_id|in_array:$wishlist_products.product_ids} hidden{/if}" id="button_wishlist_{$product.product_id}_ls_delete"
               data-ca-elm-id="{$product.product_id}" 
               data-ca-target-id="account_info*" 
               href="{"wishlist.delete?cart_id=`$wishlist_products.cart_ids[$product.product_id]`&from_ls=Y&redirect_url=`$redirect_url`"|fn_url}" 
               title="{__('delete')}"
               ><i class="ls-icon ls-icon-heart"></i></a>
          
               <a class="cm-ajax cm-ajax-full-render cm-post lspw add {if $product.product_id|in_array:$wishlist_products.product_ids} hidden{/if}" id="button_wishlist_{$product.product_id}_ls_add"
               data-ca-elm-id="{$product.product_id}"  
               data-ca-target-id="account_info*" 
               href="{"wishlist.add..`$product.product_id`?product_data[`$product.product_id`][product_id]=`$product.product_id`&from_ls=Y&redirect_url=`$redirect_url`"|fn_url}" 
               title="{__('add_to_wishlist')}"
               ><i class="ls-icon ls-icon-heart-empty"></i></a>              
          {/if}       
    </div>      
</li>
{/foreach}

{if $live_search.total_items >  $live_search.page* $live_search.items_per_page}
    {assign var=diff value = $live_search.total_items - $live_search.page* $live_search.items_per_page}
    {if $diff < $live_search.items_per_page}
        {assign var=more value=$diff}
    {else}
        {assign var=more value=$live_search.items_per_page}
    {/if}
    <li class="ls-show-more-block"><a data-ls-page="{$live_search.page+1}" data-ls-q="{$live_search.q}" data-ls-block-id='{$live_search.block_id}' class="ls-show-more"><span>{__('cls_show_more', ["[per_page]"=>$more])} {__('cls_products', [$more])}</span></a>    
        <div class="ls-show-more-loading" style="display:none">
            <div class="cssload-container">
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
                <span class="cssload-dots"></span>
            </div>
        </div>
    </li>
{/if}
{/strip}
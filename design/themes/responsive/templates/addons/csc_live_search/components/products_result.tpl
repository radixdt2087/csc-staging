<div class="ls-container css_dir_{$language_direction}">
    <div class="csc_snize-dropdown-arrow"><div class="csc_snize-arrow-outer"></div><div class="csc_snize-arrow-inner csc_snize-arrow-inner-label"></div></div>    
    <div class="csc_live_search_total">{if $products}<a href="{"products.search&q=`$live_search.q`&search_performed=Y"|fn_url}">{__('cls_found', [$live_search.total_items])} {$live_search.total_items} {__('cls_products', [$live_search.total_items])}</a> {/if}<i class="ls_closer" title="{__('close')}"></i></div>
   
   <div class="cls_search_result"> 
        {if $categories}
            <div class="csc_root_tree_element">
              {__('categories')}
          </div>
            <ul class="csc_template-small csc_live_search_tree">    			
                {foreach from=$categories item="category"}		   
                    <li class="clearfix csc_subelement">	           
                            <div class="csc_template-small__item-description csc_live_search_brand">
                            {if $addons.csc_live_search.show_parent_category=="Y" && $category.parent_category}
                            <a href="{"categories.view?category_id=`$category.parent_category_id`"|fn_url}">{$category.parent_category nofilter}</a>&nbsp;/&nbsp;{/if}<a class="csc_no_line" href="{"categories.view?category_id=`$category.category_id`"|fn_url}">{$category.category nofilter}</a> 
                            </div>
                    </li>
                {/foreach}
            </ul>
        {/if}
		{if $storefront_categories}
            <div class="csc_root_tree_element">
              {__('storefronts_categories')}
          </div>
            <ul class="csc_template-small csc_live_search_tree">    			
                {foreach from=$storefront_categories item="category"}		   
                    <li class="clearfix csc_subelement">	           
                            <div class="csc_template-small__item-description csc_live_search_brand">
                            {if $addons.csc_live_search.show_parent_category=="Y" && $category.parent_category}
                            <a href="{"categories.view?category_id=`$category.parent_category_id`&company_id=`$category.company_id`"|fn_url}">{$category.parent_category nofilter}</a>&nbsp;/&nbsp;{/if}<a class="csc_no_line" href="{"categories.view?category_id=`$category.category_id`&company_id=`$category.company_id`"|fn_url}">{$category.category nofilter}</a> 
                            </div>      
                    </li>		   
                {/foreach}        
            </ul>
        {/if}
        
        {if $brands}
            <div class="csc_root_tree_element">
                {__('our_brands')}
            </div>	
            <ul class="csc_template-small csc_live_search_tree">
                
                {foreach from=$brands item="brand"}	    
                    <li class="csc_subelement clearfix">	           
                            <div class="csc_template-small__item-description csc_live_search_tree" >
                           <a class="csc_no_line" href="{"product_features.view?variant_id=`$brand.variant_id`"|fn_url}">  {$brand.variant nofilter}</a>   
                            </div>      
                    </li>		    
                {/foreach}
                
            </ul>
        {/if}
        {if $vendors}
            <div class="csc_root_tree_element">
                {__('companies')}
            </div>	
            <ul class="csc_template-small csc_live_search_tree">             
                {foreach from=$vendors item="vendor"}
                    <li class="csc_subelement clearfix">	           
                            <div class="csc_template-small__item-description csc_live_search_tree" >
                           <a class="csc_no_line" href="{"companies.products?company_id=`$vendor.company_id`"|fn_url}">  {$vendor.company nofilter}</a>   
                            </div>      
                    </li>		    
                {/foreach}
                
            </ul>
        {/if}
        
        {if $blogs}
            <div class="csc_root_tree_element">
              {__('blog')}
          </div>
            <ul class="csc_template-small csc_live_search_tree">    			
                {foreach from=$blogs item="blog"}		   
                    <li class="clearfix csc_subelement">	           
                            <div class="csc_template-small__item-description csc_live_search_brand">
                            <a class="csc_no_line" title="{$blog.page nofilter}" href="{"pages.view?page_id=`$blog.page_id`"|fn_url}">{$blog.page|truncate:50 nofilter}</a> 
                            </div>      
                    </li>		   
                {/foreach}        
            </ul>
        {/if}       
        {if $pages}
            <div class="csc_root_tree_element">
              {__('pages')}
          </div>
            <ul class="csc_template-small csc_live_search_tree">    			
                {foreach from=$pages item="page"}		   
                    <li class="clearfix csc_subelement">	           
                            <div class="csc_template-small__item-description csc_live_search_brand">
                            <a class="csc_no_line" title="{$page.page nofilter}" href="{"pages.view?page_id=`$page.page_id`"|fn_url}">{$page.page|truncate:50 nofilter}</a> 
                            </div>      
                    </li>		   
                {/foreach}        
            </ul>
        {/if}
        {if $products}
        <script>alert("hello");</script>
        <ul class="csc-template-small ls_products" id="ls_products_{$live_search.block_id}">   
            {include file="addons/csc_live_search/components/list.tpl" product=$product}
        <!--ls_products_{$live_search.block_id}--></ul>
        {/if}
        {if  !$products && !$brands && !$categories && !$vendors}
        <ul>
            <li class="css_live_no_found">
            
            {__('cls_not_found')} (<b>{$live_search.q}</b>)
            
            </li>
        </ul>
        {/if}
    </div>
</div>
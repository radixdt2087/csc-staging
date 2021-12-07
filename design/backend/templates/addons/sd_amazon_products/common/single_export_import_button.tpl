{foreach from=$active_marketplaces item=marketplace key=active name=num}
    {if $smarty.foreach.num.iteration == 1 && $addons.sd_amazon_products.$marketplace == 'Y'}
        <li class="divider"></li>
    {/if}
    {if $addons.sd_amazon_products.$marketplace == 'Y'}
        <li><a href="{"amazon_products.{$mode}.{$action}?{$target}=`$id`&region=`$active`&redirect_url=`$return_current_url`"|fn_url}" class="cm-post {if $target == "product_id" && ($addons.sd_amazon_products.sync_quantity == "Y" || $addons.sd_amazon_products.sync_price == "Y")}cm-ajax cm-comet{/if}"><i class="flag flag-{$active}"></i>{$title}&nbsp;({$active|upper})</a></li>
    {/if}
{/foreach}
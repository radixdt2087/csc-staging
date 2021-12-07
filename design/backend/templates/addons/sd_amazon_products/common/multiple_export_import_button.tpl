{* Multiple import *}
{foreach from=$active_marketplaces item=marketplace key=active name=num}
    {if $smarty.foreach.num.iteration == 1 && $addons.sd_amazon_products.$marketplace == 'Y'}
        <li class="divider"></li>
    {/if}
    {if $addons.sd_amazon_products.$marketplace == 'Y'}
        <li><a class="cm-process-items cm-submit cm-process-items cm-ajax cm-comet" data-ca-target-form="{$form_name}" data-ca-dispatch="dispatch[amazon_products.{$mode}.{$action}.{$active}]"><i class="flag flag-{$active}"></i>{$title}&nbsp;({$active|upper})</a></li>
    {/if}
{/foreach}
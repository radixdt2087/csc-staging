<div id="content_plan_addons_{$id}" class="hidden">
{assign var=add_package value=","|explode:$plan.add_package}
{foreach from=$allow_addon_data key=key item=item}
<div class="control-group">
<label class="control-label" for="add_package">{$item.name}</label>                                
<div class="controls">                
<input id="addpackage{$item.id}" type="checkbox" name="plan_data[add_package][]" class="input-mini" value="{$item.id}" {if $item.id|in_array:$add_package}checked{/if}/>        
</div>
</div>
{/foreach}
</div>
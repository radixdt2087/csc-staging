{$text_style = "Tygh\Enum\Addons\Ab_stickers\StickerStyles::TEXT"|constant}
{$graphic_style = "Tygh\Enum\Addons\Ab_stickers\StickerStyles::GRAPHIC"|constant}
{$dynamic_type = "Tygh\Enum\Addons\Ab_stickers\StickerTypes::DYNAMIC"|constant}
{$constant_type = "Tygh\Enum\Addons\Ab_stickers\StickerTypes::CONSTANT"|constant}
{** stickers section **}
{strip}
{capture name="mainbox"}
{$hide_inputs = !"ab__stickers.edit"|fn_check_view_permissions}
<form action="{""|fn_url}" method="post" name="ab__stickers_form" enctype="multipart/form-data"{if $hide_inputs} class="cm-hide-inputs"{/if}>
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="storefront_id" value="{$app['storefront']->storefront_id}" />
{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_stickers"}
{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents_stickers"}
{assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}
{capture name="sidebar"}
{include file="common/saved_search.tpl" dispatch="ab__stickers.manage" view_type="ab__stickers"}
{include file="addons/ab__stickers/views/ab__stickers/components/stickers_search_form.tpl" dispatch="ab__stickers.manage"}
{/capture}
{if $stickers}
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive">
<thead>
<tr>
<th width="1%" class="left">
{include file="common/check_items.tpl"}
</th>
<th width="10%" class="mobile-hide"><a class="cm-ajax" href="{"`$c_url`&sort_by=position&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents_stickers">{__("position_short")}{if $search.sort_by == "position"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="15%" class="mobile-hide"></th>
<th width="30%"><a class="cm-ajax" href="{"`$c_url`&sort_by=name_for_admin&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents_stickers">{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="25%"><a class="cm-ajax" href="{"`$c_url`&sort_by=type&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents_stickers">{__("ab__stickers.params.type")}{if $search.sort_by == "type"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
<th width="6%" class="right mobile-hide">&nbsp;</th>
<th width="13%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents_stickers">{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
</tr>
</thead>
<tbody>
{foreach $stickers as $sticker}
<tr class="cm-row-status-{$sticker.status|lower}">
<input type="hidden" value="{$sticker.hash}" name="stickers[{$sticker.sticker_id}][hash]">
<td class="left mobile-hide">
<input type="checkbox" name="sticker_ids[]" value="{$sticker.sticker_id}" class="cm-item" />
</td>
<td data-th="{__("position_short")}" class="mobile-hide">
<input class="input-micro input-hidden" type="text" name="stickers[{$sticker.sticker_id}][position]" value="{$sticker.position}">
</td>
<td class="mobile-hide ty-center">
{if $sticker.style == $text_style}
<a class="row-status ab-stickers-container__{$sticker.output_position_list}{$addons.ab__stickers.output_position}" href="{"ab__stickers.update?sticker_id=`$sticker.sticker_id`"|fn_url}">
<span class="ab-sticker T-sticker {$sticker.appearance.appearance_style|default:$addons.ab__stickers.ts_appearance}" style="{if $sticker.appearance.uppercase_text == "Y"}text-transform:uppercase;font-size:.8em;{/if}color:{$sticker.appearance.text_color};background-color:{$sticker.appearance.sticker_bg};{if $sticker.appearance.border_width != "0" && $addons.ab__stickers.ts_appearance != "beveled_angle"}box-shadow: inset 0 0 0 {$sticker.appearance.border_width} {$sticker.appearance.border_color}{/if}">{fn_ab__stickers_get_sticker_string_value($sticker.name_for_desktop|default:$sticker.name_for_admin, $default_placeholders|default:[]) nofilter}</span>
</a>
{else}
{include file="common/image.tpl" image=$sticker.main_pair image_width=48 image_hieght=48 no_ids=true href="ab__stickers.update?sticker_id=`$sticker.sticker_id`"|fn_url}
{/if}
</td>
<td data-th="{__('name')}">
<a class="row-status" href="{"ab__stickers.update?sticker_id=`$sticker.sticker_id`"|fn_url}">{$sticker.name_for_admin}</a>
</td>
<td data-th="{__('ab__stickers.params.type')}"><span class="row-status">{__("ab__stickers.params.type.`$sticker.type`")}</span></td>
<td class="mobile-hide">
{capture name="tools_list"}
<li>{btn type="list" text=__("edit") href="ab__stickers.update?sticker_id=`$sticker.sticker_id`"}</li>
{if !$hide_inputs}
<li>{btn type="list" class="cm-post" text=__("clone") href="ab__stickers.clone?sticker_ids[]=`$sticker.sticker_id`"}</li>
<li class="divider"></li>
<li>{btn type="list" class="cm-confirm" text=__("delete") href="ab__stickers.delete?sticker_ids[]=`$sticker.sticker_id`" method="POST"}</li>
{/if}
{/capture}
<div class="hidden-tools">
{dropdown content=$smarty.capture.tools_list}
</div>
</td>
<td class="right nowrap" data-th="{__("status")}">
{include file="common/select_popup.tpl" id=$sticker.sticker_id status=$sticker.status hidden=false object_id_name="sticker_id" table="ab__stickers" popup_additional_class="cm-no-hide-input dropleft" non_editable=$hide_inputs}
</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
{else}
<p class="no-items">{__("no_data")}.<br/>{__("ab__stickers.will_used_default")}</p>
{/if}
{include file="common/pagination.tpl" div_id="pagination_contents_stickers"}
{capture name="buttons"}
{if $stickers && !$hide_inputs}
{capture name="tools_list"}
<li>{btn type="list" class="cm-post" text=__('clone_selected') dispatch="dispatch[ab__stickers.m_clone]" form="ab__stickers_form"}</li>
<li>{btn type="list" class="cm-post" text=__('activate_selected') dispatch="dispatch[ab__stickers.m_change_status.A]" form="ab__stickers_form"}</li>
<li>{btn type="list" class="cm-post" text=__('disable_selected') dispatch="dispatch[ab__stickers.m_change_status.D]" form="ab__stickers_form"}</li>
<li>{btn type="delete_selected" dispatch="dispatch[ab__stickers.m_delete]" form="ab__stickers_form"}</li>
{if "DEVELOPMENT"|defined && $smarty.const.DEVELOPMENT == true}
<li class="divider"></li>
<li><a class="cm-submit cm-confirm" data-ca-target-form="ab__stickers_form" data-ca-dispatch="dispatch[ab__stickers.m_delete.all]">{__('ab__stickers.delete_all')}</a></li>
{/if}
{/capture}
{dropdown content=$smarty.capture.tools_list}
{include file="buttons/save.tpl" but_name="dispatch[ab__stickers.m_update]" but_role="action" but_target_form="ab__stickers_form" but_meta="cm-submit"}
{/if}
{/capture}
{capture name="adv_buttons"}
{capture name="add_button_content"}
<li>{btn type="list" text=__('ab__stickers.manage.add.constant') href="ab__stickers.add?type=`$constant_type`"}</li>
<li>{btn type="list" text=__('ab__stickers.manage.add.dynamic') href="ab__stickers.add?type=`$dynamic_type`"}</li>
{/capture}
{dropdown content=$smarty.capture.add_button_content class="" icon="icon-plus"}
{/capture}
</form>
{/capture}
{capture name="mainbox"}
{if (!fn_ab__stickers_sticker_with_condition_exist('discount') || !fn_ab__stickers_sticker_with_condition_exist('free_shipping')) && $stickers}
<p style="color:red">{__("ab__stickers.you_have_no_standart_stickers")}</p>
{/if}
{$smarty.capture.mainbox nofilter}
{/capture}
{$title_end = __("ab__stickers.manage")}
{include file="addons/ab__addons_manager/views/ab__am/components/menu.tpl" addon="ab__stickers"}
{include file="common/mainbox.tpl"
title_start=__("ab__stickers")|truncate:40
title_end=$title_end
content=$smarty.capture.mainbox
buttons=$smarty.capture.buttons
adv_buttons=$smarty.capture.adv_buttons
select_languages=true
select_storefront=true
show_all_storefront=false
sidebar=$smarty.capture.sidebar}
{/strip}
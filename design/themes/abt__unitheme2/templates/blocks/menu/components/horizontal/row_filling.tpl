{hook name="blocks:topmenu_dropdown_2levels_elements"}
    <div class="ty-menu__submenu-items cm-responsive-menu-submenu{if $item1.abt__ut2_mwi__text && $item1.abt__ut2_mwi__text_position !="bottom"} with-pic{/if} row-filling" data-cols-count="{$settings_cols}" {if $settings.abt__device != "mobile"}style="min-height:{$settings.abt__ut2.general.menu_min_height}"{/if}>
        {$Viewlimit=$block.properties.no_hidden_elements_second_level_view|default:5}
        <div class="ty-menu__submenu-col">
            {foreach from=$item1.$childs item="item2" name="item2"}
                <div class="ty-top-mine__submenu-col" {if $settings.abt__device != "mobile"}style="width:{$col_width}%"{/if}>
                    {assign var="item2_url" value=$item2|fn_form_dropdown_object_link:$block.type}
                    <div class="ty-menu__submenu-item-header{if $item2.active || $item2|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-header-active{/if}{if $item2.class} {$item2.class}{/if}">
                        <a href="{$item2_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link">
                            {if $block.properties.abt_menu_icon_items == "YesNo::YES"|enum && $item2.abt__ut2_mwi__icon && $settings.abt__device != "mobile"}
                                {include file="common/image.tpl" images=$item2.abt__ut2_mwi__icon class="ut2-mwi-icon" no_ids=true}
                            {/if}
                            <bdi>{$item2.$name}</bdi>
                            {if $item2.abt__ut2_mwi__status == "YesNo::YES"|enum && $item2.abt__ut2_mwi__label}
                                <span class="m-label" style="color:{$item2.abt__ut2_mwi__label_color};background-color:{$item2.abt__ut2_mwi__label_background};{if $item2.abt__ut2_mwi__label_background == "#ffffff"}border: 1px solid {$item2.abt__ut2_mwi__label_color}{else}border: 1px solid {$item2.abt__ut2_mwi__label_background};{/if}">{$item2.abt__ut2_mwi__label}</span>
                            {/if}
                        </a>
                    </div>
                    {if $item2.$childs}
                        <a class="ty-menu__item-toggle visible-phone cm-responsive-menu-toggle">
                            <i class="ut2-icon-outline-expand_more"></i>
                        </a>
                    {/if}
                    <div class="ty-menu__submenu">
                        <div class="ty-menu__submenu-list{if $item2.abt__ut2_mwi__dropdown == "YesNo::YES"|enum} tree-level-col{elseif $item2.$childs|count > $Viewlimit} hiddenCol{/if} cm-responsive-menu-submenu"{if $item2.$childs|count > $Viewlimit} style="height: {$Viewlimit * 21}px;"{/if}>
                            {if $item2.$childs}
                                {hook name="blocks:topmenu_dropdown_3levels_col_elements"}
                                {foreach from=$item2.$childs item="item3" name="item3"}
                                    {assign var="item3_url" value=$item3|fn_form_dropdown_object_link:$block.type}
                                    <div class="ty-menu__submenu-item{if $item3.active || $item3|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}{if $item3.class} {$item3.class}{/if}">
                                        <a href="{$item3_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link"><bdi>{$item3.$name}</bdi>{if $item3.abt__ut2_mwi__status == "YesNo::YES"|enum && $item3.abt__ut2_mwi__label}<span class="m-label" style="color: {$item3.abt__ut2_mwi__label_color};background-color: {$item3.abt__ut2_mwi__label_background};{if $item3.abt__ut2_mwi__label_background == "#ffffff"}border: 1px solid {$item3.abt__ut2_mwi__label_color}{else}border: 1px solid {$item3.abt__ut2_mwi__label_background};{/if}">{$item3.abt__ut2_mwi__label}</span>{/if}</a>
                                    </div>
                                {/foreach}
                                {/hook}
                            {/if}
                        </div>
                        {if $item2.$childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="YesNo::YES"|enum}
                            <a href="{if $block.properties.abt__ut2_view_more_btn_behavior|default:"view_items" == "view_items"}javascript:void(0);" onMouseOver="$(this).prev().addClass('view');$(this).addClass('hidden');{else}{$item2_url|default:"javascript:void(0)"}" rel="nofollow{/if}" class="ut2-more"><span>{__("more")}</span></a>
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>

        {if $item1.abt__ut2_mwi__status == "YesNo::YES"|enum && $item1.abt__ut2_mwi__text|trim && $settings.abt__device != "mobile"}
            <div class="ut2-mwi-html {$item1.abt__ut2_mwi__text_position}">{$item1.abt__ut2_mwi__text|trim nofilter}</div>
        {else}
            {if $item1.show_more && $item1_url}
                <div class="ty-menu__submenu-item ty-menu__submenu-alt-link">
                    <a href="{$item1_url}">{__("text_topmenu_more", ["[item]" => $item1.$name])}</a>
                </div>
            {/if}
        {/if}
    </div>
{/hook}
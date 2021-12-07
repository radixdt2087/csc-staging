{$items_in_big_cols = ($subitems_count / $settings_cols)|ceil}
{$big_cols_count = $subitems_count % $settings_cols}

{$splited_elements = fn_abt__ut2_split_elements_for_menu($item1.$childs, $settings_cols, $items_in_big_cols, $big_cols_count)}
{$second_level_counter = 0}

{foreach from=$splited_elements item="row"}
    {$Viewlimit=$block.properties.no_hidden_elements_second_level_view|default:5}

    <div class="ty-menu__submenu-col" {if $settings.abt__device != "mobile"}style="width:{$col_width}%"{/if}>
        {foreach from=$row item="item2" name="item2"}
            <div class="second-lvl" data-elem-index="{$second_level_counter}">
                {$second_level_counter = $second_level_counter + 1}

                {assign var="item2_url" value=$item2|fn_form_dropdown_object_link:$block.type}
                <div class="ty-menu__submenu-item-header {if $item2.active || $item2|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-header-active{/if}">
                    <a href="{$item2_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link{if empty($item2.$childs)} no-items{/if}{if $item2.class} {$item2.class}{/if}">{if $item2.abt__ut2_mwi__icon && $block.properties.abt_menu_icon_items == "YesNo::YES"|enum && $settings.abt__device != 'mobile'}<div class="ut2-mwi-icon">{include file="common/image.tpl" images=$item2.abt__ut2_mwi__icon class="ut2-mwi-icon" no_ids=true}</div>{/if}
                        <bdi>{$item2.$name}
                            {if $item2.abt__ut2_mwi__status == "YesNo::YES"|enum && $item2.abt__ut2_mwi__label}
                                <span class="m-label" style="color: {$item2.abt__ut2_mwi__label_color};background-color: {$item2.abt__ut2_mwi__label_background}; {if $item2.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item2.abt__ut2_mwi__label_color}{else}border: 1px solid {$item2.abt__ut2_mwi__label_background};{/if}">{$item2.abt__ut2_mwi__label}</span>
                            {/if}
                        </bdi>
                    </a>
                    {if $item2.$childs && $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}<i class="icon-right-dir ut2-icon-outline-arrow_forward"></i>{/if}
                </div>
                {if !empty($item2.$childs)}
                    <a class="ty-menu__item-toggle visible-phone cm-responsive-menu-toggle">
                        <i class="ut2-icon-outline-expand_more"></i>
                    </a>
                    <div class="ty-menu__submenu {if $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum && $item2.abt__ut2_mwi__text && $item2.abt__ut2_mwi__text_position !="bottom"}tree-level-img{/if}" {if $item1.abt__ut2_mwi__dropdown =="YesNo::YES"|enum}style="min-height: {$settings.abt__ut2.general.menu_min_height}"{/if}>
                        {if $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}
                            <div class="sub-title-two-level"><bdi>{$item2.$name}</bdi></div>
                            {$max_amount3=2*$block.properties.elements_per_column_third_level_view}
                            {$item2.$childs=array_slice($item2.$childs, 0, $max_amount3, true)}
                            {foreach from=array_chunk($item2.$childs, ceil($item2.$childs|count / 2), true) item="item2_childs"}
                                <div class="ty-menu__submenu-list {if $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}tree-level-col {else}{if $item2_childs|count > $Viewlimit}hiddenCol {/if}{/if}cm-responsive-menu-submenu" {if $item2_childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="YesNo::YES"|enum}style="height: {$Viewlimit * 21}px;"{/if}>
                                    {if $item2_childs}
                                        {hook name="blocks:topmenu_dropdown_3levels_col_elements"}
                                        {foreach from=$item2_childs item="item3" name="item3"}
                                            {assign var="item3_url" value=$item3|fn_form_dropdown_object_link:$block.type}
                                            <div class="ty-menu__submenu-item{if $item3.active || $item3|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}">
                                                <a href="{$item3_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link{if $item3.class} {$item3.class}{/if}">
                                                    <bdi>{$item3.$name}
                                                        {if $item3.abt__ut2_mwi__status == "YesNo::YES"|enum && $item3.abt__ut2_mwi__label}
                                                            <span class="m-label" style="color: {$item3.abt__ut2_mwi__label_color}; background-color: {$item3.abt__ut2_mwi__label_background}; {if $item3.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item3.abt__ut2_mwi__label_color}{else}border: 1px solid {$item3.abt__ut2_mwi__label_background};{/if}">{$item3.abt__ut2_mwi__label}</span>
                                                        {/if}
                                                    </bdi>
                                                </a>
                                            </div>
                                        {/foreach}
                                        {/hook}
                                    {/if}
                                </div>
                            {/foreach}
                        {else}
                            <div class="ty-menu__submenu-list {if $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}tree-level-col {else}{if $item2.$childs|count > $Viewlimit}hiddenCol {/if}{/if}cm-responsive-menu-submenu" {if $item2.$childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="YesNo::YES"|enum}style="height: {$Viewlimit * 21}px;"{/if}>
                                {if $item2.$childs}
                                    {if $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}<div class="sub-title-two-level"><bdi>{$item2.$name}</bdi></div>{/if}
                                    {hook name="blocks:topmenu_dropdown_3levels_col_elements"}
                                    {foreach from=$item2.$childs item="item3" name="item3"}
                                        {assign var="item3_url" value=$item3|fn_form_dropdown_object_link:$block.type}
                                        <div class="ty-menu__submenu-item{if $item3.active || $item3|fn_check_is_active_menu_item:$block.type} ty-menu__submenu-item-active{/if}">
                                            <a href="{$item3_url|default:"javascript:void(0)"}" class="ty-menu__submenu-link{if $item3.class} {$item3.class}{/if}">
                                                <bdi>{$item3.$name}
                                                    {if $item3.abt__ut2_mwi__status == "YesNo::YES"|enum && $item3.abt__ut2_mwi__label}
                                                        <span class="m-label" style="color: {$item3.abt__ut2_mwi__label_color}; background-color: {$item3.abt__ut2_mwi__label_background}; {if $item3.abt__ut2_mwi__label_background == '#ffffff'}border: 1px solid {$item3.abt__ut2_mwi__label_color}{else}border: 1px solid {$item3.abt__ut2_mwi__label_background};{/if}">{$item3.abt__ut2_mwi__label}</span>
                                                    {/if}
                                                </bdi>
                                            </a>
                                        </div>
                                    {/foreach}
                                    {/hook}
                                {/if}
                            </div>
                        {/if}

                        {if $item1.abt__ut2_mwi__status == "YesNo::YES"|enum && $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum && $item2.abt__ut2_mwi__text|trim && $settings.abt__device != 'mobile'}
                            <div class="ut2-mwi-html {if $item2.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}bottom{else}{$item2.abt__ut2_mwi__text_position}{/if}">{$item2.abt__ut2_mwi__text nofilter}</div>
                        {/if}

                        {if $item2.$childs|count > $Viewlimit && $item1.abt__ut2_mwi__dropdown !="YesNo::YES"|enum}
                            <a href="{if $block.properties.abt__ut2_view_more_btn_behavior|default:'view_items' == 'view_items'}javascript:void(0);" onMouseOver="$(this).prev().addClass('view');$(this).addClass('hidden');{else}{$item2_url|default:"javascript:void(0)"}" rel="nofollow{/if}" class="ut2-more"><span>{__("more")}</span></a>
                        {/if}
                    </div>
                {/if}
            </div>
        {/foreach}
    </div>
{/foreach}

{if $item1.abt__ut2_mwi__status == "YesNo::YES"|enum && $item1.abt__ut2_mwi__text|trim && $item1.abt__ut2_mwi__dropdown !="YesNo::YES"|enum && $settings.abt__device != 'mobile'}
    <div class="ut2-mwi-html {if $item1.abt__ut2_mwi__dropdown == "YesNo::YES"|enum}bottom{else}{$item1.abt__ut2_mwi__text_position}{/if}">{$item1.abt__ut2_mwi__text nofilter}</div>
{/if}

{if $item1.show_more && $item1_url}
    <div class="ty-menu__submenu-alt-link"><a class="ty-btn" href="{$item1_url}" title="">{__("text_topmenu_more", ["[item]" => $item1.$name])}</a></div>
{/if}

{script src="js/addons/abt__unitheme2/abt__ut2_column_calculator.js"}
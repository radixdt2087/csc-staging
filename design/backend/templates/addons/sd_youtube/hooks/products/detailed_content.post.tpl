{if "ULTIMATE"|fn_allowed_for || "MULTIVENDOR"|fn_allowed_for}
    {include file="common/subheader.tpl" title=__("youtube") target="#youtube_setting"}
    {if !$runtime.company_id && $product_data.shared_product == "Y"}
        {assign var="show_update_for_all" value=true}
    {/if}
    {if $runtime.company_id && $product_data.shared_product == "Y" && $product_data.company_id != $runtime.company_id}
        {assign var="no_hide_input_if_shared_product" value="cm-no-hide-input"}
    {/if}
    <div id="youtube_setting" class="collapse in">

        <div class="control-group">
            <label class="control-label" for="youtube_link">{__("youtube_link")}:</label>
            <div class="controls">
                <input type="text" id="youtube_link" name="product_data[youtube_link]"
                       value="{$product_data.youtube_link}" class="input-long {$no_hide_input_if_shared_product}"
                       size="5" onchange="fn_product_youtube_settings(this);"
                       onkeyup="fn_product_youtube_settings(this);"/>
                {include file="buttons/update_for_all.tpl" display=$show_update_for_all object_id='youtube_link' name="update_all_vendors[youtube_link]"}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">{__("show_youtube_video")}:</label>
            <div class="controls">
                <input type="checkbox" name="product_data[show_youtube_video]"
                       class="{$no_hide_input_if_shared_product}" id="show_youtube_video"
                       {if $product_data.show_youtube_video == "YesNo::YES"|enum}checked="checked"{/if}
                       onclick="fn_activate_replace_main_image();" {if !$product_data.youtube_link}disabled="disabled"{/if}
                       value="Y"/>
                {include file="buttons/update_for_all.tpl" display=$show_update_for_all object_id='show_youtube_video' name="update_all_vendors[show_youtube_video]"}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">{__("replace_main_image")}:</label>
            <div class="controls">
                <input type="hidden" name="product_data[replace_main_image]" value="N"/>
                <input type="checkbox" name="product_data[replace_main_image]"
                       class="{$no_hide_input_if_shared_product}" id="replace_main_image"
                       {if $product_data.replace_main_image == "YesNo::YES"|enum}checked="checked"{/if} {if $product_data.show_youtube_video != "YesNo::YES"|enum}disabled="disabled"{/if}
                       value="Y" onclick="fn_activate_position_youtube();"/>
                {include file="buttons/update_for_all.tpl" display=$show_update_for_all object_id='replace_main_image' name="update_all_vendors[replace_main_image]"}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">{__("position")}:</label>
            <div class="controls">
                <input type="hidden" name="product_data[position_youtube]" value="N"/>
                <input type="text" name="product_data[position_youtube]" id="position_youtube" size="10" value="{$product_data.position_youtube|default:"2"}" class="input-small" {if $product_data.replace_main_image == "YesNo::YES"|enum}disabled="disabled"{/if}/>
                {include file="buttons/update_for_all.tpl" display=$show_update_for_all object_id='replace_main_image' name="update_all_vendors[posotion_youtube]"}
            </div>
        </div>

        {include file="addons/sd_youtube/settings/product_youtube_videos.tpl"}
    </div>
{/if}

<script type="text/javascript">
    function fn_activate_replace_main_image()
    {
        var replace_main_image = $('#replace_main_image');

        if ($('#show_youtube_video').prop('checked')) {
            replace_main_image.prop('disabled', false);
        } else {
            replace_main_image.prop('disabled', true);
            replace_main_image.prop('checked', false);
        }

        return true;
    }

    function fn_product_youtube_settings(elm)
    {
        var jelm = Tygh.$(elm);
        var available = false;

        Tygh.$('input', jelm.parent()).each(function() {
            if (Tygh.$(this).val()) {
                available = true;
            }
        });

        Tygh.$('input#show_youtube_video').prop('disabled', !available);

        if (Tygh.$('input#replace_main_image').prop('disabled') != true) {
            Tygh.$('input#replace_main_image').prop('disabled', !available);
        }
    }

    function fn_activate_position_youtube()
    {
        var position_youtube = $('#position_youtube');

        if ($('#replace_main_image').prop('checked')) {
            position_youtube.prop('disabled', true);
        } else {
            position_youtube.prop('disabled', false);
            position_youtube.prop('checked', true);
        }

        return true;
    }
</script>

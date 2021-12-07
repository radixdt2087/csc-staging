{assign var="map_container" value="wk_sp_map_canvas"}
{include file="addons/wk_store_pickup/views/wk_store_pickup/components/maps/google.tpl"}

<div class="hidden" id="map_picker" title="{__("select_coordinates")}">
    <div class="map-canvas" id="{$map_container}" style="z-index: 2000; height: 100%;"></div>

    <form name="map_picker" action="" method="">
    <div class="buttons-container">
        <a class="cm-dialog-closer cm-cancel tool-link btn">{__("cancel")}</a>
        {if $allow_save}
            {include file="buttons/button.tpl" but_text=__("set") but_role="action" but_meta="btn-primary cm-dialog-closer cm-wsp-map-save-location"}
        {/if}
    </div>
    </form>
</div>
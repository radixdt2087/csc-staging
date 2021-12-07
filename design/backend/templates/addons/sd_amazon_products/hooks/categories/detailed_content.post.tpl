{include file="common/subheader.tpl" title=__("amazon") target="#acc_sd_amazon_products"}
<div id="acc_sd_amazon_products" class="collapsed in">

    <div class="control-group" id="amz_synchronization">
        <label for="elm_amz_synchronization" class="control-label">{__("sd_amz_synchronization")}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_synchronization_desc_categories')}"><i class="icon-question-sign"></i></a>:</label>
        <div class="controls">
            <input type="hidden" value="N" name="category_data[amz_synchronization]"/>
            <input type="checkbox" id="elm_amz_synchronization" name="category_data[amz_synchronization]" {if $category_data.amz_synchronization == 'Y'} checked="checked"{/if} value="Y" />
        </div>
    </div>

    <div class="control-group">
        <h5>{__("sd_amz_product_classifier")}</h5>
        <p>{__('sd_amz_product_classifier_desc')}</p>

        <div class="control-group">
            <label for="amz_item_type" class="control-label">{__('amz_item_type')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_item_type_desc')}"><i class="icon-question-sign"></i></a>:</label>
            <div class="controls">
                <input type="text" name="category_data[amz_item_type]" id="amz_item_type" size="80" value="{$category_data.amz_item_type}" class="input-text-large" />
            </div>
        </div>

        <div class="control-group">
            <label for="amz_browse_node" class="control-label">{__('sd_amz_browse_node')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_browse_node_desc')}"><i class="icon-question-sign"></i></a>:</label>
            <div class="controls">
                <input type="text" name="category_data[amz_browse_node]" id="amz_browse_node" size="80" value="{$category_data.amz_browse_node}" class="input-text-large" />
            </div>
        </div>
    </div>
</div>
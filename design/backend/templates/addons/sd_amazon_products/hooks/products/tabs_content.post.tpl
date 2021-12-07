{if $product_data.product_id}
    <div id="content_amazon">

        {include file="common/subheader.tpl" title=__("general") target="#general"}
        <div id="general" class="collapse in">

            {if !$product_data.variation_parent_product_id}
                <div class="control-group" id="amz_synchronization">
                    <label for="elm_amz_synchronization" class="control-label">{__("sd_amz_synchronization")}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_synchronization_desc_products')}"><i class="icon-question-sign"></i></a>:</label>
                    <div class="controls">
                        <input type="hidden" value="N" name="product_data[amz_synchronization]"/>
                        <input type="checkbox" id="elm_amz_synchronization" name="product_data[amz_synchronization]" {if $product_data.amz_synchronization == 'Y'} checked="checked"{/if} value="Y" />
                    </div>
                </div>

                <h5>{__("sd_amz_product_identifiers")}</h5>
                <p>{__('sd_amz_product_identifiers_desc')}</p>
            {/if}

            <div class="control-group">
                <label for="amz_sku" class="control-label">{__('amz_sku')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_sku_desc')}"><i class="icon-question-sign"></i></a>:</label>
                <div class="controls">
                    <input type="text" name="product_data[amz_sku]" id="amz_sku" size="80" value="{$product_data.amz_sku}" class="input-text-large" />
                </div>
            </div>
            <div class="control-group">
                <label for="amz_asin" class="control-label">{__('amz_asin')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_asin_desc')}"><i class="icon-question-sign"></i></a>:</label>
                <div class="controls">
                    <input type="text" name="product_data[amz_asin]" id="amz_asin" size="80" value="{$product_data.amz_asin}" class="input-text-large" />
                </div>
            </div>
            <div class="control-group">
                <label for="amz_ean" class="control-label">{__('amz_ean')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_ean_desc')}"><i class="icon-question-sign"></i></a>:</label>
                <div class="controls">
                    <input type="text" name="product_data[amz_ean]" id="amz_ean" size="80" value="{$product_data.amz_ean}" class="input-text-large" />
                </div>
            </div>
            <div class="control-group">
                <label for="amz_upc" class="control-label">{__('amz_upc')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_upc_desc')}"><i class="icon-question-sign"></i></a>:</label>
                <div class="controls">
                    <input type="text" name="product_data[amz_upc]" id="amz_upc" size="80" value="{$product_data.amz_upc}" class="input-text-large" />
                </div>
            </div>
            <div class="control-group">
                <label for="amz_isbn" class="control-label">{__('amz_isbn')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_isbn_desc')}"><i class="icon-question-sign"></i></a>:</label>
                <div class="controls">
                    <input type="text" name="product_data[amz_isbn]" id="amz_isbn" size="80" value="{$product_data.amz_isbn}" class="input-text-large" />
                </div>
            </div>
        </div>

        {include file="common/subheader.tpl" title=__("advanced") target="#advanced"}
        <div id="advanced" class="collapse in">
            {if !$product_data.variation_parent_product_id}
                <h5>{__("sd_amz_product_classifier")}</h5>
                <p>{__('sd_amz_product_classifier_desc')}</p>

                {*<div class="control-group">
                    <label class="control-label" for="elm_root_amz_category">{__("sd_amz_root_category")}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_root_category_desc')}"><i class="icon-question-sign"></i></a>:</label>
                    <div class="controls">
                        <select id="elm_root_amz_category" name="template_data[root_category]" onchange="Tygh.$.ceAjax('request', fn_url('ebay.get_subcategories?data_id=category&site_id={$template_data.site_id}&template_id={$template_data.template_id}&required_field=1&parent_id=' + this.value), {$ldelim}result_ids: 'box_ebay_category', caching: true{$rdelim});">
                            <option value="">{__("select")}</option>
                            {foreach from=$amz_root_categories item="item"}
                                <option {if $template_data.root_category == $item.category_id}selected="selected"{/if} value="{$item.category_id}">{$item.name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                {include file="addons/sd_amazon_products/views/amazon_products/components/categories.tpl" data_id="category" required_field=true selected_ebay_category=$template_data.category ebay_categories=$ebay_child_categories}*}

                <div class="control-group">
                    <label for="amz_item_type" class="control-label">{__('amz_item_type')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_item_type_desc')}"><i class="icon-question-sign"></i></a>:</label>
                    <div class="controls">
                        <input type="text" name="product_data[amz_item_type]" id="amz_item_type" size="80" value="{$product_data.amz_item_type}" class="input-text-large" />
                    </div>
                </div>

                <div class="control-group">
                    <label for="amz_browse_node" class="control-label">{__('sd_amz_browse_node')}&nbsp;<a class="cm-tooltip" title="{__('sd_amz_browse_node_desc')}"><i class="icon-question-sign"></i></a>:</label>
                    <div class="controls">
                        <input type="text" name="product_data[amz_browse_node]" id="amz_browse_node" size="80" value="{$product_data.amz_browse_node}" class="input-text-large" />
                    </div>
                </div>
            {/if}

            <h5>{__("promotion_cond_price")}</h5>
            <p>{__('sd_amz_price_desc')}</p>

            <div class="control-group" id="override_price">
                <label for="elm_override_amz_price" class="control-label">{__("amz_override_price")}:</label>
                <div class="controls">
                    <input type="hidden" value="N" name="product_data[amz_override_price]"/>
                    <input type="checkbox" id="elm_override_amz_price" name="product_data[amz_override_price]" class="cm-toggle-checkbox cm-no-hide-input" {if $product_data.amz_override_price == 'Y'} checked="checked"{/if} value="Y" />
                </div>
            </div>
            <div class="control-group">
                <label id="elm_amz_price_title" class="control-label{if $product_data.amz_override_price == 'Y'} cm-required{/if}" for="elm_amz_price">{__("amz_price")} ({$currencies.$primary_currency.symbol nofilter})&nbsp;<a class="cm-tooltip" title="{__('sd_amz_override_price_desc')}"><i class="icon-question-sign"></i></a>:</label>
                <div class="controls">
                    <input type="text" name="product_data[amz_price]" id="elm_amz_price" size="10" {if !empty($product_data.amz_price)} value="{$product_data.amz_price|fn_format_price:$primary_currency:null:false}" {else} value="{$product_data.price|fn_format_price:$primary_currency:null:false}"  {/if} class="input-long" {if $product_data.amz_override_price != 'Y'} disabled="disabled"{/if} />
                </div>
            </div>

            {if !$product_data.variation_parent_product_id}
                <h5>{__("sd_amz_product_condition")}</h5>

                <div class="control-group">
                    <label class="control-label" for="elm_amz_condition">{__("condition")}:</label>
                    <div class="controls">
                        <select id="elm_amz_condition" name="product_data[amz_condition]">
                            {foreach from=$amz_conditions item="item" key="key"}
                                <option {if $product_data.amz_condition == $key}selected="selected"{/if} value="{$key}">{$item}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <label for="amz_condition_note" class="control-label">{__('amz_condition_note')}:</label>
                    <div class="controls">
                        <textarea id="amz_condition_note" name="product_data[amz_condition_note]" rows="4" cols="50">{$product_data.amz_condition_note}</textarea>
                    </div>
                </div>
            {/if}
        </div>

        <script type="text/javascript">
            $('#elm_override_amz_price').on('change', function(){
                if ($(this).prop('checked')) {
                    $('#elm_amz_price').prop('disabled', false);
                    $('#elm_amz_price_title').addClass("cm-required");
                } else {
                    $('#elm_amz_price').prop('disabled', true);
                    $('#elm_amz_price_title').removeClass("cm-required");
                }
            });
        </script>

    </div>
{/if}
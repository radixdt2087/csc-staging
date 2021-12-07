{script src="js/tygh/order_management.js"}
{$latest_version=true}
{if version_compare('4.9.3', $smarty.const.PRODUCT_VERSION) >=0}
    {$latest_version=false}
{/if}
{capture name="mainbox"}
    {capture name="tabsbox"}
        <form action="{""|fn_url}" method="post" enctype="multipart/form-data" class="form-horizontal form-edit" name="add_product_form" id="add_product_form">
            <input type="hidden" name="store_id" value="{$store_data.store_id}" />
            <input type="hidden" name="id" value="{$store_data.store_id}" />
            <div id="content_detailed">
                <fieldset>
                    {if $selected_products}
                        <table width="100%" class="table table-responsive table-middle order-management-products">
                            <thead class="cm-first-sibling">
                                <tr>
                                    <th width="50%">{__("product")}</th>
                                    <th width="25%">{__("wk_store_stock")}</th>
                                    <th width="15%">{__("quantity")}</th>
                                    <th width="5%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach from=$selected_products item="product" key="key" name="name"}
                                <tr>
                                <td width="60%" class="" data-th="{__("wk_store_name")}">
                                    <input type="hidden" name="pickup_stores[{$key}][product_id]" value="{$product.product_id}" />
                                    <input type="hidden" name="pickup_stores[{$key}][store_id]" value="{$product.store_id}" />
                                     <input type="hidden" name="pickup_stores[{$key}][max_stock]" value="{$product.max_stock|default:$product.quantity}" />
                                    <span>{$product.product_id|fn_get_product_name}</span>
                                </td>
                                <td width="15%" data-th="{__("wk_store_stock")}">
                                    <input type="text" name="pickup_stores[{$key}][stock]" value="{$product.stock|default:$product.max_stock}" class="input-micro cm-value-integer pickup_store_stock" max="{$product.max_stock|default:$product.quantity}" data-cscart-stock="{$product.quantity|default:fn_get_product_amount($product.product_id)}" data-avail-stock="{$product.max_stock|default:$product.quantity}"/>

                                    <span class="available_stock_for_store" style="color:green">{__("available_stock_for_store",["[max_stock]"=>$product.max_stock|default:$product.quantity])}</span>
                                    </td>
                                <td width="15%" data-th="{__("quantity")}">
                                    {$product.quantity|default:fn_get_product_amount($product.product_id)}
                                </td>
                                <td width="5%" data-th="{__("tools")}"><a href="{"wk_store_pickup.remove_session_product&product_id=`$product.product_id`&store_id=`$product.store_id`"|fn_url}" class="cm-ajax cm-ajax-full-render cm-post" data-ca-target-id="content_detailed"><i class="icon-remove"></i></a></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    {/if}
                    <div class="form-inline object-selector object-product-add cm-object-product-add-container input-large">
                        <select 
                                class="cm-object-selector wk-object-product-add {if !$latest_version}cm-object-product-add{/if}"
                                tabindex="1" 
                                name="product_id" 
                                data-ca-enable-images="true" 
                                data-ca-image-width="30"  
                                data-ca-image-height="30" 
                                data-ca-enable-search="true" 
                                data-ca-load-via-ajax="true" 
                                data-ca-page-size="10" 
                                data-ca-data-url="{"products.get_products_list?lang_code=`$descr_sl`&company_id=`$store_data.company_id`&wk_store_id=`$store_data.store_id`"|fn_url nofilter}" 
                                data-ca-placeholder="{__("type_to_search_or_click_button")}"
                                data-ca-allow-clear="false" 
                                data-ca-ajax-delay="250" 
                                data-ca-autofocus="true" 
                                >
                            <option value=""></option>
                        </select>
                    </div> 
                </fieldset>
            <!--content_detailed--></div>
            {capture name="buttons"}
                {capture name="tools_list"}
                <li>
                    <a class="cm-dialog-opener" data-ca-target-id="wk_add_bulk_store_products">{__("wk_bulk_add_through_csv")}</a>
                </li>
                {/capture}
                {dropdown content=$smarty.capture.tools_list}
                {include file="buttons/save_cancel.tpl" but_name="dispatch[wk_store_pickup.store_product]" but_role="submit-link" but_target_form="add_product_form" but_text=__("add")}
            {/capture}
          
        </form>
    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox track=true}
{/capture}

{$title = __("wk_store_pickup_add_product_in_store",["[store]"=> $store_data.title])}

{include file="common/mainbox.tpl" title_start=$title_start title_end=$title_end title=$title content=$smarty.capture.mainbox select_languages=false buttons=$smarty.capture.buttons}

<div class="hidden" title="{__("wk_add_bulk_products_through_excelsheet",["[store]"=> $store_data.title])}" id="wk_add_bulk_store_products">
    <form action="{""|fn_url}" method="post" enctype="multipart/form-data" class="form-horizontal form-edit" name="add_bulk_product_form" id="add_bulk_product_form">
        <input type="hidden" name="store_id" value="{$store_data.store_id}" />
        <div class="control-group">
            <label for="type_{"wk_store_products[`$store_data.store_id`]"|md5}" class="control-label cm-required">{__("file")}</label>
            <div class="controls">
            {include file="common/fileuploader.tpl" var_name="wk_store_products[`$store_data.store_id`]" allowed_ext=".csv"}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">{__("csv_delimiter")}:</label>
            <div class="controls">{include file="views/exim/components/csv_delimiters.tpl" name="wk_store_products[delimiter]"}</div>
        </div>
        <p class="alert alert-success">{__("uploaded_csv_file_two_columns_named_stock")}</p>
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_name="dispatch[wk_store_pickup.bulk_upload]" cancel_action="close" }
        </div>
    </form>
</div>
<script>
(function(_, $) {
    {if $latest_version}
    $.ceEvent('on', 'ce.change_select_list', function(object, elm) {
        var contextTemplate = '';
        var contextData = [];
         if (elm.hasClass('wk-object-product-add') && object.data) {
            object.context = object.data.content;
        }
    });  
    {/if}
    var store_id = "{$store_data.store_id}";
    $(document).on('change', '.wk-object-product-add', function (e) {
        e.preventDefault();
        var $container = $(this).closest('.cm-object-product-add-container');
        product_id = $(this).val();
        url = $.sprintf(
            '??&store_id=??&product_id=??',
            [ fn_url('wk_store_pickup.store_session_product'), store_id, product_id],
            '??'
        );
        {literal}
            $container.find('input.select2-search__field').addClass('hidden');
            $.ceAjax('request', url, {
                method: 'post',
                result_ids: 'content_detailed',
                data:getFormData($('#add_product_form')),
                full_render: true
            });
        {/literal}
        function getFormData($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });
            return indexed_array;
        }
    });

    $(document).on('input','.pickup_store_stock',function(){
        var val = parseInt($(this).val()),
        cscart_stock = parseInt($(this).data('cscartStock')),
        avail_stock = parseInt($(this).data('availStock'));
        stock_compare_with = avail_stock<cscart_stock?avail_stock:cscart_stock;
        if(val){
            if (val>stock_compare_with) {
                $.ceNotification('show', {
                    type: 'E',
                    title: '{__("error")}',
                    message: "{__('store_stock_can_bot_be_greater_than_avail_stock')}",
                });
                $(this).val(stock_compare_with);
            }
        }
    });
}(Tygh, Tygh.$));
</script>
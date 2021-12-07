{capture name="mainbox"}
{capture name="tabsbox"}
<form action="{""|fn_url}" method="post" name="vendor_info_form" id="vendor_info_form" class="form-horizontal form-edit vendor-info-form">
<input type="hidden" name="id" value="{$smarty.request.id}" />
<input type="hidden" name="result_ids" value="content_general" />
<input type="hidden" name="selected_section" value="{$selected_section}" />
<div id="content_general">
    <div class="row-fluid">
        <div class="span8">
            {assign var=amount value=0}
            <div class="table-responsive-wrapper">
                <table width="100%" class="table table-middle table--relative table-responsive">
                <thead>
                    <tr><th width="5%"></th>
                        <th width="20%">{__("date")}</th>
                        <th width="20%">{__("product")} </th>
                        <th width="20%">{__("price")}</th>
                        <th class="center" width="20%">{__("company")}</th>
                        <th width="20%" class="right">&nbsp;{__("subtotal")}</th>
                    </tr>
                </thead>
                {foreach from=$plan_charges_details item=item }
                     <tr>  <td width="5%"></td>
                  <td class="nowrap" width="20%" data-th="{__("date")}">
                         {$item.plan_date|date_format:"`$settings.Appearance.date_format`"}</td>
                    <td width="20%" data-th="{__("product")}">  
                          {if $item.type == 'plan'}
                            {$item.plan}
                            {assign var=amount value=$item.amount}
                          {else}
                            {assign var=amount value=$item.aamount}
                            {foreach from=$addon_data item="addon" key="key"}
                                {if $item.addon_id==$addon.id}
                                    {$addon.name}{break}
                                {/if}
                            {/foreach}
                          {/if}
                    </td>
                    <td class="nowrap" width="20%" data-th="{__("price")}">
                        {include file="common/price.tpl" value=$amount}</td>
                    <td class="center" width="20%" data-th="{__("company")}">
                        {$item.company}
                    </td>
                    <td class="right" width="20%" data-th="{__("subtotal")}"><span>{include file="common/price.tpl" value=$amount}</td></span></td>
                </tr>
                {/foreach}
                <tfoot><tr><td class="right" colspan="6"><b>Total: {include file="common/price.tpl" value=$plan_charges_details[0].amount}</td></b></td></tr></tfoot>
                </table>
            </div>  
        </div>
        <div class="span4">
            <div class="well orders-right-pane form-horizontal">
                <div class="control-group">
                    {include file="common/subheader.tpl" title=__("payment_information")}
                    <div class="control-group">
                        <div class="control-label">Credit Card</div>
                        <div class="controls">
                        {if $plan_charges_details[0].card_holder_name}
                             XXXX-XXXX-XXXX-{$plan_charges_details[0].card_number|substr:-4}
                        {/if}
                        </div>
                        <div class="control-label">
                            {* <p><span class="strong">Expiration date</span>: {$plan_charges_details[0].valid_thru}</P> *}
                            Cardholder's name
                            {* <p>CVV/CVC: {$plan_charges_details[0].cvv}</p> *}
                        </div>
                        <div class="controls">
                            {if $plan_charges_details[0].card_holder_name}
                                {$plan_charges_details[0].card_holder_name}
                            {/if}
                        </div>
                    </div>
                </div>
                <div class="control-group shift-top">
                    {include file="common/subheader.tpl" title="Billing Information"}
                    <div>
                        <div>{$plan_charges_details[0].card_holder_name}</div>
                        <div>{$plan_charges_details[0].billing_address}</div>
                        <div>{$plan_charges_details[0].billing_city}, {$plan_charges_details[0].state}, {$plan_charges_details[0].zipcode}</div>
                        <div>{$plan_charges_details[0].country}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
{/capture}
{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$selected_section track=true}

{/capture}
{capture name="mainbox_title"}
    Vendor Plan/AddOn Charges &lrm;#{$smarty.get.id}
     {* &lrm;#{$order_info.id} <span class="f-middle">{__("total")}: <span>{include file="common/price.tpl" value=$order_info.total}</span>{if $order_info.company_id} / {$order_info.company_id|fn_get_company_name}{/if}</span> *}
    {* <span class="f-small">
    {if $status_settings.appearance_type == "I" && $order_info.doc_ids[$status_settings.appearance_type]}
        ({__("invoice")} #{$order_info.doc_ids[$status_settings.appearance_type]})
    {elseif $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}
        ({__("credit_memo")} #{$order_info.doc_ids[$status_settings.appearance_type]})
    {/if}
    {assign var="timestamp" value=$order_info.timestamp|date_format:"`$settings.Appearance.date_format`"|escape:url}
    / {$order_info.timestamp|date_format:"`$settings.Appearance.date_format`"},{$order_info.timestamp|date_format:"`$settings.Appearance.time_format`"}
    </span> *}
{/capture}
{include file="common/mainbox.tpl" title=$smarty.capture.mainbox_title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar sidebar_position="left" sidebar_icon="icon-user"}

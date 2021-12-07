{script src="js/lib/inputmask/jquery.inputmask.min.js"}
{script src="js/lib/creditcardvalidator/jquery.creditCardValidator.js"}
{assign var=valid value="/"|explode:$pdata.valid_thru}
{if $card_id}
    {assign var="id_suffix" value="`$card_id`"}
{else}
    {assign var="id_suffix" value=""}
{/if}
<style>
    div.accordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }
    div.accordion.active, div.accordion:hover, .accordiondefault:hover {
        background-color: #ddd; 
    }
    div.panel {
        padding: 0 18px;
        display: none;
        background-color: white;
    }
    div.panel.show {
        display: block !important;
        width: 100%;
    }
    .position-data {
        width: 130%;
        margin-left: 30px;
        margin-top: 15px;
    }
    .button {
        height: -19px;
        background: #ddd;
        padding: 10px;
        text-align: end;
        border-radius: 5px;
        line-height: 0px;
    }
    #savedDefaultCards, #savedPreviousCards{
        width: 100%;
    }
    .align-end{
        text-align: end;
    }
    .align-initial{
        text-align:initial;
    }
    .card_action{
        text-align: end;
        margin-top:  -15px;
    }
    .accordiondefault {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }
</style>
{if !empty($defaultCard)}
<div id="savedDefaultCards">
    <div class="accordiondefault">
        <div class="card_name"> <input type="radio" name="payment_info[card_token]" value="{$defaultCard->data->id}"> {ucfirst($defaultCard->data->attributes->card->type)} card ending with {$defaultCard->data->attributes->card->number}</div>
        <div class="card_action"><a class="button" type="button" onclick="savedCards()" value="Change">Change</a></div> 
    </div>
   <div class="panel show">
        <table width="100%">
            <tr>
                <td><div class="ty-control-group ty-credit-card__cvv-field cvv-field">
                    <label for="credit_card_cvv2_{$id_suffix}" class="ty-control-group__title cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
                    <input type="number" id="credit_card_cvv2_{$id_suffix}" name="payment_info[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
        class="ty-credit-card__cvv-field-input ty-inputmask-bdi" />
                    <input type="hidden" id="credit_card_token_{$id_suffix}" name="payment_info[card_token]" value="{$defaultCard->data->id}" />
                    <div class="ty-cvv2-about">
                        <span class="ty-cvv2-about__title">{__("what_is_cvv2")}</span>
                        <div class="ty-cvv2-about__note">
        
                            <div class="ty-cvv2-about__info mb30 clearfix">
                                <div class="ty-cvv2-about__image">
                                    <img src="{$images_dir}/visa_cvv.png" alt="" />
                                </div>
                                <div class="ty-cvv2-about__description">
                                    <h5 class="ty-cvv2-about__description-title">{__("visa_card_discover")}</h5>
                                    <p>{__("credit_card_info")}</p>
                                </div>
                            </div>
                            <div class="ty-cvv2-about__info clearfix">
                                <div class="ty-cvv2-about__image">
                                    <img src="{$images_dir}/express_cvv.png" alt="" />
                                </div>
                                <div class="ty-cvv2-about__description">
                                    <h5 class="ty-cvv2-about__description-title">{__("american_express")}</h5>
                                    <p>{__("american_express_info")}</p>
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div></td>
            </tr> 
        </table>
    </div>
</div>
 <!-- Previously Saved Cards -->
<div id="savedPreviousCards" style="display:none;">
    <br>
    <h2 class="litecheckout__step-title">Your Credit and Debit Cards</h2>
    {if $card_details}    
    {foreach from=$card_details key=key item=item}
        <div id="cardDetails">
            <div class="accordion">
                <div class="card_name" id="{$item->data->id}"> <input type="radio" name="payment_info[card_token]" value="{$item->data->id}"> {ucfirst($item->data->attributes->card->type)} card ending with {$item->data->attributes->card->number}</div>
                <div class="card_action" id="expiry{$item->data->id}">{$item->data->attributes->card->expiry|substr:0:2}/{$item->data->attributes->card->expiry|substr:2}<i class="ut2-icon-outline-expand_more"></i></div>
                <input type="hidden" id="c_number{$item->data->id}" value="{$item->data->attributes->card->number}"/>
                <input type="hidden" id="customer_id{$item->data->id}" value="{$item->data->attributes->customerId}"/>
                <input type="hidden" id="card_type{$item->data->id}" value="{$item->data->attributes->card->type}"/>
            </div>
            <div class="panel">
                <div class="saved_card position-data" id="saved_card">
                    <div class="row">
                        <div class="span6 align-initial"><b>Name on Card</b></div>
                        <div class="span6 align-end"><b>Billing Address</b></div>
                    </div>
                    <div class="row">
                        <div class="span6 align-initial" style="vertical-align: baseline;" id="card_name{$item->data->id}">{ucfirst($item->data->attributes->card->holder)}</div>
                        <div class="span6 align-end"><div id="address1{$item->data->id}">{$item->data->attributes->billingAddress->line1}</div><br>
                            <div id="address2{$item->data->id}">{$item->data->attributes->billingAddress->line2}</div><br>
                            <div id="city{$item->data->id}">{$item->data->attributes->billingAddress->city}</div><br>
                            <div id="state{$item->data->id}">{$item->data->attributes->billingAddress->state}</div><br>
                            <div id="country{$item->data->id}">{$item->data->attributes->billingAddress->country}</div>, <div id="zip{$item->data->id}">{$item->data->attributes->billingAddress->zip}</div></div>
                    </div>
                    <div class="row">
                        <div class="span6">
                            <div class="ty-control-group ty-credit-card__cvv-field cvv-field">
                                <label for="credit_card_cvv2_{$item->data->id}" class="ty-control-group__title cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
                                <input type="number" id="credit_card_cvv2_{$item->data->id}" name="payment_info[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                class="ty-credit-card__cvv-field-input ty-inputmask-bdi" />
                                <div class="ty-cvv2-about">
                                    <span class="ty-cvv2-about__title">{__("what_is_cvv2")}</span>
                                    <div class="ty-cvv2-about__note">
                                        <div class="ty-cvv2-about__info mb30 clearfix">
                                            <div class="ty-cvv2-about__image">
                                                <img src="{$images_dir}/visa_cvv.png" alt="" />
                                            </div>
                                            <div class="ty-cvv2-about__description">
                                                <h5 class="ty-cvv2-about__description-title">{__("visa_card_discover")}</h5>
                                                <p>{__("credit_card_info")}</p>
                                            </div>
                                        </div>
                                        <div class="ty-cvv2-about__info clearfix">
                                            <div class="ty-cvv2-about__image">
                                                <img src="{$images_dir}/express_cvv.png" alt="" />
                                            </div>
                                            <div class="ty-cvv2-about__description">
                                                <h5 class="ty-cvv2-about__description-title">{__("american_express")}</h5>
                                                <p>{__("american_express_info")}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span6"><p><a class="cm-ajax cm-post cm-submit" style="color:#007aff;;" href="{"card_management.setDefault?card_token={$item->data->id}"|fn_url}" >Set as default</a> payment method.</p></div>
                        <div class="span6 align-end"><a class="cm-ajax cm-post cm-submit button" href="{"card_management.delete?card_token={$item->data->id}"|fn_url}">Remove</a> &nbsp; <a class="button edit-btn" id="{$item->data->id}">Edit</a></div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    {/foreach}
    <div class="edit_card" id="edit_card" style="display: none;">
        <br>
        <h2 class="litecheckout__step-title">Edit your Card</h2>
        <div class="accordion">
             <div class="card_name" id="card_name"> 
            </div>
        </div>
        <div class="panel show">
            <div class="edit_card1 position-data" id="edit_card1" >
                <div class="row">
                    <div class="span6 align-initial"><b>Name on Card</b></div>
                    <div class="span6 align-end"><b>Billing Address</b></div>
                </div>
                <div class="row">
                    <div class="span6 align-initial" style="vertical-align: baseline;"><input type="text" id="credit_card_name" name="payment_infoe[cardholder_name]" value="" class="cm-cc-name ty-credit-card__input ty-uppercase" /> <br>
                        <input type="hidden" name="card_id" id="card_id" value="">
                        <input type="hidden" name="card_number_val" id="card_number_val" value="">
                        <input type="hidden" name="cust_id" id="cust_id" value="">
                        <input type="hidden" name="edit_payment_data[card_type]" id="c_type" value="">
                        <div class="ty-credit-card__control-group ty-control-group">
                            <label for="credit_card_month" {literal}data-ca-regexp="^\d{1,2}$"{/literal} data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date cm-cc-exp-month">{__("valid_thru")}</label>
                            <label for="credit_card_year" {literal}data-ca-regexp="^\d{2,4}$"{/literal} data-ca-message="" class="cm-regexp cm-cc-date cm-cc-exp-year cc-year hidden"></label>
                            <input type="number" id="credit_month" name="payment_infoe[expiry_month]" value="" class="ty-credit-card__input-short"/>
                            <input type="number" id="credit_year" name="payment_infoe[expiry_year]" value="" class="ty-credit-card__input-short"/>
                            
                        </div>
                    </div>
                    <div class="span6 align-end"><input type="text" id="billing_address_line_1" name="payment_infoe[b_address]" value="" placeholder="Billing Address Line 1"/><br>
                        <input type="text" id="billing_address_line_2" name="payment_infoe[b_address_2]" value="" placeholder="Billing Address Line 2"/><br>
                        <input type="text" id="billing_city" name="payment_infoe[b_city]" value="" placeholder="City"/><br>
                        <input type="text" id="billing_state" name="payment_infoe[b_state]" value="" placeholder="State"/><br>
                        <input type="text" id="billing_country" name="payment_infoe[b_country]" value="" placeholder="Country"/> <input type="text" id="billing_zipcode" name="payment_infoe[b_zipcode]" value="" placeholder="Zipcode"/></div>
                </div>
                <div class="row">
                    <div class="span6">
                        <div class="ty-control-group ty-credit-card__cvv-field cvv-field">
                            <label for="credit_card_cvv2" class="ty-control-group__title cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
                            <input type="number" id="credit_card_cvv2_{$id_suffix}" name="payment_infoe[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            class="ty-credit-card__cvv-field-input ty-inputmask-bdi" />
                            <div class="ty-cvv2-about">
                                <span class="ty-cvv2-about__title">{__("what_is_cvv2")}</span>
                                <div class="ty-cvv2-about__note">
                                    <div class="ty-cvv2-about__info mb30 clearfix">
                                        <div class="ty-cvv2-about__image">
                                            <img src="{$images_dir}/visa_cvv.png" alt="" />
                                        </div>
                                        <div class="ty-cvv2-about__description">
                                            <h5 class="ty-cvv2-about__description-title">{__("visa_card_discover")}</h5>
                                            <p>{__("credit_card_info")}</p>
                                        </div>
                                    </div>
                                    <div class="ty-cvv2-about__info clearfix">
                                        <div class="ty-cvv2-about__image">
                                            <img src="{$images_dir}/express_cvv.png" alt="" />
                                        </div>
                                        <div class="ty-cvv2-about__description">
                                            <h5 class="ty-cvv2-about__description-title">{__("american_express")}</h5>
                                            <p>{__("american_express_info")}</p>
                                        </div>
                                    </div>                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="span6"><p><a class="cm-ajax cm-post cm-submit" style="color:#007aff;" href="{"card_management.setDefault?card_token={$item->data->id}"|fn_url}">Set as default</a> payment method.</p>
                    </div>
                    <div class="span6 align-end"><a class="cm-ajax cm-post cm-submit button" href="{"card_management.delete?card_token={$item->data->id}"|fn_url}">Remove</a> 
                        <a class="button button_cart_818">Update your Card</a> <a class="button cancel-btn">Cancel</a>
                    </div>
                </div>                     
            </div>
        </div>
        <br>
    </div>
    {/if}  
    <div id="adding_card">
        <div class="accordion">
            <div class="card_name">Add New card</div>
        </div>
        <div class="panel" id="panel_add">
            <div class="litecheckout__item">
                <div class="clearfix">
                    <div class="ty-credit-card cm-cc_form_{$id_suffix}">
                        <div class="ty-credit-card__control-group ty-control-group">
                            <label for="credit_card_number_{$id_suffix}" class="ty-control-group__title cm-cc-number cc-number_{$id_suffix} ">{__("card_number")}</label>
                            <input size="35" type="text" id="credit_card_number_{$id_suffix}" name="payment_info1[card_number]" value="{$pdata.card_number}" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" />
                            <input size="35" type="hidden" id="credit_card_numbers_{$id_suffix}" name="payment_info1[card_numbers]" value="{$pdata.card_number}" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" />
                            <ul class="ty-cc-icons cm-cc-icons cc-icons_{$id_suffix}">
                                <li class="ty-cc-icons__item cc-default cm-cc-default"><span class="ty-cc-icons__icon default">&nbsp;</span></li>
                                <li class="ty-cc-icons__item cm-cc-visa"><span class="ty-cc-icons__icon visa">&nbsp;</span></li>
                                <li class="ty-cc-icons__item cm-cc-visa_electron"><span class="ty-cc-icons__icon visa-electron">&nbsp;</span></li>
                                <li class="ty-cc-icons__item cm-cc-mastercard"><span class="ty-cc-icons__icon mastercard">&nbsp;</span></li>
                                <li class="ty-cc-icons__item cm-cc-maestro"><span class="ty-cc-icons__icon maestro">&nbsp;</span></li>
                                <li class="ty-cc-icons__item cm-cc-amex"><span class="ty-cc-icons__icon american-express">&nbsp;</span></li>
                                <li class="ty-cc-icons__item cm-cc-discover"><span class="ty-cc-icons__icon discover">&nbsp;</span></li>
                            </ul>
                        </div>
                        <div class="ty-credit-card__control-group ty-control-group">
                            <label for="card_month_{$id_suffix}" {literal}data-ca-regexp="^\d{1,2}$"{/literal} data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date_{$id_suffix} cm-cc-exp-month ">{__("valid_thru")}</label>
                            <label for="card_year_{$id_suffix}" {literal}data-ca-regexp="^\d{2,4}$"{/literal} data-ca-message="" class=" cm-regexp cm-cc-date cm-cc-exp-year cc-year_{$id_suffix} hidden"></label>
                            <input type="number" id="exp_month" name="payment_info1[expiry_month]" value="" class="ty-credit-card__input-short"/>
                            <input type="number" id="exp_year" name="payment_info1[expiry_year]" value="" class="ty-credit-card__input-short"/>
                        </div>
                        <div class="ty-credit-card__control-group ty-control-group">
                            <label for="credit_card_name_{$id_suffix}" class="ty-control-group__title ">{__("cardholder_name")}</label>
                            <input size="35" type="text" id="credit_card_name_{$id_suffix}" name="payment_info1[cardholder_name]" value="{$pdata.card_holder_name}" class="cm-cc-name ty-credit-card__input ty-uppercase" />
                        </div>
                    </div>
                    <div class="ty-control-group ty-credit-card__cvv-field cvv-field">
                        <label for="credit_card_cvv2_{$id_suffix}" class="ty-control-group__title  cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
                        <input type="number" id="credit_card_cvv2_{$id_suffix}" name="payment_info1[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                        class="ty-credit-card__cvv-field-input ty-inputmask-bdi" />
                        <div class="ty-cvv2-about">
                            <span class="ty-cvv2-about__title">{__("what_is_cvv2")}</span>
                            <div class="ty-cvv2-about__note">
                                <div class="ty-cvv2-about__info mb30 clearfix">
                                    <div class="ty-cvv2-about__image">
                                        <img src="{$images_dir}/visa_cvv.png" alt="" />
                                    </div>
                                    <div class="ty-cvv2-about__description">
                                        <h5 class="ty-cvv2-about__description-title">{__("visa_card_discover")}</h5>
                                        <p>{__("credit_card_info")}</p>
                                    </div>
                                </div>
                                <div class="ty-cvv2-about__info clearfix">
                                    <div class="ty-cvv2-about__image">
                                        <img src="{$images_dir}/express_cvv.png" alt="" />
                                    </div>
                                    <div class="ty-cvv2-about__description">
                                        <h5 class="ty-cvv2-about__description-title">{__("american_express")}</h5>
                                        <p>{__("american_express_info")}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input id="button_cart_817" type="submit" name="submitCardData" value="Add your Card" />
        </div>  
    </div>
</div>

{else}
<div class="litecheckout__item">
    <div class="clearfix">
        <div class="ty-credit-card cm-cc_form_{$id_suffix}">
            <div class="ty-credit-card__control-group ty-control-group">
                <label for="credit_card_number_{$id_suffix}" class="ty-control-group__title cm-cc-number cc-number_{$id_suffix}">{__("card_number")}</label>
                <input size="35" type="text" id="credit_card_number_{$id_suffix}" name="payment_info[card_number]" value="{$pdata.card_number}" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" />
                <input size="35" type="hidden" id="credit_card_numbers_{$id_suffix}" name="payment_info[card_numbers]" value="{$pdata.card_number}" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" />
                <ul class="ty-cc-icons cm-cc-icons cc-icons_{$id_suffix}">
                    <li class="ty-cc-icons__item cc-default cm-cc-default"><span class="ty-cc-icons__icon default">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-visa"><span class="ty-cc-icons__icon visa">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-visa_electron"><span class="ty-cc-icons__icon visa-electron">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-mastercard"><span class="ty-cc-icons__icon mastercard">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-maestro"><span class="ty-cc-icons__icon maestro">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-amex"><span class="ty-cc-icons__icon american-express">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-discover"><span class="ty-cc-icons__icon discover">&nbsp;</span></li>
                </ul>
            </div>

            <div class="ty-credit-card__control-group ty-control-group">
                <label for="credit_card_month_{$id_suffix}" {literal}data-ca-regexp="^\d{1,2}$"{/literal} data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date_{$id_suffix} cm-cc-exp-month ">{__("valid_thru")}</label>
                <label for="credit_card_year_{$id_suffix}" {literal}data-ca-regexp="^\d{2,4}$"{/literal} data-ca-message="" class=" cm-regexp cm-cc-date cm-cc-exp-year cc-year_{$id_suffix} hidden"></label>
                <input type="number" id="credit_card_month_{$id_suffix}" min="0" name="payment_info[expiry_month]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;&nbsp;/&nbsp;&nbsp;<input type="number" min="0" id="credit_card_year_{$id_suffix}"  name="payment_info[expiry_year]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;
            </div>

            <div class="ty-credit-card__control-group ty-control-group">
                <label for="credit_card_name_{$id_suffix}" class="ty-control-group__title ">{__("cardholder_name")}</label>
                <input size="35" type="text" id="credit_card_name_{$id_suffix}" name="payment_info[cardholder_name]" value="{$pdata.card_holder_name}" class="cm-cc-name ty-credit-card__input ty-uppercase" />
            </div>
        </div>

  <div class="ty-control-group ty-credit-card__cvv-field cvv-field">
            <label for="credit_card_cvv2_{$id_suffix}" class="ty-control-group__title  cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
            <input type="number" id="credit_card_cvv2_{$id_suffix}" name="payment_info[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
 class="ty-credit-card__cvv-field-input ty-inputmask-bdi" />

            <div class="ty-cvv2-about">
                <span class="ty-cvv2-about__title">{__("what_is_cvv2")}</span>
                <div class="ty-cvv2-about__note">

                    <div class="ty-cvv2-about__info mb30 clearfix">
                        <div class="ty-cvv2-about__image">
                            <img src="{$images_dir}/visa_cvv.png" alt="" />
                        </div>
                        <div class="ty-cvv2-about__description">
                            <h5 class="ty-cvv2-about__description-title">{__("visa_card_discover")}</h5>
                            <p>{__("credit_card_info")}</p>
                        </div>
                    </div>
                    <div class="ty-cvv2-about__info clearfix">
                        <div class="ty-cvv2-about__image">
                            <img src="{$images_dir}/express_cvv.png" alt="" />
                        </div>
                        <div class="ty-cvv2-about__description">
                            <h5 class="ty-cvv2-about__description-title">{__("american_express")}</h5>
                            <p>{__("american_express_info")}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
{/if}   

<script type="text/javascript">
    var acc = document.getElementsByClassName("accordion");
    var i;
    
    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function(){
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
      }
    }
    $(".button_cart_818").click(function() {
        $.ceAjax('request', fn_url('card_management.update'), {
            method: 'POST',
            data: {  
                'holder' : $('#edit_card1').find("input[name='payment_infoe[cardholder_name]']").val(),
                'month' : $('#edit_card1').find("input[name='payment_infoe[expiry_month]']").val(),
                'year' : $('#edit_card1').find("input[name='payment_infoe[expiry_year]']").val(),
                'cvv' : $('#edit_card1').find("input[name='payment_infoe[cvv2]']").val(), 
                'b_address' : $('#edit_card1').find("input[name='payment_infoe[b_address]']").val(),
                'b_address_2' : $('#edit_card1').find("input[name='payment_infoe[b_address_2]']").val(),
                'b_city' : $('#edit_card1').find("input[name='payment_infoe[b_city]']").val(), 
                'b_state' : $('#edit_card1').find("input[name='payment_infoe[b_state]']").val(), 
                'b_country' : $('#edit_card1').find("input[name='payment_infoe[b_country]']").val(), 
                'b_zipcode' : $('#edit_card1').find("input[name='payment_infoe[b_zipcode]']").val(),
                'card_token' : $('#edit_card1').find("input[name='card_id']").val(), 
            },
        });  
     });
     $("#button_cart_817").click(function() {
            $.ceAjax('request', fn_url('card_management.add'), {
                method: 'POST',
                data: {  
                    'holder' : $('#panel_add').find("input[name='payment_info1[cardholder_name]']").val(),
                    'card_number' : $('#panel_add').find("input[name='payment_info1[card_number]']").val(),
                    'month' : $('#panel_add').find("input[name='payment_info1[expiry_month]']").val(),
                    'year' : $('#panel_add').find("input[name='payment_info1[expiry_year]']").val(),
                    'cvv' : $('#panel_add').find("input[name='payment_info1[cvv2]']").val(),  
                },
                callback: function(data){
                    alert(data);
                }
                });    
     });
     $('.edit-btn').click(function() {
            var id = $(this).attr('id');
            $('#card_name').text($("#"+id).text());
            $('#credit_card_name').val($("#card_name"+id).html());
            var exp_date = $("#expiry"+id).text().split("/");
            $('#credit_month').val(exp_date[0]);
            $('#credit_year').val(exp_date[1]);
            $("#billing_address_line_1").val($("#address1"+id).text());
            $("#billing_address_line_2").val($("#address2"+id).text());
            $("#billing_city").val($("#city"+id).text());
            $("#billing_state").val($("#state"+id).text());
            $("#billing_country").val($("#country"+id).text());
            $("#billing_zipcode").val($("#zip"+id).text());           
            $("#c_type").val($("#card_type"+id).val());
            $("#card_number_val").val($("#c_number"+id).val());
            $("#cust_id").val($("#customer_id"+id).val());
            $("#card_id").val(id);
            $("#edit_card").show();
            $("#adding_card").hide();
            $("#cardDetails").hide();
        });
    function savedCards() {
        var x = document.getElementById("savedPreviousCards");
        var y = document.getElementById("savedDefaultCards");
        if (x.style.display === "none") {
            x.style.display = "block";
            y.style.display = "none";
        } else {
            x.style.display = "none";
            y.style.display = "block";
        }
    }
    $('.cancel-btn').click(function() {
        $('#edit_card').hide();
        $("#card_action").val("Cancel");
        $("#adding_card").show();
        $("#cardDetails").show();
   });
    (function(_, $) {
    
        var isChromeOnOldAndroid = function() {
            var ua = navigator.userAgent;
            return (/Android/.test(ua) && /Chrome/.test(ua));
        };
        cinfo=$("#litecheckout_step_customer_info").html();
        cinfo2=$("#litecheckout_step_customer_info").prev().html();
    
        $("#litecheckout_step_customer_info").prev().html(cinfo);
        $("#litecheckout_step_customer_info").html(cinfo2);
    var ccFormId = '{$id_suffix}';
    
        $.ceEvent('on', 'ce.commoninit', function() {
    
            var icons           = $('.cc-icons_' + ccFormId + ' li');
    
            var ccNumber        = $(".cc-number_" + ccFormId);
            var ccNumberInput   = $("#" + ccNumber.attr("for"));
    
            var ccCv2           = $(".cc-cvv2_" + ccFormId);
            var ccCv2Input      = $("#" + ccCv2.attr("for"));
    
            var ccMonth         = $(".cc-date_" + ccFormId);
            var ccMonthInput    = $("#" + ccMonth.attr("for"));
    
            var ccYear          = $(".cc-year_" + ccFormId);
            var ccYearInput     = $("#" + ccYear.attr("for"));
                if (!isChromeOnOldAndroid()) {
    
                    if($("#credit_card_number_"+ccFormId).val()!= '') {
                        ccNumberInput.inputmask("XXXX XXXX XXXX 9[9][9][9]", {
                            placeholder: ''
                        });
                    } else {
                        ccNumberInput.inputmask("9999 9999 9999 9[9][9][9]", {
                            placeholder: ''
                        });
                    }
    
                }    
            $("#credit_card_number_"+ccFormId).keyup(function(e){
                 var keyCode = e.keyCode;
                 if (keyCode == 8) {
                    $("#credit_card_number_"+ccFormId).inputmask("9999 9999 9999 9[9][9][9]", {
                        placeholder: ''
                    });
                     $("#credit_card_number_"+ccFormId).val('');
                 }
            });
    
        });
    
    })(Tygh, Tygh.$);
    
        </script>

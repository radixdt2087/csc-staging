{script src="js/lib/inputmask/jquery.inputmask.min.js"}
{script src="js/lib/creditcardvalidator/jquery.creditCardValidator.js"}
{assign var=valid value="/"|explode:$pdata.valid_thru}
{if $card_id}
    {assign var="id_suffix" value="`$card_id`"}
{else}
    {assign var="id_suffix" value=""}
{/if}
<style>
    div.accordionnew {
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
        margin: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    div.accordionnew.active, div.accordionnew:hover, .accordiondefault:hover {
        background-color: #ddd; 
    }
    div.panel {
        padding: 0 18px;
        display: none;
        background-color: white;
    }
    div.panel.show {
        display: block !important;
        /*width: 100%;*/
    }
    .position-data {
        width: 130%;
        margin-left: 30px;
        margin-top: 15px;
    }
    .button {
        height: -19px;
        background: #6498f0;
        padding: 10px;
        text-align: end;
        border-radius: 5px;
        line-height: 0px;
        color: #fff;
    }
    .buttonChange {
        height: -19px;
        /* background: #ddd; */
        padding: 10px;
        text-align: end;
        border-radius: 5px;
        line-height: 0px;
    }
    .lineheight {
        height: -19px;
        background: #ddd;
        padding: 10px;
        text-align: end;
        border-radius: 5px;
        line-height: 50px;
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
        margin-top: -5px;
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
    #adding_card{
        width: 100%;
    }

.container-fluid.litecheckout__header {
    padding: 25px;
}
.span12.main-content-grid {
    background: #fff;
    margin: 32px 0px;
    padding: 32px;
}
.defaultPayment {
    width: 100%;
    display: inline-block;
    border-top: 1px solid #dddddd;
    border-bottom: 1px solid #dddddd;
    padding: 20px 0px;
    margin: 32px 0px;
}
.defaulttitlepayment {
    width: 20%;
    float: left;
}
.defaultPayment #savedDefaultCards {
    width: 80%;
    float: left;
}
#savedDefaultCards .accordiondefault {
    background: #fff;
    display: inline;
    padding: 0px;
}
.card_name .span20 {
    float: left;
    width: 90%;
    margin-top: 10px;
}
.billing_address {
    margin-top: 20px;
}
#adding_card {
    width: 100%;
    display: inline-flex;
    flex-wrap: wrap;
}
/*24-08-2021*/
h4.default-payment-title {
    width: 20%;
    float: left;
    margin: 0;
    margin-top: 6px;
}
.defaultPayment #savedDefaultCards {
    width: 79%;
    float: left;
    padding: 0;
    margin: 0;
}
.default-card-details .row:first-child {
    display: inline;
}
.span8.default-card-number {
    max-width: 70%;
}
.span1.crad-image {
    max-width: 20%;
    float: left;
    width: 60px;
}
.span7.default-card-name {
    margin-top: 9px;
    max-width: 70%;
    width: 380px;
}
.row.billing-address {
    margin: 20px 0 0 0;
}
.saved_cards_title .default-payment-title {
    width: 100%;
}
.saved-card_name {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 5px 5px 15px 5px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
    display: inline-block;
}
.row.saved_cards_title {
    width: 100%;
    margin-left: 0px;
    margin-bottom: 20px;
}
.other-added-card {
    background: #fff;
    border: 1px solid #dddddd;
    display: inline-block;
    width: 100%;
    margin-bottom: 20px;
}
.saved-card_name {
    background: #eee;
    width: 100%;
    display: inline-block;
    padding: 15px 5px;
    box-sizing: border-box;
}
.saved-cards-details .row {
    margin: 0px;
    padding: 5px 0px;
}
.saved-card-image {
    margin-top: 5px;
    padding-left: 10px;
}
.span5.saved-card-number {
    float: left;
    margin-left: 50px;
}
.savedCardsDetails .row {
    width: 100%;
    display: inline-block;
    margin: 0px;
    margin-top: 20px;
}
.row.card_cvv_details {
    border-top: 1px solid #ddd;
    display: inline-block;
    width: 100%;
    margin: 0px;
    margin-top: 20px;
}
.ty-credit-card__cvv-field {
    display: inline-block;
    margin: 10px 0px;
    FONT-VARIANT: JIS04;
    width: 100%;
    max-width: 100%;
}
.ty-control-group__title {
    display: block;
    padding: 10px 0;
    font-weight: bold;
    width: 30%;
    float: left;
}
.row.card_action_perform {
    border-top: 1px solid #ddd;
    display: inline-block;
    padding: 15px 0px;
    margin-top: 0px;
}
div#saved_card {
    background: none;
}
.edit_card .default-payment-title {
    width: 100%;
    margin-bottom: 10px;
    float: left;
}
.edit_card {
    width: 100%;
    display: inline-block;
}
.edit-card-number {
    padding: 15px;
    background: #efefef;
    float: left;
    width: -webkit-fill-available;
    /* color: #c2c2c2; 
    font-weight: 500;
    letter-spacing: 2;*/
}
.panel.show.edit-card-details {
    float: left;
    width: 96%;
    border: 1px solid #dddddd;
    padding: 25px;
    margin: 10px 0px;
}
.saved-card-number p {
    display: none;
}
.button {
    line-height: 20px;
    display: inline-block;
}
div#saved_card + br {
    display: none;
}
.edit-billing-address p input {
    width: 100%;
}
.edit-card-cvv {
    display: inline-block;
    width: 100%;
    padding: 15px 0px;
    margin: 15px 0px;
    border-top: 1px solid #dddddd;
}
.edit-card-action {
    border-top: 1px solid #dddddd;
    padding-top: 15px;
    display: inline-block;
    width: 100%;
}
.cc-validity-edit {
    margin-top: 15px;
}
#adding_card .litecheckout__item {
    margin-top: 15px;
}
#adding_card .ty-credit-card.cm-cc_form_17 {
    max-width: 100%;
}
.new_card {
    display: inline-flex;
    width: 100%;
    flex-wrap: wrap;
}
.new_card .litecheckout__item {
    margin-top: 15px;
}
.new_card .ty-credit-card.cm-cc_form_17 {
    max-width: 100%;
}
.new_card #panel_add {
    width: 100%;
}
.new_card .clearfix {
    width: 100%;
}
.new_card .cvv-field {
    width: 48%;
    margin-left: 20px;
    margin-top: 20px;
}
.new_card .cm-cc-cvv2 {
    width: 100%;
    margin-right: 10px;
    padding-bottom: 6px;
}
.add_new_card {
    width: 100%;
}
.add_new_card .clearfix {
    width: 100%;
}
.add_new_card .cvv-field {
    width: 48%;
    margin-left: 20px;
    margin-top: 20px;
}
.add_new_card .cm-cc-cvv2 {
    width: 100%;
    margin-right: 10px;
    padding-bottom: 6px;
}
.litecheckout__address-switch, .litecheckout__terms .ty-checkout__terms  {
    margin: 0;
    border: 1px solid #ddd;
    background: #efefef;
}
#litecheckout_step_location .litecheckout__item {
    display: none;
}
h2.litecheckout__step-title {
    display: none;
}
.litecheckout__group .litecheckout__item--fill {
    display: none;
}
.litecheckout__container {
    padding: 0px;
}
.bill-add{
    padding:1px;
}
.litecheckout__newsletters {
    background: #efefef;
    border: 1px solid #ddd;
    margin: 0.5rem;
    border-radius: 3px;
    padding: 0px 10px;
}
.litecheckout litecheckout__form input[type="text"] {
    border: 1px solid #dddddd;
}
#sw_billing_address_suffix_no {
    margin: 0px;
}
.litecheckout__newsletters h2.litecheckout__step-title {
    display: block;
    font-size: 16px;
    color: #333;
    font-weight: bold;
    border-bottom: 1px dashed #333;
    width: 100%;
    padding-bottom: 10px;
}

@media (min-width: 1224px){
    .row-fluid .span5 {
    width: 40.787234%;
}
}

</style>
{if !empty($defaultCard)}
<div class="defaultPayment">
    <h4 class="default-payment-title">Payment methods</h4>
    <div id="savedDefaultCards" class="default-card-details">
            <div class="row">
                <div class="span8 default-card-number">
                    <div class="row">
                        <div class="span1 crad-image">
                            <input type="hidden" name="payment_info[card_token]" value="{$defaultCard->data->id}">
                            <div class="ty-cc-icons__item cm-cc-{$defaultCard->data->attributes->card->type} active">
                                <span class="ty-cc-icons__icon {if $defaultCard->data->attributes->card->type == 'amex'} american-express {else} {$defaultCard->data->attributes->card->type} {/if}"> &nbsp; </span>
                            </div>
                        </div>
                        <div class="span7 default-card-name">
                            <b>{ucfirst($defaultCard->data->attributes->card->type)}</b>  ending in  <b> {$defaultCard->data->attributes->card->number|substr:-4}</b></p>
                        </div>
                    </div>
                    
                </div>
                <div class="span6 change-default-card">
                    <div class="card_action">
                        <a class="buttonChange" type="button" onclick="savedCards()" value="Change">Change</a>
                    </div>                
                </div>
            </div>
            <div class="row billing-address">
                <div class="span6"> 
                    <b>Billing Address:</b> {$defaultCard->data->attributes->card->holder}, {$defaultCard->data->attributes->billingAddress->line1}, {$defaultCard->data->attributes->billingAddress->line2}, {$defaultCard->data->attributes->billingAddress->city} {$defaultCard->data->attributes->billingAddress->state} {$defaultCard->data->attributes->billingAddress->country}, {$defaultCard->data->attributes->billingAddress->zip}
                </div>
            </div>   
    </div>
</div>
<!-- Previously Saved Cards -->
<div id="savedPreviousCards" style="display:none;" class="saved-card-list">
    <div class="row saved_cards_title">
        <div class="span6">
            <h4 class="default-payment-title">Your Credit and Debit Cards</h4>
        </div>
    </div>
    {if $card_details}    
    {foreach from=$card_details key=key item=item}
        <div id="cardDetails" class="other-added-card card-id-{$item->data->id}">
            <div class="saved-card_name accordion" >
                <div class="span8 saved-cards-details">
                    <div class="row" id="{$item->data->id}">
                        <div class="span1 saved-card-image" id="save-{$item->data->id}">
                            <div class="ty-cc-icons__item cm-cc-{$item->data->attributes->card->type} active" style="margin-top: -15px;"><span class="ty-cc-icons__icon {if $item->data->attributes->card->type == 'amex'} american-express {else} {$item->data->attributes->card->type} {/if}"> &nbsp; </span></div>
                        </div>
                        <div class="span5 saved-card-number">
                            <input type="radio" name="payment_info[card_token]" {if $defaultCard->data->id == $item->data->id} checked="checked"{/if} value="{$item->data->id}" id="selectcard{$item->data->id}">
                            <b>{ucfirst($item->data->attributes->card->type)}</b> ending in <b> {$item->data->attributes->card->number|substr:-4}</b></p>
                        </div>
                    </div>
                </div>
                <div class="span6 offset2 expiry-date" id="expiry{$item->data->id}">
                    {$item->data->attributes->card->expiry|substr:0:2}/{$item->data->attributes->card->expiry|substr:2}<i class="ut2-icon-outline-expand_more"></i>
                </div>
            </div>
            <div class="panel show savedCardsDetails" id="saved_card">
                <div class="row">
                    <div class="span8 name_on_card">
                        <b>Name on Card</b>
                        <p id="card_name{$item->data->id}">{ucfirst($item->data->attributes->card->holder)}</p>
                    </div>
                    <div class="span6 offset2 address_on_card">
                        <b>Billing Address</b>
                        <p id="address1{$item->data->id}" class="bill-add">{$item->data->attributes->billingAddress->line1}</p>
                        <span id="address2{$item->data->id}" class="bill-add">{$item->data->attributes->billingAddress->line2}</span>,<span id="city{$item->data->id}" class="bill-add">{$item->data->attributes->billingAddress->city}</span>, <span id="state{$item->data->id}" class="bill-add">{$item->data->attributes->billingAddress->state}</span>, <span id="zip{$item->data->id}" class="bill-add">{$item->data->attributes->billingAddress->zip}</span>
                        <p id="country{$item->data->id}" class="bill-add">{$item->data->attributes->billingAddress->country}</p>
                        
                    </div>
                </div>
                <div class="row card_action_perform">
                <div class="span6"><p>{if $defaultCard->data->id == $item->data->id}
                    <p>This card is your current default card</p>
                    <!-- <img src="design/themes/responsive/media/images/addons/card_management/info.png" style="height: 20px;">This card is recommended for you <a title="A payment method is recommended based on the current payment performance of all your payment options." style="color:#007aff;">Why ?</a> -->
                    {else}
                    <a class="cm-ajax cm-post cm-submit" style="color:#007aff;" onClick="window.location.reload();" href="{"card_management.setDefault?card_token={$item->data->id}"|fn_url}" >Set as default</a> payment method.</p>{/if}</div>
                <div class="span6 align-end"><a class="cm-ajax cm-post cm-submit button" onClick="window.location.reload();" href="{"card_management.delete?card_token={$item->data->id}"|fn_url}">Remove</a> &nbsp; <a class="button edit-btn" id="{$item->data->id}">Edit</a></div>
                </div>
            </div>
            <br>
        </div>
    {/foreach}
    <div class="edit_card" id="edit_card" style="display: none;">
        <h4 class="default-payment-title span6 edit-card-title">Edit your Card</h4>
        <div class="edit-card-number" id="card_name"></div>
        <div class="panel show edit-card-details">
            <div class="edit_card1" id="edit_card1" >
                <div class="card-billing">
                    <div class="span8 align-initial" style="vertical-align: baseline;">
                        <div class="cc-number-edit" for="payment_infoe">
                        <p class="ty-control-group__title credit_card_number cc-number-ed">Card Number</p>
                        <input size="35" type="text" id="credit_card_number_edit" name="payment_infoe[card_number]" value="" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" />
                        <ul class="ty-cc-icons cm-cc-icons cc-icons">
                            <li class="ty-cc-icons__item cc-default cm-cc-editdefault"><span class="ty-cc-icons__icon default">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-editvisa"><span class="ty-cc-icons__icon visa">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-editvisa_electron"><span class="ty-cc-icons__icon visa-electron">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-editmastercard"><span class="ty-cc-icons__icon mastercard">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-editmaestro"><span class="ty-cc-icons__icon maestro">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-editamex"><span class="ty-cc-icons__icon american-express">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-editdiscover"><span class="ty-cc-icons__icon discover">&nbsp;</span></li>
                        </ul>
                        <input type="hidden" id="card_typee" name="payment_infoe[card_type]" value=""/>
                        
                        </div>
                        <!-- <input size="35" type="text" id="credit_card_num" name="payment_infoe[card_number]" value="" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" /> -->
                        <div class="cc-name-edit">
                            <p class="ty-control-group__title cm-cc-number cc-name">Name on Card</p>
                            <input type="text" id="credit_card_name" name="payment_infoe[cardholder_name]" value="" class="cm-cc-name ty-credit-card__input ty-uppercase" />
                            <input type="hidden" name="card_id" id="card_id" value="">
                            <input type="hidden" name="card_number_val" id="card_number_val" value="">
                            <input type="hidden" name="cust_id" id="cust_id" value="">
                        </div>
                        
                        <div class="ty-credit-card__control-group ty-control-group cc-validity-edit">
                            <label for="credit_card_month" {literal}data-ca-regexp="^\d{1,2}$"{/literal} data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date cm-cc-exp-month">{__("valid_thru")}</label>
                            <label for="credit_card_year" {literal}data-ca-regexp="^\d{2,4}$"{/literal} data-ca-message="" class="cm-regexp cm-cc-date cm-cc-exp-year cc-year hidden"></label>
                            <input type="number" id="credit_month" name="payment_infoe[expiry_month]" value="" class="ty-credit-card__input-short"/>
                            <input type="number" id="credit_year" name="payment_infoe[expiry_year]" value="" class="ty-credit-card__input-short"/>
                        </div>
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
                    <div class="span8 edit-billing-address">
                        <p class="ty-control-group__title credit_card_number">Billing Address</p>
                        <p>
                            <input type="text" id="billing_address_line_1" name="payment_infoe[b_address]" value="" placeholder="Billing Address Line 1"/>
                        </p>
                        <p>
                            <input type="text" id="billing_address_line_2" name="payment_infoe[b_address_2]" value="" placeholder="Billing Address Line 2"/>
                        </p>
                        <p>
                            <input type="text" id="billing_city" name="payment_infoe[b_city]" value="" placeholder="City"/>
                        </p>
                        <p>
                            <input type="text" id="billing_state" name="payment_infoe[b_state]" value="" placeholder="State"/>
                        </p>
                        <p>
                            <input type="text" id="billing_country" name="payment_infoe[b_country]" value="" placeholder="Country"/>
                        </p>
                        <p>
                            <input type="text" id="billing_zipcode" name="payment_infoe[b_zipcode]" value="" placeholder="Zipcode"/>
                        </p>
                    </div>
                </div>
                <!-- <div class="edit-card-cvv">
                    
                </div>  -->
                <div class="edit-card-action">
                    <div class="span8"><p><a class="cm-ajax cm-post cm-submit" style="color:#007aff;" href="{"card_management.setDefault?card_token={$item->data->id}"|fn_url}">Set as default</a> payment method.</p>
                    </div>
                    <div class="span8 align-end"><a class="button button_cart_818">Save</a> <a class="button cancel-btn">Cancel</a>
                    </div>
                </div>                     
            </div>
        </div>
        <br>
    </div>
    {/if}  
</div>
<div id="adding_card">
    <div class="accordion accordionnew">
        <div class="card_name"><b style="font-size: 20px;">+ </b><b> <a style="color:#007aff;">Add a credit card > </a> Wesave accepts all major credit cards.</b></div>
    </div>
    <div class="panel add_new_card" id="panel_add">
        <div class="litecheckout__item">
            <div class="clearfix">
                <div class="ty-credit-card cm-cc_form_{$id_suffix}">
                    <div class="ty-credit-card__control-group ty-control-group">
                        <label for="credit_card_number_add" class="ty-control-group__title cm-cc-add-number cc-number_{$id_suffix} ">{__("card_number")}</label>
                        <input size="35" type="text" id="credit_card_number_add" name="payment_info1[card_number]" value="{$pdata.card_number}" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" />
                        <ul class="ty-cc-icons cm-cc-icons cc-icons_{$id_suffix}">
                            <li class="ty-cc-icons__item cc-default cm-cc-add-default"><span class="ty-cc-icons__icon default">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-add-visa"><span class="ty-cc-icons__icon visa">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-add-visa_electron"><span class="ty-cc-icons__icon visa-electron">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-add-mastercard"><span class="ty-cc-icons__icon mastercard">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-add-maestro"><span class="ty-cc-icons__icon maestro">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-add-amex"><span class="ty-cc-icons__icon american-express">&nbsp;</span></li>
                            <li class="ty-cc-icons__item cm-cc-add-discover"><span class="ty-cc-icons__icon discover">&nbsp;</span></li>
                        </ul>
                        <input type="hidden" id="card_typea" name="payment_info1[card_type]" value=""/>
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
        <a class="button" id="button_cart_817">Add your Card</a>
    </div>  
</div>
{else}
<div class="new_card">
    <h4 class="default-payment-title">Payment methods</h4>
    <div class="accordion accordionnew">
        <div class="card_name"><b style="font-size: 20px;">+ </b><b><a style="color:#007aff;">Add a credit card > </a> Wesave accepts all major credit cards.</b></div>
    </div>
    <div class="panel show" id="panel_add">
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
                        <input type="hidden" id="card_type" name="payment_info[card_type]" value=""/>
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
                <input type="checkbox" name="payment_info[card_info_save]" id="card_info_save" value="true"> Save my Card 
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
    </div>
</div>
{/if}   

<script type="text/javascript">
    var acc = document.getElementsByClassName("accordion");
    var i;
    var flag = 0;
    if(localStorage.getItem('flag') == 1){
        $('.buttonChange').trigger('click'); 
        localStorage.removeItem('flag');
    }
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
                'number' : $('#edit_card1').find("input[name='payment_infoe[card_number]']").val(),
                'card_type' : $('#edit_card1').find("input[name='payment_infoe[card_type]']").val(), 
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
            callback: function callback(response) {
                flag = 1;
                localStorage.setItem('flag', flag);
                window.location.reload();
            }
        });  
     });
     $("#button_cart_817").click(function() {
            $.ceAjax('request', fn_url('card_management.add'), {
                method: 'POST',
                data: {  
                    'holder' : $('#panel_add').find("input[name='payment_info1[cardholder_name]']").val(),
                    'card_number' : $('#panel_add').find("input[name='payment_info1[card_number]']").val(),
                    'card_type' : $('#panel_add').find("input[name='payment_info1[card_type]']").val(), 
                    'month' : $('#panel_add').find("input[name='payment_info1[expiry_month]']").val(),
                    'year' : $('#panel_add').find("input[name='payment_info1[expiry_year]']").val(),
                    'cvv' : $('#panel_add').find("input[name='payment_info1[cvv2]']").val(),  
                },
                callback: function callback(response) {
                    flag = 1;
                    localStorage.setItem('flag', flag);
                    window.location.reload();
                }
            });
     });
     $('.edit-btn').click(function() {
            var id = $(this).attr('id');
            $('#card_name').text($("#"+id).text());
           // $("#credit_card_num").val($("#c_number"+id).val());
            $('#credit_card_name').val($("#card_name"+id).html());
            var exp_date = $("#expiry"+id).text().split(",");
            //alert(exp_date);
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
          
            $("#edit_card").insertAfter(".card-id-"+id);
            $("#edit_card").show();
            //$(".card-id"+id).after('#edit_card');
            // $("#card-id"+id+":after").
            
           // $("#adding_card").hide();
            //$("#cardDetails").hide();
        });
    function savedCards() {
        var x = document.getElementById("savedPreviousCards");
        var y = document.getElementById("defaultPayment");
        if (x.style.display === "none") {
            x.style.display = "block";
           // y.style.display = "none";
           // $('#adding_card').show();
        } else {
            x.style.display = "none";
            y.style.display = "block";
        }
    }
    $('.cancel-btn').click(function() {
        $('#edit_card').hide();
        $("#card_action").val("Cancel");
       // $("#adding_card").show();
        //$("#cardDetails").show();
   });
    // $('#save_my_card').click(function() {
    //     var saveStatus = 'true';
    //     $("#card_info_save").val(saveStatus);
    // });
    // $('#unsave_my_card').click(function() {
    //     var saveStatus = 'false';
    //     $("#card_info_save").val(saveStatus);
    // });
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

            var ccNumberEdit        = $("#credit_card_number_edit");
            var ccNumberInputEdit   = ccNumberEdit;
            
            var ccNumberAdd        = $(".cm-cc-add-number");
            var ccNumberInputAdd   = $("#" + ccNumberAdd.attr("for"));
    
            var ccCv2           = $(".cc-cvv2_" + ccFormId);
            var ccCv2Input      = $("#" + ccCv2.attr("for"));
    
            var ccMonth         = $(".cc-date_" + ccFormId);
            var ccMonthInput    = $("#" + ccMonth.attr("for"));
    
            var ccYear          = $(".cc-year_" + ccFormId);
            var ccYearInput     = $("#" + ccYear.attr("for"));
            ccNumberInput.inputmask("9999 9999 9999 9[9][9][9]", {
                placeholder: ''
            });

            ccNumberInputEdit.inputmask("9999 9999 9999 9[9][9][9]", {
                placeholder: ''
            });

            ccNumberInputAdd.inputmask("9999 9999 9999 9[9][9][9]", {
                placeholder: ''
            });
            
            if (ccNumber.length && ccNumberInput.length) {
                ccNumberInput.validateCreditCard(function (result) {
                    icons.removeClass('active');
                    if (result.card_type) {
                        icons.filter(' .cm-cc-' + result.card_type.name).addClass('active');
                        card_type = result.card_type.name;
                        if(card_type == "mastercard") {
                            card_type = "masterCard";
                        } else if(card_type == "amex") {
                            card_type = "americanExpress";
                        } else if(card_type == "diners_club_international") {
                            card_type = "dinersClub";
                        }
                        $("#card_type").val(card_type);
                     
                    }
                });
            }
            if (ccNumberEdit.length && ccNumberInputEdit.length) {
                ccNumberInputEdit.validateCreditCard(function (result) {
                icons.removeClass('active');
                if (result.card_type) {
                    icons.filter(' .cm-cc-edit' + result.card_type.name).addClass('active');
                    card_type = result.card_type.name;
                    if(card_type == "mastercard") {
                        card_type = "masterCard";
                    } else if(card_type == "amex") {
                        card_type = "americanExpress";
                    } else if(card_type == "diners_club_international") {
                        card_type = "dinersClub";
                    }
                    $("#card_typee").val(card_type); 
                   
                }
            });
            }

            if (ccNumberAdd.length && ccNumberInputAdd.length) {
                ccNumberInputAdd.validateCreditCard(function (result) {
                    icons.removeClass('active');
                    if (result.card_type) {
                        icons.filter(' .cm-cc-add-' + result.card_type.name).addClass('active');
                        card_type = result.card_type.name;
                        if(card_type == "mastercard") {
                            card_type = "masterCard";
                        } else if(card_type == "amex") {
                            card_type = "americanExpress";
                        } else if(card_type == "diners_club_international") {
                            card_type = "dinersClub";
                        }
                        $("#card_typea").val(card_type);
                    }
                });
            }
          
        });
    
    })(Tygh, Tygh.$);
    
        </script>

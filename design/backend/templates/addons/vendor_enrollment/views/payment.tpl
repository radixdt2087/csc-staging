{script src="js/lib/inputmask/jquery.inputmask.min.js"}
{script src="js/lib/creditcardvalidator/jquery.creditCardValidator.js"}
{assign var='card_pay' value='0'}
<div class="default-card">
    <h4 class="subheader">Payment Method</h4>
    {if $card_details}
        <div class="change-card default-card-details">
            <div class="row">
                <div class="span6 card">
                    <div class="row">
                        <div class="span1 card-img">
                            <div class="ty-cc-icons__item cm-cc-{$card_details[0].type} active"><span class="ty-cc-icons__icon {if $card_details[0].type == 'amex'} american-express {else} {$card_details[0].type} {/if}"> &nbsp; </span></div>
                        </div>
                        <div class="span5 card-name">
                            <b>{$card_details[0].type|@ucfirst}</b> ending in <b>{$card_details[0].card_number|substr:-4}</b></p>
                        </div>
                    </div>
                </div>
                <div class="span6 change">
                <span class="pull-right"><a class="change-card-btn">Change</a></span>
                </div>
            </div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="span6"> 
                <b>Billing Address:</b> {$card_details[0].holder_name}, {$card_details[0].address}, {$card_details[0].city}, {$card_details[0].state} {$card_details[0].country} {$card_details[0].zip}
                </div>
            </div>
        </div>
    {assign var='card_pay' value='1'}
    {/if}
</div>
<div class="list-card" style="display:none">
    <div class="row other-card-title">
        <div class="span6">
        <h4 class="subheader">Your credit cards</h4>
        </div>
        <!-- <div class="span5">
        <h4 class="subheader">Expires</h4>
        </div> -->
    </div>
    {foreach from=$card_details key=key item=card}
    <div class="other-added-card" id="other-added-card{$card.id}" card_id="{$card.id}">
        <label>
        <div class="add-card" id="{$card.id}">
            <div class="span7 other-card-details" id="payment_method{$card.id}">
                <div class="row">
                        <div class="span1">
                            <div class="ty-cc-icons__item cm-cc-{$card.type} active"><span class="ty-cc-icons__icon {if $card.type == 'amex'} american-express {else} {$card.type} {/if}"> &nbsp; </span></div>
                        </div>
                        <div class="span5">
                        <label class="radio inline">
                        <input type="radio" name="selcard" id="selcard{$card.id}" value='{$card.customer_id|cat:'|':$card.card_token}' {if $key == 0}checked{/if}>
                        {$card.type|@ucfirst} ending in {$card.card_number}
                        </label>
                        </div>
                </div>
            </div>
            <div class="span4 expirydate expiry{$card.id}">{$card.expiry|substr:0:2}/{$card.expiry|substr:2}</div>
            {* <input type="hidden" id="c_number{$card.id}" value="{$card.c_number}"/> *}
            <input type="hidden" id="customer_id{$card.id}" value="{$card.customer_id}"/>
            <input type="hidden" id="card_type{$card.id}" value="{$card.type}"/>
        </div>
        </label>
        <div class="panel{$card.id} card-details">
            <div class="row">
                <div class="span7 name-on-card">
                <strong>Name on card</strong>
                <p class="name{$card.id}">{$card.holder_name}</p>
                </div>
                <div class="span4">
                <b>Billing address</b><div class="address{$card.id}">{$card.address}</div> 
                <div> <span class="city{$card.id}">{$card.city}</span> <span class="state{$card.id}">{$card.state}</span> <span class="zip{$card.id}">{$card.zip}</span></div>
                <div class="country{$card.id}">{$card.country}</div> 
                </div>
            </div>
            <div class="row card-action">
                <div class="span6 set-payment-method">{if $card_details[0].id == $card.id }This card is your current default card{else}
                    <a class="cm-submit default_method" data-ca-dispatch="dispatch[companies.update]" id="{$card.id}" data-ca-target-form="company_update_form">Set as default </a>payment method{/if}
                </div>
                <div class="span6 right">
                    <a class="btn btn-primary cm-submit remove-btn" data-ca-dispatch="dispatch[companies.update]" data-ca-target-form="company_update_form" id="{$card.id}">Remove</a> &nbsp; <a class="btn btn-primary edit-btn" id="{$card.id}">Edit</a>
                </div>
            </div>
        </div>
    </div>
    {/foreach}
</div>
<div class="edit-card" style="display:none">
    <h4 class="subheader">Edit credit card</h4>
    <div class="clearfix">
        <div class="ty-credit-card">
            <div class="ty-credit-card__control-group ty-control-group">
                <label for="payment_method" class="ty-control-group__title cm-cc-number cc-number-edit">{__("card_number")} <font color="red">*</font></label>
                <input type="text" id="payment_method" name="edit_payment_data[card_number]" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" value=""/>
                 <ul class="ty-cc-icons cm-cc-icons cc-icons">
                    <li class="ty-cc-icons__item cc-default cm-cc-editdefault"><span class="ty-cc-icons__icon default">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-editvisa"><span class="ty-cc-icons__icon visa">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-editvisa_electron"><span class="ty-cc-icons__icon visa-electron">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-editmastercard"><span class="ty-cc-icons__icon mastercard">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-editmaestro"><span class="ty-cc-icons__icon maestro">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-editamex"><span class="ty-cc-icons__icon american-express">&nbsp;</span></li>
                    <li class="ty-cc-icons__item cm-cc-editdiscover"><span class="ty-cc-icons__icon discover">&nbsp;</span></li>
                </ul>
            </div>
            <div class="ty-credit-card__control-group ty-control-group">
                <label for="exp_month" data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date cm-cc-exp-month ">{__("valid_thru")} <font color="red">*</font></label>
                <label for="exp_year" data-ca-message="" class=" cm-regexp cm-cc-date cm-cc-exp-year cc-year hidden"></label>
                <input type="number" id="exp_month" min="0" name="edit_payment_data[expiry_month]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;&nbsp;/&nbsp;&nbsp;<input type="number" min="0" id="exp_year"  name="edit_payment_data[expiry_year]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;
            </div>
            <div class="ty-credit-card__control-group ty-control-group">
                <label for="card_name" class="ty-control-group__title ">{__("cardholder_name")} <font color="red">*</font></label>
                <input size="35" type="text" id="card_name" name="edit_payment_data[cardholder_name]" value="" class="cm-cc-name ty-credit-card__input ty-uppercase" />
            </div>
        </div>
        <div class="ty-credit-card__control-group ty-control-group cvv-no">{*ty-control-group ty-credit-card__cvv-field cvv-field*}
            <label for="e_cvv2" class="ty-control-group__title  cm-regexp cm-cc-cvv2  cc-cvv2 cm-autocomplete-off" >{__("cvv2")} <font color="red">*</font></label>
            <input type="text" id="e_cvv2" name="edit_payment_data[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
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

     <div class="ty-credit-card">
        <div class="ty-credit-card__control-group ty-control-group">
            <label for="e_address" class="ty-control-group__title">Billing Address <font color="red">*</font></label>
            <input type="text" id="e_address" name="edit_billing_data[billing_address]" value="" class="ty-credit-card__input cm-autocomplete-off" />     
        </div>
        <div class="ty-credit-card__control-group ty-control-group">
        <label for="e_city" class="ty-control-group__title">Billing City <font color="red">*</font></label>
        <input type="text" id="e_city" name="edit_billing_data[billing_city]" value=""  class="ty-credit-card__input cm-autocomplete-off" />
        </div>
        <div class="ty-credit-card__control-group ty-control-group">
        <label for="e_zip" class="ty-control-group__title">Billing Zipcode <font color="red">*</font></label>
        <input type="text" id="e_zip" name="edit_billing_data[billing_zipcode]" value=""  class="ty-credit-card__input cm-autocomplete-off" />         
        </div><br/>
    </div>
    <div class="ty-credit-card__control-group ty-control-group cvv-no">
        <label for="e_country" class="ty-control-group__title">Billing Country <font color="red">*</font></label>
        <select id="e_country" class="ty-credit-card__input cm-autocomplete-off" name="edit_billing_data[billing_country]" ><option value="">- Select country -</option>
        <option value="USA">United States</option><option value="CAN">Canada</option>
        </select>
    </div>
    <div class="ty-credit-card__control-group ty-control-group cvv-no">
        <label for="e_state" class="ty-control-group__title">Billing State <font color="red">*</font></label>
        <select id="e_state" class="cm-state" name="edit_billing_data[billing_state]">
                <option value="">-Select State-</option>
        </select>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row">
        <input type="hidden" name="card_id" id="card_id" value="">
        <input type="hidden" name="card_number_val" id="card_number_val" value="">
        <input type="hidden" name="cust_id" id="cust_id" value="">
        <input type="hidden" name="edit_payment_data[card_type]" id="c_type" value="">
        <input type="hidden" name="currency_code" id="currency_code" value="{$currencies.$primary_currency.currency_code}">
        <input type="hidden" name="curr_plan_id" id="curr_plan_id" value="{$company_data.plan_id}">
        <div class="span12">
            <div class="pull-right">
            <a class="btn btn-primary cancel-btn">Cancel</a> &nbsp; <a data-ca-dispatch="dispatch[companies.update]" data-ca-target-form="company_update_form" class="btn btn-primary cm-submit save-btn">Save</a>
            </div>
        </div>
    </div>
    <div class="row">&nbsp;</div>
</div>

<input type="hidden" name="card_action" id="card_action" value="">
<h4 class="add-card subheader" id="add_card" > <i class="icon-plus"></i> <a>Add a credit card > </a> Wesave accepts all major credit cards.</h4>
<div class="paneladd_card litecheckout__item" {if $card_details} style="display:none" {/if}>
    <div class="clearfix">
        <div class="ty-credit-card">
            <div class="ty-credit-card__control-group ty-control-group">
                <label for="card_number" class="ty-control-group__title cm-cc-number cc-number ">{__("card_number")} <font color="red">*</font></label>
                <input type="text" id="card_number" name="add_payment_data[card_number]" class="ty-credit-card__input cm-autocomplete-off ty-inputmask-bdi" value=""/>
                <ul class="ty-cc-icons cm-cc-icons cc-icons">
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
                <label for="expiry_month" data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date cm-cc-exp-month ">{__("valid_thru")} <font color="red">*</font></label>
                <label for="expiry_year" data-ca-message="" class=" cm-regexp cm-cc-date cm-cc-exp-year cc-year hidden"></label>
                <input type="number" id="expiry_month" min="0" name="add_payment_data[expiry_month]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;&nbsp;/&nbsp;&nbsp;<input type="number" min="0" id="expiry_year"  name="add_payment_data[expiry_year]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;
            </div>
            <div class="ty-credit-card__control-group ty-control-group">
                <label for="cardholder_name" class="ty-control-group__title ">{__("cardholder_name")} <font color="red">*</font></label>
                <input size="35" type="text" id="cardholder_name" name="add_payment_data[cardholder_name]" value="" class="cm-cc-name ty-credit-card__input ty-uppercase" />
            </div><br/>
            {if $card_pay == 0}
            <div class="ty-credit-card__control-group ty-control-group">
                <input type="checkbox" id="save_card" name="add_payment_data[save_card]" value="Y" /> &nbsp; Save my card
            </div>
            {/if}
            <input type="hidden" name="card_pay" value="{$card_pay}" id="card_pay" />
        </div>

        <div class="ty-credit-card__control-group ty-control-group ty-control-group cvv-no">
            <label for="cvv2" class="ty-control-group__title  cm-regexp cm-cc-cvv2  cc-cvv2 cm-autocomplete-off" >{__("cvv2")} <font color="red">*</font></label>
            <input type="text" id="cvv2" name="add_payment_data[cvv2]" value="" maxLength="4" size="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
 class="ty-credit-card__cvv-field-input ty-inputmask-bdi" />
            <input type="hidden" id="card_type" name="add_payment_data[card_type]" value=""/>
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

    <div class="ty-credit-card">
        <div class="ty-credit-card__control-group ty-control-group">
            <label for="address" class="ty-control-group__title">Billing Address <font color="red">*</font></label>
            <input type="text" id="billing_address" name="add_billing_data[billing_address]" value="" class="ty-credit-card__input cm-autocomplete-off" />     
        </div>
        <div class="ty-credit-card__control-group ty-control-group">
        <label for="city" class="ty-control-group__title">Billing City <font color="red">*</font></label>
        <input type="text" id="city" name="add_billing_data[billing_city]" value=""  class="ty-credit-card__input cm-autocomplete-off" />
        </div>
        <div class="ty-credit-card__control-group ty-control-group">
        <label for="zipcode" class="ty-control-group__title">Billing Zipcode <font color="red">*</font></label>
        <input type="text" id="zipcode" name="add_billing_data[billing_zipcode]" value=""  class="ty-credit-card__input cm-autocomplete-off" />         
        </div><br/>
        {if $card_details}
        <div class="ty-credit-card__control-group ty-control-group">
        <a class="btn btn-primary btn-primary cm-submit add-card-btn" data-ca-dispatch="dispatch[companies.update]" data-ca-target-form="company_update_form"> Add Card</a>
        </div>
        {/if}
    </div>

    <div class="ty-credit-card__control-group ty-control-group cvv-no">
        <label for="country" class="ty-control-group__title">Billing Country <font color="red">*</font></label>
            <select id="country" class="ty-credit-card__input cm-autocomplete-off" name="add_billing_data[billing_country]" ><option value="">- Select country -</option>
            <option value="USA">United States</option><option value="CAN">Canada</option>
            </select>
    </div>
    <div class="ty-credit-card__control-group ty-control-group cvv-no">
        <label for="state" class="ty-control-group__title">Billing State <font color="red">*</font></label>
        <input type="hidden" id="billingState" name="billingState" value=""/>
        <select id="state" class="cm-state" name="add_billing_data[billing_state]">
                <option value="">-Select State-</option>
        </select>
    </div>
     <input type="hidden" name="carttable" id="carttable" value="" />
     <input type="hidden" name="purchase_plan" id="purchase_plan" value="0"/>
     <input type="hidden" name="amount" id="amount" value=""/>
     <input type="hidden" name="aamount" id="aamount" value=""/>
     <input type="hidden" name="total" id="total" value=""/>
</div>
<br/>
<div class="row">
    <div class="span12">
    <a data-ca-dispatch="dispatch[companies.update]" data-ca-target-form="company_update_form" class="btn btn-primary purchase_plan cm-submit btn-primary"> Purchase Plan</a>
    </div>
</div>
<script type="text/javascript">

(function(_, $) {

    
    $.ceEvent('on', 'ce.commoninit', function() {

        var icons           = $('.cc-icons'+ ' li');

        var ccNumber        = $(".cc-number");
        var ccNumberInput   = $("#" + ccNumber.attr("for"));

        var ccNumberEdit        = $(".cc-number-edit");
        var ccNumberInputEdit   = $("#" + ccNumberEdit.attr("for"));

        var ccCv2           = $(".cc-cvv2");
        var ccCv2Input      = $("#" + ccCv2.attr("for"));

        var ccMonth         = $(".cc-date");
        var ccMonthInput    = $("#" + ccMonth.attr("for"));

        var ccYear          = $(".cc-year");
        var ccYearInput     = $("#" + ccYear.attr("for"));

        ccNumberInput.inputmask("9999 9999 9999 9[9][9][9]", {
            placeholder: ''
        });

        ccNumberInputEdit.inputmask("9999 9999 9999 9[9][9][9]", {
            placeholder: ''
        });
      /*  if($("#card_number").val()!= '') {
            ccNumberInput.inputmask("XXXX XXXX XXXX 9[9][9][9]", {
                placeholder: ''
            });
        } else {
            ccNumberInput.inputmask("9999 9999 9999 9[9][9][9]", {
                placeholder: ''
            });
        }*/
      /* $.ceFormValidator('registerValidator', {
            class_name: 'cc-number',
            message: '',
            func: function(id) {
                return ccNumberInput.inputmask("isComplete");
            }
        });*/
/*
        $.ceFormValidator('registerValidator', {
            class_name: 'cc-cvv2',
            message: '{__("error_validator_ccv_long")|escape:javascript}',
            func: function(id) {
                return $.is.blank(ccCv2Input.val()) || ccCv2Input.val().length <= 4;
            }
        });       
*/
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
                  /*  if (['visa_electron', 'maestro', 'laser'].indexOf(result.card_type.name) != -1) {
                       // ccCv2.removeClass("cm-required");
                    } else {
                       // ccCv2.addClass("cm-required");
                    }*/
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
                    $("#c_type").val(card_type);
                  /*  if (['visa_electron', 'maestro', 'laser'].indexOf(result.card_type.name) != -1) {
                       // ccCv2.removeClass("cm-required");
                    } else {
                       // ccCv2.addClass("cm-required");
                    }*/
                }
            });
        }
        $("#card_number").keyup(function(e){
             var keyCode = e.keyCode;
             if (keyCode == 8) {
                $("#card_number").inputmask("9999 9999 9999 9[9][9][9]", {
                    placeholder: ''
                });
                 $("#card_number").val('');
             }
            });
        });

})(Tygh, Tygh.$);
</script>

<script>
{literal}
var statesList={country:[{name:"United States",id:"USA",states:[{name:"Alaska",id:"AK"},{name:"Alabama",id:"AL"},{name:"Arkansas",id:"AR"},{name:"Arizona",id:"AZ"},{name:"California",id:"CA"},{name:"Colorado",id:"CO"},{name:"Connecticut",id:"CT"},{name:"District of Columbia",id:"DC"},{name:"Delaware",id:"DE"},{name:"Florida",id:"FL"},{name:"Georgia",id:"GA"},{name:"Guam",id:"GU"},{name:"Hawaii",id:"HI"},{name:"Iowa",id:"IA"},{name:"Idaho",id:"ID"},{name:"Illinois",id:"IL"},{name:"Indiana",id:"IN"},{name:"Kansas",id:"KS"},{name:"Kentucky",id:"KY"},{name:"Louisiana",id:"LA"},{name:"Massachusetts",id:"MA"},{name:"Maryland",id:"MD"},{name:"Maine",id:"ME"},{name:"Michigan",id:"MI"},{name:"Minnesota",id:"MN"},{name:"Missouri",id:"MO"},{name:"Northern Mariana Islands",id:"MP"},{name:"Mississippi",id:"MS"},{name:"Montana",id:"MT"},{name:"North Carolina",id:"NC"},{name:"North Dakota",id:"ND"},{name:"Nebraska",id:"NE"},{name:"New Hampshire",id:"NH"},{name:"New Jersey",id:"NJ"},{name:"New Mexico",id:"NM"},{name:"Nevada",id:"NV"},{name:"New York",id:"NY"},{name:"Ohio",id:"OH"},{name:"Oklahoma",id:"OK"},{name:"Oregon",id:"OR"},{name:"Pennsylvania",id:"PA"},{name:"Puerto Rico",id:"PR"},{name:"Rhode Island",id:"RI"},{name:"South Carolina",id:"SC"},{name:"South Dakota",id:"SD"},{name:"Tennessee",id:"TN"},{name:"Texas",id:"TX"},{name:"Utah",id:"UT"},{name:"Virginia",id:"VA"},{name:"Virgin Islands",id:"VI"},{name:"Vermont",id:"VT"},{name:"Washington",id:"WA"},{name:"Wisconsin",id:"WI"},{name:"West Virginia",id:"WV"},{name:"Wyoming",id:"WY"}]},{name:"Canada",id:"CAN",states:[{name:"Alberta",id:"AB"},{name:"British Columbia",id:"BC"},{name:"Manitoba",id:"MB"},{name:"New Brunswick",id:"NB"},{name:"Newfoundland and Labrador",id:"NL"},{name:"Nova Scotia",id:"NS"},{name:"Northwest Territories",id:"NT"},{name:"Nunavut",id:"NU"},{name:"Ontario",id:"ON"},{name:"Prince Edward Island",id:"PE"},{name:"Quebec",id:"QC"},{name:"Saskatchewan",id:"SK"},{name:"Yukon",id:"YT"}]}]};
$('#country').on('change', function() {
    for(var i = 0; i < statesList.country.length; i++) {
      if(statesList.country[i].id == $(this).val()) {
         $('#state').html('<option value="">-Select State-</option>');
         $.each(statesList.country[i].states, function (index, value) {
            $("#state").append('<option value="'+value.id+'">'+value.name+'</option>');
        });
      }
    }
});

$('#e_country').on('change', function() {
    for(var i = 0; i < statesList.country.length; i++) {
      if(statesList.country[i].id == $(this).val()) {
         $('#e_state').html('<option value="">-Select State-</option>');
         $.each(statesList.country[i].states, function (index, value) {
            $("#e_state").append('<option value="'+value.id+'">'+value.name+'</option>');
        });
      }
    }
});

$('#country').find('option:selected').change();
$('#e_country').find('option:selected').change();
var billingState = $("#billingState").val();
if(billingState!='') {
    for(var i = 0; i < statesList.country.length; i++) {
        if(statesList.country[i].id == $("#country").val()) {
            $('#state').html('<option value="">-Select State-</option>');
            $.each(statesList.country[i].states, function (index, value) {
                var sel = '';
                if (billingState == value.id) { sel=' selected '; }
                var html = '<option value="'+value.id+'" "'+sel+'">'+value.name+'</option>'  ;
                $("#state").append(html);
            });
        }
    }
}
{/literal}
</script>

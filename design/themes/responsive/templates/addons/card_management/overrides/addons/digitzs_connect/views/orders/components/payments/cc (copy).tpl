
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
    div.accordion.active, div.accordion:hover {
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
</style>
{if $card_details}
{foreach from=$card_details key=key item=item}
    <div class="accordion">
        {$item->card->type} card ending with {$item->card->number}
    </div>
    <div class="panel">
        <table width="100%">
            <tr>
                <td><input id="pay_with_token" type="checkbox" value="0" name="pay_with_token" class="checkbox cm-switch-availability cm-switch-visibility"></td>
                <td width="50%" style="text-align: initial;"><b>Name on Card</b></td>
                <td  width="50%" style="text-align: end;"><b >Billing Address</b></td>
            </tr>
            <tr>
                <td><input type="hidden" value="{$item->card->type}"></td>
                <td width="50%" style="vertical-align: baseline; text-align: initial;">{ucfirst($item->card->holder)}</td>
                <td width="50%" style="text-align: end;">{$item->billingAddress->line1}<br>
                    {$item->billingAddress->line2}<br>
                    {$item->billingAddress->city}<br>
                    {$item->billingAddress->state}<br>
                    {$item->billingAddress->country}, {$item->billingAddress->zip}
                </td>
            </tr>     
        </table>
    </div>
    <div class="litecheckout__item">
    
        <div class="clearfix">
            <div class="ty-credit-card cm-cc_form_{$id_suffix}">
                <div class="ty-credit-card__control-group ty-control-group">
                    <label for="credit_card_number_{$id_suffix}" class="ty-control-group__title cm-cc-number cc-number_{$id_suffix} cm-required">{__("card_number")}</label>
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
                    <label for="credit_card_month_{$id_suffix}" {literal}data-ca-regexp="^\d{1,2}$"{/literal} data-ca-message="" class="ty-control-group__title cm-regexp cm-cc-date cc-date_{$id_suffix} cm-cc-exp-month cm-required">{__("valid_thru")}</label>
                    <label for="credit_card_year_{$id_suffix}" {literal}data-ca-regexp="^\d{2,4}$"{/literal} data-ca-message="" class="cm-required cm-regexp cm-cc-date cm-cc-exp-year cc-year_{$id_suffix} hidden"></label>
                    <input type="number" id="credit_card_month_{$id_suffix}" min="0" name="payment_info[expiry_month]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;&nbsp;/&nbsp;&nbsp;<input type="number" min="0" id="credit_card_year_{$id_suffix}"  name="payment_info[expiry_year]" value="" size="2" maxlength="2" class="ty-credit-card__input-short ty-inputmask-bdi" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>&nbsp;
                </div>
    
                <div class="ty-credit-card__control-group ty-control-group">
                    <label for="credit_card_name_{$id_suffix}" class="ty-control-group__title cm-required">{__("cardholder_name")}</label>
                    <input size="35" type="text" id="credit_card_name_{$id_suffix}" name="payment_info[cardholder_name]" value="{$pdata.card_holder_name}" class="cm-cc-name ty-credit-card__input ty-uppercase" />
                </div>
            </div>
    
      <div class="ty-control-group ty-credit-card__cvv-field cvv-field">
                <label for="credit_card_cvv2_{$id_suffix}" class="ty-control-group__title cm-required cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
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
{/foreach} 
{/if}
{if $defaultCard}
<div class="accordion">
        {ucfirst($defaultCard->data->attributes->card->type)} card ending with {$defaultCard->data->attributes->card->number}
</div>
<div class="panel">
    <table width="100%">
        <tr>
            <td width="50%" style="text-align: initial;"><b>Name on Card</b></td>
            <td  width="50%" style="text-align: end;"><b >Billing Address</b></td>
        </tr>
        <tr>
            <td width="50%" style="vertical-align: baseline; text-align: initial;">{ucfirst($defaultCard->data->attributes->card->holder)}</td>
            <td width="50%" style="text-align: end;">{$defaultCard->data->attributes->billingAddress->line1}<br>
                {$defaultCard->data->attributes->billingAddress->line2}<br>
                {$defaultCard->data->attributes->billingAddress->city}<br>
                {$defaultCard->data->attributes->billingAddress->state}<br>
                {$defaultCard->data->attributes->billingAddress->country}, {$item->billingAddress->zip}
            </td>
        </tr> 
        <tr>
            <td><div class="ty-control-group ty-credit-card__cvv-field cvv-field">
                <label for="credit_card_cvv2_{$id_suffix}" class="ty-control-group__title cm-required cm-regexp cm-cc-cvv2  cc-cvv2_{$id_suffix} cm-autocomplete-off" data-ca-regexp-allow-empty="true" {literal}data-ca-regexp="^\d{3,4}$"{/literal} data-ca-message="{__("error_validator_ccv")|escape:javascript}">{__("cvv2")}</label>
                <input type="hidden" name="payment_info[card_token]" id="credit_card_tokens_{$id_suffix}" value="{$defaultCard->data->id}" />
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
          
            </td>
        </tr> 
        <!-- <tr>
            <td width="50%" style="vertical-align: baseline; text-align: initial;">
                Set this as default
            </td>
            <td width="50%" style="text-align: end;">
                <button >Remove</button> <button>Edit</button>
            </td>
        </tr>       -->
    </table>
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
        if(_.isTouch === false && jQuery.isEmptyObject(ccNumberInput.data("_inputmask")) == true) {

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

            $.ceFormValidator('registerValidator', {
                class_name: 'cc-number_' + ccFormId,
                message: '',
                func: function(id) {
                    return isChromeOnOldAndroid() || ccNumberInput.inputmask("isComplete");
                }
            });

            $.ceFormValidator('registerValidator', {
                class_name: 'cc-cvv2_' + ccFormId,
                message: '{__("error_validator_ccv_long")|escape:javascript}',
                func: function(id) {
                    return isChromeOnOldAndroid() || $.is.blank(ccCv2Input.val()) || ccCv2Input.val().length <= 4;
                }
            });
        }
 if (ccNumber.length && ccNumberInput.length) {
            ccNumberInput.validateCreditCard(function (result) {
                icons.removeClass('active');
                if (result.card_type) {
                    icons.filter(' .cm-cc-' + result.card_type.name).addClass('active');
                    if (['visa_electron', 'maestro', 'laser'].indexOf(result.card_type.name) != -1) {
                        ccCv2.removeClass("cm-required");
                    } else {
                        ccCv2.addClass("cm-required");
                    }
                }
            });
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
{if $permission == 'admin'}
<div id="content_digitzs_connect" class="hidden">
{include file="common/subheader.tpl" title="Digitzs Vendor Details"}
    {if isset($cdata.digitzs_connect_account_id) && $cdata.digitzs_connect_account_id!=''}
    <div class="control-group">
        <label class="control-label" for="merchant_id">Merchant Id</label>
        <div class="controls">
            <input type="text" disabled id="merchant_id"  name="company_data[connect]" size="32" value="{$cdata.digitzs_connect_account_id}" >
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            For more detailed processing and card fees breakdowns on your customer transactions, visit your processing dashboard at <a href="https://myiq.digitzs.com/login" target="_blank">Digitzs.com</a>
        </div>
    </div>
    {else}
    <div class="control-group">
        <label class="control-label" for="merchant_id">Digitzs Account</label>
        <div class="controls">
            <input type="text" disabled id="not_connect"  name="company_data[not_connect]" size="32" value="Not Connected" >
        </div>
    </div>
    {/if}
</div>
{/if}
{if $permission == 'access'}
<div id="content_digitzs_connect" class="hidden">
{include file="common/subheader.tpl" title="Digitzs Vendor Details"}
{if isset($cdata.digitzs_connect_account_id) && $cdata.digitzs_connect_account_id!=''}
<div class="control-group">
    <label class="control-label" for="merchant_id">Merchant Id</label>
    <div class="controls">
        <input type="text" disabled id="merchant_id"  name="company_data[connect]" size="32" value="{$cdata.digitzs_connect_account_id}" >
    </div>
</div>
<p>Credit card processing rates are approximately: Monthly Limit: $25,000*; Single Trans Limit: $2,500*; Processing fee: 2.9% + $0.30 across all card types**; Refunds: $0.30**; Chargebacks: $29**.</p>
<p>*Accounts are auto-enabled with Soft Limits. If you process a transaction above the single ticket limit or over the monthly limit, it does not stop the transaction; however, your account will be flagged for secondary verification.</p>
<p>**Actual rates vary and may depend on card type/usage. A detailed rate breakdown will be displayed in your Digitzs dashboard.</p>
<p>For more detailed processing and card fees breakdowns on your customer transactions, visit your processing dashboard at <a href="https://myiq.digitzs.com/login" target="_blank">Digitzs.com</a></p>
{else}

{if $vendor_details.verify == 0}
<div>
    <!-- <p>{strip_tags($vendor_details.content)}</p> -->
    <div class="control-group">
    <h5>Apply for your Digitzs Merchant Account</h5>
    <br/>
    <p>To begin selling, please complete the following Merchant Account application. Applications are typically processed within hours. Credit card processing rates are as follows: Monthly Limit: $25,000*; Single Trans Limit: $2,500*; Processing fee: 2.9% + $0.30 across all card types**; Refunds: $0.30**; Chargebacks: $29**.</p>
    <p>*Accounts are auto-enabled with Soft Limits. If you process a transaction above the single ticket limit or over the monthly limit, it does not stop the transaction; however, your account will be flagged for secondary verification. </p>
    <p>**Actual rates vary and may depend on card type/usage. A detailed rate breakdown will be displayed in your Digitzs dashboard. </p>
    <p><b>In order to proceed with your application we must first verify your email.  Please verify your email by selecting “Send Email” below and following the instructions indicated in the email you receive. </b></p>
</div>
<div class="control-group">
    <label class="control-label cm-required" for="email_verify">Email</label>
    <div class="controls">
    <input type="text" id="email_verify" name="company_data[email_verify]" size="32" value="{$vendor_details.user_email}" readonly>
    </div>
</div>
{if $vendor_details.email_code!=''}
<div class="control-group">
    <label class="control-label" for="mail_code">Verification Code</label>
    <div class="controls">
    <input type="text" id="mail_code" name="company_data[mail_code]" size="32" maxlength="6" >
    <input type="hidden" id="email_code" name="company_data[email_code]" size="32" value="{$vendor_details.email_code}">
    </div>
</div>
{/if}
<div class="control-group">
    <div class="controls">
    <input type="hidden" id="submitEmail" name="company_data[submitEmail]" size="32" value="0"/>
    <input type="hidden" id="resendEmail" name="company_data[resendEmail]" size="32" value="1"/>
    <a data-ca-dispatch="dispatch[companies.update]" data-ca-target-form="company_update_form" id="sendEmail" class="btn btn-primary cm-submit btn-primary">{if $vendor_details.email_code==''} Send Email {else} Verify Code {/if}</a>
        {if $vendor_details.email_code!=''}<a id="resendCode" class="btn btn-primary" >Resend Code</a>{/if}
    </div>
</div>
</div>
{else}
<div>
<p><b>Note:</b> Please add your personal and business details to create Digitzs merchant account.</p>
<div class="control-group">
        <label class="control-label cm-required" for="first_name">First Name</label>
        <div class="controls">
            <input type="text" id="first_name" name="company_data[first_name]" size="32"  {if ($vendor_details.first_name) != ""} value="{$vendor_details['first_name']}" {else} value="{$user_data.firstname}" {/if} class="input-large" >
        </div>
</div>
<div class="control-group">
        <label class="control-label cm-required" for="last_name">Last Name</label>
        <div class="controls">
            <input type="text" id="last_name" name="company_data[last_name]" size="32" {if ($vendor_details.last_name) != ""} value="{$vendor_details['last_name']}" {else} value="{$user_data.lastname}" {/if} class="input-large ">
        </div>
</div>
<div class="control-group">
    <label class="control-label cm-email cm-required" for="personal_email">Personal Email</label>
    <div class="controls">
        <input type="text" id="personal_email" name="company_data[personal_email]" size="32" {if ($vendor_details.personal_email) != ""} value="{$vendor_details['personal_email']}" {else} value="{$vendor_details.user_email}" {/if} class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="day_phone">Day Phone <br/>(xxx) xxx-xxxx </label>
    <div class="controls">
        <input type="text" id="day_phone" name="company_data[day_phone]" size="32" {if ($vendor_details.day_phone) != ""} value="{$vendor_details['day_phone']}" {else} value="{substr($company_data.phone,1)}" {/if}
        class="input-large">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="evening_phone">Evening Phone <br/> (xxx) xxx-xxxx </label>
    <div class="controls">
        <input type="text" id="evening_phone" name="company_data[evening_phone]" size="32" value="{$vendor_details['evening_phone']}" class="input-large">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="birth_date">Birth Date (MM-DD-YYYY)(18+)</label>
    <div class="controls">
        <input type="text" id="birth_date" name="company_data[birth_date]" size="32" value="{$vendor_details['birth_date']}" class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="social_security">Social Security</label>
    <div class="controls">
        <input type="password" id="social_security" name="company_data[social_security]" maxlength="9" size="32" value="{$vendor_details['social_security']}" class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="personal_address_line1">Personal Address </label>
    <div class="controls">
        <input type="text" id="personal_address_line1" name="company_data[personal_address_line1]" size="32" value="{$vendor_details['personal_address_line1']}" class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="personal_city">Personal City </label>
    <div class="controls">
        <input type="text" id="personal_city" name="company_data[personal_city]" size="32" value="{$vendor_details['personal_city']}" class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="personal_country">Personal Country </label>
    <div class="controls">
        <select id="personal_country" class="cm-country" name="company_data[personal_country]" >
        <option value="">- Select country -</option><option value="USA" {if $vendor_details['personal_country']=='USA'}selected{/if}>United States</option><option value="CAN" {if $vendor_details['personal_country']=='CAN' }selected{/if}>Canada</option>

        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="personal_state">Personal State </label>
    <div class="controls">
    <input type="hidden" id="db_personal_state" name="company_data[personal_state]" size="32" maxlength="2" value="{$vendor_details['personal_state']}" class="input-large">
    <select id="personal_state" class="cm-state" name="company_data[personal_state]">
        <option value="">- Select state -</option>
    </select>
        </div>
</div>


<div class="control-group">
        <label class="control-label cm-required" for="personal_zip">Personal Zipcode </label>
        <div class="controls">
            <input type="text" id="personal_zip" name="company_data[personal_zip]" size="32" maxlength="5" value="{$vendor_details['personal_zip']}" class="input-large ">
        </div>
</div>

<div class="control-group">
        <label class="control-label" for="same_as_above">Same as Personal Address </label>
        <div class="controls">
            <input type="checkbox" name="company_data[homepostalcheck]" id="homepostalcheck" value="yes" {if $vendor_details['homepostalcheck']=='yes'}checked{/if}/>
        </div>
</div>

<div class="control-group">
        <label class="control-label cm-required" for="business_name">Business Name</label>
        <div class="controls">
            <input type="text" id="business_name" name="company_data[business_name]" size="32" {if ($vendor_details.business_name) != ""} value="{$vendor_details['business_name']}" {else} value="{$company_data.company}" {/if} class="input-large ">
        </div>
        
</div>
<div class="control-group">
    <label class="control-label cm-required" for="url">Website URL</label>
    <div class="controls">
        <input type="text" id="url" name="company_data[url]" size="32" {if ($vendor_details.url) != ""} value="{$vendor_details['url']}" {else} value="{$company_data.url}" {/if}  class="input-large ">
    </div>
</div>
<div class="control-group">
    <label class="control-label cm-required" for="ein">EIN Number </label>
    <div class="controls">
        <input type="text" id="ein" name="company_data[ein]" size="32" value="{$vendor_details['ein']}" class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="business_address_line1">Business Address </label>
    <div class="controls">
        <input type="text" id="business_address_line1" name="company_data[business_address_line1]" size="32" value="{$vendor_details['business_address_line1']}" class="input-large ">
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="business_city">Business City </label>
    <div class="controls">
        <input type="text" id="business_city" name="company_data[business_city]" size="32" {if ($vendor_details.business_city) != ""} value="{$vendor_details['business_city']}" {else} value="{$company_data.city}" {/if} class="input-large ">
    </div>
</div>
<div class="control-group">
    <label class="control-label cm-required" for="business_country">Business Country </label>
    <div class="controls">
        <select id="business_country" name="company_data[business_country]" ><option value="">- Select country -</option>
        <option value="USA" {if $vendor_details['business_country']=='USA' || $company_data.country == 'US'}selected{/if}>United States</option><option value="CAN" {if $vendor_details['business_country']=='CAN' || $company_data.country == 'CA'}selected{/if}>Canada</option>
        </select>
    </div>
</div>
<div class="control-group">
    <label class="control-label cm-required" for="business_state">Business State </label>
    <div class="controls">
        <input type="hidden" id="db_business_state" name="company_data[business_state]" size="32" maxlength="2" value="{$vendor_details['business_state']}" class="input-large">
        <select id="business_state" class="cm-state" name="company_data[business_state]">
                <option value="">-Select State-</option>
        </select>
    </div>
</div>
<div class="control-group">
    <label class="control-label cm-required" for="business_zipcode">Business Zipcode  </label>
    <div class="controls">
        <input type="text" id="business_zipcode" name="company_data[business_zipcode]" size="32" maxlength="5"  {if ($vendor_details.business_zipcode) != ""} value="{$vendor_details['business_zipcode']}" {else} value="{$company_data.zipcode}" {/if} class="input-large ">
    </div>
</div>
<div class="control-group">
    <label class="control-label cm-required" for="bank_name">Bank name </label>
    <div class="controls">
        <input type="text" id="bank_name" name="company_data[bank_name]" size="32" value="{$vendor_details['bank_name']}" class="input-large ">
    </div>
</div>
<div class="control-group">
<label class="control-label cm-required" for="account_ownership">Account ownership </label>
<div class="controls">
<select id="account_ownership"  name="company_data[account_ownership]" ><option value="business" {if $vendor_details['account_ownership']=='business'}{/if}>Business</option><option value="personal" {if $vendor_details['account_ownership']=='personal'}{/if}>Personal</option></select>
</div>
</div>
<div class="control-group">
<label class="control-label cm-required" for="account_type">Bank Account type </label>
<div class="controls">
<select id="account_type" name="company_data[account_type]"><option value="checking" {if $vendor_details['account_type']=='checking'}{/if}>Checking</option><option value="savings" {if $vendor_details['account_type']=='savings'}{/if}>Savings</option></select>
</div>
</div>
<div class="control-group">
<label class="control-label cm-required" for="account_name">Account Name </label>
<div class="controls">
<input type="text" id="account_name" name="company_data[account_name]" size="32" value="{$vendor_details['account_name']}" class="input-large ">
</div>
</div>
<div class="control-group">
<label class="control-label cm-required cm-value-integer" for="account_number">Account No. </label>
<div class="controls">
<input type="text" id="account_number" name="company_data[account_number]" size="32" value="{$vendor_details['account_number']}" class="input-large ">
</div>
</div>
<div class="control-group">
<label class="control-label cm-required cm-value-integer" for="routing_number">Routing No. </label>
<div class="controls">
<input type="text" id="routing_number" name="company_data[routing_number]" size="32" value="{$vendor_details['routing_number']}" class="input-large ">
</div>
</div>

<div class="control-group">
<label class="control-label cm-required" for="merchant_agreement">Merchant Agreement </label>
<div class="controls">
<input type="checkbox" id="merchant_agreement" name="company_data[merchant_agreement]" value="1" {if $vendor_details['merchant_agreement']==1}checked{/if} >
</div>
</div>
<div class="control-group">
<div class="controls">
<iframe src="https://ma.digitzs.com" width="600px"></iframe>
</div>
</div>
<input type="hidden" id="digitz_data" name="company_data[digitz_data]" size="32" value="0" class="input-large ">
<input type="hidden" id="verify" name="company_data[verify]" size="32" value="{$vendor_details['verify']}" class="input-large ">
<input type="hidden" id="timestamp" name="company_data[timestamp]" size="32" value="{$vendor_details['timestamp']}" class="input-large ">
<input type="hidden" id="ip_address" name="company_data[ip_address]" size="32" value="{$vendor_details['ip_address']}" class="input-large ">
</div>
{/if}
{/if}
<script>
var myJson = {
"country": [
    {
        "name": "United States",
        "id": "USA",
        "states": [
{
    "name": "Alaska",
    "id": "AK"
},
{
    "name": "Alabama",
    "id": "AL"
},
{
    "name": "Arkansas",
    "id": "AR"
},
{
    "name": "Arizona",
    "id": "AZ"
},
{
    "name": "California",
    "id": "CA"
},
{
    "name": "Colorado",
    "id": "CO"
},
{
    "name": "Connecticut",
    "id": "CT"
},
{
    "name": "District of Columbia",
    "id": "DC"
},
{
        "name": "Delaware",
        "id": "DE"
    },
    {
        "name": "Florida",
        "id": "FL"
    },
    {
        "name": "Georgia",
        "id": "GA"
    },
    {
        "name": "Guam",
        "id": "GU"
    },
    {
        "name": "Hawaii",
        "id": "HI"
    },
    {
        "name": "Iowa",
        "id": "IA"
    },
    {
        "name": "Idaho",
        "id": "ID"
    },
    {
        "name": "Illinois",
        "id": "IL"
    },
    {
        "name": "Indiana",
        "id": "IN"
    },
    {
        "name": "Kansas",
        "id": "KS"
    },
    {
        "name": "Kentucky",
        "id": "KY"
    },
    {
        "name": "Louisiana",
        "id": "LA"
    },
    {
        "name": "Massachusetts",
        "id": "MA"
    },
    {
        "name": "Maryland",
        "id": "MD"
    },
    {
        "name": "Maine",
        "id": "ME"
    },
    {
        "name": "Michigan",
        "id": "MI"
    },
    {
        "name": "Minnesota",
        "id": "MN"
    },
    {
        "name": "Missouri",
        "id": "MO"
    },
    {
        "name": "Northern Mariana Islands",
        "id": "MP"
    },
    {
        "name": "Mississippi",
        "id": "MS"
    },
    {
        "name": "Montana",
        "id": "MT"
    },
    {
        "name": "North Carolina",
        "id": "NC"
    },
    {
        "name": "North Dakota",
        "id": "ND"
    },
    {
        "name": "Nebraska",
        "id": "NE"
    },
    {
        "name": "New Hampshire",
        "id": "NH"
    },
    {
        "name": "New Jersey",
        "id": "NJ"
    },
    {
        "name": "New Mexico",
        "id": "NM"
    },
    {
        "name": "Nevada",
        "id": "NV"
    },
    {
        "name": "New York",
        "id": "NY"
    },
    {
        "name": "Ohio",
        "id": "OH"
    },
    {
        "name": "Oklahoma",
        "id": "OK"
    },
    {
        "name": "Oregon",
        "id": "OR"
    },
    {
        "name": "Pennsylvania",
        "id": "PA"
    },
    {
        "name": "Puerto Rico",
        "id": "PR"
    },
    {
        "name": "Rhode Island",
        "id": "RI"
    },
    {
        "name": "South Carolina",
        "id": "SC"
    },
    {
        "name": "South Dakota",
        "id": "SD"
    },
    {
        "name": "Tennessee",
        "id": "TN"
    },
    {
        "name": "Texas",
        "id": "TX"
    },
    {
        "name": "Utah",
        "id": "UT"
    },
    {
        "name": "Virginia",
        "id": "VA"
    },
    {
        "name": "Virgin Islands",
        "id": "VI"
    },
    {
        "name": "Vermont",
        "id": "VT"
    },
    {
        "name": "Washington",
        "id": "WA"
    },
    {
        "name": "Wisconsin",
        "id": "WI"
    },
    {
        "name": "West Virginia",
        "id": "WV"
    },
    {
        "name": "Wyoming",
        "id": "WY"
    }
]
},
        {
            "name": "Canada",
            "id": "CAN",
            "states": [
    {
        "name": "Alberta",
        "id": "AB"
    },
    {
        "name": "British Columbia",
        "id": "BC"
    },
    {
        "name": "Manitoba",
        "id": "MB"
    },
    {
        "name": "New Brunswick",
        "id": "NB"
    },
    {
        "name": "Newfoundland and Labrador",
        "id": "NL"
    },
    {
        "name": "Nova Scotia",
        "id": "NS"
    },
    {
        "name": "Northwest Territories",
        "id": "NT"
    },
    {
        "name": "Nunavut",
        "id": "NU"
    },
    {
        "name": "Ontario",
        "id": "ON"
    },
    {
        "name": "Prince Edward Island",
        "id": "PE"
    },
    {
        "name": "Quebec",
        "id": "QC"
    },
    {
        "name": "Saskatchewan",
        "id": "SK"
    },
    {
        "name": "Yukon",
        "id": "YT"
    }
]
        }
    ]
}

var personalState = $("#db_personal_state").val() ? $("#db_personal_state").val() : "";
$('#personal_country').find('option:selected').change();
$('#business_country').find('option:selected').change();
$('#personal_country').on('change', function(){
    let selpersonalState = '';
    for(var i = 0; i < myJson.country.length; i++)
    {
      if(myJson.country[i].id == $(this).val())
      {
         $('#personal_state').html('<option value="">-Select State-</option>');
         $.each(myJson.country[i].states, function (index, value) {
            if (personalState == value.id){ selpersonalState=value.id};
            $("#personal_state").append('<option value="'+value.id+'">'+value.name+'</option>');
        });
      }
    }
    $('#personal_state').val(selpersonalState).change();
});

var businessState = $("#db_business_state").val();

if(businessState!='') {
    for(var i = 0; i < myJson.country.length; i++)
    {
        if(myJson.country[i].id == $("#business_country").val())
        {
            $('#business_state').html('<option value="">-Select State-</option>');
            $.each(myJson.country[i].states, function (index, value) {
                var sel = '';
                if (businessState == value.id) { sel=' selected '; }
                var html = '<option value="'+value.id+'" "'+sel+'">'+value.name+'</option>'  ;
                $("#business_state").append(html);
            });
        }
    }
}

$('#business_country').on('change', function() {
    for(var i = 0; i < myJson.country.length; i++)
    {
      if(myJson.country[i].id == $(this).val())
      {
         $('#business_state').html('<option value="">-Select State-</option>');
         $.each(myJson.country[i].states, function (index, value) {
            $("#business_state").append('<option value="'+value.id+'">'+value.name+'</option>');
        });
      }
    }
});


function setBusinessAddress(){
  if ($("#homepostalcheck").is(":checked")) {
    $('#business_address_line1').val($('#personal_address_line1').val());
    $('#business_address_line2').val($('#personal_address_line2').val());
    $('#business_city').val($('#personal_city').val());
    $('#business_country').val($('#personal_country').val()).change();
    $('#business_state').val($('#personal_state').val()).change();
    $('#business_zipcode').val($('#personal_zip').val());
  }
}

$('#homepostalcheck').change(function(){
  setBusinessAddress();

})
$("#homepostalcheck").attr("checked") ? 'yes' : 'no';
$(function() { if($("#mdata").val() == '1') { $("#digitzs_connect").click();}});
</script>
</div>
{/if}


<div class="heading">
     <div class="span6 titlelink" style="margin-left:2.12%">
          <h2>Add accounts</h2>
     </div>
</div>
{*{if $ach_details.isVerify == 0}
<form id="form_email" class="form_email"  method="post" action="index.php?dispatch=ach_payments.link_accounts">
     <div class="span12 ach-form" id="achLink">
          <div class="verify-code-bank">
               <div class="controls">
                    <label>Email</label>
                    <input type="text" id="email_verify" name="email_verify" size="32" {if !empty($ach_details)} value="{$ach_details.user_email}" {else}value="{$user_email}" {/if} readonly>
               </div>
               {if $ach_details.verifyCode !=''}
               <div class="controls">
                    <label>Verify Code</label>
                    <input type="text" id="bank_code" id="bank_code" name="bank_code" size="32" maxlength="6">
                    <input type="hidden" id="verifyCode" name="verifyCode" size="32" value="{$ach_details.verifyCode}">
               
               </div>
               {/if}
               <div class="controls withdraw-btn">
                    {if !empty($ach_details)}
                    <input id="verify_code" class="withdraw-btnt" type="submit" name="verify_code"  value="Verify Code" />
                    <input id="resend_code" class="withdraw-btnt" type="submit" name="resend_code"  value="Resend Code" />
                    {else}
                    <input id="verify_code" class="withdraw-btnt" type="submit" name="send_code"  value="Send Code" />
                    {/if}
               </div>
          </div>
     </div>
</form>
{else}*}
 <div class="span12 ach-form" id="achLink">
     <form id="add_bank" class="add_bank"  method="post" action="index.php?dispatch=ach_payments.add_bank">
          <div class="group" id="ach-bank-form">
               <div class="controls">
                    <label>Account Type</label>
                    <select name="accountType" id="accountType">
                         <option value="checking">Checking</option>
                         <option value="savings">Savings</option>
                    </select>
               </div>
               <div class="controls">
                    <label>Account Name</label>
                    <input type="text" name="accountName">
               </div>
               <div class="controls">
                    <label>Account Number</label>
                    <input type="text" name="accountNumber">
               </div>
               <div class="controls">
                    <label>Routing Number</label>
                    <input type="text" name="routingNumber">
               </div>
               <div class="controls">
                    <label>Country</label>
                    <select name="country" id="country">
                         <option value="USA">United States</option>
                         <option value="CAN">Canada</option>
                    </select>
               </div>
               <div class="withdraw-btn">
                    <input id="button_cart_911" class="withdraw-btnt" type="submit" name="add_bank"  value="Add Bank" />
                    <!-- <button id="button_cart_911">Add your Bank</button> -->
               </div>
          </div>
     </form>
</div>
{* {/if} *}
<script type="text/javascript">
     $("#button_cart_911").click(function() {
          $.ceAjax('request', fn_url('ach_payments.add_bank'), {
            method: 'POST',
            data: {  
                'accountType' : $('#accountType').find("option:selected").val(),
                'accountName' : $('#ach-bank-form').find("input[name='accountName']").val(), 
                'accountNumber' : $('#ach-bank-form').find("input[name='accountNumber']").val(),
                'routingNumber' : $('#ach-bank-form').find("input[name='routingNumber']").val(),
                'country' : $('#country').find("option:selected").val(),
            }       
        });  
     });
</script>
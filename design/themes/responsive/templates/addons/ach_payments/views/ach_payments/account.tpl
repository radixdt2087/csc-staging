<style>
     .remove-btn{
          text-align: end;
          padding: 10px;
     }
</style>
<div class="heading">
     <div class="span16 titlelink" style="margin-left: 2.12%;">
          <h2>Linked accounts</h2>
     </div>
     <div class="span6 linkAccount">
          <button class="withdraw-btnt"><a href="{"ach_payments.link_accounts"|fn_url}" style="color: #fff;">Link new Account</a></button>
     </div>
</div>
     {if $bank_details}    
     {foreach from=$bank_details key=key item=item}
     <div class="ach-lists span12" id="ach-id-{$item->data->id}">
          <div class="bank-name">
               <div class="bank-details span8">
                    <div class="bank-logo">
                         <img src="/images/bank_icon.svg" height="40" width="40">
                    </div>
                    <span>{$item->data->attributes->bank->accountName}</span><br>
                    <p>Checking {$item->data->attributes->bank->accountNumber}</p>
               </div>
               <div class="remove-btn span8">
                    <button id="{$item->data->id}" class="withdraw-btnt withdraw">Withdrawl</button>
                    <a class="cm-ajax cm-post cm-submit withdraw-btnt" onClick="window.location.reload();" href="{"ach_payments.remove?ach_token={$item->data->id}"|fn_url}">Remove</a>
               </div>
          </div>
     </div>
     {/foreach}
     <div class="withdraw-form span12" id="withdraw-form" style="display: none;">
          <div class="amt-limit span8">
               <span>Amount</span>
               <input type="text" name="amount_withdrawl">
               <input type="hidden" name="ach_token" id="ach_token" value="">
               <input type="hidden" name="email_ach" id="email_ach" value="{$user_email}">
          </div>
          <div class="withdraw-btn span6">
               <button id="final-withdraw" class="withdraw-btnt">Withdrawl USD</button>
          </div>
          <div class="withdrwal-note span16">
               <span><h5>Fee: ACH transactions cost 0.29% </h5></span>
          </div>
          
     </div>
     {/if}
 <script type="text/javascript">
     $(".withdraw").click(function() {
          var id = $(this).attr('id');
          $("#ach_token").val(id); 
          $("#withdraw-form").insertAfter("#ach-id-"+id);  
          $("#withdraw-form").show();
     });
     $("#final-withdraw").click(function() {
          $.ceAjax('request', fn_url('ach_payments.withdraw'), {
            method: 'POST',
            data: {  
                'ach_token' : $('#withdraw-form').find("input[name='ach_token']").val(), 
                'amount_withdrawl' : $('#withdraw-form').find("input[name='amount_withdrawl']").val(),
                'email' :  $('#withdraw-form').find("input[name='email_ach']").val(),
            }
           // callback: function callback(response) {
                //flag = 1;
                //localStorage.setItem('flag', flag);
             //   window.location.reload();
            //}     
        });  
     });
</script>
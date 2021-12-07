 {if $companies}
 	<li>{btn type="list" href="wk_vendor_kyc.send_upload_request?company_id=`$company.company_id`" text=__("send_upload_request") class="cm-ajax"}</li>
 {/if}
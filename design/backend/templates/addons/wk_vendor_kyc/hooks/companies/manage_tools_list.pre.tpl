   {if $companies}
   	<li>{btn type="list" dispatch="dispatch[wk_vendor_kyc.m_send_upload_request]" form="companies_form" text=__('send_upload_kyc_selected')}</li>
   {/if}
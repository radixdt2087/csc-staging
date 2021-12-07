{** block-description:block_vendor_information **}
<div class="ty-vendor-information">
   {* {if !empty($aff_merchant_details)}
      <a href="{"companies.products?company_id=`$aff_merchant_details.company_id`"|fn_url}"> 
     <span>&nbsp;</span><span>{$aff_merchant_details.Name}</span>
     </a>
     {else}
        <span><a href="{"companies.view?company_id=`$vendor_info.company_id`"|fn_url}">{$vendor_info.company}</a></span>
        <span>{$vendor_info.company_description nofilter}</span>
     {/if} *}
</div>
{** block-description:vendor_store_banner.store_banner **}

{assign var="vendor_company_data" value=$company_id|fn_get_company_data}
{if $vendor_company_data.vendor_store_banner.detailed.image_path}
<div class="vendor_store_banner_container">
    <img src="{$vendor_company_data.vendor_store_banner.detailed.image_path}" />
</div>
{/if}
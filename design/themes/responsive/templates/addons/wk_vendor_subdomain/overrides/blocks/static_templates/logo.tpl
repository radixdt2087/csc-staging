{** block-description:tmpl_logo **}
<div class="ty-logo-container">
{$url = ""|fn_url}
{if fn_has_vendor_subdomain($url)}
{$url=fn_remove_vendor_subdomain($url)}
{/if}
    <a href="{$url}" title="{$logos.theme.image.alt}" class="wk_vendor_subdomain_logo">
        <img src="{$logos.theme.image.image_path}" width="{$logos.theme.image.image_x}" height="{$logos.theme.image.image_y}" alt="{$logos.theme.image.alt}" class="ty-logo-container__image" />
    </a>
</div>


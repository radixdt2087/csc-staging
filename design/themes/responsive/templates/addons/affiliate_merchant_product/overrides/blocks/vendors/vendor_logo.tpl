{** block-description:block_vendor_logo **}
{if $affiliate_merchant}
    <div class="ty-logo-container-vendor">
        {$articleTitle|replace:'Garden':'Vineyard'}
                {* <a href="{$companydata.url|replace:'{subid}':{$auth.user_id}}" onclick="return !window.open(this.href, 'Google', 'width=900,height=900')" target="_blank"> *}
                <a url="{$companydata.url|replace:'{subid}':{$auth.user_id}}" class="buy_now" target="_blank">
            {if $image_path}
                <img class="ty-pict  ty-logo-container-vendor__image  cm-image" id="det_img_1" src="{$image_path}" width="500" height="500" alt="" title="" loading="lazy">
            {else}
                <span class="ty-no-image" style="min-width: px;min-height: px;"><i class="ty-no-image__icon ty-icon-image" title="No image"></i></span>
            {/if}
            </a>
    </div>
     <div class="ty-companies-btn">
            <a url="{$companydata.url|replace:'{subid}':{$auth.user_id}}" class="buy_now shop-direct-products">Shop Direct</a>
    </div>
{else}
     {if !empty($aff_merchant_details)}
        <div class="ty-logo-container-vendor">
            <a href="{"companies.products?company_id=`$aff_merchant_details.company_id`"|fn_url}">{*"companies.products?company_id=`$aff_merchant_details.company_id`"|fn_url*}
            <img class="ty-pict ty-logo-container-vendor__image cm-image"  src="{$aff_merchant_details.Image}" width="347" height="258" loading="lazy">
            </a>
        </div>
         <div class="ty-companies-btn">
                <a url="{$aff_merchant_details.BuyUrl|replace:'{subid}':{$auth.user_id}}" class="buy_now shop-direct-products">Shop Direct</a>
            </div>
     {else}
    <div class="ty-logo-container-vendor">
        {include file="common/image.tpl"
            obj_id=$vendor_info.company_id
            images=$vendor_info.logos.theme.image
            class="ty-logo-container-vendor__image"
            image_additional_attrs=["width" => $vendor_info.logos.theme.image.image_x, "height" => $vendor_info.logos.theme.image.image_y]
            show_no_image=false
            show_detailed_link=false
            capture_image=false
        }
    </div>
    {/if}
{/if}
<script type="text/javascript">
    $('.login-affiliate-redirect').click(function(event){
        event.preventDefault();
        $('.ty-account-info__buttons').children('a').trigger("click");
    });
</script>
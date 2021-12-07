{if $auth.user_id}
    {$approved = $smarty.session.auth.affiliate_approved}
    {$user_type = $smarty.session.auth.user_type}
    {if ($user_type == 'AffiliateUserTypes::PARTNER'|enum) || ($user_type == 'AffiliateUserTypes::CUSTOMER'|enum && $addons.sd_affiliate.allow_all_customers_be_affiliates == "Y")}
        <li class="ty-account-info__item ty-dropdown-box__item"><a href="{"affiliate_plans.list"|fn_url}" rel="nofollow" class="ty-account-info__a underlined">My Referrals</a></li>
    {/if}
    {if ($user_type == 'AffiliateUserTypes::CUSTOMER'|enum && $addons.sd_affiliate.allow_all_customers_be_affiliates == "N")}
    	<li class="ty-account-info__item ty-dropdown-box__item"><a href="{"profiles-update/?user_type=P"|fn_url}" rel="nofollow" class="ty-account-info__a underlined">{__("affiliates_partnership")}</a></li>
    {/if}
{/if}

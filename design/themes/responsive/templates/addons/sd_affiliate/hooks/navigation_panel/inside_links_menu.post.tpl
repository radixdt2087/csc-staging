{if !$auth.user_id}
    <li>
        <a class="ty-account-info__a underlined navigation-panel__link" href="{"auth.login_form"|fn_url}" rel="nofollow" >
            <div class="navigation-panel__flexblock">
                <span class="navigation-panel__link-icon"><i class="tt-icon-user"></i></span>
                <span class="navigation-panel__link-name">{__("affiliates_partnership")}</span>
            </div>
        </a>
    </li>
{else}
    {$user_type = $smarty.session.auth.user_type}
    {if ($user_type == 'AffiliateUserTypes::PARTNER'|enum)
        || ($user_type == 'AffiliateUserTypes::CUSTOMER'|enum
        && $addons.sd_affiliate.allow_all_customers_be_affiliates == "Y")
    }
        <li>
            <a href="{"affiliate_plans.list"|fn_url}" rel="nofollow" class="ty-account-info__a underlined navigation-panel__link">
                <div class="navigation-panel__flexblock">
                    <span class="navigation-panel__link-icon"><i class="tt-icon-user"></i></span>
                    <span class="navigation-panel__link-name">{__("affiliates_partnership")}</span>
                </div>
            </a>
        </li>
    {/if}
    {if ($user_type == 'AffiliateUserTypes::CUSTOMER'|enum
        && $addons.sd_affiliate.allow_all_customers_be_affiliates == "N")
    }
    	<li>
            <a href="{"profiles-update/?user_type=P"|fn_url}" rel="nofollow" class="ty-account-info__a underlined navigation-panel__link">
                <div class="navigation-panel__flexblock">
                    <span class="navigation-panel__link-icon"><i class="tt-icon-user"></i></span>
                    <span class="navigation-panel__link-name">{__("affiliates_partnership")}</span>
                </div>
            </a>
        </li>
    {/if}
{/if}

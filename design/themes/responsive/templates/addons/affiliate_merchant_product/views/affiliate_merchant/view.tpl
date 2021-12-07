{if $companies}
	{assign var="name" value="{$companies|@count/2}" nocache}
	<ul class="vendor-title-list">
	{foreach from=$companies item="company" name="company"}
		{if $company.affiliate_merchant}
			<li><a url = "{$company.url|replace:'{subid}':{$auth.user_id}}" class="buy_now">{$company.company}</a></li>
		{else}
			<li><a href = "{"companies.products?company_id=`$company.company_id`"|fn_url}">{$company.company}</a></li>
		{/if}
	{/foreach}
	</ul>
{/if}
{include file="common/letter_header.tpl"}
Plans/Addons Purchase
<br /><br />

<table>
    {if $plan!=''}
    <tr>
        <td>{__("vendor_plans.plan")}:</td>
        <td>{$plan}</td>
    </tr>
    {/if}
    {foreach from=$addon_names item=item key=key name=name}
        <tr>
            <td>{$item.name}:</td>
            <td>{include file="common/price.tpl" value=$item.price}&nbsp; {$item.payment_frequency}</td>
        </tr>
    {/foreach}
</table>

{include file="common/letter_footer.tpl"}

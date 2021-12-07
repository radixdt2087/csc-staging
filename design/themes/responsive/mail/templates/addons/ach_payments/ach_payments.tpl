{include file="common/letter_header.tpl"}
Customer Email Verification
<br /><br />

<table>
    <tr>
        <td>Please enter the verification code into your Customer administration page in order to proceed with your application process.</td>
    </tr>
    <tr>
        <td>Verification Code:</td>
        <td>{$email_code}</td>
    </tr>
    <tr>
        <td>Customer Login :</td>
        <td><a href="'{$url}'"> Click Here </a></td>
    </tr>    
</table>

{include file="common/letter_footer.tpl"}

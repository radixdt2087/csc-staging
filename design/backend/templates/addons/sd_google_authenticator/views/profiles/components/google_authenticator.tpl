<div id="two_steps_verification_container">
    <h4 class="subheader">{__("set_up_auth")}</h4>

    {if $show_barcode}
    <ul>
        <li>{__("get_auth_app")}</li>
        <li>{__("app_select_set_up")}</li>
        <li>{__("choose_scan")}</li>
    </ul>

    <div id="sd_google_authenticator_code_barcode">
        <img src="{$qr_code_uri}">
    </div>

    <div id="sd_google_authenticator_code_string" class="hidden">
        {$string_code}
        <br/>
        {__("spaces_dont_matter")}
    </div>

    <a href="javascript:void(0)" id="sd_google_authenticator_switch_input_source">{__("cant_scan_code")}</a>

    <div class="">
        <label class="">{__("enter_code_from_app")}</label>
        <div class="">
            <input type="text" class="input-mini" name="user_data[two_steps_auth_code]" value="" maxlength="6"/>
        </div>
    </div>
    {/if}

    {if $show_reset_button}
        {assign var="but_name" value=__("drop_current_secret_code")}
        {include file="buttons/button.tpl" but_id="sd_google_authenticator_drop_secret_button" but_role="action" but_text="`$but_name`" but_href="`$reset_button_url`" but_name="dispatch[profiles.update]"}
    {/if}

    {if !$show_reset_button && !$show_barcode}
        {__("user_havent_created_auth_code_yet")}
    {/if}
</div>

<script type="text/javascript">
    (function(_, $) {
        var i = 1;

        $("#sd_google_authenticator_switch_input_source").click(function (e) {
            var link_text = ['{__("cant_scan_code")|escape:"javascript"}', '{__("back")|escape:"javascript"}'];

            $('#sd_google_authenticator_code_barcode').toggleClass('hidden');
            $('#sd_google_authenticator_code_string').toggleClass('hidden');
            $("#sd_google_authenticator_switch_input_source").html(link_text[i % 2]);

            i++;
        });
    }(Tygh, Tygh.$));
</script>

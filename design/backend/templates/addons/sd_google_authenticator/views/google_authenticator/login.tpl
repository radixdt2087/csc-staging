<div class="modal signin-modal">
    <form action="{""|fn_url}" method="post" name="main_login_form" class=" cm-skip-check-items cm-check-changes">
        <input type="hidden" name="return_url" value="{$smarty.request.return_url|fn_url:"A":"rel"|fn_query_remove:"return_url"}">
        <div class="modal-header">
            <h4>{__("enter_auth_code")}</h4>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label for="authentication_code" class="">{__("enter_code_from_app")}:</label>
                <input type="text" id="authentication_code" name="authentication_code" size="20" value="" tabindex="2" maxlength="6">
            </div>
        </div>
        <div class="modal-footer">
            {include file="buttons/sign_in.tpl" but_name="dispatch[google_authenticator.login]" but_role="button_main" tabindex="3"}
            {include file="buttons/button.tpl" but_text=__("sign_out") but_href="auth.logout" but_role="text" but_meta="pull-right"}
            <div class="control-group" id="div_remember_device">
                <input type="hidden" name="remember_device" value="N"/>
                <input id="remember_device" type="checkbox" name="remember_device" value="Y">
                <label for="remember_device" class="">{__("remember_device")}</label>
            </div>
        </div>
    </form>
</div>

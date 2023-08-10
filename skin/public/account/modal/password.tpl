<div class="modal" id="password-renew" tabindex="-1" role="dialog" aria-labelledby="passwordRenew" aria-hidden="true">
    <div class="modal-content">
        <form action="{$url}/{$lang}/account/rstpwd/" id="form-password-renew" method="post" class="validate_form nice-form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons ico ico-close"></i></button>
                <h4 class="modal-title" id="passwordRenew">{#send_renew_password#}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input id="pw_email_ac" type="text" name="account[email_ac]" value="" class="form-control" placeholder="{#ph_mail#}" />
                    <label for="pw_email_ac">{#account_mail#}*&nbsp;:</label>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="hashtoken" value="{$hashpass}" required/>
                <button type="button" class="btn btn-default" data-dismiss="modal">{#modal_cancel#}</button>
                <button type="submit" class="btn btn-main">{#account_send#}</button>
            </div>
        </form>
    </div>
</div>
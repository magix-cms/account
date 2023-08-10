{if !isset($formclass)}
    {$formclass = ''}
{/if}
<form id="login-{if !empty($section)}{$section}-{/if}form" method="post" action="{$url}/{$lang}/account/login/" class="{$formclass}" data-refresh="{$url}/{$lang}/">
    <div class="clearfix mc-message">{$message}</div>
    <div class="form-group">
        <input type="text" class="form-control required" placeholder="{#ph_mail#}" id="login_{if !empty($section)}{$section}_{/if}email_ac" name="account[email_ac]" required/>
        <label for="login_{if !empty($section)}{$section}_{/if}email_ac" class="is_empty">{#account_mail#}</label>
    </div>
    <div class="form-group">
        <input type="password" class="form-control required" placeholder="{#ph_password#}" id="login_{if !empty($section)}{$section}_{/if}passwd_ac" name="account[passwd_ac]" required/>
        <label for="login_{if !empty($section)}{$section}_{/if}passwd_ac" class="is_empty">{#account_password#}</label>
    </div>
    <div class="form-group text-center">
        <input type="hidden" name="account[hashtoken]" value="{$hashpass}" required/>
        <input type="hidden" name="currentpage" value="{$smarty.server.REQUEST_URI}">
        <button type="submit" class="btn btn-block btn-main" value="">{#login_btn#|ucfirst}</button>
    </div>
</form>
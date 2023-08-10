{if !$account}
    <button type="button" class="navbar-toggle hidden-xs-down hidden-lg-up" data-toggle="collapse" data-target="#login-panel">
        <span class="ico ico-person"></span>
        {#my_account#}
    </button>
    <div id="login-panel" class="navbar-collapse">
        <div class="login-overlay" data-toggle="collapse" data-target="#login-panel"></div>
        <div class="sidebar">
            <form id="login-{if !empty($section)}{$section}-{/if}form" method="post" action="{$url}/{$lang}/account/login/" class="validate_form refresh_form">
                <div class="mc-message">{*{$message}*}</div>
                <button type="button" data-toggle="modal" data-target="#password-renew" class="btn btn-link" title="{#forget_password#}"><i class="material-icons ico ico-help_outline"></i></button>
                <div class="form-group">
                    <label for="login_{if !empty($section)}{$section}_{/if}email_ac" class="sr-only">{#account_mail#}</label>
                    <input type="text" class="form-control required" placeholder="{#ph_email#}" id="login_{if !empty($section)}{$section}_{/if}email_ac" name="account[email_ac]" required/>
                </div>
                <div class="form-group">
                    <label for="login_{if !empty($section)}{$section}_{/if}passwd_ac" class="sr-only">{#account_password#}</label>
                    <input type="password" class="form-control required" placeholder="{#ph_password#}" id="login_{if !empty($section)}{$section}_{/if}passwd_ac" name="account[passwd_ac]" required/>
                </div>
                <div class="form-group text-center">
                    <input type="hidden" name="account[hashtoken]" value="{$hashpass}" required/>
                    <input type="hidden" name="currentpage" value="{$smarty.server.REQUEST_URI}">
                    <button type="submit" class="btn btn-main" value="">{#login_btn#|ucfirst}</button>
                    <a href="{$url}/{$lang}/account/signup/" class="btn btn-main-outline">{#signup#}</a>
                </div>
            </form>
        </div>
    </div>
    {include file="account/modal/password.tpl"}
{else}
    <a href="{$hashurl}" class="btn btn-user-menu hidden-xs-down">
        <span class="icon">
            {if $account_config.picture && $account.img}
                {include file="img/img.tpl" img=$account.img lazy=false size='small' imgclass=''}
            {else}
                <span class="ico ico-person"></span>
            {/if}
        </span>
        <span class="button-label">{if $account_config.pseudo && $account.pseudo}{$account.pseudo}{else}{#my_account#}{/if}</span>
        {if count(array_intersect(['point','credit'], array_keys($account))) > 0}
        <span class="wallet">
        {if key_exists('credit',$account)}<span class="credit"><span class="credit-balance">{$account.credit}</span>&thinsp;<sup>{#account_credit_abv#}</sup></span>{/if}
        {if key_exists('point',$account)}<span class="point"><span class="point-balance">{$account.point}</span>&thinsp;<sup class="sr-only">{#account_point_abv#}</sup></span>{/if}
        </span>
        {/if}
    </a>
{/if}
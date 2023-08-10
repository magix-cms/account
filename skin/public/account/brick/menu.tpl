<button type="button" class="btn btn-box user-menu-btn hidden-md-down" data-toggle="collapse" data-target="#user-panel">
    <span class="icon">
        <span class="lnr ico ico-user"></span>
    </span>
    <span class="sr-only">Compte</span>
</button>
<div id="user-panel" class="{if $account}logged {/if}collapse has-overlay">
    <div class="overlay panelBack"></div>
    <div id="user-menu">
    {if $account}
        <div class="scroll-div">
            <header>
                <div class="profile">
                    <div class="user-img">
                        {if $account.img}
                            {include file="img/img.tpl" img=$account.img lazy=false size='small'}
                        {else}
                            <span class="lnr ico ico-user"></span>
                        {/if}
                    </div>
                    <p class="account-info">
                        <span class="account-name">{$account.pseudo_ac}</span>
                    </p>
                </div>
            </header>
            <nav>
                <ul>
                    <li>
                        <a href="{$hashurl}" title=""><span class="ico ico-person-circle-outline"></span>{#account#}</a>
                    </li>
                    {foreach $tabs as $tab}
                        <li><a href="/{$lang}/{$tab.url}" title="{$tab.title}">{if $tab.icon}<span class="{$tab.icon}"></span> {/if}{$tab.label}</a></li>
                    {/foreach}
                </ul>
            </nav>
            <footer class="text-center">
                <form action="{$url}/{$lang}/account/logout/" method="post">
                    <button type="submit" class="btn btn-link">{#logout#}</button>
                    <input type="hidden" name="currentpage" value="{$smarty.server.REQUEST_URI}">
                </form>
            </footer>
        </div>
    {else}
        <div class="user-panels">
            <div class="panels">
                <div class="signin-panel">
                    <div class="scroll-div">
                    {include file="account/form/signup.tpl" formclass="validate_form nice-form static_feedback" section="menu"}
                    <p class="text-center">
                        <a href="#" class="panelBack" title="{#back_panel#}">{#back_panel#}</a>
                    </p>
                    </div>
                </div>
                <div class="login-panel">
                    <span class="ico ico-person-circle-outline"></span>
                    {include file="account/form/login.tpl" formclass="validate_form nice-form refresh_form" section="menu"}
                    <p class="text-center">
                        <a href="#" class="topwd" title="{#forget_password#}">{#forget_password#}</a>
                    </p>
                    <p class="text-center">
                        {#no_account#}<br>
                        <a href="#" class="tosignin" title="{#create_btn#}">{#create_btn#}</a>
                    </p>
                </div>
                <div class="pwd-panel">
                    <p class="text-center">
                        {#did_you_forget#}
                    </p>
                    <p class="text-center">
                        {#enter_email_newpwd#}
                    </p>
                    <form action="{$url}/{$lang}/account/rstpwd/" id="form-password-renew" method="post" class="validate_form nice-form">
                        <div class="form-group">
                            <input id="pw_email_ac" type="text" name="account[email_ac]" value="" class="form-control" placeholder="{#ph_mail#}" />
                            <label for="pw_email_ac">{#account_mail#}*&nbsp;:</label>
                        </div>
                        <div class="mc-message"></div>
                        <div class="form-group text-center">
                            <input type="hidden" name="hashtoken" value="{$hashpass}" required/>
                            <button type="submit" class="btn btn-block btn-main">{#account_send#}</button>
                        </div>
                    </form>
                    <p class="text-center">
                        <a href="#" class="panelBack" title="{#back_panel#}">{#back_panel#}</a>
                    </p>
                </div>
            </div>
        </div>
    {/if}
    </div>
</div>
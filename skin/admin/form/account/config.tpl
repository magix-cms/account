<div class="row">
    {*<pre>{$account|print_r}</pre>*}
    <form id="config_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=accountConfig&amp;action=edit" class="validate_form edit_form col-ph-12 col-sm-6 col-lg-3">
        <fieldset>
            <legend>{#account_info#|ucfirst}</legend>
            <div class="form-group">
                <label for="lang">{#lang#|ucfirst}&nbsp;*:</label>
                <select name="account[id_lang]" id="lang" class="form-control required" required>
                    {foreach $langs as $id => $iso}
                        <option value="{$id}"{if $account.id_lang === $id} selected{/if}>{$iso}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="email_ac">{#email#|ucfirst}&nbsp;*:</label>
                <input id="email_ac" type="email" name="account[email_ac]" value="{$account.email_ac}" placeholder="{#ph_email#}" class="form-control required" required/>
            </div>
            {*<div class="form-group">
                <label for="contributor_code_ac">{#contributor_code_ac#|ucfirst}&nbsp;:</label>
                <input id="contributor_code_ac" type="text" name="account[contributor_code_ac]" value="{$account.contributor_code_ac}" placeholder="{#ph_contributor_code_ac#}" class="form-control" maxlength="8"/>
            </div>*}
            <div class="form-group">
                <div class="switch">
                    <input type="checkbox" id="active" name="account[active_ac]" class="switch-native-control"{if $account.active_ac eq 1 || !$account} checked{/if} />
                    <div class="switch-bg">
                        <div class="switch-knob"></div>
                    </div>
                </div>
                <label for="active">{#active_account#}</label>
            </div>
        </fieldset>
        <fieldset>
            <legend>Enregistrer</legend>
            <input type="hidden" name="account[id]" value="{$account.id_account}" />
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
    <form id="pwd_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=pwd&amp;action=edit" class="validate_form pwd_form col-ph-12 col-sm-6 col-lg-3">
        <fieldset>
            <legend>Mot de passe</legend>
            <div class="form-group">
                <label for="passwd">{#new_passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[new_passwd]" id="passwd" placeholder=" {#ph_new_passwd#|ucfirst}" required>
            </div>
            <div class="form-group">
                <label for="repeat_passwd">{#repeat_passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[repeat_passwd]" id="repeat_passwd" placeholder=" {#repeat_passwd#|ucfirst}" equalTo="#passwd" required>
            </div>
        </fieldset>
        <fieldset>
            <legend>Enregistrer</legend>
            <input type="hidden" name="account[id]" value="{$account.id_account}" />
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>
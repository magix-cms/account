<div class="row">
    <form id="account_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=account&amp;action=add" class="validate_form add_form collapse in col-ph-12 col-sm-6 col-lg-3">
        <fieldset>
            <legend>{#account_info#|ucfirst}</legend>
            <div class="form-group">
                <label for="lang">{#lang#|ucfirst}&nbsp;*:</label>
                <select name="account[id_lang]" id="lang" class="form-control required" required>
                    {foreach $langs as $id => $iso}
                        <option value="{$id}">{$iso}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="email_ac">{#email#|ucfirst}&nbsp;*:</label>
                <input id="email_ac" type="email" name="account[email_ac]" value="{$account.email}" placeholder="{#ph_email#}" class="form-control required" required/>
            </div>
            {if $config.pseudo}
            <div class="form-group">
                <label for="pseudo_ac">{#pseudo#|ucfirst} :</label>
                <input id="pseudo_ac" type="text" name="account[pseudo_ac]" value="{$account.pseudo}" placeholder="{#ph_pseudo#}" class="form-control" />
            </div>{/if}
            <div class="form-group">
                <label for="firstname_ac">{#firstname#|ucfirst} :</label>
                <input id="firstname_ac" type="text" name="account[firstname_ac]" value="{$account.firstname}" placeholder="{#ph_firstname#}" class="form-control" />
            </div>
            <div class="form-group">
                <label for="lastname_ac">{#lastname#|ucfirst} :</label>
                <input id="lastname_ac" type="text" name="account[lastname_ac]" value="{$account.lastname}" placeholder="{#ph_lastname#}" class="form-control"  />
            </div>
            <div class="form-group">
                <div class="switch">
                    <input type="checkbox" id="active" name="account[active_ac]" class="switch-native-control"{if $account.active || !$account} checked{/if} />
                    <div class="switch-bg">
                        <div class="switch-knob"></div>
                    </div>
                </div>
                <label for="active">{#active_account#}</label>
            </div>
        </fieldset>
        <fieldset>
            <legend>Mot de passe</legend>
            <div class="form-group">
                <label for="passwd">{#passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[passwd]" id="passwd" placeholder=" {#ph_passwd#|ucfirst}" required>
            </div>
            <div class="form-group">
                <label for="repeat_passwd">{#repeat_passwd#|ucfirst}&nbsp;*</label>
                <input type="password" class="form-control required" name="account[repeat_passwd]" id="repeat_passwd" placeholder=" {#repeat_passwd#|ucfirst}" equalTo="#passwd" required>
            </div>
        </fieldset>
        <fieldset>
            <legend>Enregistrer</legend>
            <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>
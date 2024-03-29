<form id="edit_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&amp;tabs=config" method="post" class="validate_form edit_form">
    <fieldset>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="pseudo" name="acConfig[pseudo]" class="switch-native-control"{if $config.pseudo} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="pseudo">{#config_pseudo#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="birthdate" name="acConfig[birthdate]" class="switch-native-control"{if $config.birthdate} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="birthdate">{#config_birthdate#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="links" name="acConfig[links]" class="switch-native-control"{if $config.links} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="links">{#config_links#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="picture" name="acConfig[picture]" class="switch-native-control"{if $config.picture} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="picture">{#config_picture#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="public" name="acConfig[public]" class="switch-native-control"{if $config.public} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="public">{#config_public#}&nbsp;?</label>
        </div>
        {*<div class="form-group">
            <div class="switch">
                <input type="checkbox" id="address" name="acConfig[address]" class="switch-native-control"{if $config.address} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="address">{#address#}&nbsp;?</label>
        </div>*}
        {*<div class="form-group">
            <div class="switch">
                <input type="checkbox" id="cartpay" name="acConfig[cartpay]" class="switch-native-control"{if $config.cartpay} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="cartpay">{#cartpay#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="google_recaptcha" name="acConfig[google_recaptcha]" class="switch-native-control"{if $config.google_recaptcha} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="google_recaptcha">{#recaptcha#}&nbsp;?</label>
        </div>
        <div id="recaptcha" class="collapse">
            <div class="row">
                <div class="col-ph-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="recaptchaApiKey">{#recaptchaApiKey#}&nbsp;:</label>
                        <input type="text" name="acConfig[recaptchaApiKey]" id="recaptchaApiKey" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="recaptchaSecret">{#recaptchaSecret#}&nbsp;:</label>
                        <input type="text" name="acConfig[recaptchaSecret]" id="recaptchaSecret" class="form-control" />
                    </div>
                </div>
            </div>
        </div>*}
    </fieldset>
    <div id="submit">
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </div>
</form>
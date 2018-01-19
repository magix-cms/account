<form id="edit_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$config.id_config}" method="post" class="validate_form edit_form">
    <fieldset>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="google_recaptcha" name="config[google_recaptcha]" class="switch-native-control"{if $config.google_recaptcha} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="concat">{#links#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="google_recaptcha" name="config[links]" class="switch-native-control"{if $config.links} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="concat">{#address#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="google_recaptcha" name="config[address]" class="switch-native-control"{if $config.address} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="concat">{#cartpay#}&nbsp;?</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="google_recaptcha" name="config[cartpay]" class="switch-native-control"{if $config.cartpay} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="concat">{#recaptcha#}&nbsp;?</label>
        </div>
        <div id="google-recaptcha" class="collapse">
            <div class="form-group">
                <label for="recaptchaApiKey">{#recaptchaApiKey#}&nbsp;:</label>
                <input type="text" name="config[recaptchaApiKey]" id="recaptchaApiKey" class="form-control" />
            </div>
            <div class="form-group">
                <label for="recaptchaSecret">{#recaptchaSecret#}&nbsp;:</label>
                <input type="text" name="config[recaptchaSecret]" id="recaptchaSecret" class="form-control" />
            </div>
        </div>
    </fieldset>
    <div id="submit">
        <input type="hidden" id="id_config" name="id_config" value="{$config.id_config}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </div>
</form>
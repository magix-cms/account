{if !isset($formclass)}
    {$formclass = ''}
{/if}
{country_data list=true}
<form id="signup-{if !empty($section)}{$section}-{/if}form" method="post" action="{$url}/{$lang}/account/signup/" class="{$formclass}">
    <div class="row">
        <div class="col-12 col-sm-6">
            <fieldset>
                <legend>Informations</legend>
                {if $account_config.pseudp}<div class="form-group">
                    <input type="text" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}pseudo_ac" name="account[pseudo_ac]" placeholder="{#ph_pseudo#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}pseudo_ac" class="is_empty">{#account_pseudo#}&nbsp;*</label>
                    </div>{/if}
                <div class="form-group">
                    <input type="text" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}firstname_ac" name="account[firstname_ac]" placeholder="{#ph_firstname#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}firstname_ac" class="is_empty">{#account_firstname#}&nbsp;*</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}lastname_ac" name="account[lastname_ac]" placeholder="{#ph_lastname#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}lastname_ac" class="is_empty">{#account_lastname#}&nbsp;*</label>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}email_ac" name="account[email_ac]" placeholder="{#ph_mail#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}email_ac" class="is_empty">{#account_mail#}&nbsp;*</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}phone_ac" name="account[phone_ac]" placeholder="{#ph_phone#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}phone_ac" class="is_empty">{#account_phone#}&nbsp;*</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}company_ac" name="account[company_ac]" placeholder="{#ph_company#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}company_ac" class="is_empty">{#account_company#}&nbsp;*</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control {*required*}" id="signup_{if !empty($section)}{$section}_{/if}vat_ac" name="account[vat_ac]" placeholder="{#ph_vat#}" {*required*}>
                    <label for="signup_{if !empty($section)}{$section}_{/if}vat_ac" class="is_empty">{#account_vat#}{*&nbsp;**}</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}passwd" name="account[passwd]" placeholder="{#ph_password#}" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}passwd" class="is_empty">{#account_password#}&nbsp;*</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control required" id="signup_{if !empty($section)}{$section}_{/if}repeat_passwd" name="account[repeat_passwd]" placeholder="{#ph_psw_conf#}" equalTo="#signup_{if !empty($section)}{$section}_{/if}passwd" required>
                    <label for="signup_{if !empty($section)}{$section}_{/if}repeat_passwd" class="is_empty">{#repeat_passwd#}&nbsp;*</label>
                </div>
            </fieldset>
        </div>
        <div class="col-12 col-sm-6">
            <fieldset>
                <legend>{#billing_address#}</legend>
                <div class="form-group">
                    <input id="street_billing" type="text" name="billing[street_address]" value="{$account.address.billing.street}" placeholder="{#ph_street_ac#}" class="form-control"  />
                    <label for="street_billing"{if !$account.address.billing.street} class="is_empty"{/if}>{#street_ac#} :</label>
                </div>
                <div class="row">
                    <div class="col-12 col-xs-6">
                        <div class="form-group">
                            <input id="postcode_billing" type="text" name="billing[postcode_address]" value="{$account.address.billing.postcode}" placeholder="{#ph_postcode_ac#}" class="form-control"  />
                            <label for="postcode_billing"{if !$account.address.billing.postcode} class="is_empty"{/if}>{#postcode_ac#} :</label>
                        </div>
                    </div>
                    <div class="col-12 col-xs-6">
                        <div class="form-group">
                            <input id="city_billing" type="text" name="billing[city_address]" value="{$account.address.billing.town}" placeholder="{#ph_city_ac#}" class="form-control"  />
                            <label for="city_billing"{if !$account.address.billing.town} class="is_empty"{/if}>{#city_ac#} :</label>
                        </div>
                    </div>
                </div>
                <div id="country_billing" class="btn-group selectpicker live-filtering form-group" data-live="true" data-autocomplete="true" data-keys="true">
                    <span class="caret"></span>
                    <input type="text"
                           placeholder="{#ph_country#}"
                           name="country_billing"
                           id="country_billing_search"
                           class="form-control live-search dropdown-toggle"
                           aria-describedby="search-country_billing"
                           tabindex="1"
                           data-id="country_billing"
                           autocomplete="nope"/>
                    <label for="country_billing_search">{#country_pc#}</label>
                    <div class="dropdown-menu">
                        <div id="filter-country_billing" class="list-to-filter">
                            <ul class="list-unstyled">
                                {foreach $countries as $country}
                                    <li class="filter-item items" data-filter="{$country.name}" data-id="{$country.iso}" data-value="{$country.name}">{#$country.name#}</li>
                                {/foreach}
                            </ul>
                            <div class="no-search-results">
                                <div class="alert alert-warning" role="alert"><i class="fa ico ico-exchange-triangle margin-right-sm"></i>{#no_entry_found#|sprintf:"<strong>'<span></span>'</strong>"}</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="country_billing_id" value="{$account.address.billing.country}"/>
                </div>
            </fieldset>
            <fieldset>
                <legend>{#delivery_address#}</legend>
                <div class="form-group">
                    <input id="street_delivery" type="text" name="delivery[street_address]" value="{$account.address.delivery.street}" placeholder="{#ph_street_ac#}" class="form-control"  />
                    <label for="street_delivery"{if !$account.address.delivery.street} class="is_empty"{/if}>{#street_ac#} :</label>
                </div>
                <div class="row">
                    <div class="col-12 col-xs-6">
                        <div class="form-group">
                            <input id="postcode_delivery" type="text" name="delivery[postcode_address]" value="{$account.address.delivery.postcode}" placeholder="{#ph_postcode_ac#}" class="form-control"  />
                            <label for="postcode_delivery"{if !$account.address.delivery.postcode} class="is_empty"{/if}>{#postcode_ac#} :</label>
                        </div>
                    </div>
                    <div class="col-12 col-xs-6">
                        <div class="form-group">
                            <input id="city_delivery" type="text" name="delivery[city_address]" value="{$account.address.delivery.town}" placeholder="{#ph_city_ac#}" class="form-control"  />
                            <label for="city_delivery"{if !$account.address.delivery.town} class="is_empty"{/if}>{#city_ac#} :</label>
                        </div>
                    </div>
                </div>
                <div id="country_delivery" class="btn-group selectpicker live-filtering form-group" data-live="true" data-autocomplete="true" data-keys="true">
                    <span class="caret"></span>
                    <input type="text"
                           placeholder="{#ph_country#}"
                           name="country_delivery"
                           id="country_delivery_search"
                           class="form-control live-search dropdown-toggle"
                           aria-describedby="search-country_delivery"
                           tabindex="1"
                           data-id="country_delivery"
                           autocomplete="nope"/>
                    <label for="country_delivery_search">{#country_pc#}</label>
                    <div class="dropdown-menu">
                        <div id="filter-country_delivery" class="list-to-filter">
                            <ul class="list-unstyled">
                                {foreach $countries as $country}
                                    <li class="filter-item items" data-filter="{$country.name}" data-id="{$country.iso}" data-value="{$country.name}">{#$country.name#}</li>
                                {/foreach}
                            </ul>
                            <div class="no-search-results">
                                <div class="alert alert-warning" role="alert"><i class="fa ico ico-exchange-triangle margin-right-sm"></i>{#no_entry_found#|sprintf:"<strong>'<span></span>'</strong>"}</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="country_delivery_id" value="{$account.address.delivery.country}"/>
                </div>
            </fieldset>
        </div>
    </div>
    {strip}
        {capture name="cond_gen"}
            <a class="targetblank" href="{$url}{#cond_gen_uri#}" title="{#cond_gen#}">{#cond_gen#}</a>
        {/capture}
        {capture name="private_laws"}
            <a class="targetblank" href="{$url}{#private_laws_uri#}" title="{#private_laws#}">{#private_laws#}</a>
        {/capture}
    {/strip}
    {if isset($account_config.newsletter) && $account_config.newsletter}
    <div class="form-group">
        <div class="checkbox">
            <label for="signup_{if !empty($section)}{$section}_{/if}newsletter">
                <input type="checkbox" name="account[newsletter]" id="signup_{if !empty($section)}{$section}_{/if}newsletter">{$smarty.config.account_signup_news|sprintf:$companyData.name|ucfirst}
            </label>
        </div>
    </div>
    {/if}
    <div class="form-group">
        <div class="checkbox">
            <label for="signup_{if !empty($section)}{$section}_{/if}cond_gen">
                <input type="checkbox" name="cond_gen" id="signup_{if !empty($section)}{$section}_{/if}cond_gen" class="required" required><span>{#account_cond_gen#|ucfirst|sprintf:$smarty.capture.cond_gen:$smarty.capture.private_laws}&nbsp;*</span>
            </label>
        </div>
    </div>
    {if isset($account_config.recaptcha) && $account_config.recaptcha}
        {include file="recaptcha/form/recaptcha.tpl" action="account"}
    {/if}
    <div class="mc-message"></div>
    <div class="form-group text-center">
        <button type="submit" class="btn btn-block btn-main">{#account_signup#|ucfirst}</button>
    </div>
</form>
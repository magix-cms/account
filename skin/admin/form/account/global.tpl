<form id="account_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=account&amp;action=edit" class="validate_form edit_form">
    <div class="row">
        <fieldset class="col-ph-12 col-md-6 col-lg-4">
            <legend>{#account_infos#}</legend>
            {if $config.pseudo}<div class="form-group">
                <label for="pseudo_ac">{#pseudo_ac#} :</label>
                <input id="pseudo_ac" type="text" name="account[pseudo_ac]" value="{$account.pseudo_ac}" placeholder="{#ph_pseudo#}" class="form-control" />
            </div>{/if}
            <div class="form-group">
                <label for="firstname_ac">{#firstname#|ucfirst} :</label>
                <input id="firstname_ac" type="text" name="account[firstname_ac]" value="{$account.firstname_ac}" placeholder="{#ph_firstname#|ucfirst}" class="form-control" />
            </div>
            <div class="form-group">
                <label for="lastname_ac">{#lastname#|ucfirst} :</label>
                <input id="lastname_ac" type="text" name="account[lastname_ac]" value="{$account.lastname_ac}" placeholder="{#ph_lastname#|ucfirst}" class="form-control"  />
            </div>
            {if $config.birthdate}<div class="form-group">
                <label for="birthdate_ac">{#birthdate_ac#|ucfirst} :</label>
                <input id="birthdate_ac" type="date" name="account[birthdate_ac]" value="{$account.birthdate_ac|date_format:'%Y-%m-%d'}" placeholder="{#ph_birthdate_ac#|ucfirst}" class="form-control"  />
            </div>{/if}
            <div class="form-group">
                <label for="phone_ac">{#phone#|ucfirst} :</label>
                <input id="phone_ac" type="text" name="account[phone_ac]" value="{$account.phone_ac}" placeholder="{#ph_phone#|ucfirst}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="company_ac">{#company#|ucfirst} :</label>
                <input id="company_ac" type="text" name="account[company_ac]" value="{$account.company_ac}" placeholder="{#ph_company#|ucfirst}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="vat_ac">{#vat#} :</label>
                <input id="vat_ac" type="text" name="account[vat_ac]" value="{$account.vat_ac}" placeholder="{#ph_vat#}" class="form-control"  />
            </div>
        </fieldset>
        <fieldset class="col-ph-12 col-md-6 col-lg-4">
            <legend>{#account_billing#}</legend>
            <div class="form-group">
                <label for="street_ac">{#street_ac#} :</label>
                <input id="street_ac" type="text" name="billing[street_address]" value="{$account.billing.street_address}" placeholder="{#ph_street#}" class="form-control"  />
            </div>
            <div class="row">
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="postcode_ac">{#postcode_ac#} :</label>
                        <input id="postcode_ac" type="text" name="billing[postcode_address]" value="{$account.billing.postcode_address}" placeholder="{#ph_postcode#}" class="form-control"  />
                    </div>
                </div>
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="city_ac">{#city_ac#} :</label>
                        <input id="city_ac" type="text" name="billing[city_address]" value="{$account.billing.city_address}" placeholder="{#ph_city#}" class="form-control"  />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="country_ac">{#country#}</label>
                <select name="billing[country_address]" id="country_ac" class="form-control">
                    <option value="">{#ph_country#}</option>
                    {foreach $countries as $k => $country}
                        <option value="{$country.iso}"{if $account.billing.country_address == $country.iso} selected{/if}>{$country.name}</option>
                    {/foreach}
                </select>
            </div>
        </fieldset>
        <fieldset class="col-ph-12 col-md-6 col-lg-4">
            <legend>{#account_delivery#}</legend>
            <div class="form-group">
                <label for="street_ac">{#street_ac#} :</label>
                <input id="street_ac" type="text" name="delivery[street_address]" value="{$account.delivery.street_address}" placeholder="{#ph_street#}" class="form-control"  />
            </div>
            <div class="row">
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="postcode_ac">{#postcode_ac#} :</label>
                        <input id="postcode_ac" type="text" name="delivery[postcode_address]" value="{$account.delivery.postcode_address}" placeholder="{#ph_postcode#}" class="form-control"  />
                    </div>
                </div>
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="city_ac">{#city_ac#} :</label>
                        <input id="city_ac" type="text" name="delivery[city_address]" value="{$account.delivery.city_address}" placeholder="{#ph_city#}" class="form-control"  />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="country_ac">{#country#}</label>
                <select name="delivery[country_address]" id="country_ac" class="form-control">
                    <option value="">{#ph_country#}</option>
                    {foreach $countries as $k => $country}
                        <option value="{$country.iso}"{if $account.delivery.country_address == $country.iso} selected{/if}>{$country.name}</option>
                    {/foreach}
                </select>
            </div>
        </fieldset>
        {if $config.links}
        <fieldset class="col-ph-12 col-md-6 col-lg-4">
            <legend>{#account_socials#}</legend>
            <div class="form-group">
                <label for="social_website">{#website_ac#}</label>
                <input type="text" class="form-control" id="social_website" name="socials[website]" {if $account.website}value="{$account.website}" {/if}placeholder="{#ph_website#}">
            </div>
            <div class="form-group">
                <label for="social_facebook">{#facebook_ac#}</label>
                <input type="text" class="form-control" id="social_facebook" name="socials[facebook]" {if $account.facebook}value="{$account.facebook}" {/if}placeholder="{#ph_facebook#}">
            </div>
            <div class="form-group">
                <label for="social_twitter">{#twitter_ac#}</label>
                <input type="text" class="form-control" id="social_twitter" name="socials[twitter]" {if $account.twitter}value="{$account.twitter}" {/if}placeholder="{#ph_twitter#}">
            </div>
            <div class="form-group">
                <label for="social_instagram">{#insta_ac#}</label>
                <input type="text" class="form-control" id="social_instagram" name="socials[instagram]" {if $account.instagram}value="{$account.instagram}" {/if}placeholder="{#ph_insta#}">
            </div>
            <div class="form-group">
                <label for="social_tiktok">{#tiktok_ac#}</label>
                <input type="text" class="form-control" id="social_tiktok" name="socials[tiktok]" {if $account.tiktok}value="{$account.tiktok}" {/if}placeholder="{#ph_tiktok#}">
            </div>
            <div class="form-group">
                <label for="social_tumblr">{#tumblr_ac#}</label>
                <input type="text" class="form-control" id="social_tumblr" name="socials[tumblr]" {if $account.tumblr}value="{$account.tumblr}" {/if}placeholder="{#ph_tumblr#}">
            </div>
            <div class="form-group">
                <label for="social_linkedin">{#linkedin_ac#}</label>
                <input type="text" class="form-control" id="social_linkedin" name="socials[linkedin]" {if $account.linkedin}value="{$account.linkedin}" {/if}placeholder="{#ph_linkedin#}">
            </div>
            <div class="form-group">
                <label for="social_viadeo">{#viadeo_ac#}</label>
                <input type="text" class="form-control" id="social_viadeo" name="socials[viadeo]" {if $account.viadeo}value="{$account.viadeo}" {/if}placeholder="{#ph_viadeo#}">
            </div>
            <div class="form-group">
                <label for="social_pinterest">{#pinterest_ac#}</label>
                <input type="text" class="form-control" id="social_pinterest" name="socials[pinterest]" {if $account.pinterest}value="{$account.pinterest}" {/if}placeholder="{#ph_pinterest#}">
            </div>
            <div class="form-group">
                <label for="social_github">{#github_ac#}</label>
                <input type="text" class="form-control" id="social_github" name="socials[github]" {if $account.github}value="{$account.github}" {/if}placeholder="{#ph_github#}">
            </div>
            <div class="form-group">
                <label for="social_soundcloud">{#soundcloud_ac#}</label>
                <input type="text" class="form-control" id="social_soundcloud" name="socials[soundcloud]" {if $account.soundcloud}value="{$account.soundcloud}" {/if}placeholder="{#ph_soundcloud#}">
            </div>
        </fieldset>
        {/if}
    </div>
    <fieldset>
        <legend>Enregistrer</legend>
        <input type="hidden" name="id" value="{$account.id_account}" />
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </fieldset>
</form>
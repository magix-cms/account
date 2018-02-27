<form id="account_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=account&amp;action=edit" class="validate_form edit_form">
    <div class="row">
        <fieldset class="col-ph-12 col-md-6 col-lg-3">
            <legend>{#account_info#|ucfirst}</legend>
            <div class="form-group">
                <label for="firstname_ac">{#firstname#|ucfirst} :</label>
                <input id="firstname_ac" type="text" name="account[firstname_ac]" value="{$account.firstname_ac}" placeholder="{#ph_firstname#}" class="form-control" />
            </div>
            <div class="form-group">
                <label for="lastname_ac">{#lastname#|ucfirst} :</label>
                <input id="lastname_ac" type="text" name="account[lastname_ac]" value="{$account.lastname_ac}" placeholder="{#ph_lastname#}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="phone_ac">{#phone#|ucfirst} :</label>
                <input id="phone_ac" type="text" name="account[phone_ac]" value="{$account.phone_ac}" placeholder="{#ph_phone#}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="street_ac">{#street_ac#|ucfirst} :</label>
                <input id="street_ac" type="text" name="address[street_address]" value="{$account.street_address}" placeholder="{#ph_street#}" class="form-control"  />
            </div>
            <div class="row">
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="postcode_ac">{#postcode_ac#|ucfirst} :</label>
                        <input id="postcode_ac" type="text" name="address[postcode_address]" value="{$account.postcode_address}" placeholder="{#ph_postcode#}" class="form-control"  />
                    </div>
                </div>
                <div class="col-ph-12 col-xs-6">
                    <div class="form-group">
                        <label for="city_ac">{#city_ac#|ucfirst} :</label>
                        <input id="city_ac" type="text" name="address[city_address]" value="{$account.city_address}" placeholder="{#ph_city#}" class="form-control"  />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="country_ac">{#country#|ucfirst}</label>
                <select name="address[country_address]" id="country_ac" class="form-control">
                    <option value="">{#ph_country#|ucfirst}</option>
                    {foreach $countries as $iso => $name}
                        <option value="{$iso}"{if $account.country_address == $iso} selected{/if}>{$name|ucfirst}</option>
                    {/foreach}
                </select>
            </div>
            <div class="form-group">
                <label for="company_ac">{#company#|ucfirst} :</label>
                <input id="company_ac" type="text" name="account[company_ac]" value="{$account.company_ac}" placeholder="{#ph_company#}" class="form-control"  />
            </div>
            <div class="form-group">
                <label for="vat_ac">{#vat#|ucfirst} :</label>
                <input id="vat_ac" type="text" name="account[vat_ac]" value="{$account.vat_ac}" placeholder="{#ph_vat#}" class="form-control"  />
            </div>
        </fieldset>
        {if $config.links}
        <fieldset class="col-ph-12 col-md-6 col-lg-4">
            <legend>{#socials#|ucfirst}</legend>
            <div class="form-group">
                <label for="social_website">{#website_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_website" name="socials[website]" {if $account.website}value="{$account.website}" {/if}placeholder="{#ph_website#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_facebook">{#facebook_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_facebook" name="socials[facebook]" {if $account.facebook}value="{$account.facebook}" {/if}placeholder="{#ph_facebook#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_instagram">{#insta_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_instagram" name="socials[instagram]" {if $account.instagram}value="{$account.instagram}" {/if}placeholder="{#ph_insta#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_twitter">{#twitter_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_twitter" name="socials[twitter]" {if $account.twitter}value="{$account.twitter}" {/if}placeholder="{#ph_twitter#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_google">{#google_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_google" name="socials[google]" {if $account.google}value="{$account.google}" {/if}placeholder="{#ph_google#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_linkedin">{#linkedin_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_linkedin" name="socials[linkedin]" {if $account.linkedin}value="{$account.linkedin}" {/if}placeholder="{#ph_linkedin#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_viadeo">{#viadeo_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_viadeo" name="socials[viadeo]" {if $account.viadeo}value="{$account.viadeo}" {/if}placeholder="{#ph_viadeo#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_pinterest">{#pinterest_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_pinterest" name="socials[pinterest]" {if $account.pinterest}value="{$account.pinterest}" {/if}placeholder="{#ph_pinterest#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_github">{#github_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_github" name="socials[github]" {if $account.github}value="{$account.github}" {/if}placeholder="{#ph_github#|ucfirst}">
            </div>
            <div class="form-group">
                <label for="social_soundcloud">{#soundcloud_ac#|ucfirst}</label>
                <input type="text" class="form-control" id="social_soundcloud" name="socials[soundcloud]" {if $account.soundcloud}value="{$account.soundcloud}" {/if}placeholder="{#ph_soundcloud#|ucfirst}">
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
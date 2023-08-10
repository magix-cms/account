{extends file="layout.tpl"}
{block name="title"}{#seo_infos_title#}{/block}
{block name="description"}{#seo_infos_desc#}{/block}
{block name='body:id'}account-edit{/block}
{block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/bootstrap-select{if $setting.mode !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/account-edit{if $setting.mode !== 'dev'}.min{/if}.css"
    ]}
{/block}

{block name="article:content"}
    {*<pre>{$account|print_r}</pre>*}
    {if $account_config.picture}<div class="user-img">
        <form id="img_form" method="post" action="{$smarty.server.REQUEST_URI}?tab=image" enctype="multipart/form-data" class="validate_form form-gen refresh_form nice-form">
            <div class="preview-img">
                <div class="preview">
                    <span class="ico ico-person-circle-outline"></span>
                    <img src="{if $account.img}{$account.img.medium.src}{else}#{/if}" alt="{#account_image#}" class="no-img img-responsive{if !$account.img} hide{/if}" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                    <input type="file" accept="image/*" id="img" name="img" value="" class="hide"/>
                </div>
                <div class="form-group text-center">
                    <label class="btn btn-main" for="img">{if !$account.img}{#add_account_img#}{else}{#change_account_img#}{/if}</label>
                    <button class="btn btn-default reset hide" disabled>{#account_img_reset#}</button>
                    <button class="btn btn-main hide" type="submit" disabled>{#account_save#}</button>
                </div>
            </div>
        </form>
    </div>{/if}
    <div class="clearfix mc-message"></div>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading_config">
                <p class="h4 panel-title collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#config_panel" aria-expanded="true" aria-controls="collapse1">
                    <span itemprop="name">Identifiant et mot de passe</span>
                    <span class="icon">
                        <span class="show-more"><i class="ico ico-edit"></i></span>
                        <span class="show-less"><i class="ico ico-close"></i></span>
                    </span>
                </p>
            </div>
            <div id="config_panel" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_config">
                <form id="config_form" method="post" action="{$smarty.server.REQUEST_URI}?tab=accountConfig" class="validate_form refresh_form">
                    <fieldset>
                        <legend>{#account_info#|ucfirst}</legend>
                        <div class="form-group">
                            <label for="email_ac" class="is_empty">{#email_ac#|ucfirst}&nbsp;*</label>
                            <input id="email_ac" type="email" name="account[email_ac]" value="{$account.email_ac}" placeholder="{#ph_email#}" class="form-control required" required/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-main" type="submit" name="action" value="edit">{#account_save#|ucfirst}</button>
                        </div>
                    </fieldset>
                </form>
                <form id="pwd_form" method="post" action="{$smarty.server.REQUEST_URI}?tab=pwd" class="validate_form pwd_form">
                    <fieldset>
                        <legend>{#account_password#|ucfirst}</legend>
                        <div class="form-group">
                            <label for="old_passwd" class="is_empty">{#old_passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="account[old_passwd]" id="old_passwd" placeholder="{#ph_old_passwd#|ucfirst}" required>
                        </div>
                        <div class="form-group">
                            <label for="passwd" class="is_empty">{#new_passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="account[new_passwd]" id="passwd" placeholder="{#ph_new_passwd#|ucfirst}" required>
                        </div>
                        <div class="form-group">
                            <label for="repeat_passwd" class="is_empty">{#repeat_passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="account[repeat_passwd]" id="repeat_passwd" placeholder="{#repeat_passwd#|ucfirst}" equalTo="#passwd" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-main" type="submit" name="action" value="edit">{#account_save#|ucfirst}</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading_info">
                <p class="h4 panel-title collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#info_panel" aria-expanded="true" aria-controls="collapse2">
                    <span itemprop="name">{#particulars#}</span>
                    <span class="icon">
                        <span class="show-more"><i class="ico ico-edit"></i></span>
                        <span class="show-less"><i class="ico ico-close"></i></span>
                    </span>
                </p>
            </div>
            <div id="info_panel" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_info">
                <form id="private-form" method="post" action="{$smarty.server.REQUEST_URI}" class="validate_form edit_form">
                    <fieldset>
                        {if $config.pseudo}<div class="form-group">
                            <label for="pseudo_ac">{#pseudo_ac#} :</label>
                            <input id="pseudo_ac" type="text" name="account[pseudo_ac]" value="{$account.pseudo_ac}" placeholder="{#ph_pseudo_ac#}" class="form-control" />
                            </div>{/if}
                        <div class="row">
                            <div class="col-12 col-xs-6">
                                <div class="form-group">
                                    <label for="firstname_ac">{#firstname_ac#} :</label>
                                    <input id="firstname_ac" type="text" name="account[firstname_ac]" value="{$account.firstname_ac}" placeholder="{#ph_firstname_ac#}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-xs-6">
                                <div class="form-group">
                                    <label for="lastname_ac">{#lastname_ac#} :</label>
                                    <input id="lastname_ac" type="text" name="account[lastname_ac]" value="{$account.lastname_ac}" placeholder="{#ph_lastname_ac#}" class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="birthdate_ac"{if !$account.birthdate_ac} class="is_empty"{/if}>{#birthdate_ac#} :</label>
                            <input id="birthdate_ac" type="date" name="account[birthdate_ac]" value="{$account.birthdate_ac}" placeholder="{#ph_date#}" class="form-control"  />
                        </div>
                        <div class="form-group">
                            <label for="phone_ac"{if !$account.phone_ac} class="is_empty"{/if}>{#phone_ac#} :</label>
                            <input id="phone_ac" type="text" name="account[phone_ac]" value="{$account.phone_ac}" placeholder="{#ph_phone_ac#}" class="form-control"  />
                        </div>
                        <div class="form-group">
                            <label for="street_ac"{if !$account.street_address} class="is_empty"{/if}>{#street_ac#} :</label>
                            <input id="street_ac" type="text" name="address[street_address]" value="{$account.street_address}" placeholder="{#ph_street_ac#}" class="form-control"  />
                        </div>
                        <div class="row">
                            <div class="col-12 col-xs-6">
                                <div class="form-group">
                                    <label for="postcode_ac"{if !$account.postcode_address} class="is_empty"{/if}>{#postcode_ac#} :</label>
                                    <input id="postcode_ac" type="text" name="address[postcode_address]" value="{$account.postcode_address}" placeholder="{#ph_postcode_ac#}" class="form-control"  />
                                </div>
                            </div>
                            <div class="col-12 col-xs-6">
                                <div class="form-group">
                                    <label for="city_ac"{if !$account.city_address} class="is_empty"{/if}>{#city_ac#} :</label>
                                    <input id="city_ac" type="text" name="address[city_address]" value="{$account.city_address}" placeholder="{#ph_city_ac#}" class="form-control"  />
                                </div>
                            </div>
                        </div>
                        <div id="country" class="btn-group btn-block selectpicker live-filtering form-group" data-live="true" data-autocomplete="true" data-keys="true">
                            <span class="caret"></span>
                            <input type="text"
                                   placeholder="{#ph_country#}"
                                   name="country"
                                   id="country_search"
                                   class="form-control live-search dropdown-toggle"
                                   aria-describedby="search-country"
                                   tabindex="1"
                                   data-id="country"
                                   autocomplete="nope"/>
                            <label for="country_search">{#country_pc#}</label>
                            <div class="dropdown-menu">
                                <div id="filter-country" class="list-to-filter">
                                    <ul class="list-unstyled">
                                        {foreach $countries as $iso => $name}
                                            <li class="filter-item items" data-filter="{$name}" data-id="{$iso}" data-value="{$name}">{$name}</li>
                                        {/foreach}
                                    </ul>
                                    <div class="no-search-results">
                                        <div class="alert alert-warning" role="alert"><i class="fa ico ico-exchange-triangle margin-right-sm"></i>{#no_entry_found#|sprintf:"<strong>'<span></span>'</strong>"}</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="country_id" value="{$account.country_address}"/>
                        </div>
                        <div class="form-group">
                            <label for="company_ac"{if !$account.company_ac} class="is_empty"{/if}>{#company_ac#} :</label>
                            <input id="company_ac" type="text" name="account[company_ac]" value="{$account.company_ac}" placeholder="{#ph_company_ac#}" class="form-control"  />
                        </div>
                        <div class="form-group">
                            <label for="vat_ac"{if !$account.vat_ac} class="is_empty"{/if}>{#tva_ac#} :</label>
                            <input id="vat_ac" type="text" name="account[vat_ac]" value="{$account.vat_ac}" placeholder="{#ph_vat_ac#}" class="form-control"  />
                        </div>
                    </fieldset>
                    <div class="mc-message"></div>
                    <fieldset class="text-center">
                        <legend class="sr-only">{#account_save#}</legend>
                        <button class="btn btn-block btn-main" type="submit" name="action" value="edit">{#account_save#}</button>
                    </fieldset>
                </form>
            </div>
        </div>
        {if $account_config.links}
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading_social">
                <p class="h4 panel-title collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#social_panel" aria-expanded="true" aria-controls="collapse3">
                    <span itemprop="name">{#socials#}</span>
                    <span class="icon">
                        <span class="show-more"><i class="ico ico-edit"></i></span>
                        <span class="show-less"><i class="ico ico-close"></i></span>
                    </span>
                </p>
            </div>
            <div id="social_panel" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_social">
                <form id="private-form" method="post" action="{$smarty.server.REQUEST_URI}?tab=socials" class="validate_form edit_form">
                    <fieldset>
                        <div class="form-group">
                            <label for="website">{#social_website#} :</label>
                            <input id="website" type="text" name="socials[website]" value="{$account.website}" placeholder="{#ph_social_website#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="facebook">{#social_facebook#} :</label>
                            <input id="facebook" type="text" name="socials[facebook]" value="{$account.facebook}" placeholder="{#ph_social_facebook#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="twitter">{#social_twitter#} :</label>
                            <input id="twitter" type="text" name="socials[twitter]" value="{$account.twitter}" placeholder="{#ph_social_twitter#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="instagram">{#social_instagram#} :</label>
                            <input id="instagram" type="text" name="socials[instagram]" value="{$account.instagram}" placeholder="{#ph_social_instagram#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="tiktok">{#social_tiktok#} :</label>
                            <input id="tiktok" type="text" name="socials[tiktok]" value="{$account.tiktok}" placeholder="{#ph_social_tiktok#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="youtube">{#social_youtube#} :</label>
                            <input id="youtube" type="text" name="socials[youtube]" value="{$account.youtube}" placeholder="{#ph_social_youtube#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="pinterest">{#social_pinterest#} :</label>
                            <input id="pinterest" type="text" name="socials[pinterest]" value="{$account.pinterest}" placeholder="{#ph_social_pinterest#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="tumblr">{#social_tumblr#} :</label>
                            <input id="tumblr" type="text" name="socials[tumblr]" value="{$account.tumblr}" placeholder="{#ph_social_tumblr#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="linkedin">{#social_linkedin#} :</label>
                            <input id="linkedin" type="text" name="socials[linkedin]" value="{$account.linkedin}" placeholder="{#ph_social_linkedin#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="viadeo">{#social_viadeo#} :</label>
                            <input id="viadeo" type="text" name="socials[viadeo]" value="{$account.viadeo}" placeholder="{#ph_social_viadeo#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="github">{#social_github#} :</label>
                            <input id="github" type="text" name="socials[github]" value="{$account.github}" placeholder="{#ph_social_github#}" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="soundcloud">{#social_soundcloud#} :</label>
                            <input id="soundcloud" type="text" name="socials[soundcloud]" value="{$account.soundcloud}" placeholder="{#ph_social_soundcloud#}" class="form-control" />
                        </div>
                    </fieldset>
                    <fieldset class="text-center">
                        <legend class="sr-only">{#account_save#}</legend>
                        <button class="btn btn-block btn-main" type="submit" name="action" value="edit">{#account_save#}</button>
                    </fieldset>
                </form>
            </div>
        </div>
        {/if}
    </div>
{/block}
{block name="scripts"}
    {$js_files = [
    'defer' => [
        "/skin/{$theme}/js/vendor/tabcomplete.min.js",
        "/skin/{$theme}/js/vendor/nativeLiveFilter.min.js",
        "/skin/{$theme}/js/vendor/nativeBootstrapSelect.min.js"
        ]
    ]}
    {if $config.picture}{$js_files['defer'][] = "/skin/{$theme}/js/src/img-preview.js"}{/if}
{/block}
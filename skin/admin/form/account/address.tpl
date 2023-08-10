<div class="row">
    <form id="address_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=address&amp;action=edit" class="validate_form edit_form col-ph-12 col-sm-6 col-lg-3">
        <fieldset>
            <legend>{#address_info#|ucfirst}</legend>
            <div class="form-group">
                <label for="country_admin">{#country#|ucfirst}</label>
                <select name="country_admin" id="country_admin" class="form-control">
                    <option value="">{#ph_country#|ucfirst}</option>
                    {foreach $countries as $iso => $name}
                        <option value="{$iso}"{if $employee.country_admin == $iso} selected{/if}>{$name|ucfirst}</option>
                    {/foreach}
                </select>
            </div>
        </fieldset>
        <fieldset>
            <legend>Enregistrer</legend>
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>
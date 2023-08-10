<div class="row">
    <form id="delete_img_account" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=delete" method="post" class="validate_form delete_form_img col-ph-12">
        <div class="form-group">
            <input type="hidden" id="del_img" name="del_img" value="{$account.id_account}">
            <button class="btn btn-danger" type="submit">{#remove#|ucfirst}</button>
        </div>
    </form>
    {*{foreach $langs as $id => $iso}
        {if $iso@first}{$default = $id}{break}{/if}
    {/foreach}*}
    <form id="edit_img_news" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&amp;edit={$account.id_account}&amp;tabs=img" method="post" class="validate_form edit_form_img col-ph-12 col-md-6 col-lg-5">
        <div id="drop-zone" class="img-drop{if !isset($account.img_ac) || empty($account.img_ac)} no-img{/if}">
            <div id="drop-buttons" class="form-group">
                <label id="clickHere" class="btn btn-default">
                    ou cliquez ici.. <span class="fa fa-upload"></span>
                    <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
                    <input type="file" id="img" name="img" />
                    <input type="hidden" id="id" name="id" value="{$account.id_account}">
                </label>
            </div>
            <div class="preview-img">
                <img id="preview"
                     src="{if isset($account.img_ac) && !empty($account.img_ac)}/upload/account/{$account.id_account}/{$account.img_ac}{else}#{/if}"
                     alt="Déposez votre images ici..."
                     class="{if isset($account.img_ac) && !empty($account.img_ac)}preview{else}no-img{/if} img-responsive" />
            </div>
        </div>
{*        {include file="language/brick/dropdown-lang.tpl" onclass="true"}*}
        <fieldset>
            <legend>Enregistrer</legend>
            <input type="hidden" name="page[id]" value="{$account.id_account}" />
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
    {*<div class="col-ph-12 col-md-6 col-lg-7">
        <h3>Tailles disponibles</h3>
        <div class="block-img">
            {if $page.imgSrc != null}
                {include file="news/brick/img.tpl"}
            {/if}
        </div>
    </div>*}
</div>
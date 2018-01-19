{extends file="layout.tpl"}
{block name='head:title'}{#account_plugin#}{/block}
{block name='body:id'}account{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des cartes">{#account_plugin#}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-xs-12 col-md-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header panel-nav">
                    <h2 class="panel-heading h5">{#edit_account#|ucfirst}</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#global#}</a></li>
                        <li role="presentation"><a href="#address" aria-controls="address" role="tab" data-toggle="tab">{#address#}</a></li>
                        <li role="presentation"><a href="#socials" aria-controls="socials" role="tab" data-toggle="tab">{#socials#}</a></li>
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="general">
                            {include file="forms/account/global.tpl"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="address">
                            {include file="forms/account/address.tpl"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="socials">
                            {include file="forms/account/socials.tpl"}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    {/if}
{/block}
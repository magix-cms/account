{extends file="layout.tpl"}
{block name='head:title'}{#account_plugin#}{/block}
{block name='body:id'}account{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des comptes clients">{#account_plugin#}</a></h1>
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
                        <li role="presentation" class="active"><a href="#config" aria-controls="socials" role="tab" data-toggle="tab">{#account_config#}</a></li>
                        <li role="presentation"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#global#}</a></li>
                        {*<li role="presentation"><a href="#address" aria-controls="address" role="tab" data-toggle="tab">{#address#}</a></li>*}
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="config">
                            {include file="form/account/config.tpl"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="general">
                            {include file="form/account/global.tpl"}
                        </div>
                        {*<div role="tabpanel" class="tab-pane" id="address">
                            {include file="section/form/list-form.tpl" dir_controller="" controller="account" sub="address" data=$sTaxes id=$account.id_account class_form="col-xs-12 col-lg-5" class_table="col-xs-12 col-lg-7"}
                            *}{*{include file="forms/account/address.tpl"}*}{*
                            *}{*{if !isset($class_form)}{$class_form = "col-ph-12 col-md-6"}{/if}
                            {if !isset($class_table)}{$class_table = "col-ph-12 col-md-6"}{/if}
                            {if !isset($dir_controller)}{$dir_controller = $controller}{/if}
                            <div class="row">
                                <form id="add_{$sub}" action="{geturl}/{baseadmin}/index.php?controller={$smarty.get.controller}&amp;action=add&tabs=address&edit={$id}" data-sub="{$sub}" method="post" class="validate_form add_to_list {$class_form}">
                                    {include file="{$dir_controller}/form/{$sub}.tpl"}
                                </form>
                                <div class="{$class_table}">
                                    <div class="table-responsive">
                                        <table class="table table-condensed{if isset($customClass)} {$customClass}{/if}">
                                            <tbody id="{$sub}List" class="direct-edit-table">
                                            {if !empty($data)}
                                                {foreach $data as $row}
                                                    {include file="{$dir_controller}/loop/{$sub}.tpl" first=$row@first}
                                                {/foreach}
                                            {/if}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>*}{*
                        </div>*}
                    </div>
                </div>
            </section>
        </div>
    {/if}
{/block}
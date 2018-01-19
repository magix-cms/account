{extends file="layout.tpl"}
{block name='head:title'}{#account_plugin#}{/block}
{block name='body:id'}account{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_account#|ucfirst}: {$account|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_account#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_account#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">{#account_plugin#}</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
        <div class="panels">
            <section class="panel">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header panel-nav">
                    <h2 class="panel-heading h5">{#account_management#|ucfirst}</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#accounts#}</a></li>
                        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">{#config#}</a></li>
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="general">
                            {include file="section/form/table-form-2.tpl" idcolumn='id_account' data=$accounts activation=true sortable=false controller="account"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="config">
                            {include file="forms/config.tpl"}
                        </div>
                    </div>
                </div>
            </section>
        </div>
        {include file="modal/delete.tpl" data_type='address' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_account_message#}}
        {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}
{extends file="layout.tpl"}
{block name='head:title'}{#account_plugin#}{/block}
{block name='body:id'}account{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_account#|ucfirst}: {$accounts|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_account#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_account#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des profils">{#account_plugin#}</a></h1>
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
                        <div class="mc-message">{if $message}{$message}{/if}</div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="general">
                            {include file="section/form/table-form-3.tpl" idcolumn='id_account' data=$accounts activation=true sortable=false controller="account" change_offset=true}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="config">
                            {include file="form/config.tpl"}
                        </div>
                    </div>
                </div>
            </section>
        </div>
        {include file="modal/delete.tpl" data_type='address' title={#modal_delete_title#|ucfirst} info_text=true}
        {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
        plugins/account/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function() {
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            var offset = "{if isset($offset)}{$offset}{else}null{/if}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller,offset);
            }
            if (typeof account == "undefined") {
                console.log("account is not defined");
            } else
            {
                account.run();
            }
        });
    </script>
{/block}
{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>#seo_account_title#]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>#seo_account_desc#]}{/block}
{block name='body:id'}account-admin{/block}
{block name='body:class'}account-admin{/block}
{block name="styleSheet"}
    {$css_files = [
    "account-admin"
    ]}
{/block}

{block name="article:content"}
    <header>
        <h1 class="text-center">{#my_account#}</h1>
        {*<div class="profile">
            <div class="picture">
                <div class="user-img">
                    {if $account.img}
                        {include file="img/img.tpl" img=$account.img lazy=false size='small'}
                    {else}
                        <span class="ico ico-person-circle-outline"></span>
                    {/if}
                    <a href="{$url}/{$lang}/account/{$hash}/infos/" title="Paramètres">
                        <span class="lnr ico ico-settings-outline"></span>
                    </a>
                </div>
            </div>
            <p class="account-info">
                <span class="account-name">{$account.pseudo_ac}</span>
            </p>
        </div>*}
    </header>
    <nav>
        <ul class="list-grid">
            <li>
                <a class="btn btn-block btn-default" href="{$url}/{$lang}/account/{$hash}/infos/" title="">
                    <span class="lnr ico ico-cog"></span>
                    <span class="tile-name">{#account_config#}</span>
                </a>
            </li>
            <li>
                <a class="btn btn-block btn-default" href="{$url}/{$lang}/catalog/" title="">
                    <span class="lnr ico ico-folder-search"></span>
                    <span class="tile-name">{#catalog#}</span>
                </a>
            </li>
            {foreach $tabs as $k => $tab}
                <li>
                    <a class="btn btn-block btn-default" href="/{$lang}/{$tab.url}" title="{$tab.title}">
                        {if $tab.icon}<span class="{$tab.icon}"></span>{/if}
                        <span class="tile-name">{$tab.label}</span>
                    </a>
                </li>
            {/foreach}
            {*<li>
                <a class="btn btn-block btn-default" href="{$url}/{$lang}/account/{$hash}/config/" title="">
                    <span class="lnr ico ico-settings-outline"></span>
                    <span class="tile-name">Paramètres</span>
                </a>
            </li>*}

        </ul>
    </nav>
    <div class="text-center">
        <form action="{$url}/{$lang}/account/logout/" method="post">
            <button type="submit" class="btn btn-lg btn-link logout"><span class="lnr ico ico-power-switch"></span>{#logout#}</button>
            <input type="hidden" name="currentpage" value="{$smarty.server.REQUEST_URI}">
        </form>
    </div>
{/block}
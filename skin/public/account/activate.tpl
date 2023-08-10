{extends file="layout.tpl"}
{block name="title"}{#seo_activate_title#|sprintf:$companyData.name}{/block}
{block name="description"}{#seo_activate_desc#|sprintf:$companyData.name}{/block}
{block name='body:id'}account-activated{/block}
{*block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/account-activate{if $setting.mode !== 'dev'}.min{/if}.css"
    ]}
{/block*}

{block name="main"}
<main id="content">
    {block name="article:before"}{/block}

    {block name='article'}
    <article id="article" class="container">
        {block name='article:content'}
            <div class="row row-center">
                <div class="col-12 col-sm-6 col-xl-4">
                    <h1 class="h3 text-center">{#activate_h1#}</h1>
                    <p class="alert alert-success"><span class="fa fa-check"></span> {#activate_msg#}</p>
                    <p class="lead text-center">{#activate_connect#|sprintf:$companyData.name}</p>
                    <p>
{*                        <button class="btn btn-block btn-sd" data-toggle="collapse" data-target="#user-panel">{#login_title#}</button>*}
                        <button type="button" class="btn btn-block btn-main user-menu-btn panelBack" data-toggle="collapse" data-target="#user-panel">
                            <span class="button-label">{#login_title#}</span>
                        </button>
                    </p>
                </div>
            </div>
        {/block}
    </article>
    {/block}

    {block name="article:after"}{/block}
</main>
{/block}
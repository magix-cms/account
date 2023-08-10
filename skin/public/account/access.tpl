{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>#seo_login_title#]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>#seo_login_desc#]}{/block}
{block name='body:id'}account-login{/block}
{block name="styleSheet"}
    {$css_files = [
    "account-login",
    "account-signup",
    "form"
    ]}
{/block}

{block name="main"}
    <main id="content">
        {block name="article:before"}{/block}

        {block name='article'}
        <article id="article" class="container">
            <h1 class="h3">{#access_root_h1#}</h1>
            {block name="article:content"}
            <div class="row row-center">
                <div id="login-box" class="col-12 col-sm-6 col-lg-4">
                    <div class="content-box">
                        <h2 class="h4">{#login_root_h1#}</h2>
                        {include file="account/form/login.tpl" formclass="validate_form nice-form refresh_form" section="access"}
                        {*<div class="row">
                            <p class="col-12 col-sm-6"><a class="topwd" data-toggle="collapse" href="#user-panel">{#forget_password#}</a></p>
                        </div>*}
                    </div>
                </div>
                <div id="signup-box" class="col-12 col-sm-6 col-lg-4">
                    <div class="content-box">
                        <h2 class="h4">{#signup_root_h1#}</h2>
                        {include file="account/form/signup.tpl" formclass="validate_form nice-form static_feedback" section="access"}
                    </div>
                </div>
            </div>
            {/block}
        </article>
        {/block}
        {*{block name="article:after"}
            {include file="account/modal/password.tpl"}
        {/block}*}
    </main>
{/block}
{block name="scripts"}
    {$jquery = true}
    {$js_files = [
    'group' => [
    'form'
    ],
    'normal' => [
    ],
    'defer' => [
    "/skin/{$theme}/js/{if $setting.mode === 'dev'}src/{/if}form{if $setting.mode !== 'dev'}.min{/if}.js",
    "/skin/{$theme}/js/vendor/localization/messages_{$lang}.js"
    ]
    ]}
    {if {$lang} !== "en"}{$js_files['defer'][] = "/libjs/vendor/localization/messages_{$lang}.js"}{/if}
{/block}
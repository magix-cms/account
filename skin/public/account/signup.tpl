{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{#seo_signup_title#|sprintf:$companyData.name}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{#seo_signup_desc#|sprintf:$companyData.name}]}{/block}
{block name='body:id'}signup{/block}
{block name="main"}
    <main id="content">
        {block name="article:before"}{/block}
        {block name='article'}
            <article id="article" class="container">
                {block name='article:content'}
                    <div class="row row-center">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="content-box">
                                <h1 class="h3 text-center">{#signup_root_h1#}</h1>
                                {include file="account/form/signup.tpl" formclass="validate_form nice-form static_feedback"}
                            </div>
                        </div>
                    </div>
                {/block}
            </article>
        {/block}

        {block name="article:after"}{/block}
    </main>
{/block}
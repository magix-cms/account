{extends file="layout.tpl"}
{block name="title"}{$proContent.seo.title}{/block}
{block name="description"}{$proContent.seo.description}{/block}
{block name='body:id'}account-public{/block}
{block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/account-public{if $setting.mode !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/favorite{if $setting.mode !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/rating{if $setting.mode !== 'dev'}.min{/if}.css"
    ]}
{/block}

{block name='article'}{country_data}
    <article class="container" itemprop="mainContentOfPage">
        {block name='article:content'}
            <header class="profile">
                <div class="picture">
                    {if $userData.img}
                        {include file="img/img.tpl" img=$userData.img lazy=true size='small'}
                    {else}
                        <span class="ico ico-person-circle-outline"></span>
                    {/if}
                </div>
                <h1>{$userData.pseudo_ac}</h1>
                <div class="ratings">
                {include file='account/brick/rating.tpl' ratingValue=4.5}
                <span>14 évaluations</span>
                </div>
            </header>
            <div class="content-nav">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#products" aria-controls="general" role="tab" data-toggle="tab">products</a>
                    </li>
                    <li role="presentation">
                        <a href="#eval" aria-controls="mail" role="tab" data-toggle="tab">eval</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="products">
                    <p class="h2">products</p>
                    <div class="grid">
                        {$products = [
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']],
                        ['name' => 'test','price' => 10.00,'resume' => 'lorem ipsum dolor si amet','url' => '/fr/catalog/1-test-cat/1-test-prod/','seo' => ['description' => 'lorem ipsum dolor si amet','title' => 'lorem ipsum dolor si amet']]
                        ]}
                        {include file="catalog/loop/product.tpl" data=$products classCol='product'}
                    </div>
                </div>
                <div class="tab-pane" id="eval">
                    <p class="h2">eval</p>
                    eval
                </div>
            </div>
        {/block}
    </article>
{/block}
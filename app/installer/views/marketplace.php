<?php $view->script('marketplace', 'installer:app/bundle/marketplace.js', ['vue', 'marked']) ?>

<div id="marketplace" v-cloak>

    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
        <div class="uk-flex uk-flex-middle uk-flex-wrap">

            <h2 class="uk-h3 uk-margin-remove">{{ title | trans }}</h2>

            <div class="uk-search uk-search-default pk-search">
                <span uk-search-icon></span>
                <input class="uk-search-input" type="search" v-model="search" debounce="300">
            </div>

        </div>
    </div>

    <marketplace :api="api" :search="search" :page="page" :type="type" :installed="installed"></marketplace>

</div>

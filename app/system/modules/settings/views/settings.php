<?php $view->script('settings', 'app/system/modules/settings/app/bundle/settings.js', ['vue']) ?>

<form id="settings" class="uk-form-horizontal" @submit.prevent="save" v-cloak>

    <div class="pk-grid-large" uk-grid>
        <div class="pk-width-sidebar">

            <div class="uk-panel">

                <ul class="uk-nav uk-nav-default pk-nav-large" ref="tab">
                    <li v-for="section in orderBy(sections, 'priority')" :key="section.name"><a><span class="uk-margin-right" :uk-icon="section.icon" ratio="1.25"></span><span class="uk-text-middle">{{ section.label | trans }}</span></a></li>
                </ul>

            </div>

        </div>
        <div class="pk-width-content">

            <ul class="uk-switcher settings-tab uk-margin" ref="content">
                <li v-for="section in orderBy(sections, 'priority')" :key="section.name">
                    <component :is="section.name" :name="get(section.name)" :settings.sync="settings"></component>
                </li>
            </ul>

        </div>
    </div>

</form>

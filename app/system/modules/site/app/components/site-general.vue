<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'General' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <v-input id="form-title" v-model="config.title" name="title" type="text" rules="required" view="class: uk-form-width-large uk-input" :message="'Site title cannot be blank.'" />
            </div>

            <div class="uk-margin">
                <label for="form-logo" class="uk-form-label">{{ 'Logo' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.view.logo" class-name="uk-form-width-large" />
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-favicon" class="uk-form-label">{{ 'Favicon' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.icons.favicon" :source.sync="config.icons.favicon" class-name="uk-form-width-large" />
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-appicon" class="uk-form-label">{{ 'Appicon' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.icons.appicon" :source.sync="config.icons.appicon" class-name="uk-form-width-large" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import SiteMixin from '../mixins/site-mixin';

export default {

    mixins: [SiteMixin],

    section: {
        label: 'General',
        icon: 'settings',
        priority: 10
    }

};

</script>

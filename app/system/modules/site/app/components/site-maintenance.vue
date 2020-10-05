<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'Maintenance' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class=" uk-form-horizontal">
            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Offline' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="config.maintenance.enabled" class="uk-checkbox" type="checkbox" value="1"> {{ 'Put the site offline and show the offline message.' | trans }}</label>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-offlinemessage" class="uk-form-label">{{ 'Message' | trans }}</label>
                <div class="uk-form-controls">
                    <textarea id="form-offlinemessage" v-model="config.maintenance.msg" class="uk-form-width-large uk-textarea" :placeholder="'We\'ll be back soon.' | trans" rows="5" />
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-logo" class="uk-form-label">{{ 'Logo' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.maintenance.logo" :source.sync="config.maintenance.logo" class-name="uk-form-width-large" />
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
        label: 'Maintenance',
        icon: 'cone',
        priority: 30
    }

};

</script>

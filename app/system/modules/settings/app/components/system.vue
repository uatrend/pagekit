<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'System' | trans }}
                </h2>
            </div>
            <div class="uk-h3 uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-storage" class="uk-form-label">{{ 'Storage' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-storage" v-model="config['system/finder'].storage" class="uk-form-width-large uk-input" type="text" placeholder="/storage">
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-fileextensions" class="uk-form-label">{{ 'File Extensions' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-fileextensions" v-model="options['system/finder']['extensions']" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="uk-form-width-large uk-input" type="text">
                <div class="uk-text-meta">
                    {{ 'Allowed file extensions for the storage upload.' | trans }}
                </div>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-user-recaptcha-enable" class="uk-form-label">{{ 'Google reCAPTCHA' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <div>
                    <label><input id="form-user-recaptcha-enable" v-model="options['system/captcha'].recaptcha_enable" class="uk-checkbox" type="checkbox"> {{ 'Enable for user registration and comments' | trans }}</label>
                </div>
                <div v-if="options['system/captcha'].recaptcha_enable" class="uk-margin-small">
                    <input id="form-user-recaptcha-sitekey" v-model="options['system/captcha'].recaptcha_sitekey" class="uk-form-width-large uk-input" :placeholder="'Site key' | trans">
                </div>
                <div v-if="options['system/captcha'].recaptcha_enable" class="uk-margin-small">
                    <input id="form-user-recaptcha-secret" v-model="options['system/captcha'].recaptcha_secret" class="uk-form-width-large uk-input" :placeholder="'Secret key' | trans">
                </div>
                <div class="uk-text-meta">
                    {{ 'Only key pairs for Google reRECAPTCHA V2 Invisible are supported.' | trans }}
                </div>
            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-form-label">{{ 'Developer' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <div class="uk-margin-small">
                    <label><input v-model="config.application.debug" class="uk-checkbox" type="checkbox" value="1"> {{ 'Enable debug mode' | trans }}</label>
                </div>
                <div class="uk-margin-small">
                    <label><input v-model="config.debug.enabled" class="uk-checkbox" type="checkbox" value="1" :disabled="!sqlite"> {{ 'Enable debug toolbar' | trans }}</label>
                </div>
                <div v-if="!sqlite" class="uk-text-meta">
                    {{ 'Please enable the SQLite database extension.' | trans }}
                </div>
                <div v-if="config.application.debug || config.debug.enabled" class="uk-text-meta">
                    {{ 'Please note that enabling debug mode or toolbar has serious security implications.' | trans }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import SettingsMixin from '../mixins/settings-mixin';

export default {

    mixins: [SettingsMixin],

    section: {
        label: 'System',
        icon: 'settings',
        priority: 10
    },

    data() {
        return window.$system;
    }

};

</script>

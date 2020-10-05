<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'Localization' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-sitelocale" class="uk-form-label">{{ 'Site Locale' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-sitelocale" v-model="option.site.locale" class="uk-form-width-large uk-select">
                    <option v-for="(lang, key) in locales" :key="key" :value="key">
                        {{ lang }}
                    </option>
                </select>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-adminlocale" class="uk-form-label">{{ 'Admin Locale' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-adminlocale" v-model="option.admin.locale" class="uk-form-width-large uk-select">
                    <option v-for="(lang, key) in locales" :key="key" :value="key">
                        {{ lang }}
                    </option>
                </select>
            </div>
        </div>

        <p v-html="$trans('Is your language not available? Please help out by translating Pagekit into your own language on %link%.', {link:link})" />
    </div>
</template>

<script>

import SettingsMixin from '../mixins/settings-mixin';

export default {

    mixins: [SettingsMixin],

    section: {
        label: 'Localization',
        icon: 'location',
        priority: 20
    },

    data() {
        return { locales: window.$system.locales };
    },

    computed: {

        link() {
            return '<a href="https://www.transifex.com/pagekit/pagekit-cms/">Transifex</a>';
        },

        option() {
            return this.options.system;
        }

    }

};

</script>

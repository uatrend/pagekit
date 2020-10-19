<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'Misc' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <h3 class="uk-h4 uk-margin-small">
            {{ 'Editor Settings' | trans }}
        </h3>

        <div class="uk-margin-small">
            <label for="form-user-editor" class="uk-form-label">{{ 'Default editor' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-user-editor" v-model="type" class="uk-select uk-form-width-large">
                    <option v-for="(e, i) in $options.editors" :key="i" :value="e.value">
                        {{ e.name }}
                    </option>
                </select>
            </div>
        </div>
        <div v-if="type === 'tinymce'" class="uk-margin-small">
            <label for="form-user-editor" class="uk-form-label">{{ 'Display mode' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <div class="uk-margin-small">
                    <label><input v-model="mode" type="radio" class="uk-radio" value=""> {{ 'Default' | trans }}</label>
                </div>
                <div class="uk-margin-small">
                    <label><input v-model="mode" type="radio" class="uk-radio" value="split"> {{ 'Spliting visual and code editor' | trans }}</label>
                </div>
                <div class="uk-inline uk-form-width-large uk-text-meta">
                    <span>{{ "By default, only one editor is displayed; in split mode, visual and code editors are displayed at the same time." | trans }}</span>
                </div>
            </div>
        </div>
        <div v-if="type === 'tinymce'" class="uk-margin-small">
            <label for="form-tinymce-uikit" class="uk-form-label">{{ 'Presets' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <div class="uk-margin-small">
                    <label><input v-model="presets.tinymce_uikit" type="checkbox" class="uk-checkbox"> {{ 'Preload UIkit framework scripts' | trans }}</label>
                </div>
                <div class="uk-margin-small">
                    <label><input v-model="presets.tinymce_body_class" type="checkbox" class="uk-checkbox"> {{ 'Add UIkit container class' | trans }}</label>
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
        label: 'Misc',
        icon: 'cog',
        priority: 100
    },

    data() {
        return _.extend({
            type: window.$pagekit.editor.editor || '',
            mode: window.$pagekit.editor.mode || '',
            presets: window.$pagekit.editor.presets || {}
        }, window.$system);
    },

    // TODO
    editors: {
        html: {
            name: 'HTML',
            value: 'html'
        },
        tinymce: {
            name: 'TinyMCE',
            value: 'tinymce',
            split: true
        },
        codemirror: {
            name: 'Codemirror',
            value: 'code'
        }
    },

    computed: {
        editor() {
            return _.find(this.$options.editors, { value: this.type });
        }
    },

    watch: {
        type(value) {
            if (!this.editor.split) {
                this.mode = '';
            }
        }
    },

    events: {
        'settings-save': function ($event, settings) {
            const option = { 'system/editor': { editor: this.type, mode: this.mode, presets: this.presets } };
            _.extend(settings.options, option);
        }
    }

};

</script>

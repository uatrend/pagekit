<template>
    <div class="uk-form-horizontal">
        <div class="uk-margin">
            <span class="uk-form-label">{{ 'Title' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <label><input v-model="options.title_hide" class="uk-checkbox" type="checkbox"> {{ 'Hide Title' | trans }}</label>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-theme-title-size" class="uk-form-label">{{ 'Title Size' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-theme-title-size" v-model="options.title_size" class="uk-form-width-large uk-select">
                    <option v-for="(title, value) in heading" :key="value" :value="value">
                        {{ title | trans }}
                    </option>
                </select>
            </div>
        </div>

        <div class="uk-margin">
            <span class="uk-form-label">{{ 'Alignment' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <label><input v-model="options.alignment" class="uk-checkbox" type="checkbox"> {{ 'Center the title and content.' | trans }}</label>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-theme-badge" class="uk-form-label">{{ 'HTML Class' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-theme-badge" v-model="options.html_class" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-theme-panel" class="uk-form-label">{{ 'Panel Style' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-theme-panel" v-model="options.panel" class="uk-form-width-large uk-select">
                    <option value="">
                        {{ 'None' | trans }}
                    </option>
                    <option value="uk-card uk-card-default uk-card-body">
                        {{ 'Card' | trans }}
                    </option>
                    <option value="uk-card uk-card-primary uk-card-body">
                        {{ 'Card Primary' | trans }}
                    </option>
                    <option value="uk-card uk-card-secondary uk-card-body">
                        {{ 'Card Secondary' | trans }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>

import WidgetMixin from '@system/modules/widget/app/mixins/widget-mixin';

const WidgetTheme = {

    mixins: [WidgetMixin],

    section: {
        label: 'Theme',
        priority: 90
    },

    data() {
        return {
            heading: {
                'uk-h1': 'H1',
                'uk-h2': 'H2',
                'uk-h3': 'H3',
                'uk-h4': 'H3',
                'uk-card-title': 'Card Title',
                'uk-heading-large': 'Extra Large'
            },
            menus: false
        };
    },

    computed: {
        options() {
            return this.widget.theme;
        }
    },

    created() {
        if (!this.options.title_size || !_.filter(this.heading, (title, value) => value === this.options.title_size).length) {
            this.options.title_size = 'uk-h3';
        }
    }
};

export default WidgetTheme;

window.Widgets.components.theme = WidgetTheme;

</script>

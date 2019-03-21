<template>
    <div class="uk-form-horizontal">
        <div class="uk-margin">
            <span class="uk-form-label">{{ 'Title' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <label><input v-model="widget.theme.title_hide" class="uk-checkbox" type="checkbox"> {{ 'Hide Title' | trans }}</label>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-theme-title-size" class="uk-form-label">{{ 'Title Size' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-theme-title-size" v-model="widget.theme.title_size" class="uk-form-width-large uk-select">
                    <option value="uk-panel-title">
                        {{ 'Default' | trans }}
                    </option>
                    <option value="uk-h1 uk-margin-remove-top">
                        {{ 'H1' | trans }}
                    </option>
                    <option value="uk-h2 uk-margin-remove-top">
                        {{ 'H2' | trans }}
                    </option>
                    <option value="uk-h3 uk-margin-remove-top">
                        {{ 'H3' | trans }}
                    </option>
                    <option value="uk-h4 uk-margin-remove-top">
                        {{ 'H4' | trans }}
                    </option>
                    <option value="uk-heading-large uk-margin-remove-top">
                        Extra Large
                    </option>
                </select>
            </div>
        </div>

        <div class="uk-margin">
            <span class="uk-form-label">{{ 'Alignment' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <label><input v-model="widget.theme.alignment" class="uk-checkbox" type="checkbox"> {{ 'Center the title and content.' | trans }}</label>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-theme-badge" class="uk-form-label">{{ 'HTML Class' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-theme-badge" v-model="widget.theme.html_class" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-theme-panel" class="uk-form-label">{{ 'Panel Style' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-theme-panel" v-model="widget.theme.panel" class="uk-form-width-large uk-select">
                    <option value="">
                        {{ 'None' | trans }}
                    </option>
                    <option value="uk-card uk-card-default">
                        {{ 'Card' | trans }}
                    </option>
                    <option value="uk-card uk-card-primary">
                        {{ 'Card Primary' | trans }}
                    </option>
                    <option value="uk-card uk-card-secondary">
                        {{ 'Card Secondary' | trans }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>

module.exports = {

    section: {
        label: 'Theme',
        priority: 90,
    },

    props: ['widget', 'config'],

};

window.Widgets.components.theme = module.exports;

</script>

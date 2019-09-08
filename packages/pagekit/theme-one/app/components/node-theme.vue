<template>
    <div class="uk-form-horizontal">
        <template v-if="node.type === 'page'">
            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Title' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="node.theme.title_hide" class="uk-checkbox" type="checkbox"> {{ 'Hide Title' | trans }}</label>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Title Size' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="node.theme.title_large" class="uk-checkbox" type="checkbox"> {{ 'Extra large title.' | trans }}</label>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Alignment' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="node.theme.alignment" class="uk-checkbox" type="checkbox"> {{ 'Center the title and content.' | trans }}</label>
                </div>
            </div>
        </template>

        <div class="uk-margin">
            <label for="form-class" class="uk-form-label">{{ 'HTML Class' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-class" v-model="node.theme.html_class" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <div class="uk-margin">
            <span class="uk-form-label">{{ 'Sidebar' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <label><input v-model="node.theme.sidebar_first" class="uk-checkbox" type="checkbox"> {{ 'Show the sidebar before the content.' | trans }}</label>
            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-form-label">Hero {{ 'Position' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <p class="uk-margin-small">
                    <label><input v-model="node.theme.hero_viewport" class="uk-checkbox" type="checkbox"><span class="uk-margin-small-left">{{ 'Full viewport height' | trans }}</span></label>
                </p>
                <p class="uk-margin-small">
                    <label><input v-model="node.theme.hero_contrast" class="uk-checkbox" type="checkbox"><span class="uk-margin-small-left">{{ 'Invert colors' | trans }}</span></label>
                </p>
                <p class="uk-margin-small">
                    <label><input v-model="node.theme.navbar_transparent" class="uk-checkbox" type="checkbox"><span class="uk-margin-small-left">{{ 'Transparent navbar as overlay' | trans }}</span></label>
                </p>
                <p class="uk-margin-small uk-margin-bottom">
                    <label><input v-model="node.theme.hero_parallax" class="uk-checkbox" type="checkbox"><span class="uk-margin-small-left">{{ 'Enable parallax effect' | trans }}</span></label>
                </p>
                <div class="uk-form-width-large">
                    <input-image v-model="node.theme.hero_image" input-class="uk-form-width-large" />
                </div>
                <div class="uk-text-meta">
                    {{ 'Select a background image for the hero position.' | trans }}
                </div>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-top-style" class="uk-form-label">Top {{ 'Position' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-top-style" v-model="node.theme.top_style" class="uk-form-width-large uk-select">
                    <option value="uk-section-default">
                        {{ 'Default' | trans }}
                    </option>
                    <option value="uk-section-muted">
                        {{ 'Muted' | trans }}
                    </option>
                </select>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-main-style" class="uk-form-label">Main {{ 'Position' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-main-style" v-model="node.theme.main_style" class="uk-form-width-large uk-select">
                    <option value="uk-section-default">
                        {{ 'Default' | trans }}
                    </option>
                    <option value="uk-section-muted">
                        {{ 'Muted' | trans }}
                    </option>
                </select>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-bottom-style" class="uk-form-label">Bottom {{ 'Position' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-bottom-style" v-model="node.theme.bottom_style" class="uk-form-width-large uk-select">
                    <option value="uk-section-default">
                        {{ 'Default' | trans }}
                    </option>
                    <option value="uk-section-muted">
                        {{ 'Muted' | trans }}
                    </option>
                </select>
                <div class="uk-text-meta uk-margin">
                    {{ 'Note: Position settings only work if you publish a widget in that position.' | trans }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>

var NodeTheme = {

    section: {
        label: 'Theme',
        priority: 90,
    },

    props: ['node'],

};

export default NodeTheme;

window.Site.components['node-theme'] = NodeTheme;

</script>

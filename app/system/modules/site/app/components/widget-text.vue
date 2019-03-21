<template>
    <div class="pk-grid-large pk-width-sidebar-large" uk-grid>
        <div class="pk-width-content uk-form-stacked">
            <div class="uk-margin">
                <input
                    v-model="widget.title"
                    v-validate="'required'"
                    class="uk-width-1-1 uk-form-large uk-input"
                    type="text"
                    name="title"
                    :placeholder="'Enter Title' | trans"
                >
                <div v-show="errors.first('title')" class="uk-text-meta uk-text-danger">
                    {{ 'Title cannot be blank.' | trans }}
                </div>
            </div>

            <div class="uk-margin">
                <v-editor v-model="widget.data.content" :value.sync="widget.data.content" :options="{markdown : widget.data.markdown}" mode="combine" />
                <p class="uk-margin-small-top">
                    <label><input v-model="widget.data.markdown" class="uk-checkbox" type="checkbox"> {{ 'Enable Markdown' | trans }}</label>
                </p>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <component :is="'template-settings'" :widget.sync="widget" :config.sync="config" :form="form" />
        </div>
    </div>
</template>

<script>

module.exports = {

    name: 'widget-text',

    section: {
        label: 'Settings',
    },

    inject: ['$validator'],

    props: ['widget', 'config', 'form'],

    created() {
        // this.$options.partials = this.$parent.$options.partials;
        this.$options.components['template-settings'] = this.$parent.$options.components['template-settings'];
    },

};

window.Widgets.components['system-text--settings'] = module.exports;

</script>

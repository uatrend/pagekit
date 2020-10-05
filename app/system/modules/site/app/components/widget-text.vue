<template>
    <div class="pk-grid-large pk-width-sidebar-large" uk-grid>
        <div class="pk-width-content uk-form-stacked">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <v-input id="form-title" v-model="widget.title" type="text" name="title" placeholder="Enter Title" view="class: uk-width-1-1 uk-form-large uk-input" rules="required" message="Title cannot be blank." />
            </div>

            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Content' | trans }}</label>
                <v-editor v-model="widget.data.content" :options="{markdown : widget.data.markdown}" />
                <p class="uk-margin-small-top">
                    <label><input v-model="widget.data.markdown" class="uk-checkbox" type="checkbox"> {{ 'Enable Markdown' | trans }}</label>
                </p>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <component :is="'template-settings'" v-model="widget" :config="config" />
        </div>
    </div>
</template>

<script>

const WidgetText = {

    name: 'widget-text',

    section: { label: 'Settings' },

    inject: ['$components'],

    props: ['config', 'value'],

    data() {
        return {
            widget: this.value
        };
    },

    watch: {

        value(widget) {
            this.widget = widget;
        },

        widget(widget) {
            this.$emit('input', widget);
        }
    },

    created() {
        _.extend(this.$options.components, this.$components);
    }

};

export default WidgetText;

window.Widgets.components['system-text.settings'] = WidgetText;

</script>

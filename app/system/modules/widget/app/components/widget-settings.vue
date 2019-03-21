<template>
    <div class="uk-grid pk-grid-large pk-width-sidebar-large">
        <div class="pk-width-content uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <div class="uk-form-controls">
                    <input
                        id="form-title"
                        v-model="widget.title"
                        v-validate="'required'"
                        class="uk-input uk-form-width-large"
                        type="text"
                        name="title"
                    >
                    <div v-show="errors.first('title')" class="uk-text-meta uk-text-danger">
                        {{ 'Title cannot be blank.' | trans }}
                    </div>
                </div>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <component :is="'template-settings'" :widget.sync="widget" :config.sync="config" :form="form" />
        </div>
    </div>
</template>

<script>

module.exports = {

    section: {
        label: 'Settings',
    },

    inject: ['$validator'],

    props: ['widget', 'form', 'config'],

    created() {
        this.$options.components['template-settings'] = this.$parent.$options.components['template-settings'];
    },

};

</script>

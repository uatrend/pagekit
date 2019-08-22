<template>
    <div class="pk-grid-large pk-width-sidebar-large" uk-grid>
        <div class="pk-width-content uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <div class="uk-form-controls">
                    <input
                        v-model="widget.title"
                        v-validate="'required'"
                        class="uk-form-width-large uk-input"
                        type="text"
                        name="title"
                        :placeholder="'Enter Title' | trans"
                    >
                    <div v-show="errors.first('title')" class="uk-text-meta uk-text-danger">
                        {{ 'Title cannot be blank.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Login Redirect' | trans }}</label>
                <div class="uk-form-controls wp-form-width-max-large">
                    <input-link v-model="widget.data.redirect_login" cls="uk-form-width-large"></input-link>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Logout Redirect' | trans }}</label>
                <div class="uk-form-controls wp-form-width-max-large">
                    <input-link v-model="widget.data.redirect_logout" cls="uk-form-width-large"></input-link>
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

    replace: false,

    inject: ['$validator'],

    props: ['widget', 'config', 'form'],

    created() {
        this.$options.components['template-settings'] = this.$parent.$options.components['template-settings'];
    },

};

window.Widgets.components['system-login--settings'] = module.exports;

</script>

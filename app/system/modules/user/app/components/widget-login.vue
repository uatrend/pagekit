<template>
    <div class="pk-grid-large pk-width-sidebar-large" uk-grid>
        <div class="pk-width-content uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <v-input id="form-title" v-model="widget.title" type="text" name="title" placeholder="Enter Title" view="class: uk-form-width-large uk-input" rules="required" message="Title cannot be blank." />
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Login Redirect' | trans }}</label>
                <div class="uk-form-controls wp-form-width-max-large">
                    <input-link v-model="widget.data.redirect_login" class-name="uk-form-width-large" />
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Logout Redirect' | trans }}</label>
                <div class="uk-form-controls wp-form-width-max-large">
                    <input-link v-model="widget.data.redirect_logout" class-name="uk-form-width-large" />
                </div>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <component :is="'template-settings'" v-model="widget" :config.sync="config" />
        </div>
    </div>
</template>

<script>

import WidgetMixin from '@system/modules/widget/app/mixins/widget-mixin';

const WidgetLogin = {

    mixins: [WidgetMixin],

    section: { label: 'Settings' },

    replace: false
};

export default WidgetLogin;

window.Widgets.components['system-login.settings'] = WidgetLogin;

</script>

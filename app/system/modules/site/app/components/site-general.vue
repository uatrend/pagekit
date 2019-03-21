<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-margin-remove">
                    {{ 'General' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <div class="uk-form-controls">
                    <input
                        id="form-title"
                        v-model="config.title"
                        v-validate="'required'"
                        class="uk-form-width-large uk-input"
                        name="title"
                        type="text"
                    >
                    <div v-show="errors.first('title')" class="uk-text-meta uk-text-danger">
                        {{ 'Site title cannot be blank.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-logo" class="uk-form-label">{{ 'Logo' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.view.logo" :source.sync="config.view.logo" input-class="uk-form-width-large" />
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-favicon" class="uk-form-label">{{ 'Favicon' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.icons.favicon" :source.sync="config.icons.favicon" input-class="uk-form-width-large" />
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-appicon" class="uk-form-label">{{ 'Appicon' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.icons.appicon" :source.sync="config.icons.appicon" input-class="uk-form-width-large" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>

module.exports = {

    section: {
        label: 'General',
        icon: 'pk-icon-large-settings',
        priority: 10,
    },

    inject: ['$validator'],

    props: ['config'],

};

</script>

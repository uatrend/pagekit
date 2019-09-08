<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'Theme' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-form uk-form-horizontal">
            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Logo Contrast' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.logo_contrast" input-class="uk-form-width-large" />
                    <div class="uk-text-meta">
                        {{ 'Select an alternative logo which looks great on images.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Logo Off-canvas' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.logo_offcanvas" input-class="uk-form-width-large" />
                    <div class="uk-text-meta">
                        {{ 'Select an optional logo for the off-canvas menu.' | trans }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

var SiteTheme = {

    section: {
        label: 'Theme',
        icon: 'pk-icon-large-brush',
        priority: 15,
    },

    data() {
        return _.extend({ config: {} }, window.$theme);
    },

    events: {

        'save:settings': function() {
            this.$http.post('admin/system/settings/config', { name: this.name, config: this.config }).catch(function (res) {
                this.$notify(res.data, 'danger');
            });
        },

    },

};

export default SiteTheme;

window.Site.components['site-theme'] = SiteTheme;

</script>

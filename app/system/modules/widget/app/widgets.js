const TemplateSettings = require('./templates/widget-settings.html');

window.Widgets = module.exports = {

    data() {
        return {
            widgets: [],
        };
    },

    created() {
        this.resource = this.$resource('api/site/widget{/id}');
    },

    components: {

        settings: require('./components/widget-settings.vue').default,
        visibility: require('./components/widget-visibility.vue').default,
        'template-settings': {
            props: ['widget', 'form', 'config'],
            template: TemplateSettings,
        },

    },

};

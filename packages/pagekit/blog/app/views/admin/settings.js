var Settings = {

    el: '#settings',

    mixins: [Theme.Mixins.Helper],

    data() {
        return window.$data;
    },

    theme: {
        hiddenHtmlElements: ['.pk-width-content li > div.uk-flex'],
        elements() {
            var vm = this;
            return {
                'submit': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button uk-button-primary',
                    on: {click: () => vm.save()},
                    priority: 0,
                }
            }
        }
    },

    methods: {

        save() {
            this.$http.post('admin/system/settings/config', { name: 'blog', config: this.config }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

    },

};

export default Settings;

Vue.ready(Settings);

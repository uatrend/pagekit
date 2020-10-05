const Settings = {

    el: '#settings',

    mixins: [Theme.Mixins.Helper],

    data: window.$data,

    theme: {
        hideEls: ['#settings > div:first-child'],
        elements() {
            const vm = this;
            return {
                save: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button uk-button-primary',
                    on: { click: () => vm.save() }
                }
            };
        }
    },

    methods: {

        save() {
            this.$http.post('admin/system/settings/config', { name: 'system/user', config: this.config }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        }

    }

};

export default Settings;

Vue.ready(Settings);

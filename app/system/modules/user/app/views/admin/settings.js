module.exports = {

    el: '#settings',

    mixins: [Theme.Mixins.Helper],

    data: window.$data,

    theme:{
        hiddenHtmlElements: ['#settings > div:first-child'],
        elements() {
            var vm = this;
            return {
                save: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button uk-button-primary',
                    on: {click: () => vm.save()}
                }
            }
        }
    },

    methods: {

        save() {
            this.$http.post('admin/system/settings/config', { name: 'system/user', config: this.config }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

    },

};

Vue.ready(module.exports);

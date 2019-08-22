module.exports = {

    name: 'marketplace',

    el: '#marketplace',

    mixins: [Theme.Mixins.Helper],

    data() {
        return _.extend({
            search: this.$session.get('marketplace.search', ''),
        }, window.$data);
    },

    theme: {
        hiddenHtmlElements: ['#marketplace > div:first-child'],
        elements() {
            var vm = this;
            return {
                search: {
                    scope: 'navbar-right',
                    type: 'search',
                    class: 'uk-text-small',
                    domProps: {
                        value: () => vm.search || ''
                    },
                    on: {
                        input: function(e) {
                            !vm.search && vm.$set(vm, 'search', '');
                            vm.search = e.target.value
                        }
                    }
                }
            }
        }
    },

    watch: {

        search(search) {
            this.$session.set('marketplace.search', search);
        },

    },

    components: {
        marketplace: require('../components/marketplace.vue').default,
    },

};

Vue.ready(module.exports);

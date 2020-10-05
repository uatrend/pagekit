import MarketplaceComponent from '../components/marketplace.vue';

const Marketplace = {

    name: 'marketplace',

    el: '#marketplace',

    mixins: [Theme.Mixins.Helper],

    data() {
        return _.extend({ search: this.$session.get('marketplace.search', '') }, window.$data);
    },

    theme: {
        hideEls: ['#marketplace > div:first-child'],
        elements() {
            const vm = this;
            return {
                search: {
                    scope: 'navbar-right',
                    type: 'search',
                    class: 'uk-text-small',
                    domProps: { value: () => vm.search || '' },
                    on: {
                        input(e) {
                            !vm.search && vm.$set(vm, 'search', '');
                            vm.search = e.target.value;
                        }
                    }
                }
            };
        }
    },

    watch: {

        search(search) {
            this.$session.set('marketplace.search', search);
        }

    },

    components: { marketplace: MarketplaceComponent }

};

export default Marketplace;

Vue.ready(Marketplace);

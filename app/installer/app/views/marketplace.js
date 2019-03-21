module.exports = {

    name: 'marketplace',

    el: '#marketplace',

    data() {
        return _.extend({
            search: this.$session.get('marketplace.search', ''),
        }, window.$data);
    },

    mounted() {
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

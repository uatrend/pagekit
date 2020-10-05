export default {

    name: 'Menu',

    mixins: [Theme.Mixins.Helper, Theme.Mixins.Components],

    type: 'theme-menu',

    data() {
        return _.extend({
            nav: null,
            item: null,
            subnav: null,
            size: null,
            // Added
            sb: this.$session.get('admin.sidebar', false),
            breakpoint: false
        }, window.$pagekit);
    },

    created() {
        const menu = _(this.menu).sortBy('priority').groupBy('parent').value();
        const item = _.find(menu.root, 'active');

        this.nav = menu.root;

        if (item) {
            this.item = item;
            this.subnav = menu[item.id];
        }

        window.addEventListener('resize', this.checkmedia);

        this.checkmedia();
    },

    methods: {
        checkmedia() {
            this.breakpoint = !!window.matchMedia('(min-width: 1200px)').matches;
        },
        isActiveParent(item) {
            const menu = _(this.menu).sortBy('priority').groupBy('parent').value();
            return !!((typeof menu[item.id] != 'undefined' && item.active));
        },
        getChildren(item) {
            const menu = _(this.menu).sortBy('priority').groupBy('parent').value();
            return typeof menu[item.id] != 'undefined' ? menu[item.id] : [];
        },
        getActive(menu) {
            const active = _.filter(menu, (item) => item.active)[0];
            return active && active.label;
        }
    }

};

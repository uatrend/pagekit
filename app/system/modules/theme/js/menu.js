import {
    $, on, css, addClass, removeClass, hasClass,
} from 'uikit-util';

export default {

    name: 'Menu',

    data() {
        return _.extend({
            nav: null,
            item: null,
            subnav: null,
            size: null,
            sb: this.$session.get('admin.sidebar', false),
        }, window.$pagekit);
    },

    beforeCreate() {},

    created() {
        const menu = _(this.menu).sortBy('priority').groupBy('parent').value();
        const item = _.find(menu.root, 'active');
        const self = this;

        this.nav = menu.root;

        if (item) {
            this.item = item;
            this.subnav = menu[item.id];
        }

        this.size = this.sbSize(menu);

        if (this.sb) {
            css($('.tm-body-wrapper'), 'marginLeft', this.size);
            addClass($('body'), 'sidebar-expanded');
        }
    },

    mounted() {
        removeClass($('body'), 'preload');
    },

    methods: {

        toggleSidebar() {
            this.sb = !this.sb;
            this.setStyle();
            this.$session.set('admin.sidebar', this.sb);
        },

        getChild(item, check) {
            const menu = _(this.menu).sortBy('priority').groupBy('parent').value();
            return check ? !!menu[item] : menu[item];
        },

        style(el) {
            let style = {};

            if (this.sb && el == 'sidebar') {
                style = { width: this.size };
            } else if (this.sb && el != 'sidebar') {
                style = { 'margin-left': this.size };
            }

            return style;
        },

        setStyle() {
            const body = $('body');
            const wrapper = $('.tm-body-wrapper');

            if (!hasClass(wrapper, 'transition')) {
                addClass(wrapper, 'transition');
            }

            if (this.sb) {
                addClass(body, 'sidebar-expanded');
                css(wrapper, 'margin-left', this.size);
            } else {
                removeClass(body, 'sidebar-expanded');
                css(wrapper, 'margin-left', '');
            }
        },

        sbSize(menu) {
            const rightSpace = 35;
            let maxWidth = 0;
            let sizes = Object.assign({}, menu.root);
            const selector = $('#js-appnav>li>a>span');
            const toggle = $('.tm-toggle-sidebar');
            const toggleSpan = $('.tm-toggle-sidebar>a>span');
            const fonts = [css(toggleSpan, 'font'), css(selector, 'font')];
            const toggleTextSize = this.txtSize(toggleSpan.textContent, fonts[0]);

            sizes = _.map(sizes, item => this.txtSize(this.$trans(item.label), fonts[1]));
            sizes.push(toggleTextSize);
            maxWidth = Math.max.apply(null, sizes);

            return `${maxWidth
                    + parseInt(css(toggle, 'paddingLeft'))
                    + parseInt(css($('i', toggle), 'width'))
                    + parseInt(css($('span', toggle), 'marginLeft'))
                    + rightSpace}px`;
        },

        txtSize(txt, font) {
            const element = document.createElement('canvas');
            const context = element.getContext('2d');

            context.font = font;
            return context.measureText(txt).width;
        },

    },

};

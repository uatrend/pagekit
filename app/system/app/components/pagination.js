module.exports = {

    template: '<ul class="uk-pagination uk-flex-center"></ul>',

    props: {
        current: {
            default: 0,
            type: Number,
        },

        pages: {
            default: 1,
            type: Number,
        },

        replaceState: {
            type: Boolean,
            default: true,
        },
    },

    data() {
        return {
            page: this.current,
        };
    },

    created() {
        this.key = `${this.$parent.$options.name}.pagination`;

        if (this.page === null && this.$session.get(this.key)) {
            this.$set(this, 'page', this.$session.get(this.key));
        }

        if (this.replaceState) {
            this.$state('page', this.page);
        }
    },

    mounted() {
        const vm = this;

        this.pagination = UIkit.pagination(this.$el, { pages: this.pages, currentPage: this.page || 0 });
        UIkit.util.on(this.pagination.$el, 'select.uk.pagination', (e, pagination, page) => {
            vm.$emit('input', Number(page));
            // vm.$set(vm, 'page', page);
        });
    },

    watch: {

        current(page) {
            this.$set(this, 'page', page);
        },

        page(page) {
            this.pagination.selectPage(page || 0);
            this.$session.set(this.key, page || 0);
        },

        pages(pages) {
            if (!this.pages) this.page = 0;
            this.pagination.render(pages);
        },

    },

};

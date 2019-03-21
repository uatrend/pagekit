module.exports = {

    name: 'post',

    el: '#post',

    data() {
        return _.merge({
            posts: false,
            config: {
                filter: this.$session.get('posts.filter', { order: 'date desc', limit: 10 }),
            },
            pages: 0,
            count: '',
            selected: [],
            canEditAll: false,
        }, window.$data);
    },

    mounted() {
        this.resource = this.$resource('api/blog/post{/id}');
        this.$watch('config.page', this.load, { immediate: true });
    },

    watch: {

        'config.filter': {
            handler(filter) {
                if (this.config.page) {
                    this.config.page = 0;
                } else {
                    this.load();
                }

                this.$session.set('posts.filter', filter);
            },
            deep: true,
        },

        // 'config.page': function(page) {
        //     this.load()
        // }

    },

    computed: {

        statusOptions() {
            const options = _.map(this.$data.statuses, (status, id) => ({ text: status, value: id }));

            return [{ label: this.$trans('Filter by'), options }];
        },

        users() {
            const options = _.map(this.$data.authors, author => ({ text: author.username, value: author.user_id }));

            return [{ label: this.$trans('Filter by'), options }];
        },
    },

    methods: {

        active(post) {
            return this.selected.indexOf(post.id) != -1;
        },

        save(post) {
            this.resource.save({ id: post.id }, { post }).then(function () {
                this.load();
                this.$notify('Post saved.');
            });
        },

        status(status) {
            const posts = this.getSelected();

            posts.forEach((post) => {
                post.status = status;
            });

            this.resource.save({ id: 'bulk' }, { posts }).then(function () {
                this.load();
                this.$notify('Posts saved.');
            });
        },

        remove() {
            this.resource.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Posts deleted.');
            });
        },

        toggleStatus(post) {
            post.status = post.status === 2 ? 3 : 2;
            this.save(post);
        },

        copy() {
            if (!this.selected.length) {
                return;
            }

            this.resource.save({ id: 'copy' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Posts copied.');
            });
        },

        load() {
            this.resource.query({ filter: this.config.filter, page: this.config.page }).then(function (res) {
                const { data } = res;

                this.$set(this, 'posts', data.posts);
                this.$set(this, 'pages', data.pages);
                this.$set(this, 'count', data.count);
                this.$set(this, 'selected', []);
            });
        },

        getSelected() {
            return this.posts.filter(function (post) { return this.selected.indexOf(post.id) !== -1; }, this);
        },

        getStatusText(post) {
            return this.statuses[post.status];
        },

    },

};

Vue.ready(module.exports);

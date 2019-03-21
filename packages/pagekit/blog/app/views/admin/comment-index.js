module.exports = {

    name: 'comment',

    el: '#comments',

    mixins: [Vue2Filters.mixin],

    data() {
        return _.merge({
            posts: [],
            config: {
                filter: this.$session.get('comments.filter', {}),
            },
            comments: false,
            pages: 0,
            count: '',
            selected: [],
            user: window.$pagekit.user,
            replyComment: {},
            editComment: {},
        }, window.$data);
    },

    mounted() {
        this.Comments = this.$resource('api/blog/comment{/id}');
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

                this.$session.set('comments.filter', filter);
            },
            deep: true,
        },

    },

    computed: {

        statusOptions() {
            const options = _.map(this.$data.statuses, (status, id) => ({ text: status, value: id }));

            return [{ label: this.$trans('Filter by'), options }];
        },

    },

    methods: {

        active(comment) {
            return this.selected.indexOf(comment.id) != -1;
        },

        submit() {
            const vm = this;
            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.save(vm.editComment.id ? vm.editComment : vm.replyComment);
                }
            });
        },

        save(comment) {
            return this.Comments.save({ id: comment.id }, { comment }).then(function () {
                this.load();
                this.$notify('Comment saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

        status(status) {
            const comments = this.getSelected();

            comments.forEach((comment) => {
                comment.status = status;
            });

            this.Comments.save({ id: 'bulk' }, { comments }).then(function () {
                this.load();
                this.$notify('Comments saved.');
            });
        },

        remove() {
            this.Comments.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Comments deleted.');
            });
        },

        load() {
            this.cancel();

            this.Comments.query({
                filter: this.config.filter, post: this.config.post && this.config.post.id || 0, page: this.config.page, limit: this.config.limit,
            }).then(function (res) {
                const { data } = res;

                this.$set(this, 'posts', data.posts);
                this.$set(this, 'comments', data.comments);
                this.$set(this, 'pages', data.pages);
                this.$set(this, 'count', data.count);
                this.$set(this, 'selected', []);
            });
        },

        getSelected() {
            const vm = this;
            return this.comments.filter(comment => vm.selected.indexOf(comment.id) !== -1);
        },

        getStatusText(comment) {
            return this.statuses[comment.status];
        },

        cancel() {
            this.$set(this, 'replyComment', {});
            this.$set(this, 'editComment', {});
        },

        reply(comment) {
            this.cancel();
            this.$set(this, 'replyComment', {
                parent_id: comment.id, post_id: comment.post_id, author: this.user.name, email: this.user.email,
            });
        },

        edit(comment) {
            this.cancel();
            this.$set(this, 'editComment', _.merge({}, comment));
        },

        toggleStatus(comment) {
            comment.status = comment.status === 1 ? 0 : 1;
            this.save(comment);
        },

    },

};

Vue.ready(module.exports);

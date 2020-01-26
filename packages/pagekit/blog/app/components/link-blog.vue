<template>
    <div class="uk-margin">
        <label class="uk-form-label">{{ 'View' | trans }}</label>
        <div class="uk-form-controls">
            <select v-model="post" class="uk-width-1-1 uk-select">
                <option value="@blog">
                    {{ 'Posts View' | trans }}
                </option>
                <optgroup :label="'Posts' | trans">
                    <option v-for="p in posts" :value="p | link">
                        {{ p.title }}
                    </option>
                </optgroup>
            </select>
        </div>
    </div>
</template>

<script>

var LinkBlog = {

    link: {
        label: 'Blog',
    },

    props: ['link'],

    data() {
        return {
            posts: [],
            post: '',
        };
    },

    created() {
        // TODO: Implement pagination or search
        this.$http.get('api/blog/post', { params: { filter: { limit: 1000 } } }).then(function (res) {
            this.$set(this, 'posts', res.data.posts);
        });
    },

    mounted() {
        this.post = '@blog';
    },

    watch: {

        post(post) {
            this.$parent.link = post;
        },

    },

    filters: {

        link(post) {
            return `@blog/id?id=${post.id}`;
        },

    },

};

export default LinkBlog;

window.Links.default.components['link-blog'] = LinkBlog;

</script>

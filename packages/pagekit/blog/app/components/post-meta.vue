<template>
    <div class="uk-form-horizontal">
        <div class="uk-margin">
            <label for="form-meta-title" class="uk-form-label">{{ 'Title' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-meta-title" v-model="post.data.meta['og:title']" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-meta-description" class="uk-form-label">{{ 'Description' | trans }}</label>
            <div class="uk-form-controls">
                <textarea id="form-meta-description" v-model="post.data.meta['og:description']" class="uk-form-width-large uk-textarea" rows="5" type="text" />
            </div>
        </div>
    </div>
</template>

<script>

import PostMixin from '../mixins/post-mixin';

const PostMeta = {

    mixins: [PostMixin],

    section: {
        label: 'Meta',
        priority: 100
    }

};

export default PostMeta;

window.Post.components['post-meta'] = PostMeta;

</script>

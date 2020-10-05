<template>
    <div class="pk-grid-large pk-width-sidebar-large uk-form-stacked" uk-grid>
        <div class="pk-width-content">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <v-input id="form-title" v-model="post.title" name="title" type="text" placeholder="Enter Title" view="class: uk-form-large uk-width-1-1 uk-input" rules="required" message="Title cannot be blank." />
            </div>
            <div class="uk-margin">
                <label for="form-post-content" class="uk-form-label">{{ 'Content' | trans }}</label>
                <div class="uk-form-controls">
                    <v-editor id="post-content" v-model="post.content" :options="{markdown : post.data.markdown}" />
                </div>
            </div>
            <div class="uk-margin">
                <label for="form-post-excerpt" class="uk-form-label">{{ 'Excerpt' | trans }}</label>
                <div class="uk-form-controls">
                    <v-editor id="post-excerpt" v-model="post.excerpt" :options="{markdown: post.data.markdown, height: 150, scripts: false}" />
                </div>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <div class="uk-panel">
                <div class="uk-margin">
                    <label for="form-image" class="uk-form-label">{{ 'Image' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-image-meta v-model="post.data.image" class-name="uk-form-width-large" />
                    </div>
                </div>

                <div class="uk-margin">
                    <label for="form-slug" class="uk-form-label">{{ 'Slug' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-slug" v-model="post.slug" class="uk-input uk-form-width-large" type="text">
                    </div>
                </div>
                <div class="uk-margin">
                    <label for="form-status" class="uk-form-label">{{ 'Status' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-status" v-model="post.status" class="uk-select uk-form-width-large">
                            <option v-for="(status, id) in data.statuses" :key="id" :value="id">
                                {{ status }}
                            </option>
                        </select>
                    </div>
                </div>
                <div v-if="data.canEditAll" class="uk-margin">
                    <label for="form-author" class="uk-form-label">{{ 'Author' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-author" v-model="post.user_id" class="uk-select uk-form-width-large">
                            <option v-for="author in data.authors" :key="author.id" :value="author.id">
                                {{ author.username }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label">{{ 'Publish on' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-date v-model="post.date" />
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">{{ 'Restrict Access' | trans }}</label>
                    <div class="uk-form-controls uk-form-controls-text">
                        <p v-for="role in data.roles" :key="role.id" class="uk-margin-small">
                            <label><input v-model="post.roles" class="uk-checkbox" type="checkbox" :value="role.id" number> {{ role.name }}</label>
                        </p>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label">{{ 'Options' | trans }}</label>
                    <div class="uk-form-controls uk-form-controls-text">
                        <p class="uk-margin-small">
                            <label><input v-model="post.data.markdown" class="uk-checkbox" type="checkbox" value="1"> {{ 'Enable Markdown' | trans }}</label>
                        </p>
                        <p class="uk-margin-small">
                            <label><input v-model="post.comment_status" class="uk-checkbox" type="checkbox" value="1"> {{ 'Enable Comments' | trans }}</label>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import PostMixin from '../mixins/post-mixin';

export default {

    mixins: [PostMixin],

    section: { label: 'Post' }

};

</script>

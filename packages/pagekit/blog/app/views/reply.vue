<template>
    <div class="js-comment-reply uk-margin-auto">
        <h3 class="uk-h4 uk-margin-medium-top">
            {{ 'Leave a comment' | trans }}
            <small>
                <a v-if="parent" href="#" accesskey="c" class="uk-text-muted uk-margin-small-left" @click.prevent="cancel">{{ 'Cancel' | trans }}</a>
            </small>
        </h3>

        <div v-show="error" class="uk-alert uk-alert-danger">
            {{ error }}
        </div>

        <validation-observer v-if="user.canComment" v-slot="{ invalid, passes }" slim>
        <form class="uk-form-stacked" @submit.prevent="passes(save)">
            <p v-if="user.isAuthenticated">
                {{ 'Logged in as %name%' | trans({name:user.name}) }}
            </p>

            <template v-else>
                <div class="uk-margin">
                    <label for="form-name" class="uk-form-label">{{ 'Name' | trans }}</label>
                    <v-input id="form-name" name="author" type="text" v-model="author" view="class: uk-input uk-form-width-large" rules="required" message="Name cannot be blank." />
                </div>

                <div class="uk-margin">
                    <label for="form-email" class="uk-form-label">{{ 'Email' | trans }}</label>
                    <v-input id="form-email" name="email" type="email" v-model="email" view="class: uk-input uk-form-width-large" rules="required|email" message="Email invalid." />
                </div>
            </template>

            <div class="uk-margin">
                <label for="form-comment" class="uk-form-label">{{ 'Comment' | trans }}</label>
                <v-input id="form-comment" name="content" v-model="content" rows="10" view="tag: textarea, class: uk-textarea uk-form-width-large" rules="required" message="Comment cannot be blank." />
            </div>

            <div class="uk-margin">
                <button class="uk-button uk-button-primary" type="submit" accesskey="s">
                    <span>{{ 'Submit' | trans }}</span>
                </button>
            </div>

        </form>
        </validation-observer>

        <template v-else>
            <p v-if="user.isAuthenticated">
                {{ 'You are not allowed to post comments.' | trans }}
            </p>
            <p v-else>
                <a :href="login">{{ 'Please login to leave a comment.' | trans }}</a>
            </p>
        </template>
    </div>
</template>

<script>

import { ValidationObserver, VInput } from 'SystemApp/components/validation.vue';

export default {

    name: 'Reply',

    data() {
        return {
            author: '',
            email: '',
            content: '',
            error: false,
            form: false,
        };
    },

    computed: {

        user() {
            return this.config.user;
        },

        login() {
            return this.$url('user/login', { redirect: window.location.href });
        },

    },

    methods: {

        save() {

            const comment = {
                parent_id: this.parent,
                post_id: this.config.post,
                content: this.content,
            };

            if (!this.user.isAuthenticated) {
                comment.author = this.author;
                comment.email = this.email;
            }

            this.$set(this, 'error', false);

            this.$resource('api/blog/comment{/id}').save({ id: 0 }, { comment }).then(function (res) {
                const { data } = res;

                if (!this.user.skipApproval) {
                    this.root.messages.push(this.$trans('Thank you! Your comment needs approval before showing up.'));
                } else {
                    this.root.load().then(() => {
                        window.location.hash = `comment-${data.comment.id}`;
                    });
                }

                this.cancel();
            }, () => {
                // TODO better error messages
                this.$set(this, 'error', this.$trans('Unable to comment. Please try again later.'));
            });
        },

        cancel() {
            this.root.reply();
        },

    },

    components: {
        ValidationObserver,
        VInput
    }

};

</script>

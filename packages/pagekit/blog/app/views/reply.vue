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

        <form v-if="user.canComment" class="uk-form-stacked" @submit.prevent="save">
            <p v-if="user.isAuthenticated">
                {{ 'Logged in as %name%' | trans({name:user.name}) }}
            </p>

            <template v-else>
                <div class="uk-margin">
                    <label for="form-name" class="uk-form-label">{{ 'Name' | trans }}</label>
                    <div class="uk-form-controls">
                        <input
                            id="form-name"
                            v-model="author"
                            v-validate="'required'"
                            class="uk-form-width-large uk-input"
                            type="text"
                            name="author"
                        >
                        <div v-show="errors.first('author')" class="uk-text-small uk-text-danger">
                            {{ 'Name cannot be blank.' | trans }}
                        </div>
                    </div>
                </div>

                <div class="uk-margin">
                    <label for="form-email" class="uk-form-label">{{ 'Email' | trans }}</label>
                    <div class="uk-form-controls">
                        <input
                            id="form-email"
                            v-model="email"
                            v-validate="'required|email'"
                            class="uk-form-width-large uk-input"
                            type="email"
                            name="email"
                        >
                        <div v-show="errors.first('email')" class="uk-text-small uk-text-danger">
                            {{ 'Email invalid.' | trans }}
                        </div>
                    </div>
                </div>
            </template>

            <div class="uk-margin">
                <label for="form-comment" class="uk-form-label">{{ 'Comment' | trans }}</label>
                <div class="uk-form-controls">
                    <textarea
                        id="form-comment"
                        v-model="content"
                        v-validate="'required'"
                        class="uk-form-width-large uk-textarea"
                        name="content"
                        rows="10"
                    />
                    <div v-show="errors.first('content')" class="uk-text-small uk-text-danger">
                        {{ 'Comment cannot be blank.' | trans }}
                    </div>
                </div>
            </div>

            <p>
                <button class="uk-button uk-button-primary" type="submit" accesskey="s">
                    <span>{{ 'Submit' | trans }}</span>
                </button>
            </p>
        </form>

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
            const vm = this;

            this.$validator.validateAll().then((res) => {
                if (res) {
                    const comment = {
                        parent_id: vm.parent,
                        post_id: vm.config.post,
                        content: vm.content,
                    };

                    if (!vm.user.isAuthenticated) {
                        comment.author = vm.author;
                        comment.email = vm.email;
                    }

                    vm.$set(vm, 'error', false);

                    vm.$resource('api/blog/comment{/id}').save({ id: 0 }, { comment }).then(function (res) {
                        const { data } = res;

                        if (!vm.user.skipApproval) {
                            vm.root.messages.push(this.$trans('Thank you! Your comment needs approval before showing up.'));
                        } else {
                            vm.root.load().then(() => {
                                window.location.hash = `comment-${data.comment.id}`;
                            });
                        }

                        vm.cancel();
                    }, () => {
                        // TODO better error messages
                        vm.$set(vm, 'error', vm.$trans('Unable to comment. Please try again later.'));
                    });
                }
            });
        },

        cancel() {
            this.root.reply();
        },

    },

};

</script>

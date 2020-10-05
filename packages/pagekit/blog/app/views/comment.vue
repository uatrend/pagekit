<template>
    <li :id="'comment-'+comment.id">
        <article :class="['uk-comment uk-visible-toggle', {'uk-comment-primary': comment.special}, {'tm-reply-visible': replyVisible && !comment.special}]">
            <header class="uk-comment-header uk-position-relative">
                <div class="uk-grid-medium uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        <img v-gravatar="comment.email" class="uk-comment-avatar uk-border-rounded" width="60" height="60" :alt="comment.author">
                    </div>
                    <div class="uk-width-expand">
                        <h4 class="uk-comment-title uk-margin-remove">
                            {{ comment.author }}
                        </h4>
                        <p class="uk-comment-meta uk-margin-remove-top">
                            <time :datetime="comment.created">{{ comment.created | relativeDate({max:2592000}) }}</time>
                            | <a class="uk-link-muted" :href="permalink" uk-scroll>#</a>
                        </p>
                    </div>
                </div>
                <div v-if="comment.status" class="uk-text-small uk-position-top-right uk-position-small uk-hidden-hover">
                    <a v-if="showReplyButton && user.canComment" href="#" @click.prevent="replyTo">{{ 'Reply' | trans }}</a>
                </div>
                <p v-else class="uk-comment-meta">
                    {{ 'The comment is awaiting approval.' }}
                </p>
            </header>

            <div class="uk-comment-body">
                <p v-html="comment.content" />
            </div>

            <div v-for="(message, id) in comment.messages" :key="id" class="uk-alert">
                {{ message }}
            </div>

            <div v-if="config.enabled" ref="reply" />
        </article>

        <ul v-if="tree[comment.id] && depth < config.max_depth">
            <comment v-for="c in tree[comment.id]" :key="c.id" :comment="c" :config="config" :tree="tree" :root="root" />
        </ul>
    </li>
</template>

<script>

export default {

    name: 'Comment',

    props: ['comments', 'comment', 'config', 'tree', 'root'],

    data() {
        return {
            replyVisible: false
        };
    },

    computed: {

        depth() {
            let depth = 1; let
                parent = this.$parent;

            while (parent) {
                if (parent.$options.name === 'Comment') {
                    depth++;
                }
                parent = parent.$parent;
            }

            return depth;
        },

        showReplyButton() {
            return this.config.enabled && !this.isLeaf && this.root.replyForm.$parent !== this;
        },

        remainder() {
            return this.isLeaf && this.tree[this.comment.id] || [];
        },

        isLeaf() {
            return this.depth >= this.config.max_depth;
        },

        permalink() {
            return `#comment-${this.comment.id}`;
        },

        user() {
            return this.config.user;
        }

    },

    methods: {

        replyTo() {
            this.root.reply(this);
        }

    }

};

</script>

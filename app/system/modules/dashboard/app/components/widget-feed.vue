<template>
    <div>
        <div v-if="editing" class="uk-card-header pk-panel-teaser">
            <form class="uk-form-stacked">
                <div class="uk-margin">
                    <label for="form-feed-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-feed-title" v-model="widget.title" class="uk-width-1-1 uk-input" type="text" name="widget[title]">
                    </div>
                </div>

                <div class="uk-margin">
                    <label for="form-feed-url" class="uk-form-label">{{ 'URL' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-feed-url" v-model="widget.url" class="uk-width-1-1 uk-input" type="text" name="url" lazy>
                    </div>
                </div>

                <div class="uk-margin">
                    <label for="form-feed-count" class="uk-form-label">{{ 'Number of Posts' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-feed-count" v-model="widget.count" class="uk-select uk-width-1-1" number>
                            <option v-for="i in 10" :key="i" :value="i">
                                {{ i }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">{{ 'Post Content' | trans }}</label>

                    <div class="uk-form-controls uk-form-controls-text">
                        <p class="uk-margin-small">
                            <label><input v-model="widget.content" class="uk-radio" type="radio" value=""> {{ "Don't show" | trans }}</label>
                        </p>
                        <p class="uk-margin-small">
                            <label><input v-model="widget.content" class="uk-radio" type="radio" value="1"> {{ 'Show on all posts' | trans }}</label>
                        </p>
                        <p class="uk-margin-small">
                            <label><input v-model="widget.content" class="uk-radio" type="radio" value="2"> {{ 'Only show on first post.' | trans }}</label>
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <div class="uk-card-body">
            <div v-if="status != 'loading'">
                <h3 v-if="widget.title" class="uk-h3">
                    {{ widget.title }}
                </h3>
                <div v-else class="uk-margin-bottom">
                    <h3 v-if="!widget.title && head.title" class="uk-h3 uk-margin-remove-bottom">
                        {{ head.title }}
                    </h3>
                    <p v-if="!widget.title && head.title && head.description" class="uk-margin-remove uk-text-small uk-text-meta">
                        {{ head.description }}
                    </p>
                </div>

                <ul class="uk-list uk-list-divider uk-margin-remove">
                    <li v-for="(entry, key) in count" :key="key">
                        <a :href="entry.link" target="_blank" v-html="entry.title" />
                        <p class="uk-text-small uk-text-muted uk-text-nowrap uk-margin-remove-top uk-margin-small-bottom">
                            {{ entry.publishedDate | relativeDate }}
                        </p>

                        <div v-if="widget.content == '1'" class="uk-margin-small-top uk-text-justify uk-text-small" v-html="entry.contentSnippet" />

                        <div v-if="widget.content == '2'" class="uk-margin-small-top uk-text-justify uk-text-small" v-html="key == 0 ? entry.contentSnippet : ''" />
                    </li>
                </ul>

                <div v-if="status == 'error'">
                    {{ 'Unable to retrieve feed data.' | trans }}
                </div>

                <div v-if="!widget.url && !editing">
                    {{ 'No URL given.' | trans }}
                </div>
            </div>

            <div v-else class="uk-text-center">
                <v-loader />
            </div>
        </div>
    </div>
</template>

<script>

import WidgetMixin from '../mixins/widget-mixin';

export default {

    name: 'Feed',

    mixins: [WidgetMixin],

    type: {

        id: 'feed',
        label: 'Feed',
        description: () => {},
        defaults: {
            count: 5,
            url: 'http://pagekit.com/blog/feed',
            content: ''
        }

    },

    data() {
        return {
            status: '',
            feed: {},
            head: {
                title: '',
                description: ''
            }
        };
    },

    computed: {
        count() {
            const vm = this;
            if (!this.feed.entries) return;
            return this.feed.entries.filter((entry, key) => key < vm.widget.count);
        }
    },

    watch: {

        'widget.url': function (url) {
            if (!url) {
                this.$parent.edit(true);
            }

            this.load();
        },

        'widget.count': function (count, old) {
            const { entries } = this.feed;
            if (entries && count > old && count > entries.length) {
                this.load();
            }
        }

    },

    mounted() {
        if (this.widget.url) {
            this.load();
        }
    },

    methods: {

        load() {
            this.$set(this, 'feed', {});
            this.$set(this, 'status', '');

            if (!this.widget.url) {
                return;
            }

            this.$set(this, 'status', 'loading');

            this.$http.get(`https://api.rss2json.com/v1/api.json?rss_url=${this.widget.url}`, { params: { cache: 60 } })
                .then(function (res) {
                    const { data } = res;

                    if (res.status === 200 && data.items && data.items.length) {
                        const entries = data.items;
                        entries.forEach((entry) => {
                            entry.publishedDate = new Date(entry.pubDate.replace(/\s+/g, 'T'));
                            entry.contentSnippet = entry.description.replace(/<(\/)?p([^>]*)>/g, '<$1p$2>');
                        });
                        this.feed = { entries };
                        this.head = {
                            title: data.feed.title ? data.feed.title : '',
                            description: data.feed.description ? data.feed.description : ''
                        };
                        this.status = 'done';
                    } else {
                        this.status = 'error';
                    }
                }, function () {
                    this.status = 'error';
                });
        }

    }

};

</script>

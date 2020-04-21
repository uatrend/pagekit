<template>
    <div>
        <div class="uk-grid-medium uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid>
            <div v-for="pkg in packages">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-media-top">
                        <div class="uk-inline-clip uk-transition-toggle">
                            <div class="uk-background-cover uk-position-cover" :style="{ 'background-image': 'url(' + pkg.extra.image + ')' }" />
                            <canvas class="uk-responsive-width uk-display-block" width="1200" height="800" />
                            <div class="uk-transition-fade uk-position-cover uk-position-small uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle" />
                        </div>
                    </div>

                    <div class="uk-card-body uk-padding-small">
                        <h2 class="uk-card-title uk-margin-remove">
                            {{ pkg.title }}
                        </h2>
                        <p class="uk-text-muted uk-margin-remove">
                            {{ pkg.author.name }}
                        </p>
                    </div>

                    <a class="uk-position-cover" @click.prevent="details(pkg)" />
                </div>
            </div>
        </div>

        <v-pagination :pages="pages" v-model="$parent.page" v-show="pages > 1 || page > 0" />

        <div ref="modal" class="uk-modal-container" uk-modal>
            <div class="uk-modal-dialog marketplace">
                <div v-if="pkg">

                    <div class="uk-modal-header uk-position-relative">
                        <h2 class="uk-h3 uk-margin-remove">
                            {{ pkg.title }}
                        </h2>
                        <ul class="uk-subnav uk-subnav-divider uk-margin-remove-top uk-margin-remove-bottom">
                            <li v-if="pkg.author.homepage">
                                <a class="uk-link-muted" :href="pkg.author.homepage" target="_blank">{{ pkg.author.name }}</a>
                            </li>
                            <li v-else class="uk-text-muted">
                                <span>{{ pkg.author.name }}</span>
                            </li>
                            <li class="uk-text-muted">
                                <span>{{ 'Version %version%' | trans({version:pkg.version}) }}</span>
                            </li>
                            <li v-if="pkg.license" class="uk-text-muted">
                                <span>{{ pkg.license }}</span>
                            </li>
                        </ul>

                        <div class="pk-modal-dialog-badge uk-position-center-right">
                            <button v-if="isInstalled(pkg)" class="uk-button uk-button-default" disabled>
                                {{ 'Installed' | trans }}
                            </button>
                            <button v-else class="uk-button uk-button-primary" @click.prevent="doInstall(pkg)">
                                {{ 'Install' | trans }}
                            </button>
                        </div>
                    </div>

                    <div class="uk-modal-body">
                        <div class="uk-child-width-1-2@m" uk-grid>
                            <div class="uk-grid-item-match">
                                <div class="uk-height-max-large uk-cover-container">
                                    <img :alt="pkg.title" :src="pkg.extra.image" uk-cover>
                                    <canvas width="1200" height="800"></canvas>
                                </div>
                            </div>
                            <div>
                                <div class="uk-height-max-large uk-overflow-auto">
                                    <div v-if="pkg.description" v-html="marked(pkg.description)"></div>
                                    <ul class="uk-grid-small uk-margin-top" uk-grid>
                                        <li v-if="pkg.demo">
                                            <a class="uk-button uk-button-default" :href="pkg.demo" target="_blank">{{ 'Demo' | trans }}</a>
                                        </li>
                                        <li v-if="pkg.support">
                                            <a class="uk-button uk-button-default" :href="pkg.support" target="_blank">{{ 'Support' | trans }}</a>
                                        </li>
                                        <li v-if="pkg.documentation">
                                            <a class="uk-button uk-button-default" :href="pkg.documentation" target="_blank">{{ 'Documentation' | trans }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 v-show="packages && !packages.length" class="uk-h1 uk-text-muted uk-text-center">
            {{ 'Nothing found.' | trans }}
        </h3>

        <p v-show="status == 'error'" class="uk-alert uk-alert-warning">
            {{ 'Cannot connect to the marketplace. Please try again later.' | trans }}
        </p>
    </div>
</template>

<script>

import Package from '../lib/package';

export default {

    mixins: [Package],

    props: {
        api: { type: String, default: '' },
        search: { type: String, default: '' },
        page: { type: Number, default: 0 },
        type: { type: String, default: 'pagekit-extension' },
        installed: {
            type: Array,
            default() {
                return [];
            },
        },
    },

    data() {
        return {
            pkg: null,
            packages: null,
            updates: null,
            pages: 0,
            iframe: '',
            status: '',
        };
    },

    created() {
        this.$options.name = this.type;
    },

    mounted() {
        this.$watch('page', this.query, { immediate: true });

        this.queryUpdates(this.installed, function (res) {
            const { data } = res;
            this.$set(this, 'updates', data.packages.length ? data.packages : null);
        });
    },

    watch: {

        search() {
            if (this.page) {
                this.$parent.page = 0;
            } else {
                this.query();
            }
        },

        type() {
            if (this.page) {
                this.$parent.page = 0;
            } else {
                this.query();
            }
        },

    },

    methods: {

        query() {
            const url = `${this.api}/api/package/search`;
            const options = { emulateJSON: true };

            this.$http.post(url, { q: this.search, type: this.type, page: this.page }, options).then(function (res) {
                const { data } = res;
                this.$set(this, 'packages', data.packages);
                this.$set(this, 'pages', data.pages);
            }, function () {
                this.$set(this, 'packages', null);
                this.$set(this, 'status', 'error');
            });
        },

        details(pkg) {
            if (!this.modal) {
                this.modal = UIkit.modal(this.$refs.modal);
            }

            this.$set(this, 'pkg', pkg);
            this.$set(this, 'status', '');

            this.modal.show();
        },

        doInstall(pkg) {
            this.modal.hide();
            this.install(pkg, this.installed);
        },

        isInstalled(pkg) {
            return _.isObject(pkg) ? _.find(this.installed, { name: pkg.name }) : undefined;
        },

        marked,
    },

};

</script>

<template>
    <div>
        <div class="uk-grid-medium uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid>
            <div v-for="(p, key) in packages" :key="key">
                <div class="uk-card uk-card-default uk-transition-toggle">
                    <div class="uk-card-media-top">
                        <div class="uk-inline-clip">
                            <div class="uk-background-contain uk-position-cover" :data-src="p.extra.image" uk-img />
                            <canvas width="1200" height="800" />
                            <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-default" />
                        </div>
                    </div>

                    <div class="uk-card-body uk-padding-small">
                        <h2 class="uk-card-title uk-margin-remove">
                            {{ p.title }}
                        </h2>
                        <p class="uk-text-small uk-text-muted uk-margin-remove">
                            {{ p.author.name }}
                        </p>
                    </div>

                    <a class="uk-position-cover" @click.prevent="details(p)" />
                </div>
            </div>
        </div>

        <v-pagination v-show="pages > 1 || $parent.page > 0" v-model="$parent.page" :pages="pages" />

        <v-modal ref="modal" large>
            <template v-if="pkg">
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

                    <div class="uk-position-center-right uk-position-medium">
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
                            <div class="uk-inline-clip">
                                <div class="uk-background-contain uk-position-cover" :data-src="pkg.extra.image" uk-img />
                                <canvas width="1200" height="800" />
                            </div>
                        </div>
                        <div>
                            <div class="uk-position-relative uk-height-1-1">
                                <div class="tm-description uk-position-cover">
                                    <div class="tm-description-wrapper">
                                        <div uk-overflow-auto="selContainer: .tm-description; selContent: .tm-description-wrapper">
                                            <div v-if="pkg.description" class="uk-text-small" v-html="marked(pkg.description)" />
                                        </div>
                                        <div class="uk-margin-top">
                                            <ul class="uk-grid-small" uk-grid>
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
            </template>
        </v-modal>

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

    mixins: [Package, Theme.Mixins.Helper],

    props: {
        api: { type: String, default: '' },
        search: { type: String, default: '' },
        page: { type: Number, default: 0 },
        type: { type: String, default: 'pagekit-extension' },
        installed: {
            type: Array,
            default() {
                return [];
            }
        }
    },

    data() {
        return {
            pkg: null,
            packages: null,
            updates: null,
            pages: 0,
            iframe: '',
            status: ''
        };
    },

    theme: {
        elements() {
            const vm = this;
            return {
                pagination: {
                    scope: 'topmenu-right',
                    type: 'pagination',
                    caption: 'Pages',
                    props: {
                        value: () => vm.$parent.page,
                        pages: () => vm.pages,
                        name: () => vm.$options.name,
                        options: () => ({
                            lblPrev: '<span uk-pagination-previous></span>',
                            lblNext: '<span uk-pagination-next></span>',
                            displayedPages: 3,
                            edges: 1
                        })
                    },
                    on: {
                        input: (e) => {
                            if (typeof e === 'number') {
                                vm.$parent.page = e;
                            }
                        }
                    },
                    watch: (_vm) => ({ pages: _vm.pages, page: _vm.page, search: _vm.search }),
                    vif: () => (vm.pages > 1 || vm.$parent.page > 0),
                    priority: 0
                }
            };
        }
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
        }

    },

    created() {
        this.$options.name = this.type;
    },

    mounted() {
        this.$watch('page', this.query, { immediate: true });

        this.queryUpdates(this.installed, (res) => {
            const { data } = res;
            this.$set(this, 'updates', data.packages.length ? data.packages : null);
        });
    },

    methods: {

        query() {
            const url = `${this.api}/api/package/search`;
            const options = { emulateJSON: true };

            this.$http.post(url, { q: this.search, type: this.type, page: this.page }, options).then((res) => {
                const { data } = res;
                this.$set(this, 'packages', data.packages);
                this.$set(this, 'pages', data.pages);
            }, () => {
                this.$set(this, 'packages', null);
                this.$set(this, 'status', 'error');
            });
        },

        details(pkg) {
            this.$set(this, 'pkg', pkg);
            this.$set(this, 'status', '');

            this.$refs.modal.open();
        },

        doInstall(pkg) {
            this.$refs.modal.close();
            this.install(pkg, this.installed);
        },

        isInstalled(pkg) {
            return _.isObject(pkg) ? _.find(this.installed, { name: pkg.name }) : undefined;
        },

        marked(text) {
            const correctHeadings = function (str) {
                const headings = / *(#{1,6}) *([^\n]+?) *(?:#+ *)?(?:\n+|$)/g;
                [...str.matchAll(headings)].length && [...str.matchAll(headings)].forEach((arr) => {
                    if (str.indexOf(`${arr[1]} ${arr[2]}`) === -1) {
                        str = str.replace(new RegExp(arr[1] + arr[2]), `${arr[1]} ${arr[2]}`);
                    }
                });
                return str;
            };
            return marked(correctHeadings(text));
        }
    }

};

</script>

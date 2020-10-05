<template>
    <div class="package-details">
        <div class="uk-modal-header uk-flex uk-flex-middle">
            <img v-if="pkg.extra && pkg.extra.icon" class="uk-margin-right" width="50" height="50" :alt="pkg.title" :src="pkg | icon">

            <div class="uk-flex-1">
                <h2 class="uk-h3 uk-margin-remove">
                    {{ pkg.title }}
                </h2>
                <ul class="uk-subnav uk-subnav-divider uk-margin-remove-bottom uk-margin-remove-top">
                    <li v-if="pkg.authors && pkg.authors[0]" class="uk-text-muted">
                        <span>{{ pkg.authors[0].name }}</span>
                    </li>
                    <li class="uk-text-muted">
                        <span>{{ 'Version %version%' | trans({version:pkg.version}) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div v-show="messages.checksum" class="uk-alert uk-alert-danger uk-margin-remove">
            {{ 'The checksum of the uploaded package does not match the one from the marketplace. The file might be manipulated.' | trans }}
        </div>

        <div v-show="messages.update" class="uk-alert uk-alert-primary uk-margin-remove">
            {{ 'There is an update available for the pkg.' | trans }}
        </div>

        <div class="uk-modal-body">
            <p v-if="pkg.description">
                {{ pkg.description }}
            </p>

            <ul class="uk-list uk-list-collapse uk-text-small">
                <li v-if="pkg.license">
                    <strong>{{ 'License:' | trans }}</strong> {{ pkg.license }}
                </li>
                <template v-if="pkg.authors && pkg.authors[0]">
                    <li v-if="pkg.authors[0].homepage">
                        <strong>{{ 'Homepage:' | trans }}</strong>
                        <a :href="pkg.authors[0].homepage" target="_blank">{{ pkg.authors[0].homepage }}</a>
                    </li>
                    <li v-if="pkg.authors[0].email">
                        <strong>{{ 'Email:' | trans }}</strong>
                        <a :href="'mailto:' + pkg.authors[0].email">{{ pkg.authors[0].email }}</a>
                    </li>
                </template>
            </ul>

            <img v-if="pkg.extra && pkg.extra.image" width="1200" height="800" :alt="pkg.title" :src="pkg | image">
        </div>
    </div>
</template>

<script>

import Version from '../lib/version';

export default {

    filters: {

        icon(pkg) {
            const extra = pkg.extra || {};

            if (!extra.icon) {
                return this.$url('app/system/assets/images/placeholder-icon.svg');
            } if (!extra.icon.match(/^(https?:)?\//)) {
                return `${pkg.url}/${extra.icon}`;
            }

            return extra.icon;
        },

        image(pkg) {
            const extra = pkg.extra || {};

            if (!extra.image) {
                return this.$url('app/system/assets/images/placeholder-image.svg');
            } if (!extra.image.match(/^(https?:)?\//)) {
                return `${pkg.url}/${extra.image}`;
            }

            return extra.image;
        }

    },

    props: {
        api: {
            type: String,
            default: ''
        },
        package: {
        // pkg: {
            type: Object,
            default() {
                return {};
            }
        }
    },

    data() {
        return {
            pkg: {},
            messages: {}
        };
    },

    watch: {

        pkg: {

            handler() {
                if (!this.pkg.name) {
                    return;
                }

                if (_.isArray(this.pkg.authors)) {
                    this.pkg.author = this.pkg.authors[0];
                }

                this.$set(this, 'messages', {});

                this.queryPackage(this.pkg, (res) => {
                    const { data } = res;

                    let { version } = this.pkg;
                    const pkg = data.versions[version];

                    // verify checksum
                    if (pkg && this.pkg.shasum) {
                        this.$set(this.messages, 'checksum', pkg.dist.shasum !== this.pkg.shasum);
                    }

                    // check version
                    _.each(data.versions, (p) => {
                        if (Version.compare(p.version, version, '>')) {
                            version = p.version;
                        }
                    });

                    this.$set(this.messages, 'update', version !== this.pkg.version);
                });
            },

            immediate: true

        }
    },

    created() {
        this.pkg = this.package;
    },

    methods: {

        queryPackage(pkg, success) {
            return this.$http.get(`${this.api}/api/package/{+name}`, { params: { name: _.isObject(pkg) ? pkg.name : pkg } }).then(success, this.error);
        }

    }

};

</script>

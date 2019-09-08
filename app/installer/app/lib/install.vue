<template>

    <v-modal ref="output" :options="options">
        <div class="uk-modal-header uk-flex uk-flex-middle">
            <h2>{{ 'Installing %title% %version%' | trans({title:pkg.title,version:pkg.version}) }}</h2>
        </div>

        <div class="uk-modal-body">

            <pre v-show="showOutput" class="pk-pre uk-text-break" v-html="output" uk-overflow-auto />

            <div v-show="status == 'loading'" class="uk-alert uk-flex uk-flex-middle uk-background-muted">
                <v-loader/>
                <span v-show="!showOutput" class="uk-margin-small-left">{{ 'Prepare' | trans }}...</span>
                <span v-show="showOutput" class="uk-margin-small-left">{{ 'Installing %title% %version%' | trans({title:pkg.title,version:pkg.version}) }}...</span>
            </div>

            <div v-show="status == 'success'" class="uk-alert uk-alert-success uk-margin-remove">{{ 'Successfully installed.' | trans }}</div>
            <div v-show="status == 'error'" class="uk-alert uk-alert-danger uk-margin-remove">{{ 'Error' | trans }}</div>

        </div>

        <div v-show="status != 'loading'" class="uk-modal-footer uk-text-right">
            <a class="uk-button uk-button-text uk-margin-right" @click.prevent="close">{{ 'Close' | trans }}</a>
            <a v-show="status == 'success'" class="uk-button uk-button-primary" @click.prevent="enable">{{ 'Enable' | trans }}</a>
        </div>
    </v-modal>

</template>

<script>

import Output from './output';

export default {

    mixins: [Output],

    methods: {

        install(pkg, packages, onClose, packagist) {
            this.$set(this, 'pkg', pkg);
            this.cb = onClose;

            self = this;

            return this.$http.get('admin/system/package/install', { params: { package: pkg, packagist: Boolean(packagist) }, progress() { self.init(this); } }).then(function () {
                this.scrollToEnd();
                if (this.status === 'success' && packages) {
                    const index = _.findIndex(packages, { name: pkg.name });

                    if (index !== -1) {
                        packages.splice(index, 1, pkg);
                    } else {
                        packages.push(pkg);
                    }
                }
            }, function (msg) {
                this.$notify(msg.data, 'danger');
                this.close();
            });
        },

        enable() {
            this.$parent.enable(this.pkg);
            this.close();
        },
    }

};

</script>

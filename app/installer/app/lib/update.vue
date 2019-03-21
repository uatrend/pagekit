<template>
    <!-- <div> -->
    <v-modal ref="output" :options="options">
        <div class="uk-modal-header uk-flex uk-flex-middle">
            <h2>{{ 'Updating %title% to %version%' | trans({title:pkg.title,version:updatePkg.version}) }}</h2>
        </div>

        <div class="uk-modal-body">
            <pre class="pk-pre uk-text-break" v-html="output" />

            <v-loader v-show="status == 'loading'" />

            <div v-show="status == 'success'" class="uk-alert uk-alert-success">
                {{ 'Successfully updated.' | trans }}
            </div>
            <div v-show="status == 'error'" class="uk-alert uk-alert-danger">
                {{ 'Error' | trans }}
            </div>
        </div>

        <div v-show="status != 'loading'" class="uk-modal-footer uk-text-right">
            <a class="uk-button uk-button-secondary" @click.prevent="close">{{ 'Close' | trans }}</a>
        </div>
    </v-modal>
    <!-- </div> -->
</template>

<script>

module.exports = {

    mixins: [require('./output')],

    methods: {

        update(pkg, updates, onClose, packagist) {
            this.$set(this, 'pkg', pkg);
            this.$set(this, 'updatePkg', updates[pkg.name]);

            this.cb = onClose;

            self = this;

            // return this.$http.post('admin/system/package/install', {package: updates[pkg.name], packagist: Boolean(packagist)},null , {xhr: this.init()}).then(function () {
            return this.$http.get('admin/system/package/install', { params: { package: updates[pkg.name], packagist: Boolean(packagist) }, progress() { self.init(this); } }).then(function () {
                if (this.status === 'loading') {
                    this.status = 'error';
                }

                if (this.status === 'success') {
                    Vue.delete(updates, pkg.name);
                }

                if (pkg.enabled) {
                    this.$parent.enable(pkg).then(null, function () {
                        this.status = 'error';
                    });
                }
            }, function (msg) {
                this.$notify(msg.data, 'danger');
                this.close();
            });
        },
    },

};

</script>

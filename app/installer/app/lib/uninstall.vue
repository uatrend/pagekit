<template>
    <!-- <div> -->
    <v-modal ref="output" :options="options">
        <div class="uk-modal-header uk-flex uk-flex-middle">
            <h2>{{ 'Removing %title% %version%' | trans({title:pkg.title,version:pkg.version}) }}</h2>
        </div>

        <div class="uk-modal-body">
            <pre class="pk-pre uk-text-break" v-html="output" />

            <v-loader v-show="status == 'loading'" />

            <div v-show="status == 'success'" class="uk-alert uk-alert-success">
                {{ 'Successfully removed.' | trans }}
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

        uninstall(pkg, packages) {
            this.$set(this, 'pkg', pkg);

            self = this;

            // return this.$http.post('admin/system/package/uninstall', {name: pkg.name}, {xhr: this.init()}).then(function () {
            return this.$http.get('admin/system/package/uninstall', { params: { name: pkg.name }, progress() { self.init(this); } }).then(function () {
                if (this.status === 'success' && packages) {
                    packages.splice(packages.indexOf(pkg), 1);
                }
            }, function (msg) {
                this.$notify(msg.data, 'danger');
                this.close();
            });
        },

    },

};

</script>

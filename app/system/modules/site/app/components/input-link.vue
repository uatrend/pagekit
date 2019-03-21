<template>
    <div>
        <div :class="['pk-form-link', cls ? cls : '']">
            <input
                v-if="isRequired"
                :id="id"
                v-validate="'required'"
                class="uk-width-1-1 uk-input"
                type="text"
                :value="link"
                :name="name"
                @change="update"
            >
            <input
                v-else
                :id="id"
                class="uk-width-1-1 uk-input"
                type="text"
                :value="link"
                :name="name"
                @change="update"
            >
            <a class="pk-form-link-toggle pk-link-icon uk-flex-middle" @click.prevent="open">{{ 'Select' | trans }} <i class="pk-icon-link pk-icon-hover uk-margin-small-left" /></a>
        </div>

        <p v-show="url" class="uk-text-muted uk-margin-small-top uk-margin-remove-bottom">
            {{ url }}
        </p>

        <v-modal ref="modal">
            <form class="uk-margin" @submit.prevent="update">
                <div class="uk-modal-header">
                    <h2>{{ 'Select Link' | trans }}</h2>
                </div>

                <div class="uk-modal-body">
                    <panel-link ref="links" v-model="c_link" />
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-secondary uk-modal-close" type="button">
                        {{ 'Cancel' | trans }}
                    </button>
                    <button class="uk-button uk-button-primary" type="submit" :disabled="!c_link">
                        {{ 'Update' | trans }}
                    </button>
                </div>
            </form>
        </v-modal>
    </div>
</template>

<script>
const { isInput } = UIkit.util;

module.exports = {

    name: 'input-link',

    props: ['link', 'name', 'cls', 'id', 'required'],

    inject: ['$validator'],

    data() {
        return {
            c_link: this.link,
            url: false,
        };
    },

    watch: {

        link: {
            handler: 'load',
            immediate: true,
        },

    },

    computed: {

        isRequired() {
            return this.required !== undefined;
        },

    },

    methods: {

        load() {
            if (this.link) {
                this.$http.get('api/site/link', { params: { link: this.link } }).then(function (res) {
                    this.url = res.data.url || false;
                }, function () {
                    this.url = false;
                });
            } else {
                this.url = false;
            }
        },

        open() {
            this.$refs.modal.open();
        },

        update(e) {
            this.$emit('input', isInput(e.target) ? e.target.value : this.c_link);
            this.$refs.modal.close();
        },

    },

};

Vue.component('input-link', (resolve) => {
    resolve(module.exports);
});

</script>

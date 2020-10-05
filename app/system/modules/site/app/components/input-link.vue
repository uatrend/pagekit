<template>
    <div>
        <v-input :id="id" v-model.lazy="link" :name="name" type="text" :rules="{required: isRequired}" :view="view" :message="required" />

        <div v-show="url" class="uk-text-muted uk-text-small">
            {{ url }}
        </div>

        <v-modal ref="modal">
            <form class="uk-margin" @submit.prevent="update">
                <div class="uk-modal-header">
                    <h2 class="uk-h4">
                        {{ 'Select Link' | trans }}
                    </h2>
                </div>

                <div class="uk-modal-body">
                    <panel-link @link-changed="changed" />
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">
                        {{ 'Cancel' | trans }}
                    </button>
                    <button class="uk-button uk-button-primary" type="submit" :disabled="!disabled()" autofocus="">
                        {{ 'Update' | trans }}
                    </button>
                </div>
            </form>
        </v-modal>
    </div>
</template>

<script>

import VInput from '@system/app/components/validation.vue';

const InputLink = {

    name: 'InputLink',

    components: { VInput },

    props: ['name', 'className', 'id', 'required', 'value'],

    data() {
        return {
            link: this.value,
            url: false,
            selected: false,
            view: {
                type: 'icon',
                class: ['uk-input', this.className],
                icon: 'link',
                iconClick: this.open,
                iconTag: 'a',
                iconDir: 'right',
                iconLabel: 'Select'
            }
        };
    },

    computed: {

        isRequired() {
            return this.required !== undefined;
        }

    },

    watch: {

        link: {
            handler: 'load',
            immediate: true
        }

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
                this.url = '';
            }
        },

        open() {
            this.$refs.modal.open();
        },

        changed(link) {
            this.selected = link;
        },

        update() {
            this.link = this.selected;
            this.$emit('input', this.link);
            this.$refs.modal.close();
        },

        disabled() {
            return this.selected;
        }

    }

};

Vue.component('InputLink', (resolve) => {
    resolve(InputLink);
});

export default InputLink;

</script>

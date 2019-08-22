<template>
    <div>
        <div :class="['pk-form-link', cls ? cls : '']">
            <input v-if="isRequired" :id="id" v-validate="'required'" class="uk-width-1-1 uk-input" type="text" v-model.lazy="link" :name="name">
            <input v-else :id="id" class="uk-width-1-1 uk-input" type="text" v-model.lazy="link" :name="name">
            <a class="pk-form-link-toggle pk-link-icon uk-flex-middle" @click.prevent="open">{{ 'Select' | trans }} <i class="pk-icon-link pk-icon-hover uk-margin-small-left" /></a>
        </div>

        <div v-show="url" class="uk-text-muted uk-text-small">
            {{ url }}
        </div>

        <div uk-modal ref="modal">
            <div class="uk-modal-dialog">
                <form class="uk-margin" @submit.prevent="update">
                    <div class="uk-modal-header">
                        <h2>{{ 'Select Link' | trans }}</h2>
                    </div>

                    <div class="uk-modal-body">
                        <panel-link ref="links"></panel-link>
                    </div>

                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">
                            {{ 'Cancel' | trans }}
                        </button>
                        <button class="uk-button uk-button-primary" type="submit" :disabled="!showUpdate()" autofocus="">
                            {{ 'Update' | trans }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
const { on } = UIkit.util;

module.exports = {

    name: 'input-link',

    props: ['value', 'name', 'cls', 'id', 'required'],

    inject: ['$validator'],

    data() {
        return {
            link: this.value,
            url: false,
        };
    },

    watch: {

        link: {
            handler: 'load',
            immediate: true,
        }

    },

    computed: {

        isRequired() {
            return this.required !== undefined;
        }

    },

    mounted() {
        this.modal = UIkit.modal(this.$refs.modal, {escClose: true, bgClose: false, stack: true});

    },

    methods: {

        load() {
            if (this.link) {
                this.$emit('input', this.link);
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
            var vm = this;
            // this.modal = UIkit.modal(this.$refs.modal, {escClose: true, bgClose: false, stack: true});
            this.modal.show();
            // on(this.modal.$el, 'hidden', function(ref){
            //     var target = ref.target;
            //     var currentTarget = ref.currentTarget;

            //     if (target === currentTarget) {
            //         // vm.modal.$destroy(true);
            //     }
            // })
        },

        update() {
            this.$set(this, 'link', this.$refs.links.link);
            this.modal.hide();
        },

        showUpdate: function () {
            return this.$refs.links && !!this.$refs.links.link;
        }

    },

};

Vue.component('input-link', (resolve) => {
    resolve(module.exports);
});

</script>

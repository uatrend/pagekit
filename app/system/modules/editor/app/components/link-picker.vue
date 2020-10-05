<template>
    <v-modal ref="modal" :closed="close">
        <form class="uk-form-stacked" @submit.prevent="update">
            <div class="uk-modal-header">
                <h2 class="uk-h4">
                    <span uk-icon="link" ratio="1.1" /><span class="uk-text-middle uk-margin-small-left">{{ 'Link' | trans }}</span>
                </h2>
            </div>

            <div class="uk-modal-body">
                <div class="uk-margin">
                    <label for="form-link-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-link-title" v-model="link.txt" class="uk-input uk-width-1-1" type="text">
                    </div>
                </div>

                <div class="uk-margin">
                    <label for="form-link-url" class="uk-form-label">{{ 'URL' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-link id="form-link-url" v-model="link.link" class-name="uk-width-xlarge" />
                    </div>
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">
                    {{ 'Cancel' | trans }}
                </button>
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Update' | trans }}
                </button>
            </div>
        </form>
    </v-modal>
</template>

<script>

export default {

    data() {
        return { link: { link: '', txt: '', class: '' } };
    },

    mounted() {
        this.$refs.modal.open();
    },

    methods: {

        close() {
            this.$destroy(true);
        },

        update() {
            this.$refs.modal.close();
            this.$emit('select', this.link);
        }

    }

};

</script>

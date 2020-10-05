<template>
    <v-modal ref="modal" :closed="close">
        <form class=" uk-form-stacked" @submit.prevent="update">
            <div class="uk-modal-header">
                <h2 class="uk-h4">
                    <span uk-icon="image" ratio="1.1" /><span class="uk-text-middle uk-margin-small-left">{{ 'Image' | trans }}</span>
                </h2>
            </div>

            <div class="uk-modal-body">
                <div class="uk-margin">
                    <input-image v-model="image.data.src" :input-field="false" class-name="uk-width-1-1" @image-selected="selected" />
                </div>

                <div class="uk-margin">
                    <label for="form-src" class="uk-form-label">{{ 'URL' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-src" v-model="image.data.src" class="uk-width-1-1 uk-input" type="text" lazy>
                    </div>
                </div>

                <div class="uk-margin">
                    <label for="form-alt" class="uk-form-label">{{ 'Alt' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-alt" v-model="image.data.alt" class="uk-width-1-1 uk-input" type="text">
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

    name: 'ImagePicker',

    data() {
        return { image: { data: { src: '', alt: '' } } };
    },

    mounted() {
        this.$refs.modal.open();
    },

    methods: {
        selected(path) {
            if (path && !this.image.data.alt) {
                const alt = path.split('/').slice(-1)[0].replace(/\.(jpeg|jpg|png|svg|gif)$/i, '').replace(/(_|-)/g, ' ').trim();
                const first = alt.charAt(0).toUpperCase();

                this.image.data.alt = first + alt.substr(1);
            }
        },

        close() {
            this.$destroy(true);
        },

        update() {
            // Changed Order
            this.$emit('select', this.image);
            this.$refs.modal.close();
        }

    }

};

</script>

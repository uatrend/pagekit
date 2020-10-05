<template>
    <v-modal ref="modal" :closed="close">
        <form class=" uk-form-stacked" @submit.prevent="update">
            <div class="uk-modal-header">
                <h2 class="uk-h4">
                    <span uk-icon="video" ratio="1.1" /><span class="uk-text-middle uk-margin-small-left">{{ 'Video' | trans }}</span>
                </h2>
            </div>

            <div class="uk-modal-body">
                <div class="uk-margin">
                    <input-video v-model="video.data.src" class-name="uk-width-1-1" />
                </div>

                <div class="uk-margin">
                    <label for="form-src" class="uk-form-label">{{ 'URL' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-src" v-model="video.data.src" class="uk-width-1-1 uk-input" type="text" debounce="500">
                    </div>
                </div>
                <div class="uk-child-width-1-2@s uk-grid-small uk-flex-bottom" uk-grid>
                    <div>
                        <div class="uk-child-width-1-2 uk-grid-small" uk-grid>
                            <div>
                                <label for="form-src" class="uk-form-label">{{ 'Width' | trans }}</label>
                                <input id="form-width" v-model="video.data.width" class="uk-width-1-1 uk-input" type="text" :placeholder="'auto' | trans">
                            </div>
                            <div>
                                <label for="form-src" class="uk-form-label">{{ 'Height' | trans }}</label>
                                <input id="form-height" v-model="video.data.height" class="uk-width-1-1 uk-input" type="text" :disabled="!isVimeo && !isYoutube" :placeholder="'auto' | trans">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="uk-child-width-1-2 uk-grid-small uk-text-small" uk-grid>
                            <div class="uk-text-nowrap uk-text-truncate">
                                <label><input v-model="video.data.autoplay" class="uk-checkbox" type="checkbox"> {{ 'Autoplay' | trans }}</label><br>
                                <label v-show="!isVimeo"><input v-model="video.data.controls" class="uk-checkbox" type="checkbox"> {{ 'Controls' | trans }}</label>
                            </div>
                            <div class="uk-text-nowrap uk-text-truncate">
                                <label><input v-model="video.data.loop" class="uk-checkbox" type="checkbox"> {{ 'Loop' | trans }}</label><br>
                                <label v-show="!isVimeo && !isYoutube"><input v-model="video.data.muted" class="uk-checkbox" type="checkbox"> {{ 'Muted' | trans }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="!isYoutube && !isVimeo" class="uk-margin">
                    <label class="uk-form-label">{{ 'Poster Image' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-image v-model="video.data.poster" :input-field="false" class-name="uk-width-1-1" />
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

    name: 'VideoPicker',

    data() {
        return { video: { data: { src: '', controls: true } } };
    },

    computed: {

        isYoutube() {
            return this.video.data.src ? this.video.data.src.match(/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/) : false;
        },

        isVimeo() {
            return this.video.data.src ? this.video.data.src.match(/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/) : false;
        }

    },

    mounted() {
        this.$refs.modal.open();
    },

    methods: {

        close() {
            this.$destroy(true);
        },

        update() {
            this.$emit('select', this.video);
            this.$refs.modal.close();
        }

    }

};

</script>

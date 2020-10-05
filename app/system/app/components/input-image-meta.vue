<template>
    <div>
        <a v-if="!image.src" class="uk-placeholder uk-text-center uk-display-block uk-margin-remove" @click.prevent="pick">
            <span class="uk-text-muted" uk-icon="image" ratio="3" />
            <p class="uk-text-muted uk-margin-small-top">{{ 'Add Image' | trans }}</p>
        </a>

        <div v-else :class="['uk-inline-clip uk-transition-toggle uk-visible-toggle', className]">
            <img :data-src="$url(image.src)" uk-img>
            <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-default" />
            <a class="uk-position-cover" @click.prevent="pick" />
            <div class="uk-invisible-hover uk-position-top-right pk-panel-badge">
                <ul class="uk-iconnav">
                    <li><a v-confirm="'Reset image?'" class="uk-icon-link" uk-icon="icon: trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" /></li>
                </ul>
            </div>
        </div>

        <v-modal ref="modal">
            <form class="uk-form-stacked" @submit="update">
                <div class="uk-modal-header">
                    <h2 class="uk-h4">
                        <span uk-icon="image" ratio="1.1" /><span class="uk-text-middle uk-margin-small-left">{{ 'Image' | trans }}</span>
                    </h2>
                </div>

                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <input-image v-model="img.src" :input-field="false" class-name="uk-width-1-1" @image-selected="updateAttribute" />
                    </div>

                    <div class="uk-margin">
                        <label for="form-src" class="uk-form-label">{{ 'URL' | trans }}</label>
                        <div class="uk-form-controls">
                            <input id="form-src" v-model.lazy="img.src" class="uk-width-1-1 uk-input" type="text">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label for="form-alt" class="uk-form-label">{{ 'Alt' | trans }}</label>
                        <div class="uk-form-controls">
                            <input id="form-alt" v-model="img.alt" class="uk-width-1-1 uk-input" type="text">
                        </div>
                    </div>
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">
                        {{ 'Cancel' | trans }}
                    </button>
                    <button class="uk-button uk-button-primary" type="button" @click.prevent="update">
                        {{ 'Update' | trans }}
                    </button>
                </div>
            </form>
        </v-modal>
    </div>
</template>

<script>

const InputImageMeta = {

    props: ['value', 'className'],

    data() {
        return _.merge({
            img: {},
            image: {}
        }, $pagekit);
    },

    created() {
        this.image = this.value || { src: '', alt: '' };
        this.img = _.extend({}, this.image);
    },

    methods: {

        pick() {
            this.img.src = this.image.src;
            this.img.alt = this.image.alt;
            this.$refs.modal.open();
        },

        update() {
            this.image.src = this.img.src;
            this.image.alt = this.img.alt;
            this.$emit('input', this.image);
            this.$refs.modal.close();
        },

        remove() {
            this.img.src = '';
            this.image.src = '';
        },

        updateAttribute(path) {
            if (path && !this.img.alt) {
                const alt = path.split('/').slice(-1)[0].replace(/\.(jpeg|jpg|png|svg|gif)$/i, '').replace(/(_|-)/g, ' ').trim();
                const first = alt.charAt(0).toUpperCase();

                this.img.alt = first + alt.substr(1);
            }
        }
    }

};

Vue.component('InputImageMeta', (resolve, reject) => {
    Vue.asset({
        js: [
            'app/system/modules/finder/app/bundle/panel-finder.js'
        ]
    }).then(() => {
        resolve(InputImageMeta);
    });
});

export default InputImageMeta;

</script>

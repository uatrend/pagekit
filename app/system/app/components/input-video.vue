<template>
    <div>
        <a v-if="!source" class="uk-placeholder uk-display-block uk-text-muted uk-text-center uk-margin-remove" @click.prevent="pick">
            <span uk-icon="video" ratio="3" />
            <p class="uk-text-small uk-margin-small-top">{{ 'Select Video' | trans }}</p>
        </a>

        <div v-else :class="['uk-form-width-large uk-inline-clip uk-visible-toggle', className]">
            <img v-if="image" :src="image">
            <video v-if="video" controls class="uk-width-1-1" :src="video" uk-video="autoplay: false" />
            <div class="uk-invisible-hover uk-position-top-right pk-panel-badge">
                <ul class="uk-iconnav">
                    <li><a class="uk-icon-link" uk-icon="icon: file-edit" :title="'Edit' | trans" uk-tooltip="delay: 500" @click.prevent="pick" /></li>
                    <li><a v-confirm="'Reset video?'" class="uk-icon-link" uk-icon="icon: trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" /></li>
                </ul>
            </div>
        </div>

        <v-modal ref="modal" large>
            <panel-finder ref="finder" :root="storage" :modal="true" @finder-select="selectFinder" />

            <div class="uk-modal-footer">
                <div class="uk-flex uk-flex-middle uk-flex-between">
                    <div>
                        <div v-if="isFinder">
                            <span v-if="!finder.selected.length" class="uk-text-meta">{{ '{0} %count% Files|{1} %count% File|]1,Inf[ %count% Files' | transChoice(finder.count, {count: finder.count}) }}</span>
                            <span v-else class="uk-text-meta">{{ '{1} %count% File selected|]1,Inf[ %count% Files selected' | transChoice(finder.selected.length, {count:finder.selected.length}) }}</span>
                        </div>
                    </div>
                    <div>
                        <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">
                            {{ 'Cancel' | trans }}
                        </button>
                        <button class="uk-button uk-button-primary" type="button" :disabled="!choice" @click.prevent="select">
                            {{ 'Select' | trans }}
                        </button>
                    </div>
                </div>
            </div>
        </v-modal>
    </div>
</template>

<script>

const InputVideo = {

    props: ['value', 'className'],

    data() {
        return _.merge({
            image: undefined,
            video: undefined,
            source: this.value,
            choice: '',
            finder: {}
        }, $pagekit);
    },

    computed: {

        isFinder() {
            return !!((this.finder.hasOwnProperty('selected') && this.finder.selected));
        }

    },

    watch: {

        source: {
            handler: 'update',
            immediate: true
        }
    },

    mounted() {
        const vm = this;
        UIkit.util.on(this.$refs.modal.$el, 'shown', () => {
            vm.finder = vm.$refs.finder;
        });
    },

    methods: {
        selectFinder(val) {
            this.choice = this.selectButton();
        },

        selectButton() {
            if (!this.isFinder) return;
            const selected = this.$refs.finder.getSelected();
            return selected && selected.length === 1 && this.$refs.finder.isVideo(selected[0]);
        },

        pick() {
            this.$refs.modal.open();
        },

        select() {
            this.source = decodeURI(this.$refs.finder.getSelected()[0]);
            this.$emit('input', this.source);
            this.$emit('video-selected', this.source);
            this.$refs.finder.removeSelection();
            this.$refs.modal.close();
        },

        remove() {
            this.source = '';
            this.$emit('input', this.source);
            this.$emit('video-removed');
        },

        update(src) {
            const matches = (src.match(/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/));

            this.image = undefined;
            this.video = undefined;

            if (matches) {
                this.image = `//img.youtube.com/vi/${matches[1]}/hqdefault.jpg`;
            } else if (src.match(/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/)) {
                this.$http.get('http://vimeo.com/api/oembed.json', { params: { url: src }, cache: 10 }).then((res) => {
                    const { data } = res;
                    this.image = data.thumbnail_url;
                });
            } else {
                this.video = this.$url(src);
            }
        }

    }

};

Vue.component('InputVideo', (resolve, reject) => {
    Vue.asset({
        js: [
            'app/system/modules/finder/app/bundle/panel-finder.js'
        ]
    }).then(() => {
        resolve(InputVideo);
    });
});

export default InputVideo;

</script>

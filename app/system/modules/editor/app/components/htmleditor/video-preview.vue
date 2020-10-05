<template>
    <div v-if="!video.data.src" class="uk-panel uk-placeholder uk-placeholder-large uk-text-center uk-visible-toggle">
        <img width="60" height="60" :alt="'Placeholder Video' | trans" :src="$url('app/system/assets/images/placeholder-video.svg')">
        <p class="uk-text-small uk-text-muted uk-margin-small-top">
            {{ 'Add Video' | trans }}
        </p>

        <a class="uk-position-cover" @click.prevent="config" />

        <div class="uk-position-top-right pk-panel-badge uk-invisible-hover">
            <ul class="uk-iconnav">
                <li><a uk-icon="trash" :title="'Delete' | trans" data-uk-tooltip="{delay: 500}" @click.prevent="remove" /></li>
            </ul>
        </div>
    </div>

    <div v-else class="uk-form-width-large uk-inline-clip uk-transition-toggle uk-visible-toggle">
        <img v-if="imageSrc" :src="imageSrc">
        <video v-if="videoSrc" class="uk-responsive-width" :src="videoSrc" :width="width" :height="height" />

        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle" />

        <a class="uk-position-cover" @click.prevent="config" />

        <div class="uk-position-top-right pk-panel-badge uk-invisible-hover">
            <ul class="uk-iconnav">
                <li><a v-confirm="'Reset video?'" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" /></li>
            </ul>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        index: {
            type: String,
            default: ''
        }
    },

    data() {
        return { imageSrc: false, videoSrc: false, width: '', height: '' };
    },

    computed: {

        video() {
            return this.$parent.videos[this.index] || {};
        }

    },

    watch: {
        'video.data': {
            handler: 'update',
            immediate: true,
            deep: true
        }
    },

    methods: {

        config() {
            this.$parent.openModal(this.video);
        },

        remove() {
            this.video.replace('');
        },

        update(data) {
            let matches;

            this.$set(this, 'imageSrc', false);
            this.$set(this, 'videoSrc', false);
            this.$set(this, 'width', data.width || 690);
            this.$set(this, 'height', data.height || 390);

            const src = data.src || '';
            if (matches = (src.match(/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/))) { // eslint-disable-line no-cond-assign
                this.imageSrc = `//img.youtube.com/vi/${matches[1]}/hqdefault.jpg`;
            } else if (src.match(/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/)) {
                this.$http.get('http://vimeo.com/api/oembed.json', { params: { url: src }, cache: 10 }).then(function (res) {
                    this.imageSrc = res.data.thumbnail_url;
                });
            } else {
                this.videoSrc = this.$url(src);
            }
        }

    }

};

</script>

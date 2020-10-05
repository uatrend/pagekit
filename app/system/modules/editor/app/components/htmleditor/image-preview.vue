<template>
    <div v-if="!isSource" class="uk-panel uk-placeholder uk-placeholder-large uk-text-center uk-visible-toggle">
        <img width="60" height="60" :alt="'Placeholder Image' | trans" :src="$url('app/system/assets/images/placeholder-image.svg')">
        <p class="uk-text-muted uk-margin-small-top">
            {{ 'Add Image' | trans }}
        </p>
        <a class="uk-position-cover" @click.prevent="config" />
        <div class="uk-position-top-right pk-panel-badge uk-invisible-hover">
            <ul class="uk-iconnav">
                <li><a uk-icon="trash" :title="'Delete' | trans" data-uk-tooltip="{delay: 500}" @click.prevent="remove" /></li>
            </ul>
        </div>
    </div>

    <div v-else class="uk-inline-clip uk-transition-toggle uk-visible-toggle">
        <img v-if="hasAttribute('uk-img')" :data-src="$url(image.data['data-src'])" :src="$url(image.data.src)" :alt="image.data.alt" :uk-img="image.data['uk-img'] ? $url(image.data['uk-img']) : ''">
        <img v-else-if="hasAttribute('uk-svg')" :data-src="$url(image.data['data-src'])" :src="$url(image.data.src)" :alt="image.data.alt" :uk-svg="image.data['uk-svg'] ? $url(image.data['uk-svg']) : ''">
        <img v-else-if="image.data.src" :src="$url(image.data.src)" :alt="image.data.alt">

        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle" />

        <a class="uk-position-cover" @click.prevent="config" />

        <div class="uk-position-top-right pk-panel-badge uk-invisible-hover">
            <ul class="uk-iconnav">
                <li><a v-confirm="'Reset image?'" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" /></li>
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

    computed: {

        image() {
            return this.$parent.images[this.index] || {};
        },

        isSource() {
            const imageComponent = this.image.data['uk-img'] || this.image.data['uk-svg'];

            return imageComponent || this.image.data['data-src'] || this.image.data.src;
        }

    },

    methods: {

        hasAttribute(prop) {
            return this.image.data.hasOwnProperty(prop);
        },

        config() {
            this.$parent.openModal(this.image);
        },

        remove() {
            this.image.replace('');
        }

    }

};

</script>

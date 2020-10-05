<template>
    <a :class="['uk-link', link.class]" :href="link.link" @click.prevent="$parent.openModal(link)">
        <span v-if="link.txt" v-html="link.txt" />
        <span v-else>{{ 'Select Link' | trans }}</span>
        <span uk-icon="link" />
    </a>
</template>

<script>

export default {

    name: 'LinkPreview',

    props: {
        index: {
            type: String,
            default: ''
        }
    },

    computed: {

        link() {
            return this.$parent.links[this.index] || {};
        }

    }

};

</script>

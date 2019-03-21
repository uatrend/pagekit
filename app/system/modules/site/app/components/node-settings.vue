<template>
    <div class="uk-form-horizontal">
        <component :is="'template-settings'" :node="node" :roles="roles" />
    </div>
</template>

<script>

export default {

    props: ['node', 'roles'],

    section: {
        label: 'Settings',
    },

    created() {
        this.$options.components['template-settings'] = this.$root.$options.components['template-settings'];
    },

};

</script>

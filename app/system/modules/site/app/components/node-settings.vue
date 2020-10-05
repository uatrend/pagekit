<template>
    <div class="uk-form-horizontal">
        <component :is="'template-settings'" v-model="node" :roles="roles" />
    </div>
</template>

<script>

import NodeMixin from '../mixins/node-mixin';

export default {

    mixins: [NodeMixin],

    section: { label: 'Settings' }

};

</script>

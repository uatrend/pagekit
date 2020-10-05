<template>
    <div>
        <div class="uk-margin">
            <label class="uk-form-label">{{ 'Extension' | trans }}</label>
            <div class="uk-form-controls">
                <select v-model="type" class="uk-width-1-1 uk-select">
                    <option v-for="(type, key) in types" :key="key" :value="type.value">
                        {{ type.text | trans }}
                    </option>
                </select>
            </div>
        </div>

        <component :is="type" v-if="type" v-model="link" />
    </div>
</template>

<script>

const PanelLink = {

    components: {},

    data() {
        return {
            type: false,
            link: ''
        };
    },

    computed: {

        types() {
            const types = [];

            _.forIn(this.$options.components, (component, name) => {
                if (component.link) {
                    types.push({ text: component.link.label, value: name });
                }
            });

            return _.sortBy(types, 'text');
        }

    },

    watch: {

        type: {
            handler(type) {
                if (!type && this.types.length) {
                    this.type = this.types[0].value;
                }
            },
            immediate: true
        },

        link(link) {
            this.$emit('link-changed', link);
        }

    }

};

Vue.component('PanelLink', (resolve) => {
    resolve(PanelLink);
});

export default PanelLink;

</script>

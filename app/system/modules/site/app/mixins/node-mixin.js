export default {

    inject: ['$components'],

    props: ['roles', 'value'],

    data() {
        return {
            node: this.value
        };
    },

    watch: {

        value(val) {
            this.node = val;
        },

        node(val) {
            this.$emit('input', val);
        }

    },

    created() {
        _.extend(this.$options.components, this.$components);
    }
};

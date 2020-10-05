export default {

    inject: ['$components'],

    props: ['config', 'value'],

    data() {
        return {
            user: this.value
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

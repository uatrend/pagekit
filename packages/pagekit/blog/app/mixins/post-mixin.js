export default {

    props: ['value', 'data'],

    inject: ['$components'],

    data() {
        return {
            post: this.value
        };
    },

    watch: {

        value(val) {
            this.post = val;
        },

        post(val) {
            this.$emit('input', val);
        }
    },

    created() {
        _.extend(this.$options.components, this.$components);
    }
};

export default {

    inject: ['$components'],

    props: ['value'],

    data() {
        return {
            config: this.value
        };
    },

    watch: {

        value(cfg) {
            this.config = cfg;
        },

        config(cfg) {
            this.$emit('input', cfg);
        }

    },

    created() {
        _.extend(this.$options.components, this.$components);
    }

};

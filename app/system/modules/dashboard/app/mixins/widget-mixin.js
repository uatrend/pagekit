export default {

    props: ['value', 'editing'],

    replace: false,

    data() {
        return {
            widget: this.value
        };
    },

    watch: {

        widget: {
            handler(val) {
                this.$emit('input', val);
            },
            deep: true
        }

    }
};

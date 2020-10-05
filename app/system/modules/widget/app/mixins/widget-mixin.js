export default {

    props: ['config', 'value'],

    inject: ['$components'],

    data() {
        return {
            widget: this.value
        };
    },

    watch: {

        value(widget) {
            this.widget = widget;
        },

        widget(widget) {
            this.$emit('input', widget);
        }
    },

    created() {
        _.extend(this.$options.components, this.$components);
    }
};

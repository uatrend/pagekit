export default {

    props: ['name', 'settings'],

    data() {
        return _.extend({}, this.settings);
    },

    computed: {

        moduleConfig() {
            return this.config[this.name];
        },

        moduleOptions() {
            return this.options[this.name];
        }

    },

    created() {
        this.updateData(this.settings);
    },

    mounted() {
        _.forEach(this.$data, (value, key) => {
            if (!this.settings.hasOwnProperty(key)) return;
            this.$watch(key, (data, prev) => {
                this.$trigger('settings-changed', { key, data });
            }, { deep: true });
        });
    },

    methods: {

        updateData(settings) {
            _.forEach(settings, (value, key) => {
                if (_.isEqual(this[key], value)) return;
                this.$set(this, key, _.merge({}, value));
            });
        }

    }
};

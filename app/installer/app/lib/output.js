module.exports = {

    data() {
        return {
            pkg: {},
            updatePkg: {},
            output: '',
            status: 'loading',
            options: {
                bgClose: false,
                escClose: false,
            },
        };
    },

    created() {
        this.$mount();
    },

    methods: {

        init(request) {
            const vm = this;

            this.open();

            return vm.setOutput(request.responseText);
        },

        setOutput(output) {
            const lines = output.split('\n');
            const match = lines[lines.length - 1].match(/^status=(success|error)$/);

            if (match) {
                this.status = match[1];
                delete lines[lines.length - 1];
                this.output = lines.join('\n');
            } else {
                this.output = output;
            }
        },

        open() {
            this.$refs.output.open();
            UIkit.util.on(this.$refs.output.modal.$el, 'hide', this.onClose);
        },

        close() {
            this.$refs.output.close();
        },

        onClose() {
            if (this.cb) {
                this.cb(this);
            }

            this.$destroy();
        },

    },

    watch: {
        status() {
            if (this.status !== 'loading') {
                this.$refs.output.modal.$options.props.bgclose = true;
                this.$refs.output.modal.$options.props.keyboard = true;
            }
        },
    },

};

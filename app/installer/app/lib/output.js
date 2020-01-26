const on = UIkit.util.on;

export default {

    data() {
        return {
            pkg: {},
            updatePkg: {},
            output: '',
            status: 'loading',
            options: () => ({
                bgClose: false,
                escClose: false,
            }),
            showOutput: false,
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
            this.showOutput = true;

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
            on(this.$refs.output.modal.$el, 'hidden', this.onClose);
        },

        close() {
            this.$refs.output.close();
        },

        onClose() {
            if (this.cb) {
                this.cb(this);
            }

            this.$destroy();
        }
    },

    watch: {
        status() {
            if (this.status !== 'loading') {
                // TODO
                // this.$refs.output.modal.$options.props.bgClose = true;
                //this.$refs.output.modal.$options.props.escClose = true;
            }
        },
    },

};

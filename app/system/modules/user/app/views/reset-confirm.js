var util        = UIkit.util,
    $           = util.$,
    on          = util.on,
    html        = util.html,
    append      = util.append;

module.exports = {

    el: '#reset-confirm',

    data() {
        return {
            error: null,
            hidePassword: true
        }
    },

    methods: {

        submit() {

            const vm = this;

            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.$refs.resetform.submit()
                }
            })

        }
    }

};

Vue.ready(module.exports);
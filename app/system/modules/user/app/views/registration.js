module.exports = {

    name: 'registration',

    el: '#user-registration',

    data() {
        return {
            user: {},
            error: null,
            hidePassword: true
        }
    },

    mounted() {
        this.$nextTick(function(){
            UIkit.util.removeAttr(this.$el, 'hidden');
        }.bind(this))
    },

    methods: {

        valid() {
            const vm = this;

            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.submit();
                }
            })
        },

        submit() {
            this.error = null;

            this.$http.post('user/registration/register', { user: this.user }).then((res) => {
                window.location.replace(res.data.redirect);
            }, function (error) {
                this.error = error.data;
            });
        },

    },

};

Vue.ready(module.exports);

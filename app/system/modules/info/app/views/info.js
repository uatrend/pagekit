const Info = {

    name: 'info',

    el: '#info',

    mixins: [Theme.Mixins.Helper],

    data: { info: window.$info },

    theme: {
        hideEls: ['.pk-width-content li > h2']
    },

    computed: {
        VueVersion() {
            return window.Vue ? Vue.version : '-';
        },
        UIkitVersion() {
            return window.UIkit ? UIkit.version : '-';
        }
    }

};

export default Info;

Vue.ready(Info);

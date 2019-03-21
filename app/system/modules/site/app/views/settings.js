window.Site = {

    name: 'site-settings',

    el: '#settings',

    mixins: [Vue2Filters.mixin],

    data() {
        return _.merge({ form: {} }, window.$data);
    },

    mounted() {
        UIkit.switcher(this.$refs.tab, { connect: '.settings-tab' });
    },

    computed: {

        sections() {
            const sections = []; const
                hash = window.location.hash.replace('#', '');

            _.forIn(this.$options.components, (component, name) => {
                const { section } = component;

                if (component.section) {
                    section.name = name;
                    section.active = name == hash;
                    sections.push(section);
                }
            });

            return sections;
        },

    },

    methods: {

        submit() {
            const vm = this;

            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.save();
                }
            });
        },

        save() {
            this.$trigger('save:settings', this.config);

            this.$http.post('admin/system/settings/config', { name: 'system/site', config: this.config }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

    },

    components: {

        'site-code': require('../components/site-code.vue').default,
        'site-meta': require('../components/site-meta.vue').default,
        'site-general': require('../components/site-general.vue').default,
        'site-maintenance': require('../components/site-maintenance.vue').default,

    },

};

Vue.ready(window.Site);

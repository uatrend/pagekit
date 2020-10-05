import LocaleComponent from './components/locale.vue';
import SystemComponent from './components/system.vue';
import MiscComponent from './components/misc.vue';

window.Settings = {

    name: 'settings',

    el: '#settings',

    mixins: [Theme.Mixins.Helper, Theme.Mixins.UIElements],

    data() {
        return {
            settings: _.merge({}, window.$settings)
        };
    },

    theme: {
        hideEls: ['.pk-width-content li > div > div.uk-flex'],
        elements() {
            const vm = this;
            return {
                title: {
                    scope: 'breadcrumbs',
                    type: 'caption',
                    caption: () => {
                        const activeTab = vm.$theme.getActiveTab('leftTab', vm);
                        const section = vm.sections.filter((s) => s.name === activeTab)[0];
                        return section && vm.$trans(section.label);
                    }
                },
                submit: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button uk-button-primary',
                    on: { click: () => vm.save() },
                    priority: 0
                }
            };
        }
    },

    mounted() {
        this.$ui.tab('leftTab', this.$refs.tab, { connect: this.$refs.content, state: true });
    },

    computed: {

        sections() {
            const sections = [];
            _.forIn(this.$options.components, (component, name) => {
                const { section } = component;

                if (section) {
                    section.name = name;
                    sections.push(section);
                }
            });
            return _.orderBy(sections, 'priority');
        }

    },

    methods: {

        save() {
            this.$trigger('settings-save', this.settings);
            this.$resource('admin/system/settings/save').save({ config: this.settings.config, options: this.settings.options }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

        get(name) {
            return name.replace('-', '/');
        },

        changed($event, settings) {
            this.$set(this.settings, settings.key, settings.data);
        }

    },

    components: {
        locale: LocaleComponent,
        system: SystemComponent,
        misc: MiscComponent
    },

    events: {

        'settings-changed': 'changed'

    }

};

Vue.ready(window.Settings);

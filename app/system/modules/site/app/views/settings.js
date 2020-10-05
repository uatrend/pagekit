import { ValidationObserver, VInput } from '@system/app/components/validation.vue';
import SiteCode from '../components/site-code.vue';
import SiteMeta from '../components/site-meta.vue';
import SiteGeneral from '../components/site-general.vue';
import SiteMaintenance from '../components/site-maintenance.vue';

window.Site = {

    name: 'site-settings',

    el: '#settings',

    mixins: [Theme.Mixins.Helper, Theme.Mixins.UIElements],

    provide: { $components: { 'v-input': VInput } },

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
                    on: { click: () => vm.submit() },
                    priority: 0
                }
            };
        }
    },

    data() {
        return _.merge({ form: {} }, window.$data);
    },

    mounted() {
        this.$ui.tab('leftTab', this.$refs.tab, { connect: this.$theme.getDomElement(this.$refs.content), state: true });
    },

    computed: {

        sections() {
            const sections = [];
            const hash = window.location.hash.replace('#', '');

            _.forIn(this.$options.components, (component, name) => {
                const { section } = component;

                if (component.section) {
                    section.name = name;
                    section.active = name === hash;
                    sections.push(section);
                }
            });

            return sections;
        }

    },

    methods: {

        async submit() {
            const isValid = await this.$refs.observer.validate();
            if (isValid) {
                this.save();
            }
        },

        save() {
            this.$trigger('settings-save', this.config);

            this.$http.post('admin/system/settings/config', { name: 'system/site', config: this.config }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        }

    },

    components: {
        'site-code': SiteCode,
        'site-meta': SiteMeta,
        'site-general': SiteGeneral,
        'site-maintenance': SiteMaintenance,
        'validation-observer': ValidationObserver
    }

};

Vue.ready(window.Site);

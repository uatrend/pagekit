import SiteCode from '../components/site-code.vue';
import SiteMeta from '../components/site-meta.vue';
import SiteGeneral from '../components/site-general.vue';
import SiteMaintenance from '../components/site-maintenance.vue';

import { ValidationObserver, VInput } from 'SystemApp/components/validation.vue';

window.Site = {

    name: 'site-settings',

    el: '#settings',

    mixins: [Theme.Mixins.Helper, Theme.Mixins.Elements],

    provide: {
        '$components': {
            'v-input': VInput
        }
    },

    theme: {
        hiddenHtmlElements: ['.pk-width-content li > div > div.uk-flex'],
        elements() {
            var vm = this;
            return {
                'title': {
                    scope: 'breadcrumbs',
                    type: 'caption',
                    caption: () => {
                        let trans = vm.$options.filters.trans,
                            activeTab = vm.$theme.activeTab('leftTab', vm),
                            section = vm.sections.filter((section)=>section.name === activeTab)[0];
                        return vm.$trans(section.label);
                    }
                },
                'submit': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button uk-button-primary',
                    on: {click: () => vm.submit()},
                    priority: 0,
                }
            }
        },
    },

    data() {
        return _.merge({ form: {} }, window.$data);
    },

    created() {
        this.$theme.$tabs('leftTab', '#settings .uk-nav', { connect: '.settings-tab', state: true });
    },

    mounted() {
        // UIkit.switcher(this.$refs.tab, { connect: '.settings-tab' });
    },

    computed: {

        sections() {
            const sections = [];
            const hash = window.location.hash.replace('#', '');

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

        async submit() {
            const isValid = await this.$refs.observer.validate();
            if (isValid) {
                this.save();
            }
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
        'site-code': SiteCode,
        'site-meta': SiteMeta,
        'site-general': SiteGeneral,
        'site-maintenance': SiteMaintenance,
        'validation-observer': ValidationObserver
    },

};

Vue.ready(window.Site);

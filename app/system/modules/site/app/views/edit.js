import NodeSettings from '../components/node-settings.vue';
import NodeLink from '../components/node-link.vue';
import TemplateSettings from '../templates/settings.html';

window.Site = {

    name: 'page',

    el: '#site-edit',

    mixins: [Theme.Mixins.Helper],

    data() {
        return _.merge({
            sections: [], form: {}, active: 0, processing: false,
        }, window.$data);
    },

    theme: {
        hiddenHtmlElements: ['#site-edit > div:first-child'],
        elements() {
            var vm = this;
            return {
                'title': {
                    scope: 'breadcrumbs',
                    type: 'caption',
                    caption: () => {
                        let trans = this.$options.filters.trans;
                        return vm.node.id && trans ? trans('Edit %type%', {type:vm.type.label}) : trans('Add %type%', {type:vm.type.label})
                    }
                },
                'savepage': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button tm-button-success',
                    spinner: () => vm.processing,
                    on: {click: () => vm.submit()},
                    priority: 1,
                },
                'close': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: vm.node.id ? 'Close' : 'Cancel',
                    class: 'uk-button uk-button-text',
                    attrs: {
                        href: () => vm.$url.route('admin/site/page')
                    },
                    disabled: () => vm.processing,
                    priority: 0,
                }
            }
        }
    },

    created() {
        let sections = []; const type = _.kebabCase(this.type.id); let active; const
            vm = this;

        _.forIn(this.$options.components, (component, name) => {
            if (component.section) {
                sections.push(_.extend({ name, priority: 0 }, component.section));
            }
        });

        sections = _.sortBy(sections.filter((section) => {
            active = section.name.match('(.+)--(.+)');

            if (active === null) {
                return !_.find(sections, { name: `${type}--${section.name}` });
            }

            return active[1] == type;
        }, this), 'priority');

        this.$set(this, 'sections', sections);
    },

    mounted() {
        const vm = this;

        this.Nodes = this.$resource('api/site/node{/id}');

        // this.tab = UIkit.tab(this.$els.tab, {connect: this.$els.content});
        this.tab = UIkit.tab('#page-tab', { connect: '#page-content' });

        UIkit.util.on(this.tab.connects, 'show', (e, tab) => {
            if (tab != vm.tab) return false;
            for (const index in tab.toggles) {
                if (tab.toggles[index].parentNode.classList.contains('uk-active')) {
                    vm.active = index;
                    break;
                }
            }
        });

        this.$watch('active', function (active) {
            this.tab.show(active);
        });

        this.$state('active');
    },

    computed: {

        path() {
            return `${this.node.path ? this.node.path.split('/').slice(0, -1).join('/') : ''}/${this.node.slug || ''}`;
        },

    },

    filers: {

    },

    methods: {

        submit() {
            const vm = this;

            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.processing = true;
                    vm.save();
                }
            });
        },

        save() {
            const data = { node: this.node }; const
                vm = this;

            this.$trigger('save:node', data);

            this.Nodes.save({ id: this.node.id }, data).then(function (res) {
                const { data } = res;
                if (!this.node.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/site/page/edit', { id: data.node.id }));
                }

                this.$set(this, 'node', data.node);

                this.$notify(this.$trans('%type% saved.', { type: this.type.label }));
                setTimeout(() => {
                    vm.processing = false;
                }, 500);
            }, function (res) {
                this.processing = false;
                this.$notify(res.data, 'danger');
            });
        },

    },

    components: {
        settings: NodeSettings,
        'link--settings': NodeLink,
        'template-settings': { inject: ['$validator'], props: ['node', 'roles'], template: TemplateSettings },
    },

};

Vue.ready(window.Site);

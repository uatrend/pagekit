import UIkit from 'uikit';
import { $, on, toNodes, each, findAll } from 'uikit-util';

import Version from '@installer/app/lib/version';

import Panel from '../components/widget-panel.vue';
import Feed from '../components/widget-feed.vue';
import Location from '../components/widget-location.vue';

window.Dashboard = {

    name: 'dashboard',

    mixins: [Theme.Mixins.Helper],

    el: '#dashboard',

    provide() {
        return {
            $components: this.$options.components
        };
    },

    data() {
        return _.extend({
            editing: {},
            update: {}
        }, window.$data);
    },

    theme: {
        hideEls: '#dashboard > div:first-child > div:last-child',
        elements() {
            const vm = this;
            return {
                addwidget: {
                    scope: 'topmenu-left',
                    type: 'dropdown',
                    caption: 'Add Widget',
                    class: 'uk-button uk-button-text',
                    icon: { attrs: { 'uk-icon': 'triangle-down' } },
                    dropdown: { options: () => 'mode: click' },
                    items: () => vm.getTypes().map((type) => {
                        const props = {
                            on: { click: () => vm.add(type) },
                            caption: type.label,
                            class: 'uk-dropdown-close'
                        };
                        return { ...type, ...props };
                    })
                }
            };
        }
    },

    created() {
        const self = this;

        this.Widgets = this.$resource('admin/dashboard{/id}');

        this.$set(this, 'widgets', this.widgets.filter((widget, idx) => {
            if (self.getType(widget.type)) {
                widget.idx = widget.idx === undefined ? idx : widget.idx;
                widget.column = widget.column === undefined ? 0 : widget.column;

                return true;
            }

            return false;
        }));

        this.checkVersion();

        on(window, 'load', this.load);
    },

    computed: {

        columns() {
            let i = 0;
            return _.groupBy(this.widgets, () => i++ % 3);
        },

        hasUpdate() {
            return this.update && Version.compare(this.update.version, this.version, '>');
        }

    },

    methods: {

        load() {
            const self = this;

            // widget re-ordering
            const sortables = findAll('.pk-sortable', $(this.$el));

            sortables.forEach((el) => {
                const sortableItem = UIkit.sortable(el, { group: 'widgets' });

                on(sortableItem.$el, 'added moved removed', (e, sortable, item) => {
                    const mode = e.type;

                    if (!mode) {
                        return;
                    }

                    switch (mode) {
                        case 'added':
                        case 'moved':
                        case 'removed': {
                            const { widgets } = self;
                            const column = Number.parseInt(UIkit.util.data(sortable.$el, 'column'), 10);
                            const data = {};
                            let widget;

                            each(findAll('[data-idx]', $(sortable.$el)), (i, idx) => {
                                widget = _.find(widgets, { id: i.getAttribute('data-id') });
                                widget.column = column;
                                widget.idx = idx;
                            });

                            widgets.forEach((w) => { data[w.id] = w; });

                            self.$http.post('admin/dashboard/savewidgets', { widgets: data }).then((res) => {
                                // cleanup empty items - maybe fixed with future vue.js version
                                // sortables.children().forEach(function () {
                                // if (!this.children.length) $(this).remove();
                                // });
                            });
                            break;
                        }
                        default:
                    }
                });
            });
        },

        getColumn(column) {
            column = parseInt(column || 0, 10);
            return _.sortBy(this.widgets.filter((widget) => widget.column === column), 'idx');
        },

        add(type) {
            let column = 0;
            const sortables = findAll('.uk-sortable[data-column]', $('#dashboard'));

            sortables.forEach((el, idx) => {
                column = (el.children.length < toNodes(sortables)[0].children.length) ? idx : column;
            });

            this.Widgets.save({ widget: _.merge({ type: type.id, column, idx: 100 }, type.defaults) }).then(function (res) {
                const { data } = res;
                this.widgets.push(data);
                this.editing[data.id] = true;
            });
        },

        save(widget) {
            const data = { widget };
            this.$trigger('widget-save', data);
            this.Widgets.save({ id: widget.id }, data);
        },

        remove(widget) {
            this.Widgets.delete({ id: widget.id }).then(function () {
                this.widgets.splice(_.findIndex(this.widgets, { id: widget.id }), 1);
            });
        },

        getType(id) {
            return _.find(this.getTypes(), { id });
        },

        getTypes() {
            const types = [];
            _.forIn(this.$options.components, (component, name) => {
                const { type } = component;
                if (type) {
                    type.component = name;
                    types.push(type);
                }
            });

            return types;
        },

        checkVersion() {
            this.$http.get(`${this.api}/api/update`, { params: { cache: 60 } }).then(function (res) {
                const update = res.data[this.channel === 'nightly' ? 'nightly' : 'latest'];

                if (update) {
                    this.$set(this, 'update', update);
                }
            });
        }

    },

    components: {

        panel: Panel,
        feed: Feed,
        location: Location

    }

};

Vue.ready(window.Dashboard);

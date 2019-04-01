import {
    $, on, css, addClass, removeClass, hasClass, toNodes,
} from 'uikit-util';
import { VueNestable, VueNestableHandle } from 'vue-nestable';

// device detection
window.isMobile = false; // initiate as false
if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) { window.isMobile = true; }
const is_iPad = navigator.userAgent.match(/iPad/i) != null;
if (is_iPad) { window.isMobile = true; }

Vue.ready({

    name: 'site',

    el: '#site',

    mixins: [Vue2Filters.mixin],

    data() {
        return _.merge({
            edit: {}, // undefined,
            menu: this.$session.get('site.menu', {}),
            menus: [],
            nodes: [],
            treedata: [],
            selected: [],
        }, window.$data);
    },

    created() {
        this.Menus = this.$resource('api/site/menu{/id}');
        this.Nodes = this.$resource('api/site/node{/id}');

        const vm = this;
        this.load().then(() => {
            vm.$set(vm, 'menu', _.find(vm.menus, { id: vm.menu.id }) || vm.menus[0]);
        });

        this.$watch(vm => (vm.menu, vm.nodes, Date.now()), () => {
            this.tree('update');
        }, { deep: true });

        on(window, 'resize', () => {
            this.propWidth();
        });
    },

    mounted() {},

    methods: {

        propWidth() {
            css(this.$refs['table-header'], {
                minWidth: '100%',
                width: this.$refs.nestable.$el.offsetWidth ? this.$refs.nestable.$el.offsetWidth : '100%',
                opacity: '1',
            });
        },

        change(value, options) {
            if (!options || !options.pathTo) return;

            const vm = this;

            var updateTree = (tree, parent_id) => {
                _.forEach(tree, (item, id) => {
                    item.priority = id;
                    item.parent_id = parent_id;
                    if (item.children.length) updateTree(item.children, item.id);
                });
            };

            updateTree(this.treedata, 0);

            vm.Nodes.save({ id: 'updateOrder' }, {
                menu: vm.menu.id,
                nodes: vm.tree('flatten'), // vm.nestableList(this.treedata)
            }).then(vm.load, () => {
                vm.$notify('Reorder failed.', 'danger');
            });
        },

        tree(...args) {
            const [fn, ...props] = arguments; const vm = this;
            const methods = {
                unflatten() {
                    let [array, parent, tree] = arguments; const
                        self = this;

                    tree = typeof tree !== 'undefined' ? tree : [];
                    parent = typeof parent !== 'undefined' ? parent : { id: 0 };

                    const children = _.filter(array, child => child.parent_id == parent.id);

                    if (!_.isEmpty(children)) {
                        if (parent.id == 0) {
                            tree = children;
                        } else {
                            parent.children = children;
                        }
                        _.each(children, (child) => { self.unflatten(array, child); });
                    }

                    return tree;
                },
                flatten() {
                    const treeStructure = { children: vm.treedata };

                    const flatten = (children, extractChildren, level, order) => Array.prototype.concat.apply(
                        children.map(x => ({ ...x, level: level || 1, order: x.priority || 0 })),
                        children.map(x => flatten(extractChildren(x) || [], extractChildren, (level || 1) + 1)),
                    );

                    const extractChildren = x => x.children;

                    const flat = flatten(extractChildren(treeStructure), extractChildren).map(x => delete x.children && x);

                    return flat;
                },
                update() {
                    let nodes = vm.nodes.map((entry) => { entry.class = 'check-item'; return entry; });
                    nodes = _(nodes).filter({ menu: vm.menu.id }).sortBy('priority').value();
                    vm.treedata = this.unflatten(nodes);
                    vm.$nextTick(() => {
                        vm.propWidth();
                    });
                },
            };

            return methods[fn] && (typeof methods[fn] === 'function') ? methods[fn](props) : false;
        },

        load() {
            const vm = this;
            return Vue.Promise.all([
                this.Menus.query(),
                this.Nodes.query(),
            ]).then((responses) => {
                vm.$set(vm, 'menus', responses[0].data);
                vm.$set(vm, 'nodes', responses[1].data);
                vm.$set(vm, 'selected', []);

                if (!_.find(vm.menus, { id: vm.menu.id })) {
                    vm.$set(vm, 'menu', vm.menus[0]);
                }
            }, () => {
                vm.$notify('Loading failed.', 'danger');
            });
        },

        isActive(menu) {
            return this.menu && this.menu.id === menu.id;
        },

        selectMenu(menu) {
            this.$set(this, 'selected', []);
            this.$set(this, 'menu', menu);
            this.$session.set('site.menu', menu);
        },

        removeMenu(e, menu) {
            this.Menus.delete({ id: menu.id }).finally(this.load);
        },

        editMenu(e, menu) {
            if (!menu) {
                menu = {
                    id: '',
                    label: '',
                };
            }

            this.$set(this, 'edit', _.merge({ positions: [] }, menu));
            this.$refs.modal.open();
        },

        saveMenu(menu) {
            this.Menus.save({ menu }).then(this.load, function (res) {
                this.$notify(res.data, 'danger');
            });

            this.cancel();
        },

        getMenu(position) {
            return _.find(this.menus, menu => _.includes(menu.positions, position));
        },

        cancel() {
            this.$refs.modal.close();
        },

        status(status) {
            const nodes = this.getSelected();

            nodes.forEach((node) => {
                node.status = status;
            });

            this.Nodes.save({ id: 'bulk' }, { nodes }).then(function () {
                this.load();
                this.$notify('Page(s) saved.');
            });
        },

        moveNodes(menu) {
            const vm = this;
            const nodes = this.getSelected();

            var updateChilds = function (node) {
                _.forEach(node.children, (item) => {
                    const search = _.filter(nodes, e => e.id == item.id);
                    if (!search.length) {
                        const key = Object.keys(vm.nodes).find(key => vm.nodes[key].id === item.id);
                        vm.nodes[key].parent_id = null;
                        nodes.push(vm.nodes[key]);
                    }
                    if (item.children) updateChilds(item);
                });
            };

            nodes.forEach((node) => {
                node.parent_id = null;
                node.menu = menu;
                updateChilds(node);
            });

            this.Nodes.save({ id: 'bulk' }, { nodes }).then(function () {
                this.load();
                this.$notify(this.$trans('Pages moved to %menu%.', {
                    menu: _.find(this.menus.concat({
                        id: 'trash',
                        label: this.$trans('Trash'),
                    }), { 'id': menu}).label,
                }));
            });
        },

        removeNodes() {
            if (this.menu.id !== 'trash') {
                const nodes = this.getSelected();

                nodes.forEach((node) => {
                    node.status = 0;
                });

                this.moveNodes('trash');
            } else {
                this.Nodes.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                    this.load();
                    this.$notify('Page(s) deleted.');
                });
            }
        },

        getType(node) {
            return _.find(this.types, { id: node.type });
        },

        getSelected() {
            return this.nodes.filter(function (node) {
                return this.isSelected(node);
            }, this);
        },

        isSelected(node, children) {
            const vm = this;
            if (_.isArray(node)) {
                return _.every(node, node => vm.isSelected(node, children), this);
            }

            return this.selected.indexOf(node.id) !== -1 && (!children || !this.tree[node.id] || this.isSelected(this.tree[node.id], true));
        },

        toggleSelect(node) {
            const index = this.selected.indexOf(node.id);

            if (index == -1) {
                this.selected.push(node.id);
            } else {
                this.selected.splice(index, 1);
            }
        },

        label(id) {
            return _.result(_.find(this.menus, 'id', id), 'label');
        },

        protected(types) {
            return _.reject(types, { protected: true });
        },

        trash(menus) {
            return _.reject(menus, { id: 'trash' });
        },

        divided(menus) {
            return _.reject(menus, { fixed: true }).concat({ divider: true }, _.filter(menus, { fixed: true }));
        },

        menuLabel(id) {
            return this.$trans('(Currently set to: %menu%)', { menu: this.label(id) });
        },

        isFrontpage(node) {
            return node.url === '/';
        },

        type(node) {
            return this.getType(node) || {};
        },

        setFrontpage(node) {
            this.Nodes.save({ id: 'frontpage' }, { id: node.id }).then(function () {
                this.load();
                this.$notify('Frontpage updated.');
            });
        },

        toggleStatus(node) {
            node.status = node.status === 1 ? 0 : 1;

            this.Nodes.save({ id: node.id }, { node }).then(function () {
                this.load();
                this.$notify('Page saved.');
            });
        },

    },

    computed: {

        showDelete() {
            const vm = this;
            return this.showMove && _.every(this.getSelected(), node => !(vm.getType(node) || {}).protected, this);
        },

        showMove() {
            return this.isSelected(this.getSelected(), true);
        },

        isMobile() {
            return window.isMobile;
        },
    },

    components: {
        VueNestable,
        VueNestableHandle,
    },

});

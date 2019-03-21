module.exports = {

    name: 'widget',

    el: '#widget-edit',

    mixins: [window.Widgets],

    data() {
        return _.merge({
            form: {}, sections: [], active: 0, processing: false,
        }, window.$data);
    },

    created() {
        let sections = []; const type = _.kebabCase(this.widget.type); let
            active;

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
        this.tab = UIkit.tab('#widget-tab', { connect: '#widget-content' });

        const vm = this;

        UIkit.util.on(this.tab.connects, 'show', (e, tab, sel) => {
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

        // set position from get param
        if (!this.widget.id) {
            const match = new RegExp('[?&]position=([^&]*)').exec(location.search);
            this.widget.position = (match && decodeURIComponent(match[1].replace(/\+/g, ' '))) || '';
        }
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
            const vm = this;

            this.$trigger('save:widget', { widget: this.widget });

            this.$resource('api/site/widget{/id}').save({ id: this.widget.id }, { widget: this.widget }).then(function (res) {
                const { data } = res;

                this.$trigger('saved:widget');

                if (!this.widget.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/site/widget/edit', { id: data.widget.id }));
                }

                this.$set(this, 'widget', data.widget);

                this.$notify('Widget saved.');
                setTimeout(() => {
                    vm.processing = false;
                }, 500);
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

        cancel() {
            // TODO
            this.$trigger('cancel:widget');
        },

    },

};

Vue.ready(module.exports);

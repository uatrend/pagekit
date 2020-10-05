<template>
    <div id="pk-profiler" class="pf-profiler">
        <div class="pf-navbar">
            <ul v-if="data" class="pf-navbar-nav">
                <li v-for="section in sections" :key="section.name">
                    <component :is="section.name" :data="data[section.name == 'TimeComponent' ? 'Time' : section.name]" @click.native="open(section.name)" />
                </li>
            </ul>

            <a v-if="panel" class="pf-close" @click.prevent="close">
                <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" data-svg="close-icon">
                    <line fill="none" stroke="#000" stroke-width="1.1" x1="1" y1="1" x2="13" y2="13" />
                    <line fill="none" stroke="#000" stroke-width="1.1" x1="13" y1="1" x2="1" y2="13" />
                </svg>
            </a>
        </div>

        <div ref="panel" class="pf-profiler-panel" :style="{display: panel ? 'block' : 'none', height: height}" />
    </div>
</template>

<script>

import _ from 'lodash';

const config = window.$debugbar;

export default {

    name: 'DebugBar',

    data() {
        return {
            request: null,
            data: null,
            panel: null,
            sections: {}
        };
    },

    computed: {

        height() {
            return `${Math.ceil(window.innerHeight / 2)}px`;
        }

    },

    created() {
        const sections = {};

        _.forIn(this.$options.components, (component, name) => {
            if (component.options && component.options.section) {
                _.set(sections, name, _.merge({ name }, component.options.section));
            }
        }, this);

        this.sections = _.fromPairs(_.map(_.orderBy(sections, 'priority'), (s) => [s.name, s]));

        this.load(config.current).then((res) => {
            this.$set(this, 'request', res.data.__meta);
        });
    },

    methods: {

        load(id) {
            return this.$http.get('_debugbar/{id}', { params: { id } }).then((res) => {
                const entries = Object.entries(res.data).map((entry) => [entry[0][0].toUpperCase() + entry[0].slice(1), entry[1]]);
                const data = Object.fromEntries(entries);

                this.$set(this, 'data', data);
                return res;
            });
        },

        open(name) {
            const section = this.sections[name];
            const vm = _.find(this.$children, ['$options.name', name]);

            if (!section.template) {
                return;
            }

            if (this.panel) {
                this.close();
            }

            const panel = new Vue({
                name: 'Data',
                parent: vm,
                filters: vm.$options.filters,
                data: this.data[section.name],
                computed: vm.$options.computed,
                template: section.template
            });

            panel.$mount();
            UIkit.util.html(this.$refs.panel, panel.$el);

            this.$set(this, 'panel', panel);
        },

        close() {
            if (this.panel) {
                this.panel.$destroy(true);
            }

            this.$set(this, 'panel', null);
        }

    }

};

</script>

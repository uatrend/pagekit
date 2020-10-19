<template>
    <div class="pk-grid-large pk-width-sidebar-large uk-form-stacked" uk-grid>
        <div class="pk-width-content">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <v-input id="form-title" v-model.lazy="page.title" name="page[title]" type="text" rules="required" view="class: uk-input uk-form-large" placeholder="Enter Title" message="Title cannot be blank." />
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Content' | trans }}</label>
                <v-editor v-model="page.content" :options="{markdown : page.data.markdown}" />
                <div class="uk-margin-small-top">
                    <label>
                        <input v-model="page.data.markdown" class="uk-checkbox" type="checkbox"> {{ 'Enable Markdown' | trans }}
                    </label>
                </div>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <div class="uk-panel">
                <component :is="'template-settings'" v-model="node" :roles="roles" :title-required="false" />
            </div>
        </div>
    </div>
</template>

<script>

import NodeMixin from '../mixins/node-mixin';

const NodePage = {

    mixins: [NodeMixin],

    section: { label: 'Content' },

    data() {
        return {
            page: {
                data: {
                    title: true
                }
            }
        };
    },

    mounted() {
        if (!this.node.id) this.node.status = 1;
    },

    watch: {

        'node.data.defaults.id': {

            handler(id) {
                if (id) {
                    this.$http.get('api/site/page{/id}', { params: { id } }).then(function (res) {
                        this.$set(this, 'page', res.data);
                    });
                }
            },

            immediate: true

        }

    },

    events: {
        'node-save': function (event, data) {
            data.page = this.page;
            if (!data.node.title) {
                data.node.title = data.page.title;
            }
        }
    }

};

export default NodePage;

window.Site.components['page.settings'] = NodePage;

</script>

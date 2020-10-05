<template>
    <div class="uk-form-horizontal">
        <div class="uk-margin">
            <label for="form-url" class="uk-form-label">{{ 'Url' | trans }}</label>
            <div class="uk-form-controls">
                <input-link id="form-url" v-model="node.link" name="link" class-name="uk-form-width-large" required="Invalid url." />
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-type" class="uk-form-label">{{ 'Type' | trans }}</label>

            <div class="uk-form-controls">
                <select id="form-type" v-model="behavior" class="uk-form-width-large uk-select">
                    <option value="link">
                        {{ 'Link' | trans }}
                    </option>
                    <option value="alias">
                        {{ 'URL Alias' | trans }}
                    </option>
                    <option value="redirect">
                        {{ 'Redirect' | trans }}
                    </option>
                </select>
            </div>
        </div>

        <component :is="'template-settings'" v-model="node" :roles="roles" />
    </div>
</template>

<script>

import NodeMixin from '../mixins/node-mixin';

export default {

    mixins: [NodeMixin],

    section: {
        label: 'Settings',
        priority: 0,
        active: 'link'
    },

    computed: {

        behavior: {

            get() {
                if (this.node.data.alias) {
                    return 'alias';
                } if (this.node.data.redirect) {
                    return 'redirect';
                }

                return 'link';
            },

            set(type) {
                this.$set(this.node, 'data', _.extend(this.node.data, {
                    alias: type === 'alias',
                    redirect: type === 'redirect' ? this.node.link : false
                }));
            }

        }

    },

    created() {
        if (this.behavior === 'redirect') {
            this.node.link = this.node.data.redirect;
        }

        if (!this.node.id) {
            this.node.status = 1;
        }
    },

    events: {

        'node-save': function (event, data) {
            if (this.behavior === 'redirect') {
                data.node.data.redirect = data.node.link;
            }
        }

    }

};

</script>

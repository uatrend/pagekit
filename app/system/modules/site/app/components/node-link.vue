<template>
    <div class="uk-form-horizontal">
        <div class="uk-margin">
            <label for="form-url" class="uk-form-label">{{ 'Url' | trans }}</label>
            <div class="uk-form-controls">
                <input-link id="form-url" v-model="node.link" cls="uk-form-width-large" name="link" required></input-link>
                <div v-show="errors.first('link')" class="uk-text-meta uk-text-danger">
                    {{ 'Invalid url.' | trans }}
                </div>
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

        <component :is="'template-settings'" :node.sync="node" :roles.sync="roles" :form="form" />
    </div>
</template>

<script>

export default {

    section: {
        label: 'Settings',
        priority: 0,
        active: 'link',
    },

    inject: ['$validator'],

    props: ['node', 'roles', 'form'],

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
                    redirect: type === 'redirect' ? this.node.link : false,
                }));
            },

        },

    },

    created() {
        // this.$options.partials = this.$parent.$options.partials;
        this.$options.components['template-settings'] = this.$root.$options.components['template-settings'];

        if (this.behavior === 'redirect') {
            this.node.link = this.node.data.redirect;
        }

        if (!this.node.id) {
            this.node.status = 1;
        }
    },

    events: {

        'save:node': function () {
            if (this.behavior === 'redirect') {
                this.node.data.redirect = this.node.link;
            }
        },

    },

};

</script>

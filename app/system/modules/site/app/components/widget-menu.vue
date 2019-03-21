<template>
    <div class="pk-grid-large pk-width-sidebar-large" uk-grid>
        <div class="pk-width-content uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>

                <div class="uk-form-controls">
                    <input
                        v-model="widget.title"
                        v-validate="'required'"
                        class="uk-form-width-large uk-input"
                        type="text"
                        name="title"
                        :placeholder="'Enter Title' | trans"
                    >
                    <div v-show="errors.first('title')" class="uk-text-meta uk-text-danger">
                        {{ 'Title cannot be blank.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-menu" class="uk-form-label">{{ 'Menu' | trans }}</label>

                <div class="uk-form-controls">
                    <select id="form-menu" v-model="widget.data.menu" class="uk-form-width-large uk-select">
                        <option value="">
                            {{ '- Menu -' | trans }}
                        </option>
                        <option v-for="m in menus" :key="m.id" :value="m.id">
                            {{ m.label }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-level" class="uk-form-label">{{ 'Start Level' | trans }}</label>

                <div class="uk-form-controls">
                    <select id="form-level" v-model="widget.data.start_level" class="uk-form-width-large uk-select" number>
                        <option value="1">
                            1
                        </option>
                        <option value="2">
                            2
                        </option>
                        <option value="3">
                            3
                        </option>
                        <option value="4">
                            4
                        </option>
                        <option value="5">
                            5
                        </option>
                        <option value="6">
                            6
                        </option>
                        <option value="7">
                            7
                        </option>
                        <option value="8">
                            8
                        </option>
                        <option value="9">
                            9
                        </option>
                        <option value="10">
                            10
                        </option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-depth" class="uk-form-label">{{ 'Depth' | trans }}</label>

                <div class="uk-form-controls">
                    <select id="form-depth" v-model="widget.data.depth" class="uk-form-width-large uk-select" number>
                        <option value="">
                            {{ 'No Limit' | trans }}
                        </option>
                        <option value="1">
                            1
                        </option>
                        <option value="2">
                            2
                        </option>
                        <option value="3">
                            3
                        </option>
                        <option value="4">
                            4
                        </option>
                        <option value="5">
                            5
                        </option>
                        <option value="6">
                            6
                        </option>
                        <option value="7">
                            7
                        </option>
                        <option value="8">
                            8
                        </option>
                        <option value="9">
                            9
                        </option>
                        <option value="10">
                            10
                        </option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Sub Items' | trans }}</span>

                <div class="uk-form-controls uk-form-controls-text">
                    <p class="uk-margin-small">
                        <label><input v-model="widget.data.mode" class="uk-radio" type="radio" value="all"> {{ 'Show all' | trans }}</label>
                    </p>

                    <p class="uk-margin-small">
                        <label><input v-model="widget.data.mode" class="uk-radio" type="radio" value="active"> {{ 'Show only for active item' | trans }}</label>
                    </p>
                </div>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <component :is="'template-settings'" :widget.sync="widget" :config.sync="config" :form="form" />
        </div>
    </div>
</template>

<script>

module.exports = {

    section: {
        label: 'Settings',
    },

    inject: ['$validator'],

    props: ['widget', 'config', 'form'],

    data() {
        return {
            menus: {},
        };
    },

    created() {
        // this.$options.partials = this.$parent.$options.partials;
        this.$options.components['template-settings'] = this.$parent.$options.components['template-settings'];

        this.$http.get('api/site/menu').then(function (res) {
            this.$set(this, 'menus', res.data.filter(menu => menu.id !== 'trash'));
        });
    },

};

window.Widgets.components['system-menu--settings'] = module.exports;

</script>

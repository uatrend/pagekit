<template>
    <div>
        <div v-if="!type.disableToolbar" class="uk-position-top-right uk-position-small">
            <ul class="uk-iconnav uk-invisible-hover">
                <li v-show="type.editable !== false && !editing">
                    <a uk-icon="file-edit" :title="'Edit' | trans" uk-tooltip="delay: 500" @click.prevent="edit" />
                </li>
                <li v-show="!editing">
                    <a uk-icon="more-vertical" class="uk-sortable-handle" :title="'Drag' | trans" uk-tooltip="delay: 500" />
                </li>
                <li v-show="editing">
                    <a v-confirm="'Delete widget?'" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" />
                </li>
                <li v-show="editing">
                    <a uk-icon="check" :title="'Close' | trans" uk-tooltip="delay: 500" @click.prevent="save" />
                </li>
            </ul>
        </div>

        <component :is="type.component" v-model="current" :editing="editing" />
    </div>
</template>

<script>

export default {

    name: 'Panel',

    inject: ['$components'],

    props: {
        widget: {
            type: Object,
            default() {
                return {};
            }
        }
    },

    data() {
        return {
            current: this.widget,
            editing: false
        };
    },

    computed: {

        type() {
            return this.$parent.getType(this.current.type);
        }

    },

    created() {
        _.extend(this.$options.components, this.$components);
    },

    methods: {

        edit() {
            this.$set(this, 'editing', true);
        },

        save() {
            this.$parent.save(this.current);
            this.$set(this, 'editing', false);
        },

        remove() {
            this.$root.remove(this.current);
        }

    }

};

</script>

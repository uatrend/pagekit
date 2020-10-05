<template>
    <div uk-modal :class="modal_classes">
        <div class="uk-modal-dialog" :class="dialog_classes">
            <slot v-if="opened" />
        </div>
    </div>
</template>

<script>

import { on, Promise } from 'uikit-util';

export default {

    props: {
        large: Boolean,
        lightbox: Boolean,
        center: Boolean,
        closed: {
            type: Function,
            default: () => true
        },
        modifier: {
            type: String,
            default: ''
        },
        dialogModifier: {
            type: String,
            default: ''
        },
        options: {
            type: [Object, Function],
            default: () => ({})
        }
    },

    data() {
        return { opened: false };
    },

    computed: {

        modal_classes() {
            const classes = this.modifier.split(' ');

            if (this.large) {
                classes.push('uk-modal-container');
            }

            if (this.center) {
                classes.push('uk-flex-top');
            }

            return classes;
        },

        dialog_classes() {
            const classes = this.dialogModifier.split(' ');

            if (this.center) {
                classes.push('uk-margin-auto-vertical');
            }

            if (this.lightbox) {
                classes.push('uk-width-auto');
            }

            return classes;
        }

    },

    methods: {

        create() {
            const vm = this;
            const options = _.extend({
                stack: true
            }, (typeof this.options === 'function') ? this.options() : this.options);

            this.modal = UIkit.modal(this.$el, () => options);

            on(this.modal.$el, 'hidden', (ref) => {
                vm.opened = false;
                if (typeof vm.closed === 'function') { vm.closed(); }
                return Promise.resolve().then(() => vm.modal.$destroy(true));
            }, { self: true });
        },

        open() {
            this.create();
            this.opened = true;
            this.modal.show();
        },

        close() {
            this.modal.hide();
        }

    }

};

</script>

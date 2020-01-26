<template>
    <div uk-modal :class="modalClasses">
        <div class="uk-modal-dialog" :class="dialogClasses">
            <slot v-if="opened">
            </slot>
        </div>
    </div>
</template>

<script>

import { on } from 'uikit-util';

export default {

    props: {
        large     : Boolean,
        lightbox  : Boolean,
        center    : Boolean,
        closed    : Function,
        modifier: { type: String, default: '' },
        options: {
            default: () => ({})
        },
    },

    data() {
        return {
            opened: false
        };
    },

    mounted() {
        const vm = this;
        let options;

        options =  _.extend({stack: true}, typeof this.options === 'function' ? this.options() : this.options);
        this.modal = UIkit.modal(this.$el, () => options);

        on(this.modal.$el, 'hidden', (ref) => {

            vm.opened = false;

            if (this.closed) {
                this.closed();
            }

        });
    },

    computed: {

        modalClasses() {
            const modalClasses = this.modifier.split(' ');

            if (this.large) {
                modalClasses.push('uk-modal-container');
            }

            if (this.center) {
                modalClasses.push('uk-flex');
                modalClasses.push('uk-flex-top');
            }

            return modalClasses;
        },

        dialogClasses() {
            const dialogClasses = [];

            if (this.center) {
                dialogClasses.push('uk-margin-auto-vertical');
            }

            if (this.lightbox) {
                dialogClasses.push('uk-width-auto');
            }

            return dialogClasses;
        },

    },

    methods: {

        open() {
            this.opened = true;
            this.modal.show();
        },

        close() {
            this.modal.hide();
        },

    },

};

</script>

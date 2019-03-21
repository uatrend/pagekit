<template>
    <div uk-modal :class="classes">
        <div class="uk-modal-dialog" :class="dialogclasses">
            <slot v-if="opened" />
        </div>
    </div>
</template>

<script>

export default {

    props: {
        large: Boolean,
        lightbox: Boolean,
        modalfull: Boolean,
        smallwidth: Boolean,
        center: Boolean,
        contrast: Boolean,
        autowidth: Boolean,
        bgclose: Boolean,
        escclose: Boolean,
        closed: Function,
        dialogwidth: { type: String, default: '' },
        clsdialog: { type: String, default: '' },
        modifier: { type: String, default: '' },
        moddialog: { type: String, default: '' },
        options: {
            type: Object,
            default() {
                return {};
            },
        },
    },

    data() {
        return {
            modal: false,
            opened: false,
        };
    },

    computed: {

        classes() {
            const classes = this.modifier.split(' ');

            if (this.large) {
                classes.push('uk-modal-container');
            }

            if (this.modalfull) {
                classes.push('uk-modal-full');
            }

            if (this.smallwidth) {
                classes.push('modal-width-small');
            }

            return classes;
        },

        dialogclasses() {
            const dialogclasses = this.moddialog.split(' ');

            if (this.center) {
                dialogclasses.push('uk-margin-auto-vertical');
            }

            if (this.contrast) {
                dialogclasses.push('uk-light');
            }

            if (this.autowidth) {
                dialogclasses.push('uk-width-auto');
            }

            if (this.dialogwidth.indexOf('uk-width-') != -1) {
                dialogclasses.push(this.dialogwidth);
            }

            if (this.clsdialog.indexOf('uk-') != -1) {
                dialogclasses.push(this.clsdialog);
            }

            return dialogclasses;
        },

    },

    mounted() {
        const vm = this;

        // this.$appendTo('body');

        this.modal = UIkit.modal(this.$el, _.extend({
            modal: false, stack: true, bgClose: !this.bgclose, escClose: !this.escclose,
        }, this.options));
        UIkit.util.on(this.modal.$el, 'hidden', (ref) => {
            const { target } = ref;
            const { currentTarget } = ref;

            if (target === currentTarget) {
                Vue.nextTick(() => {
                    // vm.modal.$destroy(true);
                });
            }

            vm.opened = false;

            if (vm.closed) {
                vm.closed();
            }
        });
    },

    methods: {

        open() {
            this.opened = true;
            this.modal.show();
        },

        close() {
            this.modal.hide();
            // this.modal.$destroy(true);
        },

    },

};

</script>

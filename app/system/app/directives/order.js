import {
    $, on, append, addClass, removeClass, remove, find,
} from 'uikit-util';
// import util from 'uikit-util';

export default {

    bind(el, binding, vnode) {
        binding.dir = '';
        binding.active = false;
        binding.indicator = $('<i class="uk-margin-small-left"></i>');

        addClass(el, 'pk-table-order uk-visible-toggle');
        el._off = on(el, 'click', () => {
            binding.dir = (binding.dir == 'asc') ? 'desc' : 'asc';
            _.set(vnode.context, binding.expression, [binding.arg, binding.dir].join(' '));
        });
        append(el, binding.indicator);

        // $(el).addClass('pk-table-order uk-visible-toggle').on('click.order', function (){
        //     binding.dir = (binding.dir == 'asc') ? 'desc': 'asc';
        //     _.set(vnode.context, binding.expression, [binding.arg, binding.dir].join(' '));
        // }).append(binding.indicator);

        // console.log(binding);

        // var self = this;

        // this.dir       = '';
        // this.active    = false;
        // this.indicator = $('<i class="uk-margin-small-left"></i>');

        // $(this.el).addClass('pk-table-order uk-visible-toggle').on('click.order', function (){

        //     self.dir = (self.dir == 'asc') ? 'desc':'asc';
        //     self.vm.$set(self.expression, [self.arg, self.dir].join(' '));

        // }).append(this.indicator);

        // binding.def.update(el, binding, vnode);
    },

    // update: function (data) {
    update(el, binding, vnode) {
        const data = binding.value;

        const parts = data.split(' ');
        const field = parts[0];
        const dir = parts[1] || 'asc';

        // binding.indicator = $(el).find('i');
        binding.indicator = find('i', el);

        // binding.indicator.removeClass('pk-icon-arrow-up pk-icon-arrow-down');
        removeClass(binding.indicator, 'pk-icon-arrow-up pk-icon-arrow-down');
        removeClass(el, 'uk-active');
        // $(el).removeClass('uk-active');

        if (field == binding.arg) {
            binding.active = true;
            binding.dir = dir;
            // console.log(binding.dir);

            addClass(el, 'uk-active');
            removeClass(binding.indicator, 'uk-invisible-hover');
            addClass(binding.indicator, dir == 'asc' ? 'pk-icon-arrow-down' : 'pk-icon-arrow-up');
            // $(el).addClass('uk-active');
            // binding.indicator.removeClass('uk-invisible-hover').addClass(dir == 'asc' ? 'pk-icon-arrow-down':'pk-icon-arrow-up');
        } else {
            addClass(binding.indicator, 'pk-icon-arrow-down uk-invisible-hover');
            // binding.indicator.addClass('pk-icon-arrow-down uk-invisible-hover');
            binding.active = false;
            binding.dir = '';
        }
    },

    unbind(el, binding, vnode) {
        removeClass(el, 'pk-table-order');
        el._off();
        remove(binding.indicator);
        // $(this.el).removeClass('pk-table-order').off('.order');
        // binding.indicator.remove();
    },

};

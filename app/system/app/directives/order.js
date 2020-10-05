import { $, on, append, addClass, removeClass, remove, find, attr, removeAttr } from 'uikit-util';

export default {

    bind(el, binding, vnode) {
        binding.dir = '';
        binding.active = false;
        binding.indicator = $('<i></i>');

        addClass(el, 'pk-table-order uk-visible-toggle');
        el._off = on(el, 'click', () => {
            if (!binding.dir) {
                binding.dir = binding.value.split(' ')[1];
            }
            binding.dir = (binding.dir === 'asc') ? 'desc' : 'asc';
            _.set(vnode.context, binding.expression, [binding.arg, binding.dir].join(' '));
        });
        append(el, binding.indicator);
    },

    componentUpdated(el, binding, vnode) {
        const data = binding.value;

        const parts = data.split(' ');
        const field = parts[0];
        const dir = parts[1] || 'asc';

        binding.indicator = find('i', el);

        removeAttr(binding.indicator, 'uk-icon');
        removeClass(el, 'uk-active');

        if (field === binding.arg) {
            binding.active = true;
            binding.dir = dir;

            addClass(el, 'uk-active');
            removeClass(binding.indicator, 'uk-invisible-hover');
            attr(binding.indicator, 'uk-icon', dir === 'asc' ? 'arrow-down' : 'arrow-up');
        } else {
            addClass(binding.indicator, 'uk-invisible-hover');
            attr(binding.indicator, 'uk-icon', 'arrow-down');
            binding.active = false;
            binding.dir = '';
        }
    },

    unbind(el, binding, vnode) {
        removeClass(el, 'pk-table-order');
        el._off();
        remove(binding.indicator);
    }

};

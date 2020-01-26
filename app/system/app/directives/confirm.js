export default {

    bind(el, binding, vnode) {
        binding.def.componentUpdated(el, binding, vnode);
    },

    componentUpdated(el, binding, vnode) {
        const vm = vnode.context;
        const buttons = (el.getAttribute('buttons') || '').split(',');

        binding.options = {
            title: false,
            labels: {
                ok: buttons[0] || vm.$trans('Ok'),
                cancel: buttons[1] || vm.$trans('Cancel'),
            },
            stack: true,
        };

        // vue-confirm="'Text...?'"
        if (typeof binding.value === 'string') {
            binding.options.text = binding.value;
        }

        // vue-confirm="{title:'Title', text:'Text...?'}"
        if (typeof binding.value === 'object') {
            _.extend(binding.options, binding.value);
        }

        _.forEach(['title', 'text'], (item) => {
            binding.options[item] && vm.$trans(binding.options[item])
        })

        binding.handler = vnode.data.on.click.fns;

        vnode.data.on.click.fns = function(e) {
            UIkit.modal.confirm(binding.options.text, binding.options)
            .then(function() {
                binding.handler(e);
            }, function(){});
        }
    }
}
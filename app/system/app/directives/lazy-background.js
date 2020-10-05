const addImageMeta = function (meta, vm) {
    const exist = _.filter(vm.meta, (item) => item.url === meta.url);
    if (!exist.length) {
        vm.meta.push(meta);
    }
};

export default {
    inserted(el, binding, vnode) {
        const $this = vnode.context;
        const { meta } = binding.modifiers;

        if (meta) {
            $this.meta = $this.meta && $this.meta.length ? $this.meta : [];
        }

        binding.def.update(el, binding, vnode);
    },

    update(el, binding, vnode) {
        const { value } = binding;
        const { meta } = binding.modifiers;

        if (binding.loaded !== value) {
            const img = new Image();
            img.onload = () => {
                el.style['background-image'] = `url('${value}')`;
                if (meta) {
                    addImageMeta({ url: value, width: img.width, height: img.height }, vnode.context);
                }
            };
            img.src = value;
            binding.loaded = value;
        }
    }
};

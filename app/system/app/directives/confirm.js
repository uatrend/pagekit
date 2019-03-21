const _ = Vue.util;

export default {

    bind(el, binding, vnode) {
        binding.def.update(el, binding, vnode);
    },

    update(el, binding, vnode) {
        const buttons = (el.getAttribute('buttons') || '').split(',');

        let options = {
            title: false,
            labels: {
                ok: buttons[0] || vnode.context.$trans('Ok'),
                cancel: buttons[1] || vnode.context.$trans('Cancel'),
            },
            stack: true,
        };

        // // vue-confirm="'Title':'Text...?'"
        // if (this.arg) {
        //     this.options.title = this.arg;
        // }

        // vue-confirm="'Text...?'"
        if (typeof binding.value === 'string') {
            options.text = binding.value;
        }

        // vue-confirm="{title:'Title', text:'Text...?'}"
        if (typeof binding.value === 'object') {
            options = _.extend(options, binding.value);
        }

        const handler = vnode.data.on.click.fns;

        vnode.data.on.click.fns = function (e) {
            const modal = UIkit.modal.confirm(vnode.context.$trans(binding.value), options).then(() => {
                handler(e);
            }, () => {});
        };
    },

    unbind(el, binding, vnode) {},

};

// var _ = Vue.util;

// module.exports = {

//     priority: 500,

//     bind: function () {

//         var self = this, el = this.el, buttons = (_.getAttr(el, 'buttons') || '').split(',');

//         this.options = {
//             title: false,
//             labels: {
//                 ok: buttons[0] || this.vm.$trans('Ok'),
//                 cancel: buttons[1] || this.vm.$trans('Cancel')
//             }
//         };

//         this.dirs = this.vm._directives.filter(function (dir) {
//             return dir.name == 'on' && dir.el === el;
//         });

//         this.dirs.forEach(function (dir) {

//             _.off(dir.el, dir.arg, dir.handler);
//             _.on(dir.el, dir.arg, function (e) {
//                 // UIkit.modal.confirm(self.vm.$trans(self.options.text), self.options, function () {
//                 //         dir.handler(e);
//                 //     }
//                 // );
//                 UIkit.modal.confirm(self.vm.$trans(self.options.text), self.options).then(function() {
//                     dir.handler(e);
//                 });
//             });

//         });
//     },

//     update: function (value) {

//         // vue-confirm="'Title':'Text...?'"
//         if (this.arg) {
//             this.options.title = this.arg;
//         }

//         // vue-confirm="'Text...?'"
//         if (typeof value === 'string') {
//             this.options.text = value;
//         }

//         // vue-confirm="{title:'Title', text:'Text...?'}"
//         if (typeof value === 'object') {
//             this.options = _.extend(this.options, value);
//         }
//     },

//     unbind: function () {
//         this.dirs.forEach(function (dir) {
//             try {
//                 _.off(dir.el, dir.arg, dir.handler);
//             } catch (e) {
//             }
//         });
//     }

// };

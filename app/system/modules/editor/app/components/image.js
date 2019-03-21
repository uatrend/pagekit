/**
 * Editor Image plugin.
 */

module.exports = {

    name: "plugin-image",

    plugin: true,

    created: function () {

        if (typeof tinyMCE === 'undefined') {
            return;
        }

        var vm = this;

        this.$parent.editor.plugins.push('-pagekitImage');
        tinyMCE.PluginManager.add('pagekitImage', function (editor) {

            var showDialog = function () {

                var element = editor.selection.getNode();

                if (element.nodeName === 'IMG') {
                    editor.selection.select(element);
                    var image = {src: element.attributes.src.nodeValue, alt: element.attributes.alt.nodeValue};
                } else {
                    element = new Image() || document.createElement('img');
                    image = {};
                }

                var Picker = Vue.extend(vm.$parent.$options.utils['image-picker']);

                new Picker({
                    name: 'image-picker',
                    parent: vm,
                    data: {
                        image: {data: image}
                    }
                }).$mount()
                  .$on('select', function (image) {

                        element.setAttribute('src', '');
                        element.setAttribute('alt', '');

                        var attributes = Object.keys(element.attributes).reduce(function (previous, key) {
                            var name = element.attributes[key].name;

                            if (name === 'data-mce-src') {
                                return previous;
                            }

                            return previous + ' ' + name + '="' + (image.data[name] || element.attributes[key].nodeValue) + '"';
                        }, '');

                        editor.selection.setContent(
                            '<img' + attributes + '>'
                        );

                        editor.fire('change');

                    });
            };

            editor.addButton('image', {
                tooltip: 'Insert/edit image',
                onclick: showDialog,
                stateSelector: 'img:not([data-mce-object],[data-mce-placeholder]),figure.image'
            });

            editor.addMenuItem('image', {
                text: 'Insert/edit image',
                icon: 'image',
                context: 'insert',
                onclick: showDialog
            });

        });
    }

};
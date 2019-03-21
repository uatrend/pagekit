/**
 * Editor Link plugin.
 */

module.exports = {

    name: "plugin-link",

    plugin: true,

    created: function () {

        if (typeof tinyMCE === 'undefined') {
            return;
        }

        var vm = this;

        this.$parent.editor.plugins.push('-pagekitLink');
        tinyMCE.PluginManager.add('pagekitLink', function (editor) {

            var showDialog = function () {

                var element = editor.selection.getNode();


                if (element.nodeName === 'A') {
                    editor.selection.select(element);
                    var link = {link: element.attributes.href ? element.attributes.href.nodeValue : '', txt: element.innerHTML};
                } else {
                    element =  document.createElement('a');
                    link = {};
                }

                var Picker = Vue.extend(vm.$parent.$options.utils['link-picker']);

                new Picker({
                    parent: vm,
                    data: {
                        link: link
                    }
                }).$mount()
                  .$on('select', function (link) {

                        element.setAttribute('href', '');

                        var attributes = Object.keys(element.attributes).reduce(function (previous, key) {
                            var name = element.attributes[key].name;

                            if (name === 'data-mce-href') {
                                return previous;
                            }

                            return previous + ' ' + name + '="' + (name === 'href' ? link.link : element.attributes[key].nodeValue) + '"';
                        }, '');

                        editor.selection.setContent(
                            '<a' + attributes + '>' + link.txt + '</a>'
                        );

                        editor.fire('change');

                    });
            };

            editor.addButton('link', {
                tooltip: 'Insert/edit link',
                onclick: showDialog,
                stateSelector: 'a'
            });

            editor.addMenuItem('link', {
                text: 'Insert/edit link',
                icon: 'link',
                context: 'insert',
                onclick: showDialog
            });
        });
    }

};
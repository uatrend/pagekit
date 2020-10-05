const ThemeMixins = {
    Dashboard: {
        mixins: [Vue.Theme.Mixins.Helper],
        theme: {
            hideEls: '#dashboard > div:first-child > div:last-child',
            elements() {
                const vm = this;
                return {
                    addwidget: {
                        scope: 'topmenu-left',
                        type: 'dropdown',
                        caption: 'Add Widget',
                        class: 'uk-button uk-button-text',
                        icon: { attrs: { 'uk-icon': 'triangle-down' } },
                        dropdown: { options: () => 'mode: click' },
                        items: () => vm.getTypes().map((type) => {
                            const props = {
                                on: { click: () => vm.add(type) },
                                caption: type.label,
                                class: 'uk-dropdown-close'
                            };
                            return { ...type, ...props };
                        })
                    }
                };
            }
        }
    }
};

export default Vue.Theme ? ThemeMixins : {};

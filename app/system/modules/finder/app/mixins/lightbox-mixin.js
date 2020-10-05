export default {
    data() {
        return {
            lightbox: {
                enable: true,
                panel: UIkit.lightboxPanel,
                options: {
                    items: [],
                    template: `
                        <div class=\"uk-lightbox uk-overflow-hidden\">
                            <ul class=\"uk-lightbox-items\"></ul>
                            <div class=\"uk-lightbox-toolbar uk-position-top uk-text-right uk-transition-slide-top uk-transition-opaque\">
                                <button class=\"uk-lightbox-toolbar-icon uk-close-large\" type=\"button\" uk-close></button>
                            </div>
                            <a class=\"uk-lightbox-button uk-position-center-left uk-position-medium uk-transition-fade\" href uk-slidenav-previous uk-lightbox-item=\"previous\"></a>
                            <a class=\"uk-lightbox-button uk-position-center-right uk-position-medium uk-transition-fade\" href uk-slidenav-next uk-lightbox-item=\"next\"></a>
                            <div class=\"uk-lightbox-toolbar uk-lightbox-caption uk-position-bottom uk-text-center uk-transition-slide-bottom uk-transition-opaque uk-text-small\"></div>
                        </div>`
                }
            }
        };
    },

    methods: {
        lightboxShow(items, index) {
            this.lightbox.options.items = items;
            this.lightbox.panel(this.lightbox.options).show(index);
        }
    }
};

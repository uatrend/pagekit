const { $, $$, addClass, attr, closest, css, hasClass, on, offset, ready, removeClass } = UIkit.util;

UIkit.component('Header', {

    update: [

        {
            read() {
                return getSection() ? { height: this.$el.offsetHeight } : false;
            },

            write({ height }) {
                const [section, modifier] = getSection();

                if (!hasClass(this.$el, 'tm-header-transparent')) {
                    addClass(this.$el, 'tm-header-transparent tm-header-overlay');
                    addClass($$('.tm-headerbar-top, .tm-headerbar-bottom, .tm-toolbar-transparent'), `uk-${modifier}`);
                    removeClass($('.tm-toolbar-transparent.tm-toolbar-default'), 'tm-toolbar-default');

                    if (!$('[uk-sticky]', this.$el)) {
                        addClass($('.uk-navbar-container', this.$el), `uk-navbar-transparent uk-${modifier}`);
                    }
                }

                css($('.tm-header-placeholder', section), { height });
            },

            events: ['resize']
        }

    ]

});

UIkit.mixin({

    update: {

        read() {
            const [section, modifier] = getSection() || [];

            if (!modifier || !closest(this.$el, '[uk-header]')) {
                return;
            }

            this.animation = 'uk-animation-slide-top';
            this.clsInactive = `uk-navbar-transparent uk-${modifier}`;
            this.top = section.offsetHeight <= window.innerHeight
                ? offset(section).bottom
                : offset(section).top + 300;
        },

        write() {
            const [modifier] = getSection() || [];

            if (!this.matchMedia && modifier && !hasClass(`uk-navbar-transparent uk-${modifier}`, $('.uk-navbar-container', this.$el))) {
                addClass($('.uk-navbar-container', this.$el), `uk-navbar-transparent uk-${modifier}`);
            }
        },

        events: ['resize']

    }

}, 'sticky');

UIkit.mixin({

    computed: {

        dropbarMode({ dropbarMode }) {
            return getSection() || closest(this.$el, '[uk-sticky]') ? 'slide' : dropbarMode;
        }

    }

}, 'navbar');

function getSection() {
    const section = $('.tm-header ~ [class*="uk-section"], .tm-header ~ :not(.tm-page) > [class*="uk-section"]');
    const modifier = attr(section, 'tm-header-transparent');
    return section && modifier && [section, modifier];
}

(function (UIkit) {
    const sel = '#tm-main';

    ready(() => {
        (function (main, meta, fn) {
            if (!main) return;

            fn = function () {
                css(main, 'min-height', '');

                meta = document.body.getBoundingClientRect();

                if (meta.height < window.innerHeight) {
                    css(main, {
                        minHeight: (main.offsetHeight + (window.innerHeight - meta.height))
                    });
                }

                return fn;
            };

            on(window, 'load resize', fn());
        }($(sel)));
    });
}(UIkit));

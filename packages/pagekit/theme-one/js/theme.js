var util = UIkit.util,
    $ = util.$,
    on = util.on,
    attr = util.attr,
    before = util.before,
    css = util.css,
    each = util.each,
    addClass = util.addClass,
    hasAttr = util.hasAttr,
    offset = util.offset,
    ready = util.ready;

var selector = '.tm-header ~ [class*="uk-section"], .tm-header ~ * > [class*="uk-section"]';

UIkit.component('header', {

    update: [

        {
            read: function(data) {

                var section = $(selector);
                var modifier = attr(section, 'tm-header-transparent');

                if (!modifier || !section) {
                    return false;
                }

                data.prevHeight = this.height;
                data.height = this.$el.offsetHeight;

                var sticky = UIkit.getComponent($('[uk-sticky]', this.$el), 'sticky');

                if (sticky) {

                    var dat = sticky.$options.data;

                    if (dat.animation !== 'uk-animation-slide-top') {
                        each({
                            top: selector,
                            animation: 'uk-animation-slide-top',
                            clsInactive: `uk-navbar-transparent uk-${modifier}`
                        }, function(value, key) { return dat[key] = sticky[key] = sticky.$props[key] = value});
                    }

                    sticky.$props.top = section.offsetHeight <= window.innerHeight ? selector : offset(section).top + 300;
                }

            },

            write: function(data) {

                if (!this.placeholder) {

                    var section = $(selector);
                    var modifier = attr(section, 'tm-header-transparent');

                    addClass(this.$el, 'tm-header-transparent');
                    addClass($('.tm-headerbar-top, .tm-headerbar-bottom'), `uk-${modifier}`);

                    this.placeholder = hasAttr(section, 'tm-header-transparent-placeholder')
                        && before($('[uk-grid]', section), '<div class="tm-header-placeholder uk-margin-remove-adjacent"></div>');

                    var navbar = $('[uk-navbar]', this.$el);
                    if (attr(navbar, 'dropbar-mode') === 'push') {
                        attr(navbar, 'dropbar-mode', 'slide');
                    }

                    if (!$('[uk-sticky]', this.$el)) {
                        addClass($('.uk-navbar-container', this.$el), `uk-navbar-transparent uk-${modifier}`);
                    }

                }

                if (this.placeholder && data.prevHeight !== data.height) {
                    css(this.placeholder, {height: data.height});
                }
            },

            events: ['load', 'resize']
        }

    ]

});

(function(UIkit){

    var sel = '#tm-main';

    ready(function(){
        (function(main, meta, fn){

            if (!main) return;

            fn = function() {

               css(main, 'min-height','');

                meta = document.body.getBoundingClientRect();

                if (meta.height < window.innerHeight) {
                    css(main, {
                        'minHeight': (main.offsetHeight + (window.innerHeight - meta.height))
                    });
                }

                return fn;
            };

            on(window, 'load resize', fn());

        })($(sel));
    });

})(UIkit);
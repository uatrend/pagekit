export default function (Vue) {
    // Vue.filter('baseUrl', function (url) {
    //     return _.startsWith(url, Vue.url.options.root) ? url.substr(Vue.url.options.root.length) : url;
    // });

    // Vue.filter('trans', function (id, parameters, domain, locale) {
    //     return this.$trans(id, parameters, domain, locale);
    // });

    // Vue.filter('transChoice', function (id, number, parameters, domain, locale) {
    //     return this.$transChoice(id, number, parameters, domain, locale);
    // });

    // Vue.filter('trim', {

    //     write: function (value) {
    //         return value.trim();
    //     }

    // });

    Vue.filter('baseUrl', url => (_.startsWith(url, Vue.url.options.root) ? url.substr(Vue.url.options.root.length) : url));

    Vue.filter('trans', (id, parameters, domain, locale) => Vue.prototype.$trans(id, parameters, domain, locale));

    Vue.filter('transChoice', (id, number, parameters, domain, locale) => Vue.prototype.$transChoice(id, number, parameters, domain, locale));

    Vue.filter('trim', {

        write(value) {
            return value.trim();
        },

    });

    // vue-intl

    Vue.filter('date', (date, format, timezone) => Vue.prototype.$date(date, format, timezone));

    Vue.filter('number', (number, fractionSize) => Vue.prototype.$number(number, fractionSize));

    Vue.filter('currency', (amount, currencySymbol, fractionSize) => Vue.prototype.$currency(amount, currencySymbol, fractionSize));

    Vue.filter('relativeDate', (date, options) => Vue.prototype.$relativeDate(date, options));
}

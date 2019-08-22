import ArrayFilters from './filters-array';

export default function (Vue) {

    Vue.filter('baseUrl', url => (_.startsWith(url, Vue.url.options.root) ? url.substr(Vue.url.options.root.length) : url));

    Vue.filter('trans', (id, parameters, domain, locale) => {
        return Vue.prototype.$trans(id, parameters, domain, locale)
    })

    Vue.filter('transChoice', (id, number, parameters, domain, locale) => Vue.prototype.$transChoice(id, number, parameters, domain, locale));

    Vue.filter('trim', { write(value) {return value.trim() } });

    Vue.filter('date', (date, format, timezone) => Vue.prototype.$date(date, format, timezone));

    Vue.filter('number', (number, fractionSize) => Vue.prototype.$number(number, fractionSize));

    Vue.filter('currency', (amount, currencySymbol, fractionSize) => Vue.prototype.$currency(amount, currencySymbol, fractionSize));

    Vue.filter('relativeDate', (date, options) => Vue.prototype.$relativeDate(date, options));

    Vue.filter('lowercase', (value) => (value || value === 0) ? value.toString().toLowerCase() : '');

    // orderBy, filterBy
    Vue.use(ArrayFilters);
}
/**
 * Install plugin.
 */

import Util from './util';
import formatDate from './date';
import formatNumber from './number';
import formatCurrency from './currency';
import relativeDate from './relative';
import defaultLocale from '../dist/locales/en.json';

function plugin(Vue) {

    var vue = Vue.prototype;

    if (!vue.$locale) {
        vue.$locale = defaultLocale;
    }

    Util(Vue);

    vue.$date = formatDate;
    vue.$number = formatNumber;
    vue.$currency = formatCurrency;
    vue.$relativeDate = relativeDate;

    Vue.filter('date', formatDate);
    Vue.filter('number', formatNumber);
    Vue.filter('currency', formatCurrency);
    Vue.filter('relativeDate', relativeDate);
}

if (typeof window !== 'undefined' && window.Vue) {
    window.Vue.use(plugin);
}

export default plugin;

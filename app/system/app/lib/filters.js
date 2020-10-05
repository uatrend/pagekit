/*
 * https://github.com/freearhey/vue2-filters
 */

const { toString } = Object.prototype;
const OBJECT_STRING = '[object Object]';

const util = {
    isObject: (obj) => {
        const type = typeof obj;
        return type === 'function' || type === 'object' && !!obj;
    },
    isArray: (obj) => Array.isArray(obj),
    isPlainObject: (obj) => toString.call(obj) === OBJECT_STRING,
    toArray: (list, start) => {
        start = start || 0;
        let i = list.length - start;
        const ret = new Array(i);
        while (i--) {
            ret[i] = list[i + start];
        }
        return ret;
    },
    convertArray: (value) => {
        if (util.isArray(value)) {
            return value;
        } if (util.isPlainObject(value)) {
            // convert plain object to array.
            const keys = Object.keys(value);
            let i = keys.length;
            const res = new Array(i);
            let key;
            while (i--) {
                key = keys[i];
                res[i] = {
                    $key: key,
                    $value: value[key]
                };
            }
            return res;
        }
        return value || [];
    },
    // obj,'1.2.3' -> multiIndex(obj,['1','2','3'])
    getPath: (obj, is) => multiIndex(obj, is.split('.'))
};

// obj,['1','2','3'] -> ((obj['1'])['2'])['3']
function multiIndex(obj, is) {
    return is.length ? multiIndex(obj[is[0]], is.slice(1)) : obj;
}

function contains(val, search) {
    let i;
    if (util.isPlainObject(val)) {
        const keys = Object.keys(val);
        i = keys.length;
        while (i--) {
            if (contains(val[keys[i]], search)) {
                return true;
            }
        }
    } else if (util.isArray(val)) {
        i = val.length;
        while (i--) {
            if (contains(val[i], search)) {
                return true;
            }
        }
    } else if (val != null) {
        return val.toString().toLowerCase().indexOf(search) > -1;
    }
}

function orderBy(arr) {
    let comparator = null;
    let sortKeys;
    arr = util.convertArray(arr);

    // determine order (last argument)
    let args = util.toArray(arguments, 1);
    let order = args[args.length - 1];
    if (typeof order === 'number') {
        order = order < 0 ? -1 : 1;
        args = args.length > 1 ? args.slice(0, -1) : args;
    } else {
        order = 1;
    }

    // determine sortKeys & comparator
    const firstArg = args[0];
    if (!firstArg) {
        return arr;
    } if (typeof firstArg === 'function') {
        // custom comparator
        comparator = (a, b) => firstArg(a, b) * order;
    } else {
        // string keys. flatten first
        sortKeys = Array.prototype.concat.apply([], args);
        comparator = (a, b, i) => {
            i = i || 0;
            return i >= sortKeys.length - 1
                ? baseCompare(a, b, i)
                : baseCompare(a, b, i) || comparator(a, b, i + 1);
        };
    }

    function baseCompare(a, b, sortKeyIndex) {
        const sortKey = sortKeys[sortKeyIndex];
        if (sortKey) {
            if (sortKey !== '$key') {
                if (util.isObject(a) && '$value' in a) a = a.$value;
                if (util.isObject(b) && '$value' in b) b = b.$value;
            }
            a = util.isObject(a) ? util.getPath(a, sortKey) : a;
            b = util.isObject(b) ? util.getPath(b, sortKey) : b;
        }
        return a === b ? 0 : a > b ? order : -order;
    }

    // sort on a copy to avoid mutating original array
    return arr.slice().sort(comparator);
}

function filterBy(arr, search) {
    arr = util.convertArray(arr);
    if (search == null) {
        return arr;
    }
    if (typeof search === 'function') {
        return arr.filter(search);
    }
    // cast to lowercase string
    search = (`${search}`).toLowerCase();
    const n = 2;
    // extract and flatten keys
    const keys = Array.prototype.concat.apply([], util.toArray(arguments, n));
    const res = [];
    let item;
    let key;
    let val;
    let j;
    for (let i = 0, l = arr.length; i < l; i++) {
        item = arr[i];
        val = (item && item.$value) || item;
        j = keys.length;
        if (j) {
            while (j--) {
                key = keys[j];
                if ((key === '$key' && contains(item.$key, search))
                || contains(util.getPath(val, key), search)) {
                    res.push(item);
                    break;
                }
            }
        } else if (contains(item, search)) {
            res.push(item);
        }
    }
    return res;
}

export default (Vue) => {
    Vue.filter('baseUrl', (url) => (_.startsWith(url, Vue.url.options.root) ? url.substr(Vue.url.options.root.length) : url));
    Vue.filter('trans', (id, parameters, domain, locale, replace) => Vue.prototype.$trans(id, parameters, domain, locale, replace));
    Vue.filter('transChoice', (id, number, parameters, domain, locale) => Vue.prototype.$transChoice(id, number, parameters, domain, locale));
    Vue.filter('trim', { write(value) { return value.trim(); } });
    Vue.filter('date', (date, format, timezone) => Vue.prototype.$date(date, format, timezone));
    Vue.filter('number', (number, fractionSize) => Vue.prototype.$number(number, fractionSize));
    Vue.filter('currency', (amount, currencySymbol, fractionSize) => Vue.prototype.$currency(amount, currencySymbol, fractionSize));
    Vue.filter('relativeDate', (date, options) => Vue.prototype.$relativeDate(date, options));
    Vue.filter('lowercase', (value) => ((value || value === 0) ? value.toString().toLowerCase() : ''));
    Vue.prototype.orderBy = orderBy;
    Vue.prototype.filterBy = filterBy;
};

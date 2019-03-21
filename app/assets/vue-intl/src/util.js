/**
 * Utility functions.
 */

var util = {}, arr = Array.prototype, obj = Object.prototype;

export default function (Vue) {
    util = Vue.util;
}

export const isArray = Array.isArray;

export function isString(val) {
    return typeof val === 'string';
}

export function isNumber(val) {
    return typeof val === 'number';
}

export function isObject(obj) {
    return obj !== null && typeof obj === 'object';
}

export function isUndefined(val) {
    return typeof val === 'undefined';
}

export function isDate(val) {
    return obj.toString.call(val) === '[object Date]';
}

export function toInt(val) {
    return parseInt(val, 10);
}

export function concat(arr1, arr2, index) {
    return arr1.concat(arr.slice.call(arr2, index));
}

export function uppercase(str) {
    return isString(str) ? str.toUpperCase() : str;
}

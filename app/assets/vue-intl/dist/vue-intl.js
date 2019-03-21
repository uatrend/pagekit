/*!
 * vue-intl v0.2.2
 * Released under the MIT License.
 */

(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (global.VueIntl = factory());
}(this, function () { 'use strict';

  var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && typeof Symbol === "function" && obj.constructor === Symbol ? "symbol" : typeof obj;
  };

  /**
   * Utility functions.
   */

  var util = {};
  var arr = Array.prototype;
  var obj = Object.prototype;
  function Util (Vue) {
      util = Vue.util;
  }

  function isString(val) {
      return typeof val === 'string';
  }

  function isNumber(val) {
      return typeof val === 'number';
  }

  function isObject(obj) {
      return obj !== null && (typeof obj === 'undefined' ? 'undefined' : _typeof(obj)) === 'object';
  }

  function isUndefined(val) {
      return typeof val === 'undefined';
  }

  function isDate(val) {
      return obj.toString.call(val) === '[object Date]';
  }

  function toInt(val) {
      return parseInt(val, 10);
  }

  function concat(arr1, arr2, index) {
      return arr1.concat(arr.slice.call(arr2, index));
  }

  function uppercase(str) {
      return isString(str) ? str.toUpperCase() : str;
  }

  var NUMBER_STRING = /^\-?\d+$/;
  var R_ISO8601_STR = /^(\d{4})-?(\d\d)-?(\d\d)(?:T(\d\d)(?::?(\d\d)(?::?(\d\d)(?:\.(\d+))?)?)?(Z|([+-])(\d\d):?(\d\d))?)?$/;
  var DATE_FORMATS_SPLIT = /((?:[^yMdHhmsaZEwG']+)|(?:'(?:[^']|'')*')|(?:E+|y+|M+|d+|H+|h+|m+|s+|a|Z|G+|w+))(.*)/;
  var DATE_FORMATS = {
      yyyy: dateGetter('FullYear', 4),
      yy: dateGetter('FullYear', 2, 0, true),
      y: dateGetter('FullYear', 1),
      MMMM: dateStrGetter('Month'),
      MMM: dateStrGetter('Month', true),
      MM: dateGetter('Month', 2, 1),
      M: dateGetter('Month', 1, 1),
      dd: dateGetter('Date', 2),
      d: dateGetter('Date', 1),
      HH: dateGetter('Hours', 2),
      H: dateGetter('Hours', 1),
      hh: dateGetter('Hours', 2, -12),
      h: dateGetter('Hours', 1, -12),
      mm: dateGetter('Minutes', 2),
      m: dateGetter('Minutes', 1),
      ss: dateGetter('Seconds', 2),
      s: dateGetter('Seconds', 1),
      sss: dateGetter('Milliseconds', 3),
      EEEE: dateStrGetter('Day'),
      EEE: dateStrGetter('Day', true),
      a: ampmGetter,
      Z: timeZoneGetter,
      ww: weekGetter(2),
      w: weekGetter(1),
      G: eraGetter,
      GG: eraGetter,
      GGG: eraGetter,
      GGGG: longEraGetter
  };

  function formatDate (date, format, timezone) {

      var text = '',
          parts = [],
          formats = this.$locale.DATETIME_FORMATS,
          fn,
          match;

      format = format || 'mediumDate';
      format = formats[format] || format;

      if (isString(date)) {
          date = NUMBER_STRING.test(date) ? toInt(date) : jsonStringToDate(date);
      }

      if (isNumber(date)) {
          date = new Date(date);
      }

      if (!isDate(date) || !isFinite(date.getTime())) {
          return date;
      }

      while (format) {
          match = DATE_FORMATS_SPLIT.exec(format);
          if (match) {
              parts = concat(parts, match, 1);
              format = parts.pop();
          } else {
              parts.push(format);
              format = null;
          }
      }

      var dateTimezoneOffset = date.getTimezoneOffset();

      if (timezone) {
          dateTimezoneOffset = timezoneToOffset(timezone, date.getTimezoneOffset());
          date = convertTimezoneToLocal(date, timezone, true);
      }

      parts.forEach(function (value) {
          fn = DATE_FORMATS[value];
          text += fn ? fn(date, formats, dateTimezoneOffset) : value.replace(/(^'|'$)/g, '').replace(/''/g, "'");
      });

      return text;
  }

  function padNumber(num, digits, trim) {
      var neg = '';
      if (num < 0) {
          neg = '-';
          num = -num;
      }
      num = '' + num;
      while (num.length < digits) {
          num = '0' + num;
      }if (trim) {
          num = num.substr(num.length - digits);
      }
      return neg + num;
  }

  function dateGetter(name, size, offset, trim) {
      offset = offset || 0;
      return function (date) {
          var value = date['get' + name]();
          if (offset > 0 || value > -offset) {
              value += offset;
          }
          if (value === 0 && offset == -12) value = 12;
          return padNumber(value, size, trim);
      };
  }

  function dateStrGetter(name, shortForm) {
      return function (date, formats) {
          var value = date['get' + name]();
          var get = uppercase(shortForm ? 'SHORT' + name : name);

          return formats[get][value];
      };
  }

  function timeZoneGetter(date, formats, offset) {
      var zone = -1 * offset;
      var paddedZone = zone >= 0 ? "+" : "";

      paddedZone += padNumber(Math[zone > 0 ? 'floor' : 'ceil'](zone / 60), 2) + padNumber(Math.abs(zone % 60), 2);

      return paddedZone;
  }

  function getFirstThursdayOfYear(year) {
      // 0 = index of January
      var dayOfWeekOnFirst = new Date(year, 0, 1).getDay();
      // 4 = index of Thursday (+1 to account for 1st = 5)
      // 11 = index of *next* Thursday (+1 account for 1st = 12)
      return new Date(year, 0, (dayOfWeekOnFirst <= 4 ? 5 : 12) - dayOfWeekOnFirst);
  }

  function getThursdayThisWeek(datetime) {
      return new Date(datetime.getFullYear(), datetime.getMonth(),
      // 4 = index of Thursday
      datetime.getDate() + (4 - datetime.getDay()));
  }

  function weekGetter(size) {
      return function (date) {
          var firstThurs = getFirstThursdayOfYear(date.getFullYear()),
              thisThurs = getThursdayThisWeek(date);

          var diff = +thisThurs - +firstThurs,
              result = 1 + Math.round(diff / 6.048e8); // 6.048e8 ms per week

          return padNumber(result, size);
      };
  }

  function ampmGetter(date, formats) {
      return date.getHours() < 12 ? formats.AMPMS[0] : formats.AMPMS[1];
  }

  function eraGetter(date, formats) {
      return date.getFullYear() <= 0 ? formats.ERAS[0] : formats.ERAS[1];
  }

  function longEraGetter(date, formats) {
      return date.getFullYear() <= 0 ? formats.ERANAMES[0] : formats.ERANAMES[1];
  }

  function jsonStringToDate(string) {
      var match;
      if (match = string.match(R_ISO8601_STR)) {
          var date = new Date(0),
              tzHour = 0,
              tzMin = 0,
              dateSetter = match[8] ? date.setUTCFullYear : date.setFullYear,
              timeSetter = match[8] ? date.setUTCHours : date.setHours;

          if (match[9]) {
              tzHour = toInt(match[9] + match[10]);
              tzMin = toInt(match[9] + match[11]);
          }
          dateSetter.call(date, toInt(match[1]), toInt(match[2]) - 1, toInt(match[3]));
          var h = toInt(match[4] || 0) - tzHour;
          var m = toInt(match[5] || 0) - tzMin;
          var s = toInt(match[6] || 0);
          var ms = Math.round(parseFloat('0.' + (match[7] || 0)) * 1000);
          timeSetter.call(date, h, m, s, ms);
          return date;
      }
      return string;
  }

  function timezoneToOffset(timezone, fallback) {
      var requestedTimezoneOffset = Date.parse('Jan 01, 1970 00:00:00 ' + timezone) / 60000;
      return isNaN(requestedTimezoneOffset) ? fallback : requestedTimezoneOffset;
  }

  function addDateMinutes(date, minutes) {
      date = new Date(date.getTime());
      date.setMinutes(date.getMinutes() + minutes);
      return date;
  }

  function convertTimezoneToLocal(date, timezone, reverse) {
      reverse = reverse ? -1 : 1;
      var timezoneOffset = timezoneToOffset(timezone, date.getTimezoneOffset());
      return addDateMinutes(date, reverse * (timezoneOffset - date.getTimezoneOffset()));
  }

  var DECIMAL_SEP = '.';

  function formatNumber (number, fractionSize) {

      var formats = this.$locale.NUMBER_FORMATS;

      // if null or undefined pass it through
      return number == null ? number : formatNumber$1(number, formats.PATTERNS[0], formats.GROUP_SEP, formats.DECIMAL_SEP, fractionSize);
  }

  function formatNumber$1(number, pattern, groupSep, decimalSep, fractionSize) {

      if (isObject(number)) {
          return '';
      }

      var isNegative = number < 0;
      number = Math.abs(number);

      var isInfinity = number === Infinity;
      if (!isInfinity && !isFinite(number)) return '';

      var numStr = number + '',
          formatedText = '',
          hasExponent = false,
          parts = [];

      if (isInfinity) {
          formatedText = '∞';
      }

      if (!isInfinity && numStr.indexOf('e') !== -1) {
          var match = numStr.match(/([\d\.]+)e(-?)(\d+)/);
          if (match && match[2] == '-' && match[3] > fractionSize + 1) {
              number = 0;
          } else {
              formatedText = numStr;
              hasExponent = true;
          }
      }

      if (!isInfinity && !hasExponent) {

          var fractionLen = (numStr.split(DECIMAL_SEP)[1] || '').length;

          // determine fractionSize if it is not specified
          if (isUndefined(fractionSize)) {
              fractionSize = Math.min(Math.max(pattern.minFrac, fractionLen), pattern.maxFrac);
          }

          // safely round numbers in JS without hitting imprecisions of floating-point arithmetics
          // inspired by: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/round
          number = +(Math.round(+(number.toString() + 'e' + fractionSize)).toString() + 'e' + -fractionSize);

          var fraction = ('' + number).split(DECIMAL_SEP);
          var whole = fraction[0];
          fraction = fraction[1] || '';

          var i,
              pos = 0,
              lgroup = pattern.lgSize,
              group = pattern.gSize;

          if (whole.length >= lgroup + group) {
              pos = whole.length - lgroup;
              for (i = 0; i < pos; i++) {
                  if ((pos - i) % group === 0 && i !== 0) {
                      formatedText += groupSep;
                  }
                  formatedText += whole.charAt(i);
              }
          }

          for (i = pos; i < whole.length; i++) {
              if ((whole.length - i) % lgroup === 0 && i !== 0) {
                  formatedText += groupSep;
              }
              formatedText += whole.charAt(i);
          }

          // format fraction part.
          while (fraction.length < fractionSize) {
              fraction += '0';
          }

          if (fractionSize && fractionSize !== '0') {
              formatedText += decimalSep + fraction.substr(0, fractionSize);
          }
      } else {
          if (fractionSize > 0 && number < 1) {
              formatedText = number.toFixed(fractionSize);
              number = parseFloat(formatedText);
          }
      }

      if (number === 0) {
          isNegative = false;
      }

      parts.push(isNegative ? pattern.negPre : pattern.posPre, formatedText, isNegative ? pattern.negSuf : pattern.posSuf);

      return parts.join('');
  }

  function formatCurrency (amount, currencySymbol, fractionSize) {

      var formats = this.$locale.NUMBER_FORMATS;

      if (isUndefined(currencySymbol)) {
          currencySymbol = formats.CURRENCY_SYM;
      }

      if (isUndefined(fractionSize)) {
          fractionSize = formats.PATTERNS[1].maxFrac;
      }

      // if null or undefined pass it through
      return amount == null ? amount : formatNumber$1(amount, formats.PATTERNS[1], formats.GROUP_SEP, formats.DECIMAL_SEP, fractionSize).replace(/\u00A4/g, currencySymbol);
  }

  /**
   * Pluralization rules.
   */

  var PLURAL_CACHE = {};
  var PLURAL_CATEGORY = { ZERO: 'zero', ONE: 'one', TWO: 'two', FEW: 'few', MANY: 'many', OTHER: 'other' };
  var PLURAL_LOCALES = [['en'], ['af', 'az', 'bg', 'chr', 'el', 'es', 'eu', 'gsw', 'haw', 'hu', 'ka', 'kk', 'ky', 'ml', 'mn', 'nb', 'ne', 'no', 'or', 'sq', 'ta', 'te', 'tr', 'uz'], ['am', 'bn', 'fa', 'gu', 'hi', 'kn', 'mr', 'zu'], ['ar'], ['be'], ['br'], ['bs', 'hr', 'sr'], ['cs', 'sk'], ['cy'], ['da'], ['fil', 'tl'], ['fr', 'hy'], ['ga'], ['he', 'iw'], ['id', 'in', 'ja', 'km', 'ko', 'lo', 'my', 'th', 'vi', 'zh'], ['is'], ['ln', 'pa'], ['lt'], ['lv'], ['mk'], ['ms'], ['mt'], ['pl'], ['pt'], ['ro'], ['ru', 'uk'], ['si'], ['sl']]; // END LOCALES
  var PLURAL_RULES = [function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (i == 1 && vf.v == 0) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n == 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;if (i == 0 || n == 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n == 0) {
          return PLURAL_CATEGORY.ZERO;
      }if (n == 1) {
          return PLURAL_CATEGORY.ONE;
      }if (n == 2) {
          return PLURAL_CATEGORY.TWO;
      }if (n % 100 >= 3 && n % 100 <= 10) {
          return PLURAL_CATEGORY.FEW;
      }if (n % 100 >= 11 && n % 100 <= 99) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n % 10 == 1 && n % 100 != 11) {
          return PLURAL_CATEGORY.ONE;
      }if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 12 || n % 100 > 14)) {
          return PLURAL_CATEGORY.FEW;
      }if (n % 10 == 0 || n % 10 >= 5 && n % 10 <= 9 || n % 100 >= 11 && n % 100 <= 14) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n % 10 == 1 && n % 100 != 11 && n % 100 != 71 && n % 100 != 91) {
          return PLURAL_CATEGORY.ONE;
      }if (n % 10 == 2 && n % 100 != 12 && n % 100 != 72 && n % 100 != 92) {
          return PLURAL_CATEGORY.TWO;
      }if ((n % 10 >= 3 && n % 10 <= 4 || n % 10 == 9) && (n % 100 < 10 || n % 100 > 19) && (n % 100 < 70 || n % 100 > 79) && (n % 100 < 90 || n % 100 > 99)) {
          return PLURAL_CATEGORY.FEW;
      }if (n != 0 && n % 1000000 == 0) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (vf.v == 0 && i % 10 == 1 && i % 100 != 11 || vf.f % 10 == 1 && vf.f % 100 != 11) {
          return PLURAL_CATEGORY.ONE;
      }if (vf.v == 0 && i % 10 >= 2 && i % 10 <= 4 && (i % 100 < 12 || i % 100 > 14) || vf.f % 10 >= 2 && vf.f % 10 <= 4 && (vf.f % 100 < 12 || vf.f % 100 > 14)) {
          return PLURAL_CATEGORY.FEW;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (i == 1 && vf.v == 0) {
          return PLURAL_CATEGORY.ONE;
      }if (i >= 2 && i <= 4 && vf.v == 0) {
          return PLURAL_CATEGORY.FEW;
      }if (vf.v != 0) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n == 0) {
          return PLURAL_CATEGORY.ZERO;
      }if (n == 1) {
          return PLURAL_CATEGORY.ONE;
      }if (n == 2) {
          return PLURAL_CATEGORY.TWO;
      }if (n == 3) {
          return PLURAL_CATEGORY.FEW;
      }if (n == 6) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);var wt = getWT(vf.v, vf.f);if (n == 1 || wt.t != 0 && (i == 0 || i == 1)) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (vf.v == 0 && (i == 1 || i == 2 || i == 3) || vf.v == 0 && i % 10 != 4 && i % 10 != 6 && i % 10 != 9 || vf.v != 0 && vf.f % 10 != 4 && vf.f % 10 != 6 && vf.f % 10 != 9) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;if (i == 0 || i == 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n == 1) {
          return PLURAL_CATEGORY.ONE;
      }if (n == 2) {
          return PLURAL_CATEGORY.TWO;
      }if (n >= 3 && n <= 6) {
          return PLURAL_CATEGORY.FEW;
      }if (n >= 7 && n <= 10) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (i == 1 && vf.v == 0) {
          return PLURAL_CATEGORY.ONE;
      }if (i == 2 && vf.v == 0) {
          return PLURAL_CATEGORY.TWO;
      }if (vf.v == 0 && (n < 0 || n > 10) && n % 10 == 0) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);var wt = getWT(vf.v, vf.f);if (wt.t == 0 && i % 10 == 1 && i % 100 != 11 || wt.t != 0) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n >= 0 && n <= 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var vf = getVF(n, precision);if (n % 10 == 1 && (n % 100 < 11 || n % 100 > 19)) {
          return PLURAL_CATEGORY.ONE;
      }if (n % 10 >= 2 && n % 10 <= 9 && (n % 100 < 11 || n % 100 > 19)) {
          return PLURAL_CATEGORY.FEW;
      }if (vf.f != 0) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var vf = getVF(n, precision);if (n % 10 == 0 || n % 100 >= 11 && n % 100 <= 19 || vf.v == 2 && vf.f % 100 >= 11 && vf.f % 100 <= 19) {
          return PLURAL_CATEGORY.ZERO;
      }if (n % 10 == 1 && n % 100 != 11 || vf.v == 2 && vf.f % 10 == 1 && vf.f % 100 != 11 || vf.v != 2 && vf.f % 10 == 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (vf.v == 0 && i % 10 == 1 || vf.f % 10 == 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n) {
      return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n == 1) {
          return PLURAL_CATEGORY.ONE;
      }if (n == 0 || n % 100 >= 2 && n % 100 <= 10) {
          return PLURAL_CATEGORY.FEW;
      }if (n % 100 >= 11 && n % 100 <= 19) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (i == 1 && vf.v == 0) {
          return PLURAL_CATEGORY.ONE;
      }if (vf.v == 0 && i % 10 >= 2 && i % 10 <= 4 && (i % 100 < 12 || i % 100 > 14)) {
          return PLURAL_CATEGORY.FEW;
      }if (vf.v == 0 && i != 1 && i % 10 >= 0 && i % 10 <= 1 || vf.v == 0 && i % 10 >= 5 && i % 10 <= 9 || vf.v == 0 && i % 100 >= 12 && i % 100 <= 14) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      if (n >= 0 && n <= 2 && n != 2) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (i == 1 && vf.v == 0) {
          return PLURAL_CATEGORY.ONE;
      }if (vf.v != 0 || n == 0 || n != 1 && n % 100 >= 1 && n % 100 <= 19) {
          return PLURAL_CATEGORY.FEW;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (vf.v == 0 && i % 10 == 1 && i % 100 != 11) {
          return PLURAL_CATEGORY.ONE;
      }if (vf.v == 0 && i % 10 >= 2 && i % 10 <= 4 && (i % 100 < 12 || i % 100 > 14)) {
          return PLURAL_CATEGORY.FEW;
      }if (vf.v == 0 && i % 10 == 0 || vf.v == 0 && i % 10 >= 5 && i % 10 <= 9 || vf.v == 0 && i % 100 >= 11 && i % 100 <= 14) {
          return PLURAL_CATEGORY.MANY;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (n == 0 || n == 1 || i == 0 && vf.f == 1) {
          return PLURAL_CATEGORY.ONE;
      }return PLURAL_CATEGORY.OTHER;
  }, function (n, precision) {
      var i = n | 0;var vf = getVF(n, precision);if (vf.v == 0 && i % 100 == 1) {
          return PLURAL_CATEGORY.ONE;
      }if (vf.v == 0 && i % 100 == 2) {
          return PLURAL_CATEGORY.TWO;
      }if (vf.v == 0 && i % 100 >= 3 && i % 100 <= 4 || vf.v != 0) {
          return PLURAL_CATEGORY.FEW;
      }return PLURAL_CATEGORY.OTHER;
  }]; // END RULES

  PLURAL_LOCALES.map(function (locales, i) {
      locales.map(function (locale) {
          PLURAL_CACHE[locale] = PLURAL_RULES[i];
      });
  });

  function plural (id, num, precision) {

      var match = id.match(/^\w+/i);

      if (match) {
          id = match[0];
      }

      if (!PLURAL_CACHE[id]) {
          id = 'en';
      }

      return PLURAL_CACHE[id](num, precision);
  }

  function getDecimals(n) {
      n = n + '';
      var i = n.indexOf('.');
      return i == -1 ? 0 : n.length - i - 1;
  }

  function getVF(n, precision) {
      var v = precision;

      if (undefined === v) {
          v = Math.min(getDecimals(n), 3);
      }

      var base = Math.pow(10, v);
      var f = (n * base | 0) % base;
      return { v: v, f: f };
  }

  var approximate_multiplier = 0.75;
  var default_type = "default";
  var time_in_seconds = {
      "second": 1,
      "minute": 60,
      "hour": 3600,
      "day": 86400,
      "week": 604800,
      "month": 2629743.83,
      "year": 31556926
  };
  function relativeDate (date, options) {

      date = date instanceof Date ? date : new Date(date);

      if (options && options.max && Math.abs((date - new Date()) / 1000) > options["max"]) {
          return formatDate(date);
      }

      return format((date - new Date()) / 1000, options, this.$locale.TIMESPAN_FORMATS, this.$locale.TIMESPAN_FORMATS.localeID || this.$locale.id);
  }

  function calculate_unit(seconds, unit_options) {
      var key, multiplier, obj, options;
      if (unit_options == null) {
          unit_options = {};
      }
      options = {};
      for (key in unit_options) {
          obj = unit_options[key];
          options[key] = obj;
      }
      if (options.approximate == null) {
          options["approximate"] = false;
      }
      multiplier = options.approximate ? approximate_multiplier : 1;
      if (seconds < time_in_seconds.minute * multiplier) {
          return "second";
      } else if (seconds < time_in_seconds.hour * multiplier) {
          return "minute";
      } else if (seconds < time_in_seconds.day * multiplier) {
          return "hour";
      } else if (seconds < time_in_seconds.week * multiplier) {
          return "day";
      } else if (seconds < time_in_seconds.month * multiplier) {
          return "week";
      } else if (seconds < time_in_seconds.year * multiplier) {
          return "month";
      } else {
          return "year";
      }
  }

  function calculate_time(seconds, unit) {
      return Math.round(seconds / time_in_seconds[unit]);
  }

  function format(seconds, fmt_options, patterns, locale) {
      var key, number, obj, options;
      if (fmt_options == null) {
          fmt_options = {};
      }
      options = {};
      for (key in fmt_options) {
          obj = fmt_options[key];
          options[key] = obj;
      }
      options["direction"] || (options["direction"] = seconds < 0 ? "ago" : "until");
      if (options["unit"] === null || options["unit"] === void 0) {
          options["unit"] = calculate_unit(Math.abs(seconds), options);
      }
      options["type"] || (options["type"] = default_type);
      options["number"] = calculate_time(Math.abs(seconds), options["unit"]);
      number = calculate_time(Math.abs(seconds), options["unit"]);
      options["rule"] = plural(locale, number);
      return patterns[options["direction"]][options["unit"]][options["type"]][options["rule"]].replace(/\{[0-9]\}/, number.toString());
  }

  var DATETIME_FORMATS = { "AMPMS": ["AM", "PM"], "DAY": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"], "ERANAMES": ["Before Christ", "Anno Domini"], "ERAS": ["BC", "AD"], "FIRSTDAYOFWEEK": 6, "MONTH": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], "SHORTDAY": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], "SHORTMONTH": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], "STANDALONEMONTH": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], "WEEKENDRANGE": [5, 6], "fullDate": "EEEE, MMMM d, y", "longDate": "MMMM d, y", "medium": "MMM d, y h:mm:ss a", "mediumDate": "MMM d, y", "mediumTime": "h:mm:ss a", "short": "M/d/yy h:mm a", "shortDate": "M/d/yy", "shortTime": "h:mm a" };
  var NUMBER_FORMATS = { "CURRENCY_SYM": "$", "DECIMAL_SEP": ".", "GROUP_SEP": ",", "PATTERNS": [{ "gSize": 3, "lgSize": 3, "maxFrac": 3, "minFrac": 0, "minInt": 1, "negPre": "-", "negSuf": "", "posPre": "", "posSuf": "" }, { "gSize": 3, "lgSize": 3, "maxFrac": 2, "minFrac": 2, "minInt": 1, "negPre": "-¤", "negSuf": "", "posPre": "¤", "posSuf": "" }] };
  var id = "en";
  var localeID = "en";
  var TIMESPAN_FORMATS = { "ago": { "second": { "default": { "one": "{0} second ago", "other": "{0} seconds ago" } }, "minute": { "default": { "one": "{0} minute ago", "other": "{0} minutes ago" } }, "hour": { "default": { "one": "{0} hour ago", "other": "{0} hours ago" } }, "day": { "default": { "one": "{0} day ago", "other": "{0} days ago" } }, "week": { "default": { "one": "{0} week ago", "other": "{0} weeks ago" } }, "month": { "default": { "one": "{0} month ago", "other": "{0} months ago" } }, "year": { "default": { "one": "{0} year ago", "other": "{0} years ago" } } }, "until": { "second": { "default": { "one": "In {0} second", "other": "In {0} seconds" } }, "minute": { "default": { "one": "In {0} minute", "other": "In {0} minutes" } }, "hour": { "default": { "one": "In {0} hour", "other": "In {0} hours" } }, "day": { "default": { "one": "In {0} day", "other": "In {0} days" } }, "week": { "default": { "one": "In {0} week", "other": "In {0} weeks" } }, "month": { "default": { "one": "In {0} month", "other": "In {0} months" } }, "year": { "default": { "one": "In {0} year", "other": "In {0} years" } } }, "none": { "second": { "default": { "one": "{0} second", "other": "{0} seconds" }, "short": { "one": "{0} sec", "other": "{0} secs" }, "abbreviated": { "one": "{0}s", "other": "{0}s" } }, "minute": { "default": { "one": "{0} minute", "other": "{0} minutes" }, "short": { "one": "{0} min", "other": "{0} mins" }, "abbreviated": { "one": "{0}m", "other": "{0}m" } }, "hour": { "default": { "one": "{0} hour", "other": "{0} hours" }, "short": { "one": "{0} hr", "other": "{0} hrs" }, "abbreviated": { "one": "{0}h", "other": "{0}h" } }, "day": { "default": { "one": "{0} day", "other": "{0} days" }, "short": { "one": "{0} day", "other": "{0} days" }, "abbreviated": { "one": "{0}d", "other": "{0}d" } }, "week": { "default": { "one": "{0} week", "other": "{0} weeks" }, "short": { "one": "{0} wk", "other": "{0} wks" } }, "month": { "default": { "one": "{0} month", "other": "{0} months" }, "short": { "one": "{0} mth", "other": "{0} mths" } }, "year": { "default": { "one": "{0} year", "other": "{0} years" }, "short": { "one": "{0} yr", "other": "{0} yrs" } } }, "localeID": "en" };
  var defaultLocale = {
  	DATETIME_FORMATS: DATETIME_FORMATS,
  	NUMBER_FORMATS: NUMBER_FORMATS,
  	id: id,
  	localeID: localeID,
  	TIMESPAN_FORMATS: TIMESPAN_FORMATS
  };

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

  return plugin;

}));
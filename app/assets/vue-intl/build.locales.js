var fs = require('fs');
var md5 = require('md5');
var glob = require('glob');
var path = require('path');
var plural = path.join(__dirname, 'src/plural.js');
var output = path.join(__dirname, 'dist/locales/');
var relative = path.join(path.dirname(require.resolve('twitter_cldr')),'full/');
var source = false;

module.paths.forEach(function (modules) {
    var p = path.join(modules,'angular-i18n/');
    if (fs.existsSync(p)) {
        source = p;
    }
});

if (!source) {
  console.log('Unable to find source data.');
  return;
}

global.angular = {

    locales: [],
    plurals: {},

    module: function (name, requires, config) {

        var fn = config[1];

        fn.call(fn, this);
    },

    value: function (name, value) {

        var id = value.id.match(/^\w+/i)[0],
            fn = value.pluralCat.toString(),
            key = md5(fn), file, data;

        if (this.locales.indexOf(id) === -1) {

            if (!this.plurals[key]) {
                this.plurals[key] = {fn: fn, locales: [id]};
            } else if (this.plurals[key].locales.indexOf(id) === -1) {
                this.plurals[key].locales.push(id);
            }

            this.locales.push(id);
        }

        delete value.pluralCat;

        [value.id, id, 'en'].forEach(function(locale) {
            var path = relative+locale+'.js';
            if (!file && fs.existsSync(path)) {
                file = path;
                id = locale;
            }
        });

        if (file) {
            value.TIMESPAN_FORMATS = (new (require(file)).TimespanFormatter).patterns;
            value.TIMESPAN_FORMATS.localeID = id;
        }

        file = output + value.id + '.json';
        data = JSON.stringify(value);

        fs.writeFileSync(file, data);
    }

};

console.log('> Generating locale files ...');

if (!fs.existsSync(output)) {
    fs.mkdirSync(output);
}

glob.sync('angular-locale*', {cwd: source}).forEach(function (file) {
    require(source + file);
});

console.log('> Updating plural.js ...');

var rules = [];
var locales = [];
var content = fs.readFileSync(plural, 'utf8');
var convert = function (value) {
    return "'" + value + "'";
};

Object.keys(angular.plurals).forEach(function (key) {

    var data = angular.plurals[key];

    // skip same locales from 'en', because it's the default
    if (data.locales.indexOf('en') !== -1) {
        data.locales = ['en'];
    }

    locales.push('[' + data.locales.map(convert).toString() + ']');
    rules.push(data.fn.replace(/opt_precision/gi, 'precision'));
});

content = content.replace(/(var PLURAL_LOCALES = \[)[^]*(\]; \/\/ END LOCALES)/gm, '$1' + locales.toString() + '$2');
content = content.replace(/(var PLURAL_RULES = \[)[^]*(\]; \/\/ END RULES)/gm, '$1' + rules.toString() + '$2');

fs.writeFileSync(plural, content);
console.log('');

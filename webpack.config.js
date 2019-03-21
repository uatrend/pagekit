const _               = require('lodash');
const glob            = require('glob');
const path            = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

const mode    = (process.argv.indexOf('-p') !== -1) ? 'production' : 'development';
const skip    = ['coverkit/theme-abz1', 'coverkit/tinymce'];
const ignore  = ['packages/**/node_modules/**', 'packages/**/app/assets/**', 'packages/**/app/vendor/**'];

var exports = [];

glob.sync('{app/modules/**,app/installer/**,app/system/**,packages/**}/webpack.config.js', {ignore: ignore}).forEach((file) => {
    let dir = path.join(__dirname, path.dirname(file));
    let pkg = path.join(path.basename(path.dirname(dir)), path.basename(dir));
    if (skip.includes(pkg)) return;

    exports = exports.concat(require('./' + file).map((config) => {
        let cfg     = _.merge({mode: mode, context: dir, output: {path: dir}, externals: {"Vue": "Vue", "uikit": "UIkit", "uikit-util": "UIkit.util"}}, config),
            loaders = (loader) => { let rules = _.get(cfg, 'module.rules'); return (rules && rules.filter((e) => e.use === loader).length) },
            plugins = _.get(cfg, 'plugins');

        if (!plugins) cfg.plugins = [];
        if (loaders('vue-loader')) cfg.plugins.push(new VueLoaderPlugin());

        return cfg;
    }));

});

module.exports = exports;
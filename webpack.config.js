const _ = require('lodash');
const glob = require('glob');
const path = require('path');
const argv = require('minimist')(process.argv.slice(2));
const VueLoaderPlugin = require('vue-loader/lib/plugin');

const VueLoader = new VueLoaderPlugin();

exports = [];

// Configuration mode
const args = { p: true, mode: 'production', 'env.production': true };
const mode = _.some(args, (value, arg) => (_.get(argv, arg) === value)) ? 'production' : 'development';

// Define defaults for all webpack.config.js
const defaults = {
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: [/node_modules/, /assets/, /vendor/],
                use: ['babel-loader']
            }
        ]
    },
    resolve: {
        alias: {
            '@installer': path.resolve(__dirname, 'app/installer'),
            '@system': path.resolve(__dirname, 'app/system')
        }
    },
    externals: {
        vue: 'Vue',
        uikit: 'UIkit',
        'uikit-util': 'UIkit.util'
    }
};

// Define paths to ignore configs
const ignore = [
    'packages/**/node_modules/**',
    'packages/**/app/assets/**',
    'packages/**/app/vendor/**'
    // example - '**/pagekit/blog/**'
];

// Process
glob.sync('{app/modules/**,app/installer/**,app/system/**,packages/**}/webpack.config.js', { ignore }).forEach((file) => {
    const dir = path.join(__dirname, path.dirname(file));
    exports = exports.concat(require(`./${file}`).map((config) => make(dir, config))); // eslint-disable-line import/no-dynamic-require, global-require
});

// Make each webpack.config.js
function make(dir, config) {
    const plugins = _.get(config, 'plugins') || [];
    const rules = _.get(config, 'module.rules') || [];
    const isPluginExist = (plugin) => !!plugins.filter((plg) => (plg.constructor.name === plugin.constructor.name)).length;
    const isLoaderExist = (loader) => !!rules.filter((e) => (e.use === loader || e.use.indexOf(loader) !== -1)).length;

    // Merge with defaults
    config = _.merge({
        mode,
        context: dir,
        output: { path: dir },
        resolve: defaults.resolve,
        externals: defaults.externals
    }, config);

    // Default rules to first
    _.set(config, 'module.rules', _.concat([], defaults.module.rules, rules));

    // Push VueLoaderPlugin if needed and doesn't exist.
    if (isLoaderExist('vue-loader') && !isPluginExist(VueLoader)) {
        plugins.push(VueLoader);
        config.plugins = plugins;
    }

    return config;
}

module.exports = exports;

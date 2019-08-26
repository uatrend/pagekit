const assets = `${__dirname}/../assets`;
const node_modules = `${__dirname}/../../node_modules`;

module.exports = [

    {
        entry: {
            vue: './app/vue',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        resolve: {
            alias: {
                md5$: `${node_modules}/js-md5/src/md5.js`,
                'vue-form$': `${assets}/vue-form/dist/vue-form.common.js`,
                'vue-intl$': `${assets}/vue-intl/dist/vue-intl.common.js`,
                'vue-resource$': `${node_modules}/vue-resource/dist/vue-resource.common.js`,
                JSONStorage$: `${assets}/JSONStorage/storage.js`,
            },
        },
        module: {
            rules: [
                { test: /\.vue$/, use: 'vue-loader' },
                { test: /\.json$/, use: 'json-loader' },
                { test: /\.html$/, use: 'vue-html-loader' },
                { test: /\.css$/, use: ['vue-style-loader','css-loader'] }
            ],
        },
    },

];

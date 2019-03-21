module.exports = [

    {
        entry: {
            installer: './app/views/installer',
            extensions: './app/views/extensions',
            marketplace: './app/views/marketplace',
            themes: './app/views/themes',
            update: './app/views/update',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        module: {
            rules: [
                { test: /\.vue$/, use: 'vue-loader' },
            ],
        },
    },

];

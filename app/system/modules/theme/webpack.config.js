module.exports = [

    {
        entry: {
            theme: './js/main',
        },
        output: {
            filename: './js/[name].js',
        },
        module: {
            rules: [
                { test: /\.html$/, use: 'vue-html-loader' },
                { test: /\.vue$/, use: 'vue-loader' },
            ],
        },
    },

];

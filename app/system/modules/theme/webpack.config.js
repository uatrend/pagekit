module.exports = [
    {
        entry: {
            login: './app/views/login',
            theme: './app/views/theme'
        },
        output: { filename: './app/bundle/[name].js' },
        module: {
            rules: [
                { test: /\.html$/, use: 'html-loader' },
                { test: /\.vue$/, use: 'vue-loader' }
            ]
        }
    }

];

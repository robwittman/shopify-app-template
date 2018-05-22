const path = require('path');
const webpack = require('webpack');
const parentDir = path.join(__dirname, '../');

module.exports = {
    entry: [
        path.join(parentDir, 'src/index.js')
    ],
    output: {
        path: path.resolve(__dirname, '../build'),
        filename: 'index.bundle.js'
    },
    module: {
        rules: [{
            test: /\.(js|jsx)$/,
            exclude: /node_modules/,
            loader: 'babel-loader'
        },{
            test: /\.less$/,
            loaders: ["style-loader", "css-loder", "less-loader"]
        }]
    },
    plugins: [
        new webpack.DefinePlugin({
            "ENVIRONMENT":          JSON.stringify(process.env.ENVIRONMENT),
            "SHOPIFY_API_KEY":      JSON.stringify(process.env.SHOPIFY_API_KEY),
            "SHOPIFY_REDIRECT_URI": JSON.stringify(process.env.SHOPIFY_REDIRECT_URI),
            "SHOPIFY_SCOPES":       JSON.stringify(process.env.SHOPIFY_SCOPES),
            "PUSHER_APP_KEY":       JSON.stringify(process.env.PUSHER_APP_KEY),
            "PUSHER_HOST" :         JSON.stringify(process.env.PUSHER_HOST)
        }),
    ]
};

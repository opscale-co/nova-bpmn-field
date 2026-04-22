let mix = require('laravel-mix')
let path = require('path')
let webpack = require('webpack')

mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue({ version: 3 })
    .css('resources/css/field.css', 'css')
    .webpackConfig({
        externals: {
            vue: 'Vue',
            'laravel-nova': 'LaravelNova',
            'laravel-nova-ui': 'LaravelNovaUi',
        },
        output: {
            uniqueName: 'opscale/nova-bpmn-field',
        },
        resolve: {
            symlinks: false,
            alias: { vue: path.resolve('./node_modules/vue') },
        },
        plugins: [
            new webpack.DefinePlugin({
                __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'false',
            }),
        ],
    })
    .options({
        vue: {
            exposeFilename: true,
        },
    })

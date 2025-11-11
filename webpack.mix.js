const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .extract([
        'jquery',
        'jquery-ui',
        'bootstrap',
        '@popperjs/core',
        '@coreui/coreui',
        'axios',
        'lodash'
    ])
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .options({
        processCssUrls: false
    })
    .webpackConfig({
        resolve: {
            extensions: ['.wasm', '.mjs', '.js', '.jsx', '.json'],
            modules: ['node_modules'],
        }
    });

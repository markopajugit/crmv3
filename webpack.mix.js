const mix = require('laravel-mix');
const path = require('path');

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

mix.setPublicPath('public')
    .js('resources/js/app.js', 'js/app.js')
    .sass('resources/sass/app.scss', 'css/app.css')
    .sourceMaps()
    .webpackConfig({
        resolve: {
            extensions: ['.js', '.json', '.wasm'],
            modules: ['node_modules'],
            symlinks: false
        },
        resolveLoader: {
            modules: ['node_modules']
        }
    })
    .options({
        processCssUrls: false
    });

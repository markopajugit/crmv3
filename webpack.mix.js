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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .setPublicPath('public')
    .webpackConfig({
        context: path.resolve(__dirname),
        resolve: {
            extensions: ['.js', '.json', '.wasm'],
            modules: ['node_modules', path.resolve(__dirname, 'resources')],
            alias: {}
        }
    })
    .options({
        processCssUrls: false
    });

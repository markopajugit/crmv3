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
    .sass('resources/sass/app.scss', 'public/css', {
        sassOptions: {
            quietDeps: true, // Suppress deprecation warnings from dependencies
            silenceDeprecations: ['import', 'legacy-js-api'] // Silence specific deprecation warnings
        }
    })
    .sourceMaps()
    .options({
        processCssUrls: false
    })
    .webpackConfig({
        stats: {
            children: false
        },
        module: {
            rules: [
                {
                    test: /\.s[ac]ss$/i,
                    use: [
                        {
                            loader: 'sass-loader',
                            options: {
                                sassOptions: {
                                    quietDeps: true,
                                    silenceDeprecations: ['import', 'legacy-js-api']
                                }
                            }
                        }
                    ]
                }
            ]
        }
    });

const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */


mix.browserSync({
    proxy: 'https://maria-flag.test',
});

mix.js('resources/js/app.js', 'public/js')
    .copy('resources/fonts', 'public/fonts')
    .sass('resources/sass/style.scss', 'public/css')
        .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}

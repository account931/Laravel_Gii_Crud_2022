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
   .js('resources/js/Vue_crud_panel/vue_crud_panel_start.js',   'public/js/Vue_crud_panel')  //Vue.js; Source-> Destination
   .sass('resources/sass/app.scss', 'public/css');

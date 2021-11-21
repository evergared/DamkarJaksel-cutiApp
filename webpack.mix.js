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
    .js('resources/js/eventbus.js','public/js')
    .js('resources/js/test.js','public/js')
    .js('resources/js/formCuti.js','public/js')
    .js('resources/js/fullCalendar.js','public/js')
    .js('resources/js/dataTable.js','public/js')
    .js('resources/js/datatables/admin-asn.js','public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

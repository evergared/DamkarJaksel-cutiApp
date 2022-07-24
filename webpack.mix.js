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

mix.js('resources/js/eventbus.js','public/js')
    .js('resources/js/formCuti.js','public/js')
    .js('resources/js/formUser.js','public/js')
    .js('resources/js/formPegawai.js','public/js')
    .js('resources/js/fullCalendar.js','public/js')
    .js('resources/js/PengumumanCreatorPage.js','public/js')
    .js('resources/js/datatables/admin-asn.js','public/js/datatables')
    .js('resources/js/datatables/admin-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/kasie-asn.js','public/js/datatables')
    .js('resources/js/datatables/kasie-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/kasudin-asn.js','public/js/datatables')
    .js('resources/js/datatables/kasudin-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/tu-asn.js','public/js/datatables')
    .js('resources/js/datatables/tu-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/ppk-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/self-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/self-asn.js','public/js/datatables')    
    .js('resources/js/datatables/plt-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/plt-asn.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/user-list.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/pegawai-master.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/pegawai-asn.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/pegawai-pjlp.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/pegawai-jabatan.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/pegawai-penempatan.js','public/js/datatables')
    .js('resources/js/datatables/admin-tables/pegawai-plt.js','public/js/datatables')
    .js('resources/js/dataTable.js','public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

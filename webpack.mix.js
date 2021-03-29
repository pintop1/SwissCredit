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

mix.styles([
    'public/assets/plugins/sweet-alert2/sweetalert2.min.css',
    'public/assets/plugins/animate/animate.css',
    'public/assets/plugins/hopscotch/dist/css/hopscotch.min.css',
    'public/assets/css/bootstrap.min.css',
    'public/assets/css/jquery-ui.min.css',
    'public/assets/css/metisMenu.min.css',
    'public/assets/assets/css/app.min.css',
    'public/assets/fonts/dripicons/webfont/webfont.css',
    'public/assets/fonts/icons/LineIcons.css',
    'public/assets/fonts/fontawesome/css/all.css'
], 'public/all.css');

const { mix } = require('laravel-mix');
var ImageminPlugin = require('imagemin-webpack-plugin').default;

mix.webpackConfig({
    plugins: [
        new ImageminPlugin( {
            pngquant: {
                quality: '95-100'
            },
            test: /\.(jpe?g|png|gif|svg)$/i
        } )
    ]
});
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
mix.js('resources/assets/js/app.js', 'public/js')
   .copy('resources/assets/adminlte/plugins', 'public/adminlte/plugins')
   .copy('resources/assets/adminlte/img', 'public/images')
   .copy('resources/assets/images', 'public/images')
   .copy('resources/assets/data', 'public/data')
   .less('resources/assets/adminlte/less/AdminLTE.less', 'public/css/adminlte.css')
   .less('resources/assets/adminlte/less/skins/_all-skins.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-black.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-black-light.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-blue.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-blue-light.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-green.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-green-light.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-purple.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-purple-light.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-red.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-red-light.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-yellow.less', 'public/css/skins')
   .less('resources/assets/adminlte/less/skins/skin-yellow-light.less', 'public/css/skins')
   .sass('resources/assets/sass/base.scss', 'public/css')
   .sass('resources/assets/sass/main.scss', 'public/css');


if (mix.config.inProduction) {
    mix.version();
}

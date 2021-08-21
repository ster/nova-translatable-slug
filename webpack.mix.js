let mix = require('laravel-mix')
var path = require('path');

mix.disableNotifications();

mix
  .setPublicPath('dist')
  .js('resources/js/field.js', 'js').vue()
  .sass('resources/sass/field.scss', 'css')
  .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/js/'),
            },
        },
    })

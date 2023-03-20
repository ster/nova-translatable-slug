let mix = require('laravel-mix')

require('./nova.mix')
const path = require("path");

mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue({version: 3})
    .css('resources/css/field.css', 'css')
    .nova('ster/translatable-slug')
    .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/js/'),
            },
        },
    });

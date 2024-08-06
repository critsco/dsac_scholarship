const mix = require("laravel-mix");
const moment = require("moment");

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

mix.js("resources/js/app.js", "public/js")
    .react()
    .sass("resources/sass/app.scss", "public/css")
    .webpackConfig({
        output: {
            publicPath: "/",
            chunkFilename: "js/[name].[chunkhash].js?v=" + moment().unix(),
        },
    });

mix.version();
// if (mix.inProduction()) {
//     mix.version();
// }

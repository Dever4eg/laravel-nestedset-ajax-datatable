let mix = require('laravel-mix');

if(!mix.inProduction()) {
    var LiveReloadPlugin = require('webpack-livereload-plugin');
    mix.webpackConfig({
        plugins: [
            new LiveReloadPlugin()
        ]
    });
}

mix.js('resources/assets/js/app.js', 'public/js')
    .version();
mix.sass('resources/assets/sass/app.scss', 'public/css')
    .version();
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    
    var elixir = require('laravel-elixir');
    var browserify = require('laravel-elixir-browserify');

    elixir(function(mix) {
        browserify.init();
        mix
            .browserify('app.js', {
                insertGlobals: true,
                transform: "vueify",
                output: "public/js"
            })
            .scripts([], 'public/js/app.js')
            .sass('app.scss')
        ;
    });
});

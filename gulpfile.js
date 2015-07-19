var gulp = require('gulp');
var elixir = require('laravel-elixir');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');

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
    mix.scripts(['app.js'], 'public/assets/js/all.js');
    mix.sass(['app.scss'], 'public/assets/css');
    mix.task('images');
});

gulp.task('images', function () {
    return gulp.src('resources/assets/images/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('public/assets/img'));
});
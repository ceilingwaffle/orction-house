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
    mix.copy(
        'bower_components/jquery-validation/dist/jquery.validate.js',
        'public/assets/js/vendor/jquery-validation/jquery.validate.js'
    );
    mix.copy(
        'bower_components/jquery-validation/dist/additional-methods.js',
        'public/assets/js/vendor/jquery-validation/additional-methods.js'
    );
    mix.copy(
        'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        'public/assets/js/vendor/eonasdan-bootstrap-datetimepicker/bootstrap-datetimepicker.min.js'
    );
    mix.copy(
        'bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        'public/assets/css/vendor/eonasdan-bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'
    );
    mix.copy(
        'bower_components/moment/min/moment-with-locales.min.js',
        'public/assets/js/vendor/moment/moment-with-locales.min.js'
    );


    mix.scripts(['app.js'], 'public/assets/js/app.js');

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
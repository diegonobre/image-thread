// declaring packages used for clean, concat and minified CSS/JS
var gulp      = require('gulp'),
    css       = require('gulp-clean-css'),
    concat    = require('gulp-concat'),
    uglify    = require('gulp-uglify'),
    cleanDest = require('gulp-clean-dest'),
    watch     = require('gulp-watch');

// defining paths for CSS and JS
var path = {
    css: {
        src: [
            'app/Resources/assets/css/bootstrap.css',
            'app/Resources/assets/css/blog-post.css',
            'app/Resources/assets/css/fileinput.css'
        ],
        dist: 'web/dist/css'
    },
    js: {
        src: [
            'app/Resources/assets/js/jquery.js',
            'app/Resources/assets/js/bootstrap.js',
            'app/Resources/assets/js/fileinput.js'
        ],
        dist: 'web/dist/js'
    }
};

// CSS task
gulp.task('css', function () {
    return gulp.src(path.css.src)
        .pipe(css())
        .pipe(concat('all.min.css'))
        .pipe(cleanDest('out'))
        .pipe(gulp.dest(path.css.dist));
});

// JS task
gulp.task('js', function() {
    return gulp.src(path.js.src)
        .pipe(uglify())
        .pipe(concat('all.min.js'))
        .pipe(cleanDest('out'))
        .pipe(gulp.dest(path.js.dist))
});

// Clean task
gulp.task('clean', function () {
    return gulp.src([path.css.dist, path.js.dist], {read: false})
        .pipe(cleanDest('out'));
});

// Watch task
gulp.task('watch', function () {
    gulp.watch(path.css.src, ['clean', 'css']);
    gulp.watch(path.js.src,  ['clean', 'js']);
});

// gulp task
gulp.task('default', ['css', 'js']);

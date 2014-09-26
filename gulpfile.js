var gulp   = require('gulp'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    less   = require('gulp-less'),
    csso   = require('gulp-minify-css'),

    rename = require('gulp-rename');

gulp.task('script', function() {
    gulp.src('./web/app/main.js')
        .pipe(uglify({
            compress: true,
            mangle: true
        }))
        .pipe(rename('main.min.js'))
        .pipe(gulp.dest('./web/app'));

    gulp.src(['./web/bower_components/jquery/dist/jquery.min.js', './web/bower_components/angular/angular.min.js', './web/bower_components/angular-route/angular-route.min.js', './web/app/main.min.js'])
        .pipe(uglify({
            compress: true
        }))
        .pipe(concat('main.min.js', { newLine: '\n' }))
        .pipe(gulp.dest('./web/app'));

    gulp.src(['./web/bower_components/jquery/dist/jquery.min.js', './web/app/login.js'])
        .pipe(uglify({
            compress: true,
            mangle: true
        }))
        .pipe(concat('login.min.js', { newLine: ';' }))
        .pipe(gulp.dest('./web/app'));
});

gulp.task('style', function() {
    gulp.src(['./web/bower_components/normalize.css/normalize.css', './web/style/style.less'])
        .pipe(less())
        .pipe(csso())
        .pipe(concat('style.min.css'))
        .pipe(gulp.dest('./web/style'));
});

// gulp.task('html', function() {
//     gulp.src('./index.html')
//         .pipe(rename('index.php'))
//         .pipe(html({
//             collapseWhitespace: true,
//             removeAttributeQuotes: true,
//             caseSensitive: true
//         }))
//         .pipe(gulp.dest('/Volumes/trigen/thank/public_html/'));
// });

gulp.task('build', ['script', 'style']);

gulp.task('default', ['build'], function() {

    gulp.watch('./web/app/main.js', ['script'], function(event) {
        console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });

    gulp.watch('./web/style/style.less', ['style'], function(event) {
        console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });
    
});
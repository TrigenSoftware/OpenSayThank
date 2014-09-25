var gulp   = require('gulp'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    less   = require('gulp-less'),
    csso   = require('gulp-csso'),

    rename = require('gulp-rename');

gulp.task('script', function() {
    gulp.src(['./web/bower_components/jquery/dist/jquery.min.js', './web/bower_components/angular/angular.min.js', './web/bower_components/angular-route/angular-route.min.js', './web/app/main.js'])
        .pipe(uglify({
            compress: true
        }))
        .pipe(concat('main.min.js', { newLine: ';' }))
        .pipe(gulp.dest('./web/app'));

    gulp.src('./web/app/login.js')
        .pipe(rename('login.min.js'))
        .pipe(uglify({
            compress: true,
            mangle: true
        }))
        .pipe(gulp.dest('./web/app'));
});

gulp.task('style', function() {
    gulp.src('./web/style/style.less')
        .pipe(rename('style.min.css'))
        .pipe(less())
        .pipe(csso())
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

// gulp.task('default', ['build'], function() {

//     // gulp.watch('./app/main.cola', ['script'], function(event) {
//     //     console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
//     // });

//     // gulp.watch('./style/style.less', ['style'], function(event) {
//     //     console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
//     // });

//     // gulp.watch('./index.html', ['html'], function(event){
//     //     console.log('File '+event.path+' was '+event.type+', running tasks...');
//     // });
// });
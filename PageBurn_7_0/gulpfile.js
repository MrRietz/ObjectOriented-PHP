'use strict';

var gulp = require('gulp'),
    mainBowerFiles = require('main-bower-files'),
    less = require('gulp-less'),
    src = './src/main/webapp',
    browserSync = require('browser-sync'),
    browserSyncConfig = {
        proxy: "localhost",
        startPath: "/oophp/kmom07/home.php"
    };
//    views = {
//        src: src + "/WEB-INF/view/**/*",
//        dest: dest + "/WEB-INF/view"
//    };
    

 /* This task builds a stream with all files defined in the main property of the 
  * dependencies’ bower.json and copies it to public/lib folder. Here is what 
  */
gulp.task('mainBowerFiles', function moveBowerDeps() {
  return gulp.src(mainBowerFiles(), { base: 'bower_components' })
      .pipe(gulp.dest('webroot/lib'));
});

gulp.task('bootstrap:prepareLess', ['mainBowerFiles'], function bootstrapPrepareLess() {
  return gulp.src('less/bootstrap/variables.less')
      .pipe(gulp.dest('webroot/lib/bootstrap/less'));
});

gulp.task('bootstrap:compileLess', ['bootstrap:prepareLess'], function bootstrapCompileLess() {
  return gulp.src('webroot/lib/bootstrap/less/pageburn.less')
      .pipe(less())
      .pipe(gulp.dest('webroot/lib/bootstrap/dist/css'))
      .pipe(gulp.dest('../kmom07/css')); //change this if you want the css file to another project. 
});


          
gulp.task('browserSync', function() {
  browserSync(browserSyncConfig);
});

gulp.task('watch', ['bootstrap:compileLess', 'browserSync'], function watch() {
  gulp.watch(['less/bootstrap/variables.less'], ['bootstrap:compileLess']);
//  gulp.watch(config.views.src, ['views']);
});



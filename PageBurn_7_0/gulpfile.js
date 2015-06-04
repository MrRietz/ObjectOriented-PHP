'use strict';

var gulp = require('gulp'), 
    less = require('gulp-less'),  
    changed = require('gulp-changed'),
    mainBowerFiles = require('main-bower-files'),
    browserSync = require('browser-sync'),
    browserSyncConfig = {
        proxy: "localhost",
        startPath: "/oophp/kmom07/home.php"
    },
    views = {
        classesSrcPath: '/src/source/**/*.php',
        projectSrcPath: '../kmom07/source/*.php',
        classesTargetPath: '/src/**/*.php',
        projectTargetPath: '../kmom07/*.php'
    };
    

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

gulp.task('classes', function()  {
  return gulp.src(views.classesTargetPath)
        .pipe(changed(views.classesTargetPath)) // Ignore unchanged files
        .pipe(gulp.dest(views.classesTargetPath))
        .pipe(browserSync.reload({stream:true}));
});

gulp.task('project', function()  {
  return gulp.src(views.projectTargetPath)
        .pipe(changed(views.projectTargetPath)) // Ignore unchanged files
        .pipe(gulp.dest(views.projectTargetPath))
        .pipe(browserSync.reload({stream:true}));
});
          
gulp.task('browserSync', function() {
  browserSync(browserSyncConfig);
});

gulp.task('watch', ['bootstrap:compileLess', 'browserSync'], function watch() {
  gulp.watch(['less/bootstrap/variables.less'], ['bootstrap:compileLess']);
    console.log("Starting watch");
  gulp.watch(views.classesSrcPath, ['classes']);
  gulp.watch(views.projectSrcPath, ['project']);
  browserSync.reload({stream:true});
  console.log("Done");
});



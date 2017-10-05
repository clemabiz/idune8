var gulp       = require('gulp'),
  sass       = require('gulp-sass'),
  rename     = require('gulp-rename'),
  minify     = require('gulp-minify-css'),
  prefixer   = require('gulp-autoprefixer'),
  sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function () {
  gulp.src('../styles/scss/source.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(prefixer())
    //.pipe(minify())
    .pipe(rename('compile.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('../styles'));
});

gulp.task('editor-style', function () {
  gulp.src('../styles/ckeditor/ckeditor.styles.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(prefixer())
    //.pipe(minify())
    .pipe(rename('ckeditor.styles.css'))
    .pipe(gulp.dest('../'));
});

gulp.task('default', ['sass', 'editor-style'], function () {
  gulp.watch('../styles/scss/**/*.scss', ['sass', 'editor-style']);
  gulp.watch('../styles/ckeditor/**/*.scss', ['editor-style']);
});

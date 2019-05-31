// Load plugins
const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const minifycss = require('gulp-minify-css');
const rename = require('gulp-rename');
const notify = require('gulp-notify');
const imagemin = require('gulp-imagemin');
const concat = require('gulp-concat');
const cache = require('gulp-cache');


// Styles
gulp.task('styles', () => gulp.src('scss/style.scss')
  .pipe(sass({
    style: 'expanded',
  }))
  .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
  .pipe(gulp.dest('./'))
  .pipe(rename({ suffix: '.min' }))
  .pipe(minifycss())
  .pipe(gulp.dest('./'))
  .pipe(notify({ message: 'Styles task complete' })));


// Images
gulp.task('images', () => gulp.src('images/original/**/*')
  .pipe(cache(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true })))
  .pipe(gulp.dest('images/'))
  .pipe(notify({ message: 'Images task complete' })));


gulp.task('scripts', function() {
  return gulp.src('./js/src/**/*.js')
    // Minify the file
    .pipe(concat('all.js'))
    //.pipe(uglify())
    // Output
    .pipe(gulp.dest('./js'))
});


// Default task
//gulp.task('default', ['styles', 'images', 'watch']);

// Watch
gulp.task('watch', () => {
  // Watch .scss files
  gulp.watch('scss/**/*', gulp.series('styles'));
  // Watch image files
  gulp.watch('images/**/*', gulp.series('images'));

  gulp.watch('js/src/**/*', gulp.series('scripts'));

});


// / Load plugins
// ar gulp = require('gulp'),
//   sass = require('gulp-ruby-sass'),
//   autoprefixer = require('gulp-autoprefixer'),
//   minifycss = require('gulp-minify-css'),
//   rename = require('gulp-rename'),
//   notify = require('gulp-notify'),
//   imagemin = require('gulp-imagemin'),
//   cache = require('gulp-cache'),
//   livereload = require('gulp-livereload'),
//   lr = require('tiny-lr'),
//   server = lr();

// / Styles
// ulp.task('styles', function() {
// return gulp.src('scss/style.scss')
//   .pipe(sass({
//       style: 'expanded',
//       compass: true,
//       require: ['susy']
//   }))
//   .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
//   .pipe(gulp.dest(''))
//   .pipe(rename({ suffix: '.min' }))
//   .pipe(minifycss())
//   .pipe(gulp.dest(''))
//   .pipe(livereload(server))
//   .pipe(notify({ message: 'Styles task complete' }));
// );


// / Images
// ulp.task('images', function() {
// return gulp.src('images/original/**/*')
//   .pipe(cache(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true })))
//   .pipe(gulp.dest('images/'))
//   .pipe(livereload(server))
//   .pipe(notify({ message: 'Images task complete' }));
// );


// / Default task
// ulp.task('default', ['styles', 'images', 'watch']);

// / Watch
// ulp.task('watch', function() {

//   // Listen on port 35729
//   server.listen(35729, function (err) {
//       if (err) {
//       return console.log(err);
//   }

//   // Watch .scss files
//   gulp.watch('scss/*.scss', ['styles']);

//   // Watch image files
//   gulp.watch('images/**/*', ['images']);

//   });

// );

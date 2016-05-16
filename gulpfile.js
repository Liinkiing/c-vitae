var gulp = require('gulp'),
	gp_concat = require('gulp-concat'),
	gp_rename = require('gulp-rename'),
	gp_uglify = require('gulp-uglify'),
	gp_uglify_css = require('gulp-uglifycss'),
	js_dest = "web/js/dist",
	css_dest = "web/style/dist";


gulp.task('js-bundle', function(){
	return gulp.src(['web/js/jquery.js', 'web/js/semantic.js'])
		.pipe(gp_concat('app.js'))
		.pipe(gulp.dest(js_dest))
		.pipe(gp_rename('app.min.js'))
		.pipe(gp_uglify())
		.pipe(gulp.dest(js_dest));
});

gulp.task('css-bundle', function(){
	return gulp.src(['web/style/font-awesome.css', 'web/style/semantic.css', 'web/style/app.css'])
		.pipe(gp_concat('app.css'))
		.pipe(gulp.dest(css_dest))
		.pipe(gp_rename('app.min.css'))
		.pipe(gp_uglify_css())
		.pipe(gulp.dest(css_dest));
});

gulp.task('default', ['js-bundle', 'css-bundle'], function(){});
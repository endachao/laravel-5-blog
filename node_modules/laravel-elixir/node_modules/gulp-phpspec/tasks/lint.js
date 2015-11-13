gulp.task('lint', function() {
	return gulp.src(scripts)
		.pipe(jshint())
		.pipe(logger({ after: chalk.green(config.messages.lintSuccess)}))
		.pipe(jshint.reporter('jshint-stylish'));
});

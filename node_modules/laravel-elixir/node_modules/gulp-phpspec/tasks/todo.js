gulp.task('todo', function() {
	return gulp.src(scripts)
		.pipe(logger({after: chalk.blue(config.messages.todoSuccess)}))
		.pipe(todo())
		.pipe(gulp.dest('./'));
});

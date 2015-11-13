gulp.task('test', function () {
	return gulp.src('index.js')
		.pipe(shell(['npm test'], {
			ignoreErrors: false
		}))
		.on('error', notify.onError({
			title: "Testing Failed",
			message: "Error(s) occurred during testing..."
		}));
});

# vinyl-paths [![Build Status](https://travis-ci.org/sindresorhus/vinyl-paths.svg?branch=master)](https://travis-ci.org/sindresorhus/vinyl-paths)

> Get the file paths in a [vinyl](https://github.com/wearefractal/vinyl) stream

Useful when you need to use the file paths from a gulp pipeline in vanilla node module.


## Install

```sh
$ npm install --save vinyl-paths
```


## Usage

```js
// gulpfile.js
var gulp = require('gulp');
var stripDebug = require('gulp-strip-debug');
var del = require('del');
var vinylPaths = require('vinyl-paths');

gulp.task('delete', function () {
	return gulp.src('app/*')
		.pipe(stripDebug())
		.pipe(vinylPaths(del));
});

// or if you need to use the paths after the pipeline
gulp.task('delete2', function (cb) {
	var vp = vinylPaths();

	gulp.src('app/*')
		.pipe(vp)
		.pipe(gulp.dest('dist'))
		.on('end', function () {
			del(vp.paths, cb);
		});
});
```

*You should only use vanilla node module like this if you're already using other plugins in the pipeline, otherwise just use the module directly as `gulp.src` is costly.*


## API

### vinylPaths([callback])

The optionally supplied callback will get a file path for every file and is expected to call the callback when done. An array of the file paths so far is available as a `paths` property on the stream.

#### callback(path, cb)


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)

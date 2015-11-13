# gulp-phpspec
> PHPSpec plugin for gulp 3

## Usage

First, install `gulp-phpspec` as a development dependency:

```shell
npm install --save-dev gulp-phpspec
```

Then, add it to your `gulpfile.js`:

```javascript
var phpspec = require('gulp-phpspec');

// option 1: default format
gulp.task('phpspec', function() {
	gulp.src('phpsec.yml').pipe(phpspec());
});

// option 2: with defined bin and options
gulp.task('phpspec', function() {
	var options = {debug: false};
	gulp.src('phpspec.yml').pipe(phpspec('./vendor/bin/phpspec run',options));
});

// option 3: supply callback to integrate something like notification (using gulp-notify)

var gulp = require('gulp'),
 notify  = require('gulp-notify'),
 phpspec = require('gulp-phpspec'),
 _       = require('lodash');

  gulp.task('phpspec', function() {
    gulp.src('phpspec.yml')
      .pipe(phpspec('', {notify: true}))
      .on('error', notify.onError(testNotification('fail', 'phpspec')))
      .pipe(notify(testNotification('pass', 'phpspec')));
  });

function testNotification(status, pluginName, override) {
	var options = {
		title:   ( status == 'pass' ) ? 'Tests Passed' : 'Tests Failed',
		message: ( status == 'pass' ) ? '\n\nAll tests have passed!\n\n' : '\n\nOne or more tests failed...\n\n',
		icon:    __dirname + '/node_modules/gulp-' + pluginName +'/assets/test-' + status + '.png'
	};
	options = _.merge(options, override);
  return options;
}

```

### Sample Gulpfile

If you want a quick and dirty gulpfile, here is one I created for testing this plugin

[Gist: https://gist.github.com/mikeerickson/9163621](https://gist.github.com/mikeerickson/9163621)


## API

### (phpspecpath,options,cb)

#### phpspecpath

Type: `String`

The path to the desired PHPSpec binary
- If not supplied, the defeault path will be ./vendor/bin/phpspec

#### options.debug
Type: `Boolean (Default: false)`

Emit error details and shows command used in console

#### options.clear
Type: `Boolean (Default: false)`

Clear console before executing command


#### options.notify
Type: `Boolean (Default: false)`

Call user supplied callback to handle notification (use gulp-notify)

#### options.verbose
Type: `String (Default null)`

Adjust the default verbosity of messages
v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

#### options.noInteraction
Type: `Boolean (Default: false)`
*Note: changed from noInteract -* __0.3.0__

Do not ask any interactive question (disables code generation).

#### options.noAnsi
Type: `Boolean (Default: false)`

Disable ANSI output (ommiting parameter or setting to false will display ansi colors output)

#### options.quiet
Type: `Boolean (Default: false)`

Do not output any message.

#### options.formatter
Type: `String`

Display PHPSpec custom formatters (ie pretty)


## Credits

gulp-phpspec written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Webiste: [codedungeon.org](http://codedungeon.org)

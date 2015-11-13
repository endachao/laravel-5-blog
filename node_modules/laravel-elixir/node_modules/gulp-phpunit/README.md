# gulp-phpunit
> PHPUnit plugin for gulp 3

## Usage

First, install `gulp-phpunit` as a development dependency:

```shell
npm install --save-dev gulp-phpunit
```

Then, add it to your `gulpfile.js`:

```javascript
var phpunit = require('gulp-phpunit');

// option 1: default format
gulp.task('phpunit', function() {
	gulp.src('phpunit.xml').pipe(phpunit());
});

// option 2: with defined bin and options
gulp.task('phpunit', function() {
	var options = {debug: false};
	gulp.src('phpunit.xml').pipe(phpunit('./vendor/bin/phpunit',options));
});


// Note: Windows OS may require double backslashes if using other than default location (option 1)
gulp.task('phpunit', function() {
  gulp.src('phpunit.xml').pipe(phpunit('.\\path\\to\\phpunit'));
});

// option 3: supply callback to integrate something like notification (using gulp-notify)

var gulp = require('gulp'),
 notify  = require('gulp-notify'),
 phpunit = require('gulp-phpunit'),
 _       = require('lodash');

  gulp.task('phpunit', function() {
    gulp.src('phpunit.xml')
      .pipe(phpunit('', {notify: true}))
      .on('error', notify.onError(testNotification('fail', 'phpunit')))
      .pipe(notify(testNotification('pass', 'phpunit')));
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

## API

### phpunit(phpunitpath,options)

#### phpunitpath

Type: `String`

The path to the desired PHPUnit binary
- If not supplied, the defeault path will be ./vendor/bin/phpunit

#### options.debug
Type: `Boolean`

Debug mode enabled (enables --debug switch as well)

#### options.clear
Type: `Boolean`

Clear console before executing command

#### options.dryRun
Type: `Boolean`

Executes dry run (doesn't actually execute tests, just echo command that would be executed)

#### options.testClass
Type: `String`

Define a specific class for testing (supply full path to test class)

#### options.testSuite
Type: `String`

Define a specific test suite for testing (supply full path to test suite)

#### options.configurationFile
Type: `String`

Define a path to an xml configuration file (supply full path and filename)

#### options.notify
Type: `Boolean`

## Code Coverage Options:

Call user supplied callback to handle notification

#### options.coverageClover
Type: `String`

Generate code coverage report in Clover XML format.

#### options.coverageCrap4j
Type: `String`

Generate code coverage report in Crap4J XML format.

#### options.coverageHtml
Type: `String`

Generate code coverage report in HTML format.

#### options.coveragePhp
Type: `String`

Export PHP_CodeCoverage object to file.

#### options.coverageText
Type: `String`

Generate code coverage report in text format.
-- Default: Standard output.

#### options.coverageXml
Type: `String`

Generate code coverage report in PHPUnit XML format.


## Logging Options:

#### options.logJunit
Type: `String`

Log test execution in JUnit XML format to file.

#### options.logTap
Type: `String`

Log test execution in TAP format to file.

#### options.logJson
Type: `String`

Log test execution in JSON format.

#### options.testdoxHtml
Type: `String`

Write agile documentation in HTML format to file.

#### options.testdoxText
Type: `String`

Write agile documentation in Text format to file.

## Test Selection Options:

#### options.filter (pattern)
Type: `String`

Filter which tests to run.

#### options.testSuite (pattern)
Type: `String`

Filter which testsuite to run.

#### options.group (pattern)
Type: `String`

Only runs tests from the specified group(s).

#### options.excludeGroup
Type: `String`

Exclude tests from the specified group(s).

#### options.listGroups
Type: `String`

List available test groups.

#### options.testSuffix
Type: `String`

Only search for test in files with specified suffix(es). Default: Test.php,.phpt

## Test Execution Options:

#### options.reportUselessTests
Type: `String`

Be strict about tests that do not test anything.

#### options.strictCoverage (default: false)
Type: `Boolean`

Be strict about unintentionally covered code.

#### options.disallowTestOutput (default: false)
Type: `Boolean`

Be strict about output during tests.

#### options.enforceTimeLimit (default: false)
Type: `Boolean`

Enforce time limit based on test size.

#### options.disallowTodoTests (default: false)
Type: `Boolean`

Disallow @todo-annotated tests.

#### options.strict (default: false)
Type: `Boolean`

Run tests in strict mode (enables all of the above).

#### options.processIsolation (default: false)
Type: `Boolean`

Run each test in a separate PHP process.

#### options.noGlobalsBackup (default: false)
Type: `Boolean`

Do not backup and restore $GLOBALS for each test.

#### options.staticBackup (default: false)
Type: `Boolean`

Backup and restore static attributes for each test.

#### options.colors (default: true)
Type: `Boolean`

Use colors in output.

#### options.stderr (default: false)
Type: `Boolean`

Write to STDERR instead of STDOUT.

#### options.stopOnError (default: false)
Type: `Boolean`

Stop execution upon first error.

#### options.stopOnFailure (default: false)
Type: `Boolean`

Stop execution upon first error or failure.

#### options.stopOnRisky (default: false)
Type: `Boolean`

Stop execution upon first risky test.

#### options.stopOnIncomplete (default: false)
Type: `Boolean`

Stop execution upon first incomplete test.

#### options.stopOnSkipped (default: false)
Type: `Boolean`

Stop execution upon first skipped test.

#### options.loader
Type: `String`

TestSuiteLoader implementation to use.

#### options.repeat
Type: `Integer | String`

Runs the test(s) repeatedly.

#### options.tap
Type: `Boolean`

Report test execution progress in TAP format.

#### options.testdox
Type: `Boolean`

Report test execution progress in TestDox format.

#### options.printer
Type: `String`

TestSuiteListener implementation to use.

## Configuration Options

#### options.bootstrap
Type: `String`

A "bootstrap" PHP file that is run before the tests.

#### options.configurationFile
Type: `String`

Read configuration from XML file.

#### options.noConfiguration
Type: `Boolean`

Ignore default configuration file (phpunit.xml).

#### options.includePath
Type: `Boolean`

Prepend PHP's include_path with given path(s).



## Credits

gulp-phpunit written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Website: [codedungeon.org](http://codedungeon.org)

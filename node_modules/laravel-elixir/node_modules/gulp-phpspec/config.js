/* global module */

// APPLICATION CONFIGURATION
// =============================================================================
// global, application wide configuration (./tasks/connfig is for tasks configuration only)

"use strict";

module.exports = {

	defaults: {
		scripts: ['index.js', 'tasks/*.js', 'gulpfile.js','./lib/**/*.js','./test/**/*.js']

	},

	messages: {
		invalidBinary: 'Invalid PHPSpec Binary. The `options` should be passed as second parameter.',
		lintSuccess:   'Linting Completed Successfully...',
		sassSuccess:   'SASS Completed Successfully...',
		todoSuccess:   './TODO.MD File Updated...'
	},

	tests: {
		files: [
				['test/test.js'],
				['lib/phpspec.js','index.js']
		]
	}

};

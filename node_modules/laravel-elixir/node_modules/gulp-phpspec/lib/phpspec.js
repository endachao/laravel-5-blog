/*global module */

'use strict';

var cmd     = '';

var PHPSpec = {

	version: function() {
		var vers = require('./package').version;
		return vers;
	},

	// add all options to the default command
	buildCommand: function(bin, opt) {

		cmd = bin;

		if ( opt.testing !== 'undefined') { opt.dryRun = opt.testing; }

		if (typeof opt.testSuite === 'undefined')     { opt.testSuite = ''; }
		if (typeof opt.verbose === 'undefined')       { opt.verbose = ''; }
		if (typeof opt.dryRun === 'undefined')        { opt.dryRun = false; }
		if (typeof opt.silent === 'undefined')        { opt.silent = false; }
		if (typeof opt.testing === 'undefined')       { opt.testing = false; }
		if (typeof opt.debug === 'undefined')         { opt.debug = false; }
		if (typeof opt.testClass === 'undefined')     { opt.testClass = ''; }
		if (typeof opt.clear === 'undefined')         { opt.clear = false; }
		if (typeof opt.flags === 'undefined')         { opt.flags = ''; }
		if (typeof opt.notify === 'undefined')        { opt.notify = false; }
		if (typeof opt.noInteraction === 'undefined') { opt.noInteraction = true; }
		if (typeof opt.noAnsi === 'undefined')        { opt.noAnsi = false; }
		if (typeof opt.quiet === 'undefined')         { opt.quiet = false; }
		if (typeof opt.formatter === 'undefined')     { opt.formatter = ''; }

		cmd = opt.clear ? 'clear && ' + cmd : cmd;

		// assign default class and/or test suite
		if (opt.testSuite)     { cmd += ' ' + opt.testSuite; }
		if (opt.testClass)     { cmd += ' ' + opt.testClass; }
		if (opt.verbose)       { cmd += ' -' + opt.verbose; }
		if (opt.formatter)     { cmd += ' -f' + opt.formatter; }
		if (opt.quiet)         { cmd += ' --quiet'; }
		if (opt.noInteraction) { cmd += ' --no-interaction'; }

		cmd += opt.noAnsi ? ' --no-ansi' : ' --ansi';

		cmd += ' ' + opt.flags;

		cmd.trim(); // clean up any lingering space remnants

		return cmd;
	}
};

module.exports = PHPSpec;

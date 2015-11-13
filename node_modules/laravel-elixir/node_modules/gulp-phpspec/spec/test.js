/*jshint node:true */
/*jslint node:true */
/*global require */
/*global describe */
/*global it */

'use strict';

var config  = require('../config');
var phpspec = require('../');
var should  = require('should');
var assert  = require('chai').assert;
var expect  = require('chai').expect;

var version = require('../package').version;	// get plugin version


// LOAD TEST LIBRARY
// =============================================================================
// just in case we dont have it installed globally, need to load it here
require('mocha');


// TEST PLUGIN
// =============================================================================

describe('gulp-phpspec', function() {

		it('should not error if no parameters passed', function(done) {

			var caughtErr;

			try {
				phpspec();
			} catch (err) {
				caughtErr = err;
			}

			assert.notOk(caughtErr);
			should.not.exist(caughtErr);

			//caughtErr.message.indexOf('required').should.be.above(-1);
			done();
		});

		it('should throw error if object passed as first parameter', function(done){

			// arrange
			var caughtErr;

			// act
			try {
				phpspec({debug: true});
			} catch (err) {
				caughtErr = err;
			}

			// assert
			should.exist(caughtErr);
			caughtErr.message.should.equal(config.messages.invalidBinary);

			done();

		});

		it('should test dryRun [*** for testing only ***]', function(done){

			var caughtErr;
			var result = '';
			var options = {testing: true};

			try {
				result = phpspec('',options);
			} catch (err) {
				caughtErr = err;
			}

			should.not.exist(caughtErr);
			assert(result);

			done();

		});

		it('should append `run` flag when bin path supplied', function(done){

			var caughtErr;
			var result  = '';
			var options = {testing: true, silent: true, dryRun: true, testClass: 'testClass.php'};

			try {
				result = phpspec('test', options);
			} catch (err) {
				caughtErr = err;
			}

			should.not.exist(caughtErr);
			expect(result).to.contain('test run');

			done();

		});

		it('should return supplied `options` as part of result (where applicable)',function(done){

			var caughtErr;
			var options   = {testing: true, testClass: 'testClass.php', noInteraction: true, noAnsi: true};
			var result    = '';

			try {
				result = phpspec('',options);
			} catch ( err ) {
					caughtErr = err;
			}

			should.not.exist(caughtErr);
			expect(result).to.contain(options.testClass);
			expect(result).to.contain('--no-interaction');
			expect(result).to.contain('--no-ansi');
			done();
	});

		it('should provide default values when options not supplied',function(done){

			var caughtErr;
			var result = '';

			try {
				result = phpspec('',{testing: true});
			} catch ( err ) {
					caughtErr = err;
			}

			should.not.exist(caughtErr);
			expect(result).to.contain('--no-interaction');
			expect(result).to.contain('--ansi');

			done();

		});

		it('should return version information in result',function(done){

			var caughtErr;
			var options = {testing: true};
			var result = '';

			try {
				result = phpspec('', options);
			} catch ( err ) {
					caughtErr = err;
			}

			should.not.exist(caughtErr);
			expect(result).to.contain('--version');
			should.exist(version);

			done();

		});


});

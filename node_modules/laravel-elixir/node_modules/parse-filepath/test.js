/*!
 * parse-filepath <https://github.com/jonschlinkert/parse-filepath>
 *
 * Copyright (c) 2014, Jon Schlinkert, Brian Woodward, contributors.
 * Licensed under the MIT License
 */

'use strict';

var path = require('path');
var should = require('should');
var parsePath = require('./');

function normalize(str) {
  return path.normalize(str);
}


describe('parse-filepath:', function() {
  describe('dotfiles', function() {
    it('should recognize dotfiles', function() {
      parsePath('foo/bar/baz/.dotfile').should.eql({
        name: 'dotfile',
        dirname: normalize('foo/bar/baz'),
        extname: '',
        basename: '.dotfile',
        extSegments: []
      });
    });

    it('should recognize .gitignore', function() {
      parsePath('./.gitignore').should.eql({
        name: 'gitignore',
        dirname: '.',
        extname: '',
        basename: '.gitignore',
        extSegments: []
      });
    });

    it('should recognize config files', function() {
      parsePath('./.verbfile.md').should.eql({
        name: 'verbfile',
        dirname: '.',
        extname: '.md',
        basename: '.verbfile.md',
        extSegments: ['.md']
      });
    });

    it('should recognize config files', function() {
      parsePath('./.travis.yml').should.eql({
        name: 'travis',
        dirname: '.',
        extname: '.yml',
        basename: '.travis.yml',
        extSegments: ['.yml']
      });
    });
  });

  describe('when a single segment is passed', function() {
    it('should return the correct values', function() {
      parsePath('foo').should.eql({
        name: 'foo',
        dirname: '.',
        extname: '',
        basename: 'foo',
        extSegments: []
      });
    });

    it('should return the correct values', function() {
      parsePath('./foo').should.eql({
        name: 'foo',
        dirname: '.',
        extname: '',
        basename: 'foo',
        extSegments: []
      });
    });
  });

  describe('when a filepath is passed', function() {
    it('should return an object of path parts', function() {
      parsePath('foo/bar/baz/index.html').should.eql({
        name: 'index',
        dirname: normalize('foo/bar/baz'),
        extname: '.html',
        basename: 'index.html',
        extSegments: ['.html']
      });
    });
  });

  describe('when a filepath ends with a slash', function() {
    it('dirname should be the full filepath, and basename should be empty', function() {
      parsePath('foo/bar/baz/quux/').should.eql({
        name: '',
        dirname: normalize('foo/bar/baz/quux/'),
        extname: '',
        basename: '',
        extSegments: []
      });
    });
  });

  describe('when a filepath with multiple extensions is passed', function() {
    it('should return an object of path parts', function() {
      parsePath('foo/bar/baz/index.md.html').should.eql({
        name: 'index',
        dirname: normalize('foo/bar/baz'),
        extname: '.md.html',
        basename: 'index.md.html',
        extSegments: ['.md', '.html']
      });
    });
  });

  describe('when a filepath with zero extensions is passed', function() {
    it('should return an object of path parts', function() {
      parsePath('foo/bar/baz/index').should.eql({
        name: 'index',
        dirname: normalize('foo/bar/baz'),
        extname: '',
        basename: 'index',
        extSegments: []
      });
    });
  });

  describe('when a dirname is "."', function() {
    it('should preserve the basename', function() {
      parsePath('index.js').should.eql({
        dirname: '.',
        basename: 'index.js',
        name: 'index',
        extname: '.js',
        extSegments: ['.js'],
      });
    });
  });

  describe('when a filepath with zero extensions is passed', function() {
    it('should return an object of path parts', function() {
      parsePath('foo/bar/baz/index').should.eql({
        name: 'index',
        dirname: normalize('foo/bar/baz'),
        extname: '',
        basename: 'index',
        extSegments: []
      });
    });
  });
});

describe('utils.ext()', function() {
  describe('when a filepath with multiple extensions is passed:', function() {
    it('should return the extension from options.ext', function() {
      parsePath('foo/bar/baz.min.js').extname.should.eql('.min.js');
    });

    it('should return the extension from options.ext', function() {
      parsePath('foo/bar/baz.min.js').extSegments.should.eql(['.min', '.js']);
    });
  });

  describe('when a filepath with a single extension is passed:', function() {
    it('should return the extname', function() {
      parsePath('foo/bar/baz.js').extname.should.eql('.js');
    });

    it('should return an array with the extname', function() {
      parsePath('foo/bar/baz.js').extSegments.should.eql(['.js']);
    });
  });

  describe('when a filepath with no extensions is passed:', function() {
    it('should return an empty extname', function() {
      parsePath('foo/bar/baz').extname.should.eql('');
    });

    it('should return an empty extSegments array', function() {
      parsePath('foo/bar/baz').extSegments.should.eql([]);
    });
  });
});
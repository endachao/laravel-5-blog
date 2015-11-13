'use strict';

module.exports = plugin;

var path = require('path');
var gutil = require('gulp-util');
var through = require('through2');

var utils = require('./utils');

function plugin(options) {
  var renames = [];
  var cache = [];

  options = options || {};

  if (!options.canonicalUris) {
    options.canonicalUris = true;
  }

  options.prefix = options.prefix || '';

  options.replaceInExtensions = options.replaceInExtensions || ['.js', '.css', '.html', '.hbs'];

  return through.obj(function collectRevs(file, enc, cb) {
    if (file.isNull()) {
      this.push(file);
      return cb();
    }

    if (file.isStream()) {
      this.emit('error', new gutil.PluginError('gulp-rev-replace', 'Streaming not supported'));
      return cb();
    }

    // Collect renames from reved files.
    if (file.revOrigPath) {
      renames.push({
        unreved: fmtPath(file.revOrigBase, file.revOrigPath),
        reved: options.prefix + fmtPath(file.base, file.path)
      });
    }

    if (options.replaceInExtensions.indexOf(path.extname(file.path)) > -1) {
      // file should be searched for replaces
      cache.push(file);
    } else {
      // nothing to do with this file
      this.push(file);
    }

    cb();
  }, function replaceInFiles(cb) {
    var stream = this;

    if (options.manifest) {
      // Read manifest file for the list of renames.
      options.manifest.on('data', function (file) {
        var manifest = JSON.parse(file.contents.toString());
        Object.keys(manifest).forEach(function (srcFile) {
          renames.push({
            unreved: canonicalizeUri(srcFile),
            reved: options.prefix + canonicalizeUri(manifest[srcFile])
          });
        });
      });
      options.manifest.on('end', replaceContents);
    }
    else {
      replaceContents();
    }

    function replaceContents() {
      renames = renames.sort(utils.byLongestUnreved);

      // Once we have a full list of renames, search/replace in the cached
      // files and push them through.
      cache.forEach(function replaceInFile(file) {
        var contents = file.contents.toString();

        renames.forEach(function replaceOnce(rename) {
          var unreved = options.modifyUnreved ? options.modifyUnreved(rename.unreved) : rename.unreved;
          var reved = options.modifyReved ? options.modifyReved(rename.reved) : rename.reved;
          contents = contents.split(unreved).join(reved);
          if (options.prefix) {
            contents = contents.split('/' + options.prefix).join(options.prefix + '/');
          }
        });

        file.contents = new Buffer(contents);
        stream.push(file);
      });

      cb();
    }
  });

  function fmtPath(base, filePath) {
    var newPath = path.relative(base, filePath);

    return canonicalizeUri(newPath);
  }

  function canonicalizeUri(filePath) {
    if (path.sep !== '/' && options.canonicalUris) {
      filePath = filePath.split(path.sep).join('/');
    }

    return filePath;
  }
}

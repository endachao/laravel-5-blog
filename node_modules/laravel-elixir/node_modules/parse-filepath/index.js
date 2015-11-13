'use strict';

var path = require('path');
var endsWith = require('path-ends-with');

module.exports = function parse(fp) {
  if (typeof fp !== 'string') {
    throw new Error('parse-filepath expects a string.');
  }

  fp = fp.replace(/\\/g, '/');
  var dirname = path.dirname(fp);
  var basename = path.basename(fp);

  if (endsWith(fp, '/')) {
    dirname = fp;
    basename = '';
  }

  if (dirname !== '.') {
    basename = fp.replace(dirname, '');
  }

  var name = basename.split('.')[0];
  var ext = basename.replace(name, '');

  if (isConfigFile(fp)) {
    basename = path.basename(fp);
    var segs = basename.split('.').filter(Boolean);
    name = segs[0];
    ext = segs[1];
  } else if (isDotfile(fp)) {
    basename = ext;
    name = basename.slice(1);
    ext = '';
  }

  // create an array of extensions. useful
  // if more than one extension exists
  var segments = ext.split('.').filter(Boolean);
  if (ext && ext[0] !== '.') {
    ext = '.' + ext;
  }

  var parts = {
    dirname: path.normalize(dirname),
    basename: strip(basename),
    name: strip(name),
    extname: ext,
    extSegments: segments.map(function(ext) {
      if (ext && ext[0] !== '.') {
        ext = '.' + ext;
      }
      return ext;
    })
  };
  return parts;
};

/**
 * Strip leading and trailing slashes
 */

function strip(str) {
  return str.replace(/^[\/]+|[\/]+$/g, '');
}

/**
 * Very naive guess at whether or not the file
 * is a dotfile. It may be a directory as well.
 */

function isDotfile(fp) {
  return /^\./.test(path.basename(fp));
}

function isConfigFile(fp) {
  if(isDotfile(fp)) {
    fp = path.basename(fp);
    fp = fp.slice(1);
    return /\./.test(fp);
  }
  return false;
}
var through = require('through'),
  str2js = require('string-to-js'),
  types = ['html', 'css'];

function isValidFile (file, opts) {
  var validTypes = types;
  if (opts && opts.onlyAllow) validTypes = opts.onlyAllow;
  if (opts && opts.alsoAllow) validTypes = validTypes.concat(opts.alsoAllow);
  if (!Array.isArray(validTypes)) validTypes = [validTypes];

  return validTypes.some(function (type) {
    return file.substr(-(type.length)) === type;
  });
}

function partialify (file, opts) {

  if (!isValidFile(file, opts)) return through();

  var buffer = "";

  return through(function (chunk) {
      buffer += chunk.toString();
    },
    function () {
      if (buffer.indexOf('module.exports') === 0) {
        this.queue(buffer); // prevent "double" transforms
      } else {
        this.queue(str2js(buffer));
      }
      this.queue(null);
    });

};

exports.onlyAllow = function (extensions) {
  if (extensions) {
    if (!Array.isArray(extensions)) extensions = Array.prototype.slice.call(arguments, 0);

    types = extensions;
  }
  return partialify;
}

exports.alsoAllow = function (extensions) {
  if (!Array.isArray(extensions)) extensions = Array.prototype.slice.call(arguments, 0);
  types = types.concat(extensions);
  return partialify;
}

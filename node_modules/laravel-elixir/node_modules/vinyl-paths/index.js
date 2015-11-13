'use strict';
var through = require('through2');

module.exports = function (userCb) {
	var stream = through.obj(function (file, enc, cb) {
		this.paths.push(file.path);

		if (userCb) {
			userCb(file.path, function () {
				cb(null, file);
			});
		} else {
			cb(null, file);
		}
	});

	stream.paths = [];

	return stream;
};

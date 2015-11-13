var partialify = require('../');
var customPartialify = require('../custom');

var test = require('tape');
var browserify = require('browserify');

var vm = require('vm');
var fs = require('fs');
var path = require('path');

var html = fs.readFileSync(__dirname + '/fixtures/fixture.html', 'utf8');
var css = fs.readFileSync(__dirname + '/fixtures/fixture.css', 'utf8');
var json = fs.readFileSync(__dirname + '/fixtures/fixture.json', 'utf8');
var xml = fs.readFileSync(__dirname + '/fixtures/fixture.xml', 'utf8');
var csv = fs.readFileSync(__dirname + '/fixtures/fixture.csv', 'utf8');
var tplhtml = fs.readFileSync(__dirname + '/fixtures/fixture.tpl.html', 'utf8');

test('require() an HTML file', function (t) {
  t.plan(1);

  var b = browserify();
  b.add(__dirname + '/runners/html.js');
  b.transform(partialify);

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { console: { log: log } });
  });

  function log (msg) {
    t.equal(msg, html);
  }

});

test('require() a CSS file', function (t) {
  t.plan(1);

  var b = browserify();
  b.add(__dirname + '/runners/css.js');
  b.transform(partialify);

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { console: { log: log } });
  });

  function log (msg) {
    t.equal(msg, css);
  }

});

test('Default behavior accepts HTML and CSS', function (t) {
  t.plan(2);

  var output = {};

  var b = browserify();
  b.add(__dirname + '/runners/defaults.js');
  b.transform(partialify);

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { output: output, finish: finish });
  });

  function finish () {
    t.equal(output.html, html);
    t.equal(output.css, css);
  }

});

test('Support for extra file types can be added', function (t) {
  t.plan(4);

  var output = {};

  var b = browserify();
  b.add(__dirname + '/runners/extras.js');
  b.transform(customPartialify.alsoAllow('json', 'xml'));

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { output: output, finish: finish });
  });

  function finish () {
    t.equal(output.html, html);
    t.equal(output.css, css);
    t.equal(output.json, json);
    t.equal(output.xml, xml);
  }

});

test('Support for extra file types can be added via options', function (t) {
  t.plan(2);

  var output = {};

  var b = browserify();
  b.add(__dirname + '/runners/opts.js');
  b.transform({ alsoAllow: ['xml'] }, partialify);

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { output: output, finish: finish });
  });

  function finish () {
    t.equal(output.html, html);
    t.equal(output.xml, xml);
  }

});

test('Supported file types list can be completely custom', function (t) {
  t.plan(2);

  var output = {};

  var b = browserify();
  b.add(__dirname + '/runners/unique.js');
  b.transform(customPartialify.onlyAllow(['xml', 'csv']));

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { output: output, finish: finish });
  });

  function finish () {
    t.equal(output.xml, xml);
    t.equal(output.csv, csv);
  }

});

test('Supported file types list can be completely customized via options', function (t) {
  t.plan(2);

  var output = {};

  var b = browserify();
  b.add(__dirname + '/runners/unique.js');
  b.transform(partialify, {onlyAllow: ['xml', 'csv']});

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { output: output, finish: finish });
  });

  function finish () {
    t.equal(output.xml, xml);
    t.equal(output.csv, csv);
  }

});

test('Compound file extensions are supported', function (t) {
  t.plan(1);

  var b = browserify();
  b.add(__dirname + '/runners/tpl.html.js');
  b.transform(customPartialify.onlyAllow(['tpl.html']));

  b.bundle(function (err, src) {
    if (err) t.fail(err);
    vm.runInNewContext(src, { console: { log: log } });
  });

  function log (msg) {
    t.equal(msg, tplhtml);
  }

});

partialify
==========

require()-able HTML, CSS and (potentially) more

Supports HTML and CSS out of the box, enabling code like this.
```js
var html = require('./some.html'),
	css = require('./some.css');
```

To use, specify as a Browserify transform in your `package.json` or programmatically like so:
```js
var b = require('browserify')(),
	fs = require('fs'),
	p = require('partialify');

b.add('./entry.js');
b.transform(p);
b.bundle().pipe(fs.createWriteStream('./bundle.js'));
```

To support other file types use the custom version. You can either augment the default supported file types or specify a completely custom list.

```js
var b = require('browserify')(),
	fs = require('fs'),
	p = require('partialify/custom');

b.add('./entry.js');

b.transform(p.alsoAllow('xml'));
// or
b.transform(p.alsoAllow(['xml', 'csv']));
// or
b.transform(p.onlyAllow(['xml', 'csv']));

b.bundle().pipe(fs.createWriteStream('./bundle.js'));
```

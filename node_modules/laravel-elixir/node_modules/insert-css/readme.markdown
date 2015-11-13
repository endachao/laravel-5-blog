# insert-css

insert a string of css into the `<head>`

[![browser support](https://ci.testling.com/substack/insert-css.png)](https://ci.testling.com/substack/insert-css)

# example

suppose we've got some css:

``` css
body {
    background-color: purple;
    color: yellow;
}
```

and we want to bundle that css into a js file so that we can write an entirely
self-contained module:

``` js
var fs = require('fs');
var insertCss = require('insert-css');
var css = fs.readFileSync(__dirname + '/style.css');
insertCss(css);
document.body.appendChild(document.createTextNode('HELLO CRUEL WORLD'));
```

optionally prepend the css to the head with the `prepend` option:

``` js
insertCss(css, { prepend: true });
```

compile with [browserify](http://browserify.org) using
[brfs](https://github.com/substack/brfs) to inline the `fs.readFile()`
call:

```
$ browserify -t brfs insert.js > bundle.js
```

Now plop that bundle.js into a script tag and you'll have a self-contained js
blob with inline css!

``` html
<html>
  <head></head>
  <body>
    <script src="bundle.js"></script>
  </body>
</html>
```

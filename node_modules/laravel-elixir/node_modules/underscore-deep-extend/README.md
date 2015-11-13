# _.deepExtend 

A deep extend implementation for [underscore](http://underscorejs.org/), [lodash](http://lodash.com/) and their (AFAIK non-existent) friends.

Based conceptually on the [_.extend()](http://underscorejs.org/#extend) function in underscore.js.

Copyright (C) 2012  Kurt Milam - http://xioup.com 

Licensed under the GPL V3+.

Original source: https://gist.github.com/1868955

## Installation:

    npm install underscore-deep-extend

## Dependency: 

One of 

- [underscore.js](http://underscorejs.org/)
- [lodash.js](http://lodash.com/)
- another clone that provides `_.each` and `_.is(Array|Date|Null|Object|String|Undefined)`.

## Usage:

Load it, either as is (in the browser), as an AMD module, or as a CommonJS/Node.js module), then mix it in with the parent library (which must be explicitly injected):

    _.mixin({deepExtend: underscoreDeepExtend(_)});
    
Call it like this:

    var myObj = _.deepExtend(grandparent, child, grandchild, greatgrandchild)

## Notes:

### Keep it DRY.

This function is especially useful if you're working with JSON config documents. It allows you to create a default
config document with the most common settings, then override those settings for specific cases. It accepts any
number of objects as arguments, giving you fine-grained control over your config document hierarchy.

### Special Features and Considerations:

- parentRE allows you to concatenate strings. example:

  ``` Javascript
  var obj = _.deepExtend({url: "www.example.com"}, {url: "http://#{_}/path/to/file.html"});
      
  console.log(obj.url);
  ```
  
  output: `http://www.example.com/path/to/file.html`

- parentRE also acts as a placeholder, which can be useful when you need to change one value in an array, while
  leaving the others untouched. example:

  ``` Javascript
  var arr = _.deepExtend([100,    {id: 1234}, true,  "foo",  [250, 500]],
                         ["#{_}", "#{_}",     false, "#{_}", "#{_}"]);
  console.log(arr);
  ```

  output: `[100, {id: 1234}, false, "foo", [250, 500]]`

- The previous example can also be written like this:

  ``` Javascript
  var arr = _.deepExtend([100,    {id:1234},   true,  "foo",  [250, 500]],
                        ["#{_}", {},          false, "#{_}", []]);
  console.log(arr);
  ```
  output: `[100, {id: 1234}, false, "foo", [250, 500]]`

- And also like this:

  ``` Javascript
  var arr = _.deepExtend([100,    {id:1234},   true,  "foo",  [250, 500]],
                         ["#{_}", {},          false]);
  console.log(arr);
  ```

  output: `[100, {id: 1234}, false, "foo", [250, 500]]`

- Array order is important. example:

  ``` Javascript
  var arr = _.deepExtend([1, 2, 3, 4], [1, 4, 3, 2]);
  console.log(arr);
  ```
  
  output: `[1, 4, 3, 2]`


- You can remove an array element set in a parent object by setting the same index value to null in a child object. Example:

  ``` Javascript
  var obj = _.deepExtend({arr: [1, 2, 3, 4]}, {arr: ["#{_}", null]});
  console.log(obj.arr);
  ```
  
  output: `[1, 3, 4]`

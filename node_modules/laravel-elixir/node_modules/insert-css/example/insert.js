var fs = require('fs');
var insertCss = require('../');
var css = fs.readFileSync(__dirname + '/style.css');
insertCss(css);
document.body.appendChild(document.createTextNode('HELLO CRUEL WORLD'));

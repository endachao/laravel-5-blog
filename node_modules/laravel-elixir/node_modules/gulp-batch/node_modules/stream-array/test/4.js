var test = require('tape')
    , streamify = require('..')
    , concat = require('concat-stream')
;

test('immutable', function(t) {
    var s, a = [1, 2, 3, 4, 5];

    if (Object && 'function' === typeof(Object.observe)) {
        // will produce ugly error, but also doesn't
        // cause `covert` to detect an untested line
        Object.observe(a, t.fail);
    }

    s = streamify(a);

    s.pipe(concat({encoding: 'object'}, function(res) {
        t.equal(1, arguments.length, 'concat returns 1 arg');
        t.deepEqual(a, res, 'result array matches input');
        t.end();
    }));
});

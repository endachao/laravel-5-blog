var Readable = require('readable-stream').Readable
;

function StreamArray(list) {
    if (!Array.isArray(list))
        throw new TypeError('First argument must be an Array');

    Readable.call(this, {objectMode:true});

    this._i = 0;
    this._l = list.length;
    this._list = list;
}

StreamArray.prototype = Object.create(Readable.prototype, {constructor: {value: StreamArray}});

StreamArray.prototype._read = function(size) {
    this.push(this._i < this._l ? this._list[this._i++] : null);
};

module.exports = function(list) {
    return new StreamArray(list);
};

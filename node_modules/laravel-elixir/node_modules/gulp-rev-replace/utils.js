'use strict';

function byLongestUnreved(a, b) {
  return b.unreved.length - a.unreved.length;
}

module.exports = {
  byLongestUnreved: byLongestUnreved
};

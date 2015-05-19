/**
 * temp.js
 * 一个简单的模板填充工具，使用twig的语法
 * @param {string}template 模板字符串
 * @param {json}data 填充的数据
 * @return {string} 返回拼接后的字符串
 * @author integ@segmentfault.com
 **/

define(['jquery'], function($){
    'use strict';
    return function(template, data) {
        var str = template || '';
        // Convert the template into string
        $.each(data, function(key, val){
            var _type = typeof val,
                re = new RegExp('{{\\s*' + key + '\\s*}}', 'g');
            if (_type === 'object' && val !== null){
                $.each(val, function(k, v){
                    var r = new RegExp('{{\\s*' + key + '.' + k + '\\s*}}', 'g');
                    str = str.replace(r, v);
                });
            } else {
                str = str.replace(re, val);
            }
        });
        return str;
    };
});

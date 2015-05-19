/**
 * highlight.js
 * @Author integ@segmentfault.com
 * $wrap为空时对所有pre标签执行高亮
 **/

//define(['jquery', 'highlightjs', 'ZeroClipboard', 'math'],
//function($, hljs, ZeroClipboard, math){
$(function() {
    'use strict';

    window.highLight = function($wrap) {
        //highlight
        // detect highlight.js
        if ('undefined' === typeof(hljs)) {
            return;
        }
        // 自动载入highlight
        var hlNames = {
            actionscript : /^as[1-3]$/i,
            cmake : /^(make|makefile)$/i,
            cs : /^csharp$/i,
            css : /^css[1-3]$/i,
            delphi : /^pascal$/i,
            javascript : /^js$/i,
            markdown : /^md$/i,
            objectivec : /^(oc|objective-c)$/i,
            php  : /^php[1-6]$/i,
            sql : /^mysql$/i,
            xml : /^(html|html5|xhtml)$/i
        };
        var hlLangs = hljs.listLanguages();

        function myHighLight($this) {
            var t = $this, children = t.children(), highlighted = false;

            if (children.length > 0 && 'code' === children.get(0).nodeName.toLowerCase()) {
                var className = children.attr('class');

                if (!!className) {
                    var classes = className.split(/\s+/);
                    for (var i = 0; i < classes.length; i ++) {
                        if (0 === classes[i].indexOf('lang-')) {
                            var lang = classes[i].substring(5).toLowerCase(), finalLang;

                            if (hlLangs[lang]) {
                                finalLang = lang;
                            } else {
                                for (var l in hlNames) {
                                    if (lang.match(hlNames[l])) {
                                        finalLang = l;
                                    }
                                }
                            }

                            if (!!finalLang) {
                                var result = hljs.highlight(finalLang, children.text(), true);
                                children.html(result.value);
                                highlighted = true;
                                break;
                            }
                        }
                    }
                }
            }

            if (!highlighted) {
                var html = t.html();
                t.html(html.replace(/<\/?[a-z]+[^>]*>/ig, ''));
                hljs.highlightBlock($this[0], '', false);
            }

            //添加行号
            t.wrap('<table class="widget-highlight"><tbody><tr><td class="widget-highlight--code"></td></tr></tbody></table>');    //包上table
            var _$wrap = t.parents('.widget-highlight tr');     // tr
            _$wrap.prepend('<td class="widget-highlight--line"></td>'); //插入行号的td
            var _totalLine = t.height() / 15;
            var _$lines = _$wrap.find('.widget-highlight--line');
            // var _$code = _$wrap.find('.widget-highlight--code');
            var _width = t.parents('.widget-highlight').parent().width();
            if(_totalLine < 10) {
                _$lines.css('width', '16px');
                // _$code.css('width', _width - 16 + 'px');
                t.css('width', _width - 32 + 'px');
            } else if(_totalLine < 100) {
                _$lines.css('width', '32px');
                // _$code.css('width', _width - 32 + 'px');
                t.css('width', _width - 40 + 'px');
            } else {
                _$lines.css('width', '48px');
                // _$code.css('width', _width - 48 + 'px');
                t.css('width', _width - 51 + 'px');
            }
            // 黑魔法 dangerous
            if(_totalLine < 17) {
                _totalLine = t.height() / 15;
            } else if(_totalLine < 27) {
                _totalLine = (t.height() - 10) / 15;
            } else if(_totalLine < 55) {
                _totalLine = (t.height() - 20) / 15;
            } else if(_totalLine < 55) {
                _totalLine = (t.height() - 40) / 15;
            } else {
                _totalLine = (t.height() + 15) / 16;
            }
            for(var line = 1; line < _totalLine; line++) {
                var _h = '<p>' + line + '</p>';
                _$lines.append(_h);
            }
            
            _$lines.attr('unselectable', 'on')      //禁止选中行号
                .css('user-select', 'none')
                .on('selectstart', false);

            // 添加copy
            // var _$parent = _$wrap.parents('.widget-highlight');
            if(navigator.plugins['Shockwave Flash'] || new ActiveXObject('ShockwaveFlash.shockwaveFlash')) {
                var _$clip = $('<span class="widget-clipboard hidden"></span>');
                t.prepend(_$clip);
                ZeroClipboard.config({ swfPath: '//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.1.6/ZeroClipboard.swf' });
                var _client = new ZeroClipboard(_$clip);
                var _e = $('#global-zeroclipboard-html-bridge');
                _client.on('load', function() {
                    _e.data('placement', 'top').attr('title', 'Copy to clipboard').tooltip();
                });
                _client.on('copy', function (event) {
                    var clipboard = event.clipboardData;
                    clipboard.setData('text/plain', t.text());
                    clipboard.setData('text/html', t.html());
                    clipboard.setData('application/rtf', t.html());
                });
                _client.on('error', function() {
                    _e.attr('title', 'Flash required').tooltip('fixTitle').tooltip('show');
                });
                _client.on('aftercopy', function() {
                    _e.attr('title', 'Copied!').tooltip('fixTitle')
                        .tooltip('show').attr('title', 'Copy to clipboard').tooltip('fixTitle');
                });
                t.hover(function() {
                    _$clip.removeClass('hidden');
                });
            }
        }

        // mathJax
        var _hasMath = null;
        if(!$wrap) {
            _hasMath = $('.fmt').text().match(/\$\$/);
            if(_hasMath && _hasMath.length) {
                math();
            }
            $('pre').each(function() {
                if (!this.parentNode || $(this).parents('.CodeMirror-scroll').length) {     //codemirror不参加
                    return;
                } else {
                    myHighLight($(this));
                }
            });
        } else {
            _hasMath = $wrap.text().match(/\$\$/);
            if(_hasMath && _hasMath.length) {
                math($wrap);
            }
            $wrap.find('pre').each(function() {
                myHighLight($(this));
            });
        }
    };
});

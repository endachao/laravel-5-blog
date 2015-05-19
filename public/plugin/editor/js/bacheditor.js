/**
 * editor.js
 * 一个复杂的编辑器，支持markdown的语法，使用codemirror做渲染
 * 从lepture的Editor修改而来。http://lab.lepture.com/editor/
 *
 * 用法：
 * var myEditor = new Editor({toolbar: false, status: true});
 * myEditor.rendor('#myEditor');
 *
 * @author integ@segmentfault.com
 **/

$(function() {
    'use strict';


    Editor.prototype.uploadPath = '';
    Editor.prototype.token = '';

    /**
     * Interface of Editor.
     */
    function Editor(url) {
        //使用默认参数
        var options =  $.extend({
            toolbar: Editor.toolbar,
            statusbar: true,
            status: Editor.statusbar
        }, options);
        this.options = options;
        Editor.prototype.uploadPath = url;
    }
    window.Editor = Editor;

    // 默认的状态栏
    Editor.statusbar = ['lines', 'words', 'cursor'];

    // 默认的toolbar
    // [{name: 'bold', shortcut: 'Ctrl-B', className: 'icon-bold'}]
    Editor.toolbar = [{
            name: 'bold',
            action: toggleBold,
            className: 'editor__menu--bold'
        }, {
            name: 'italic',
            action: toggleItalic,
            className: 'editor__menu--italic'
        },
        '|',
        {
            name: 'link',
            action: drawLink,
            className: 'editor__menu--link'
        }, {
            name: 'quote',
            action: toggleBlockquote,
            className: 'editor__menu--quote'
        }, {
            name: 'code',
            action: toggleBlockcode,
            className: 'editor__menu--code'
        }, {
            name: 'image',
            action: drawImage,
            className: 'editor__menu--img'
        },
        '|',
        {
            name: 'ordered-list',
            action: toggleOrderedList,
            className: 'editor__menu--ol'
        }, {
            name: 'unordered-list',
            action: toggleUnOrderedList,
            className: 'editor__menu--ul'
        }, {
            name: 'title',
            action: toggleTitle,
            className: 'editor__menu--title'
        }, {
            name: 'hr',
            action: drawHr,
            className: 'editor__menu--hr'
        },
        '|',
        {
            name: 'undo',
            action: undo,
            className: 'editor__menu--undo'
        }, {
            name: 'redo',
            action: redo,
            className: 'editor__menu--redo'
        },
        '|',
        // {
        //     name: 'fullscreen',
        //     action: toggleBig,
        //     className: 'editor__menu--zen'
        // },
        // {
        //     name: 'zen',
        //     action: toggleFullScreen,
        //     className: 'editor__menu--two'
        // }
        {
            name: 'fullscreen',
            action: toggleBig,
            className: 'editor__menu--zen'
        }
    ];

    // 是否全屏
    Editor.isBig = false;
    Editor.originHeight = 420;

    /**
     * get the value of the Editor
     * myEditor.getVal();
     **/
    Editor.prototype.getVal = function() {
        var cm = this.codemirror;
        return cm.getValue();
    };

    /**
     * get the parsed value of the Editor
     * myEditor.getHTML();
     **/
    Editor.prototype.getHTML = function() {
        var cm = this.codemirror;
        return marked(cm.getValue());
    };

    /**
     * set the value of the Editor
     * myEditor.setVal(text);
     **/
    Editor.prototype.setVal = function(text) {
        var cm = this.codemirror;
        return cm.setValue(text);
    };

    /**
     * bind change event of the Editor
     * myEditor.change(function(cm){});
     **/
    Editor.prototype.change = function(callback) {
        this.isModified = true;
        var cm = this.codemirror;
        cm.on('change', callback);
    };



    /**
     * Render editor to the given element.
     * myEditor.render('#editor')
     * mode选择"live", 'edit', 'preview'
     */
    Editor.prototype.render = function(el, mode, callback) {
        mode = mode || 'live';
        el = $(el)[0];      //选中被渲染的textarea的DOM
        if (this._rendered && this._rendered === el) {
            // Already rendered.
            return;
        }

        this.element = el;
        this.isSubmit = false;
        var options = this.options;

        var self = this;
        var keyMaps = {};

        for (var key in shortcuts) {
            (function(key) {
                keyMaps[fixShortcut(key)] = function() {
                    shortcuts[key](self);
                };
            })(key);
        }

        // 对markdown列表的支持
        keyMaps.Enter = 'newlineAndIndentContinueMarkdownList';

        // 渲染codemirror
        this.codemirror = CodeMirror.fromTextArea(el, {
            mode: 'markdown',
            theme: 'paper',
            lineNumbers: true,
            lineWrapping: true,
            extraKeys: keyMaps,
            cursorBlinkRate: 500,
            viewportMargin: Infinity
        });
        /**
         * cm第一次渲染时cursor位置是错的
         * 第一次点击cm的文本时向当前光标位置插入一个空格然后再删掉
         **/
        var cm       = this.codemirror;
        var isAtting = false;       // 是否正在at
        var query    = '';          // at时查询的人名
        var atCatch  = {};          // 缓存at的ajax数据，可以少发几次请求
        var myDelay  = null;        // setTimeout
        var parserDelay = null;
        cm.on('change', function(event, c) {    //codemirror 一有变化就检查
            $(el).text(cm.getValue());
            //WYSIWYG
            if(parserDelay) {
                clearTimeout(parserDelay);
            }
            if($('#editorLive').length) {
                parserDelay = setTimeout(function() {
                    var text = cm.getValue();
                    $('#editorLive').html(marked(text));
                    highLight($('#editorLive'));
                }, 500);
            }
            if($('.editor-preview-active.onlive').length) {
                parserDelay = setTimeout(function() {
                    var text = cm.getValue();
                    $('.editor-preview-active.onlive').html(marked(text));
                    highLight($('.editor-preview-active.onlive'));
                }, 500);
            }
            // 用户第一次输入@时 todo 暂时注释 @ 功能
            //if(!isAtting) {
            //    if(c.text[0] === '@' && c.origin === '+input') {
            //        isAtting = true;
            //        if($('#atwho').length === 0) {  //之前没有at过
            //            var temp = '<ul id="atwho" class="dropdown-menu"></ul>';
            //            $('.editor').append(temp);
            //            editorAt('');
            //            $('#atwho').delegate('a', 'click', function(e) {
            //                e.preventDefault();
            //                isAtting = false;
            //                var curLine = cm.getCursor().line;
            //                var curCh = cm.getCursor().ch;
            //                var userName = $(this).parent('li').data('value');
            //                var atCh = cm.getRange({line: curLine, ch: 0}, {line: curLine, ch: curCh}).lastIndexOf('@');    //最后一个@的位置
            //                cm.replaceRange(userName + ' ', {line: curLine, ch: atCh + 1}, {line: curLine, ch: curCh});
            //                cm.focus();
            //                $('#atwho').html('').hide();
            //                query = '';     //最后清掉query
            //            });
            //        } else {    //已经at过
            //            // $('#atwho').html('').hide();
            //            editorAt('');
            //        }
            //    }
            //} else if(isAtting) {
            //    // 用户@中输入空格时
            //    if(c.origin === '+input' && c.text[0] === ' ') {   // 用户输入空格或@
            //        isAtting = false;   // 退出at状态
            //        query = '';
            //        $('#atwho').html('').hide();
            //    } else if(c.origin === '+input' && c.text[0] === '@') {   // 用户输入@
            //        isAtting = false;   // 退出at状态
            //        query = '';
            //        // $('#atwho').html('').hide();
            //        editorAt(query);
            //    } else if(c.origin === '+input') {    // at中输入其他字符
            //        isAtting = true;   // at状态
            //        query += c.text[0];
            //        editorAt(query);
            //    } else if(c.origin === '+delete') {
            //        if(c.removed[0] !== '@') {      //删除非@
            //            isAtting = true;   // at状态
            //            query = query.slice(0, -1);
            //            editorAt(query);
            //        } else {    // 删除@
            //            isAtting = false;
            //            query = '';
            //            $('#atwho').html('').hide();
            //        }
            //    }
            //}
        });

        var _dragText = false;
        cm.on('dragstart', function(c, e) {
            _dragText = true;
        });
        cm.on('dragover', function(c, e) {
            if(!_dragText) {
                drawImage(self);
                _dragText = false;
            }
        });

        cm.on('keydown', function(c, e) {
            if(!isAtting) {
                return;
            }
            switch(e.keyCode) {
                case 38: {      // Up
                    e.preventDefault();
                    if($('#atwho .active').length) {
                        $('#atwho .active').removeClass('active')
                            .prev('li').addClass('active');
                    }else {
                        $('#atwho li:last').addClass('active');
                    }
                    break;
                }
                case 40: {      // Down
                    e.preventDefault();
                    if($('#atwho .active').length) {
                        $('#atwho .active').removeClass('active')
                            .next('li').addClass('active');
                    }else {
                        $('#atwho li:first').addClass('active');
                    }
                    break;
                }
                case 13: {      // Enter
                    e.preventDefault();
                    $('#atwho .active a').trigger('click');
                    break;
                }
                case 27: {      // Esc
                    e.preventDefault();
                    $('#atwho').html('').hide();
                    break;
                }
            }

        });

        function editorAt(query) {
            var _sendData = {};
            if(query === '@' || query === '') {    //确保query不为空
                if($('#answerIt').length) {   // 回答问题的编辑器
                    var _qid = $('#answerIt').data('id');
                    _sendData = {questionId: _qid};
                    // $('#atwho').html('').hide();
                } else {
                    return;
                }
            }
            if(query.indexOf('@') === 0) {  //当第一个字符为@时，切掉
                query = query.slice(1);
            }
            if(query.indexOf(' ') !== -1) {     //包含空格时 输入中文时被坑了
                if(query.match(/[\u4E00-\u9FA5\uf900-\ufa2d]/ig)) { //有中文
                    query = query.replace(/[^\u4E00-\u9FA5\uf900-\ufa2d]*/g, '');
                } else {    //没有中文
                    query = query.replace(' ', '');
                    query = query.replace(/(\w){2}/g, '$1');
                }
            }
            if(query.match(/[\u4E00-\u9FA5\uf900-\ufa2d]/ig)) {     //中英混杂时
                query = query.replace(/[^\u4E00-\u9FA5\uf900-\ufa2d]*/g, '');

            }
            var cursorPos = $('.CodeMirror-cursor').caret('offset');
            var _top = cursorPos.top + cursorPos.height ;
            var _left = cursorPos.left;
            if($('.editor__menu--zen').length) {
                _top = cursorPos.top + cursorPos.height - $('.editor').offset().top;
                _left = cursorPos.left - $('.editor').offset().left;
            }
            var _html = '';
            var _temp = '<li data-value="{{name}}"><a href="javacript:void(0);"><img class="avatar-24 mr10" src="{{avatarUrl}}">{{name}} &nbsp; <small>@{{slug}}</small></a></li>';

            // 有缓存
            if(atCatch[query]) {
                if(atCatch[query].length === 0) {   //缓存中数据为空
                    return;
                } else {
                    atCatch[query].forEach(function(item) {
                        _html += template(_temp, item);
                    });
                    $('#atwho').html(_html);
                    $('#atwho').css({
                        left: _left,
                        top: _top
                    }).show();
                }
            } else {
                if(myDelay) {       // 如果已经在delay中
                    clearTimeout(myDelay);  // 清掉上次的settimeout
                }
                myDelay = setTimeout(function() {
                    if(!_sendData.questionId) {
                        _sendData = {q : query};
                    }
                    $.getJSON('/api/user?do=search',
                        _sendData, function (o) {
                        if (!o.status) {    // status为0
                            atCatch[query] = o.data;    //添加缓存数据
                            if(o.data.length === 0) {   //返回空数组
                                $('#atwho').html('').hide();
                                return;
                            }
                            o.data.forEach(function(item) {
                                _html += template(_temp, item);
                            });
                            $('#atwho').css({
                                left: _left,
                                top: _top
                            }).show();
                            $('#atwho').html(_html);
                        }
                    });
                }, 250);
            }
        }

        var isVirgin = true;
        $('.CodeMirror-lines').click(function() {
            if(isVirgin) {
                var _pos = cm.getCursor();
                cm.replaceRange(' ', _pos, _pos);
                isVirgin = false;
                cm.replaceRange('', _pos, {line: _pos.line, ch: _pos.ch + 1});
            }
        });
        // 跳转前提醒
        $(window).bind('beforeunload', function () {
            if(cm.getValue() !== $(el).text()) {
                return '你可以到草稿页，找回已经编辑的内容。';
            }
        });

        if (options.toolbar !== false) {
            this.createToolbar();   //渲染工具栏
        }
        if (options.status !== false) {     //渲染状态栏
            // //this.createStatusbar();   //暂时不需要状态栏
            // this.change(function() {    //绑定状态操作
            // });
        }

        //resize
        var resizeHtml = '<a class="editor__resize" href="javascript:void(0);">===</a>';
        $(el).parent('.editor')
            .css('min-height', '0')
            .append(resizeHtml);

        //拖动改变大小
        var staticOffset, iLastMousePos = 0, iMin = 32;
        var textarea = $('.CodeMirror');    //要改变大小的codemirror
        /* private functions */
        function startDrag(e) {
            iLastMousePos = mousePosition(e).y;

            staticOffset = textarea.height() - iLastMousePos;
            textarea.css('opacity', 0.3);

            $(document).mousemove(performDrag).mouseup(endDrag);
            return false;
        }

        function performDrag(e) {
            var iThisMousePos = mousePosition(e).y,
                iMousePos = staticOffset + iThisMousePos;
            if (iLastMousePos >= (iThisMousePos)) {
                iMousePos -= 5;
            }

            iLastMousePos = iThisMousePos;
            iMousePos = Math.max(iMin, iMousePos);
            textarea.height(iMousePos + 'px');

            if (iMousePos < iMin) {
                endDrag(e);
            }
            return false;
        }

        function endDrag() {
            $(document).unbind('mousemove', performDrag).unbind('mouseup', endDrag);
            var textarea = $('.CodeMirror');
            textarea.css('opacity', 1);
            $('.CodeMirror-scroll, .CodeMirror-gutters').css('height', '100%');
            textarea.focus();
            textarea = null;

            staticOffset = null;
            iLastMousePos = 0;
        }

        function mousePosition(e) {
            return { x: e.clientX + document.documentElement.scrollLeft, y: e.clientY + document.documentElement.scrollTop };
        }

        $('.editor__resize').on('mousedown', startDrag);

        // 当toolbar看不到时，position:fixed toolbar的位置
        $(window).scroll(function() {
            if(!self.isBig) {
                var _width = $('.editor').width();      //编辑器宽度
                var _top = $('.editor').offset().top;   //编辑器距离页面顶部的高度
                var _scrollTop = $(this).scrollTop();   //当前滚动条的位置
                var _editorTop = 62 + $('.editor-help .tab-content').height();  //编辑器应该距离页面顶部
                if(_scrollTop >= _top) {
                    $('.editor-help-content.active').removeClass('active');
                    $('.editor__menu').css({
                        position: 'fixed',
                        top: 0,
                        'z-index': 1000,
                        width: _width
                    });
                    $('.editor-help').css({
                        position: 'fixed',
                        top: '31px',
                        'z-index': 1000,
                        width: _width
                    });
                } else {
                    $('.editor__menu, .editor-help').css({
                        position: 'static',
                        width: 'auto'
                    });
                }
            }
        });

        this._rendered = this.element;

        //最后默认开启live模式
        if(mode === 'live') {
            $('.editor__menu--live').trigger('click');
        } else if(mode === 'edit') {
            $('.editor__menu--edit').trigger('click');
        } else if(mode === 'preview') {
            $('.editor__menu--preview').trigger('click');
        }

        // localStrorage
        if(window.localStorage) {
            var _localContentKey = 'autoSaveContent_' + location.pathname + location.search;
            var _localTitleKey = 'autoSaveTitle_' + location.pathname + location.search;
            var _localTagsKey = 'autoSaveTags_' + location.pathname + location.search;
            if(localStorage[_localContentKey]) {
                self.setVal(localStorage[_localContentKey]);
            }
            if(localStorage[_localTitleKey]) {
                $('#myTitle').val(localStorage[_localTitleKey]);
            }
            var _tagTmpl = '<li class="widget-addtags__input"><div class="input-group"><input type="text" value="{{ name }}" data-id="{{ id }}" class="tagText form-control input-sm" placeholder="标签，如：php" disabled="disabled"><a class="input-group-addon tagClose" href="javascript:void(0);">×</a></div></li>';
            if(localStorage[_localTagsKey]) {
                var _localTags = localStorage[_localTagsKey].split(',');
                var _html = '';
                _localTags.forEach(function(item) {
                    var _tag = item.split(':');
                    // console.log(_tag);
                    _html += template(_tagTmpl, {name: _tag[0], id: _tag[1]});
                });
                if($('.widget-addtags__input').length === 1) {
                    if(_localTags.length >= 5) {
                        $('.widget-addtags__input').remove();
                        $('.widget-addtags__add').before(_html);
                    } else {
                        $('.widget-addtags__input').before(_html);
                    }
                }
            }
        }

        // mario
        var velocity = 127; // how hard the note hits
        var marioKeys = ['E4', 'E4', 'E4', 'C4', 'E4', 'G4', 'G3',
            'C4', 'G3', 'E3', 'A3', 'B3', 'Ab3', 'A3', 'G3', 'E4', 'G4', 'A4', 'F4', 'G4', 'E4', 'C4', 'D4', 'B3',
            'C4', 'G3', 'E3', 'A3', 'B3', 'Ab3', 'A3', 'G3', 'E4', 'G4', 'A4', 'F4', 'G4', 'E4', 'C4', 'D4', 'B3',
            'G4', 'F4', 'E4', 'Db4', 'E4', 'Gb3', 'A3', 'C4', 'A3', 'C4', 'D4', 'G4', 'F4', 'E4', 'Db4', 'E4', 'C5', 'C5', 'C5',
            'G4', 'F4', 'E4', 'Db4', 'E4', 'Gb3', 'A3', 'C4', 'A3', 'C4', 'D4', 'Db4', 'D4', 'C4',
            'C4', 'C4', 'C4', 'C4', 'D4', 'E4', 'C4', 'A3', 'G3', 'C4', 'C4', 'C4', 'C4', 'D4', 'E4',
            'C4', 'C4', 'C4', 'C4', 'D4', 'E4', 'C4', 'A3', 'G3'
        ]; // the MIDI note
        var marioTimes = [
            8, 4, 4, 8, 4, 2, 2,
            3, 3, 3, 4, 4, 8, 4, 8, 8, 8, 4, 8, 4, 3, 8, 8, 3,
            3, 3, 3, 4, 4, 8, 4, 8, 8, 8, 4, 8, 4, 3, 8, 8, 2,
            8, 8, 8, 4, 4, 8, 8, 4, 8, 8, 3, 8, 8, 8, 4, 4, 4, 8, 2,
            8, 8, 8, 4, 4, 8, 8, 4, 8, 8, 3, 3, 3, 1,
            8, 4, 4, 8, 4, 8, 4, 8, 2, 8, 4, 4, 8, 4, 1,
            8, 4, 4, 8, 4, 8, 4, 8, 2
        ];

        // todo 袁超 缺少控件，暂时注释
        //MIDI.loadPlugin({
        //    targetFormat: 'mp3',
        //    soundfontUrl: '/BachEditor/js/',
        //    instrument: 'marimba',
        //    callback: function() {
        //        MIDI.setVolume(0, 127);
        //        MIDI.programChange(0, 12);
        //        var cur = 0;
        //        $('textarea').keypress(function() {
        //            var delay = 1.3 / marioTimes[cur]; // play one note every quarter second
        //            var note = MIDI.keyToNote[marioKeys[cur]];
        //            MIDI.noteOn(0, note, velocity, 0);
        //            MIDI.noteOff(0, note, delay);
        //            if(cur >= 96) {
        //                cur = 0;
        //            }else {
        //                cur++;
        //            }
        //        });
        //    }
        //});

        if(callback) {      // 有callback时执行
            callback(self);
        }
    };

    Editor.prototype.createToolbar = function(items) {
        items = items || this.options.toolbar;

        if (!items || items.length === 0) {
            return;
        }

        var bar = document.createElement('ul');
        bar.className = 'editor__menu clearfix';

        var self = this;

        self.toolbar = {};

        for (var i = 0; i < items.length; i++) {
            (function(item) {
                var el;
                if (item.name) {
                    el = createIcon(item.name, item);
                } else if (item === '|') {
                    el = createSep();
                } else {
                    el = createIcon(item);
                }

                // bind events, special for info
                if (item.action) {
                    if (typeof item.action === 'function') {
                        el.onclick = function() {
                            item.action(self);
                        };
                    } else if (typeof item.action === 'string') {
                        el.href = item.action;
                        el.target = '_blank';
                    }
                }
                self.toolbar[item.name || item] = el;
                bar.appendChild(el);
            })(items[i]);
        }

        //toolbar添加右侧预览按钮
        var toolbarRightHtml = '<li class="pull-right"><a class="editor__menu--preview" title="预览模式" href="javascript:void(0)"></a></li><li class="pull-right"><a class="editor__menu--live" href="javascript:void(0)" title="实况模式"></a></li><li class="pull-right"><a class="editor__menu--edit muted" title="编辑模式" href="javascript:void(0)"></a></li>';
        var toolbarRight = $(toolbarRightHtml);
        $(bar).append(toolbarRight);
        $('.editor').delegate('.editor__menu--edit', 'click', function() {
            if(!$(this).hasClass('muted')) {    // 当前模式为muted
                goEdit(self);
            }
        });
        $('.editor').delegate('.editor__menu--preview', 'click', function() {
            if(!$(this).hasClass('muted')) {    // 当前模式为muted
                goPreview(self);
            }
        });
        $('.editor').delegate('.editor__menu--live', 'click', function() {
            if(!$(this).hasClass('muted')) {    // 当前模式为muted
                goLive(self);
            }
        });

        //添加帮助
        var helpHtml = '<div class="editor-help">\
<ul class="editor-help-tabs nav nav-tabs" id="editorHelpTab" role="tablist">\
    <li rel="heading"><a href="#editorHelpHeading" role="tab" data-toggle="tab">标题 / 粗斜体</a></li>\
    <li rel="code"><a href="#editorHelpCode" role="tab" data-toggle="tag">代码</a></li>\
    <li rel="link"><a href="#editorHelpLink" role="tab" data-toggle="tag">链接</a></li>\
    <li rel="image"><a href="#editorHelpImage" role="tab" data-toggle="tag">图片</a></li>\
    <li rel="split"><a href="#editorHelpSplit" role="tab" data-toggle="tag">换行 / 分隔符</a></li>\
    <li rel="list"><a href="#editorHelpList" role="tab" data-toggle="tag">列表 / 引用</li></a>\
    </ul>\
\
<div class="tab-content">\
<!-- 粗斜体、标题 -->\
<div class="editor-help-content tab-pane fade" id="editorHelpHeading" rel="heading">\
\
<p>文章内容较多时，可以用标题分段：</p>\
<pre>## 大标题 ##\n\
### 小标题 ###\
</pre>\
\
<p>粗体 / 斜体</p>\
<pre>*斜体文本*    _斜体文本_\n\
**粗体文本**    __粗体文本__\n\
***粗斜体文本***    ___粗斜体文本___\
</pre>\
</div>\
<!-- end 粗斜体、标题 -->\
\
<!-- 代码 -->\
<div class="editor-help-content tab-pane fade" id="editorHelpCode" rel="code">\
<p>如果你只想高亮语句中的某个函数名或关键字，可以使用 <code>`function_name()`</code> 实现</p>\
<p>通常我们会根据您的代码片段适配合适的高亮方法，但你也可以用 <code>```</code> 包裹一段代码，并指定一种语言</p>\
<pre>```<strong>javascript</strong>\n\
$(document).ready(function () {\n\
    alert(\'hello world\');\n\
});\n\
```\
</pre>\
<p>支持的语言：<code>actionscript, apache, bash, clojure, cmake, coffeescript, cpp, cs, css, d, delphi, django, erlang, go, haskell, html, http, ini, java, javascript, json, lisp, lua, markdown, matlab, nginx, objectivec, perl, php, python, r, ruby, scala, smalltalk, sql, tex, vbscript, xml</code></p>\
\
<p>您也可以使用 4 空格缩进，再贴上代码，实现相同的的效果</p>\
<pre><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>def g(x):\n\
<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>yield from range(x, 0, -1)\n\
<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>yield from range(x)\
</pre>\
</div>\
<!-- end 代码 -->\
\
<!-- 链接 -->\
<div class="editor-help-content tab-pane fade" rel="link" id="editorHelpLink">\
\
<p>常用链接方法</p>\
<pre>文字链接 [链接名称](http://链接网址)\n\
网址链接 &lt;http://链接网址&gt;\
</pre>\
<p>高级链接技巧</p>\
<pre>这个链接用 1 作为网址变量 [Google][1].\n\
这个链接用 yahoo 作为网址变量 [Yahoo!][yahoo].\n\
然后在文档的结尾为变量赋值（网址）\n\
\n\
<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>[1]: http://www.google.com/\n\
<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>[yahoo]: http://www.yahoo.com/\
</pre>\
\
</div>\
<!-- end 链接 -->\
\
<!-- 图片 -->\
<div class="editor-help-content tab-pane fade" id="editorHelpImage" rel="image">\
\
<p>跟链接的方法区别在于前面加了个感叹号 <code>!</code>，这样是不是觉得好记多了呢？</p>\
<pre>![图片名称](http://图片网址)\
</pre>\
<p>当然，你也可以像网址那样对图片网址使用变量</p>\
<pre>这个链接用 1 作为网址变量 [Google][1].\n\
然后在文档的结尾位变量赋值（网址）\n\
\n\
<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>[1]: http://www.google.com/logo.png\
</pre>\
\
</div>\
<!-- end 图片 -->\
\
<!-- 换行、分隔符 -->\
<div class="editor-help-content tab-pane fade" id="editorHelpSplit" rel="split">\
\
<p>如果另起一行，只需在当前行结尾加 2 个空格</p>\
<pre>在当前行的结尾加 2 个空格<i class="nbsp">&nbsp;</i><i class="nbsp">&nbsp;</i>\n\
这行就会新起一行\
</pre>\
<p>如果是要起一个新段落，只需要空出一行即可。</p>\
<p>如果你有写分割线的习惯，可以新起一行输入三个减号 <code>-</code>：</p>\
<pre>---\n\
</pre>\
\
</div>\
<!-- end 换行、分隔符 -->\
\
<!-- 列表、引用 -->\
<div class="editor-help-content tab-pane fade" id="editorHelpList" rel="list">\
\
<p>普通列表</p>\
<pre>-<i class="nbsp">&nbsp;</i>列表文本前使用 [减号+空格]\n\
+<i class="nbsp">&nbsp;</i>列表文本前使用 [加号+空格]\n\
*<i class="nbsp">&nbsp;</i>列表文本前使用 [星号+空格]\
</pre>\
<p>带数字的列表</p>\
<pre>1.<i class="nbsp">&nbsp;</i>列表前使用 [数字+空格]\n\
2.<i class="nbsp">&nbsp;</i>我们会自动帮你添加数字\n\
7.<i class="nbsp">&nbsp;</i>不用担心数字不对，显示的时候我们会自动把这行的 7 纠正为 3\
</pre>\
<p>引用</p>\
<pre>&gt;<i class="nbsp">&nbsp;</i>引用文本前使用 [大于号+空格]\n\
&gt;<i class="nbsp">&nbsp;</i>折行可以不加，新起一行都要加上哦\
</pre>\
\
</div>\
<!-- end 列表、引用 -->\
</div></div>';

        var cm = this.codemirror;
        cm.on('cursorActivity', function() {
            var stat = getState(cm);

            for (var key in self.toolbar) {
                (function(key) {
                    var el = self.toolbar[key];
                    if (stat[key]) {
                        el.className += ' active';
                    } else {
                        el.className = el.className.replace(/\s*active\s*/g, '');
                    }
                })(key);
            }
        });

        var cmWrapper = cm.getWrapperElement();
        cmWrapper.parentNode.insertBefore(bar, cmWrapper);

        //为了添加样式
        $('.CodeMirror').addClass('form-control')
            .before(helpHtml);
        //帮助
        $('#editorHelpTab a').click(function (e) {
            var _$wrap = $(this).parent();
            if(! _$wrap.hasClass('pull-right')) {  //高级技巧
                if(_$wrap.hasClass('active')) {
                    e.stopPropagation();    //阻止冒泡
                    _$wrap.removeClass('active');
                    $('.editor-help-content.active').removeClass('active');
                } else {
                    e.preventDefault();
                    $(this).tab('show');
                }
            }
        });

        return bar;
    };

    Editor.prototype.createStatusbar = function(status) {
        status = status || this.options.status;

        if (!status || status.length === 0) {
            return;
        }
        var bar = document.createElement('div');
        bar.className = 'editor-statusbar';

        var pos, cm = this.codemirror;
        for (var i = 0; i < status.length; i++) {
            (function(name) {
                var el = document.createElement('span');
                el.className = name;
                if (name === 'words') {
                    el.innerHTML = '0';
                    cm.on('update', function() {
                        el.innerHTML = wordCount(cm.getValue());
                    });
                } else if (name === 'lines') {
                    el.innerHTML = '0';
                    cm.on('update', function() {
                        el.innerHTML = cm.lineCount();
                    });
                } else if (name === 'cursor') {
                    el.innerHTML = '0:0';
                    cm.on('cursorActivity', function() {
                        pos = cm.getCursor();
                        el.innerHTML = pos.line + ':' + pos.ch;
                    });
                }
                bar.appendChild(el);
            })(status[i]);
        }
        var cmWrapper = this.codemirror.getWrapperElement();
        cmWrapper.parentNode.insertBefore(bar, cmWrapper.nextSibling);
        return bar;
    };


    /**
     * Bind static methods for exports.
     */
    Editor.toggleBold = toggleBold;
    Editor.toggleItalic = toggleItalic;
    Editor.toggleBlockquote = toggleBlockquote;
    Editor.toggleBlockcode = toggleBlockcode;
    Editor.toggleUnOrderedList = toggleUnOrderedList;
    Editor.toggleOrderedList = toggleOrderedList;
    Editor.toggleTitle = toggleTitle;
    Editor.drawHr = drawHr;
    Editor.drawLink = drawLink;
    Editor.drawImage = drawImage;
    Editor.undo = undo;
    Editor.redo = redo;
    Editor.toggleFullScreen = toggleFullScreen;
    Editor.toggleBig = toggleBig;

    /**
     * Bind instance methods for exports.
     */
    Editor.prototype.toggleBold = function() {
        toggleBold(this);
    };
    Editor.prototype.toggleItalic = function() {
        toggleItalic(this);
    };
    Editor.prototype.toggleBlockquote = function() {
        toggleBlockquote(this);
    };
    Editor.prototype.toggleBlockcode = function() {
        toggleBlockcode(this);
    };
    Editor.prototype.toggleUnOrderedList = function() {
        toggleUnOrderedList(this);
    };
    Editor.prototype.toggleOrderedList = function() {
        toggleOrderedList(this);
    };
    Editor.prototype.toggleTitle = function() {
        toggleTitle(this);
    };
    Editor.prototype.drawHr = function() {
        drawHr(this);
    };
    Editor.prototype.drawLink = function() {
        drawLink(this);
    };
    Editor.prototype.drawImage = function() {
        drawImage(this);
    };
    Editor.prototype.undo = function() {
        undo(this);
    };
    Editor.prototype.redo = function() {
        redo(this);
    };
    Editor.prototype.toggleFullScreen = function() {
        toggleFullScreen(this);
    };
    Editor.prototype.toggleBig = function() {
        toggleBig(this);
    };

    // intro.js
    var isMac = /Mac/.test(navigator.platform);

    var shortcuts = {
        'Cmd-B': toggleBold,
        'Cmd-I': toggleItalic,
        'Cmd-L': drawLink,
        'Cmd-G': drawImage,
        "Cmd-'": toggleBlockquote,
        'Cmd-K': toggleBlockcode,
        'Cmd-O': toggleOrderedList,
        'Cmd-U': toggleUnOrderedList,
        'Cmd-Z': undo,
        'Cmd-Shift-Z': redo,
        'Cmd-E': drawHr,
        'Cmd-H': toggleTitle,
        'F11'  : toggleFullScreen
    };

    var tooltips = {
        bold: '加粗 <strong> Cmd+B',
        italic: '斜体 <em> Cmd+I',
        link: '链接 <a> Cmd+L',
        quote: "引用 <blockquote> Cmd+Q",
        code: '代码 <pre> <code> Cmd+K',
        image: '图片 <img> Cmd+G',
        'ordered-list': '数字列表 <ol> Cmd+O',
        'unordered-list': '普通列表 <ul> Cmd+U',
        'hr': '分割线 <hr> Cmd+R',
        'title': '标题 <h1>/<h2> Cmd+H',
        'redo': '重做 Cmd+Shift+Z',
        'undo': '撤销 Cmd+Z',
    };

    /**
     * Fix shortcut. Mac use Command, others use Ctrl.
     */
    function fixShortcut(name) {
        if (isMac) {
            name = name.replace('Ctrl', 'Cmd');
        } else {
            name = name.replace('Cmd', 'Ctrl');
        }
        return name;
    }


    /**
     * Create icon element for toolbar.
     */
    function createIcon(name, options) {
        options = options || {};
        var el = document.createElement('a');
        var shortcut = options.shortcut || tooltips[name];
        if (shortcut) {
            shortcut = fixShortcut(shortcut);
            el.title = shortcut;
            el.title = el.title.replace('Cmd', '⌘');
            if (isMac) {
                el.title = el.title.replace('Alt', '⌥');
            }
        }

        el.className = options.className || 'icon-' + name;
        // $(el).tooltip();
        var li = document.createElement('li');
        li.appendChild(el);
        return li;
    }

    function createSep() {
        var el = document.createElement('li');
        el.className = 'editor__menu--divider';
        el.innerHTML = ' | ';
        return el;
    }


    /**
     * The state of CodeMirror at the given position.
     */
    function getState(cm, pos) {
        pos = pos || cm.getCursor('start');
        var stat = cm.getTokenAt(pos);
        if (!stat.type) {    //没有markdown关键字
            return {};
        } else {
            var types = stat.type.split(' ');

            var ret = {},
                data, text;
            for (var i = 0; i < types.length; i++) {
                data = types[i];
                if (data === 'strong') {
                    ret.bold = true;
                } else if (data === 'variable-2') {
                    text = cm.getLine(pos.line);
                    if (/^\s*\d+\.\s/.test(text)) {
                        ret['ordered-list'] = true;
                    } else {
                        ret['unordered-list'] = true;
                    }
                } else if (data === 'quote') {
                    ret.quote = true;
                } else if (data === 'em') {
                    ret.italic = true;
                }
            }
            return ret;
        }
    }


    /**
     * Toggle full screen of the editor.
     */
    function toggleFullScreen(editor) {
        var cm = editor.codemirror;
        var el = $('.editor')[0];
        // https://developer.mozilla.org/en-US/docs/DOM/Using_fullscreen_mode
        var doc = document;
        var _sw = screen.width;
        var _sh = screen.height;
        var isFull = doc.isfullScreen || doc.mozFullScreen || doc.webkitIsFullScreen;
        var goBig = function() {
            $('.editor__menu--zen').addClass('editor__menu--two')
                .removeClass('editor__menu--zen');
            $('.editor').css({
                position: 'fixed',
                width: '100%',
                top: 0,
                left: 0,
                'z-index': 999,
                'margin-top': 0
            });
            $('.CodeMirror').css('height', $('html').height());
            $('.CodeMirror-gutters').css('height', '100%');
            $('.editor__resize').hide();
        };
        var request = function() {
            $('.editor').css('overflow', 'auto');
            $('.editor__resize').hide();
            $('.editor__menu--two').addClass('editor__menu--unzen')
                .removeClass('editor__menu--two');
            $('.editor__menu li.pull-right').hide();
            if (el.requestFullScreen) {
                el.requestFullScreen();
            } else if (el.mozRequestFullScreen) {
                el.mozRequestFullScreen();
            } else if (el.webkitRequestFullScreen) {
                el.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            }
            $('.CodeMirror').after('<div id="editorLive" class="editor-live fmt"></div>')

            $('.editor').css('margin', 0);
            if(_sh > _sw) {     // 针对竖屏做了优化
                $('.CodeMirror').css('height', '45%');
                $('#editorLive').css('height', '50%');
            } else {
                $('.editor__menu, .editor-help').css('width', '49%');
                $('.CodeMirror').css({height: _sh - 80, width: '49%', float: 'left'});
                $('#editorLive').css({height: _sh - 10, width: '49%', float: 'right', 'margin-top': '-70px'});
            }
            cm.focus();
            var text = cm.getValue();
            $('#editorLive').html(marker(text));
            highLight($('#editorLive'));

            $(document).on('webkitfullscreenchange mozfullscreenchange fullscreenchange', function() {
                var _isFull = document.isfullScreen || document.mozFullScreen || document.webkitIsFullScreen;
                if(!_isFull) {
                    cancel();
                }
            });

        };
        var cancel = function() {
            $('.editor__resize').show();
            $('.editor__menu--unzen').addClass('editor__menu--zen')
                .removeClass('editor__menu--unzen');
            $('.editor__menu li.pull-right').show();
            $('#editorLive').remove();
            $('.CodeMirror, .editor__menu, .editor-help').css('width', '100%');
            $('.CodeMirror').css('float', 'none');
            $('.editor').css({
                position: 'static',
                width: '100%',
                'margin-top': '20px'
            });
            if (doc.cancelFullScreen) {
                doc.cancelFullScreen();
            } else if (doc.mozCancelFullScreen) {
                doc.mozCancelFullScreen();
            } else if (doc.webkitCancelFullScreen) {
                doc.webkitCancelFullScreen();
            }
        };
        if (!isFull && $('.editor__menu--zen').length) {
            goBig();
        } else if($('.editor__menu--two').length) {
            request();
        } else {
            cancel();
        }
    }


    /**
     * Toggle big of the editor.
     */
    function toggleBig(editor) {
        var cm = editor.codemirror;
        var el = $('.editor')[0];
        var doc = document;
        var _sw = screen.width;
        var _sh = screen.height;
        var cmHeight = editor.originHeight;
        var goBig = function() {
            editor.originHeight = $('.CodeMirror').height();
            $('.editor__menu, .editor-help').css({
                position: 'static',
                width: 'auto'
            });
            $('.editor__menu--zen').addClass('editor__menu--unzen')
                .removeClass('editor__menu--zen');
            $('.editor').css({
                position: 'fixed',
                width: '100%',
                top: 0,
                left: 0,
                'z-index': 999,
                'margin-top': 0
            });
            $('.CodeMirror').css('height', $(document).height() - 70);
            cmHeight = Math.max($('.CodeMirror').height(), $('.CodeMirror-sizer').height());
            $('.CodeMirror-gutters').css('height', cmHeight);
            $('.editor__resize').hide();
            editor.isBig = true;
        };
        var cancel = function() {
            $('.editor__resize').show();
            $('.editor__menu--unzen').addClass('editor__menu--zen')
                .removeClass('editor__menu--unzen');
            $('.editor__menu li.pull-right').show();
            $('#editorLive').remove();
            $('.CodeMirror, .editor__menu, .editor-help').css('width', '100%');
            $('.CodeMirror').css({
                float: 'none',
                height: editor.originHeight
            });
            $('.editor').css({
                position: 'static',
                width: '100%',
                'margin-top': '20px'
            });
            var cmHeight = Math.max($('.CodeMirror').height(), $('.CodeMirror-sizer').height());
            $('.CodeMirror-gutters').css('height', cmHeight);
            editor.isBig = false;
        };
        if($('.editor__menu--zen').length) {
            goBig();
        } else if (cancel) {
            cancel();
        }
    }

    /**
     * Action for toggling bold.
     */
    function toggleBold(editor) {
        var cm = editor.codemirror;
        var stat = getState(cm);

        var text = '加粗文字';
        var start = '**';
        var end = '**';
        var startPoint, endPoint,curPoint;
        if(cm.getSelection()) {   // 有选中
            startPoint = cm.getCursor('from');
            endPoint = cm.getCursor('to');
        } else {    //没有选中
            curPoint = cm.getCursor();
        }
        if (stat.bold) {
            if(curPoint || cm.getRange({line: startPoint.line, ch: startPoint.ch - 2} ,startPoint) !== '**' || cm.getRange(endPoint, {line: endPoint.line, ch: endPoint.ch + 2}) !== '**') {    // 没有选中或选中的不全
                return;
            } else {
                text = cm.getSelection();
                startPoint.ch -= 2;
                endPoint.ch += 2;
                cm.replaceRange(text,
                    startPoint,
                    endPoint
                );
                endPoint.ch -= 4;
            }
        } else {
            var _text = cm.getSelection() || text;
            cm.replaceSelection(start + _text + end);
            if(curPoint) {
                startPoint = {line: curPoint.line, ch: curPoint.ch + 2};
                endPoint = {line: curPoint.line, ch: curPoint.ch + 6};
            } else {
                startPoint.ch += 2;
                endPoint.ch += 2;
            }
        }
        cm.setSelection(startPoint, endPoint);
        cm.focus();
    }


    /**
     * Action for toggling italic.
     */
    function toggleItalic(editor) {
        var cm = editor.codemirror;
        var stat = getState(cm);

        var text = '斜体文字';
        var start = '*';
        var end = '*';

        var startPoint, endPoint,curPoint;
        if(cm.getSelection()) {   // 有选中
            startPoint = cm.getCursor('from');
            endPoint = cm.getCursor('to');
        } else {    //没有选中
            curPoint = cm.getCursor();
        }
        if (stat.italic) {
            if(curPoint || cm.getRange({line: startPoint.line, ch: startPoint.ch - 1} ,startPoint) !== '*' || cm.getRange(endPoint, {line: endPoint.line, ch: endPoint.ch + 1}) !== '*') {    // 没有选中或选中的不全
                return;
            } else {
                text = cm.getSelection();
                startPoint.ch -= 1;
                endPoint.ch += 1;
                cm.replaceRange(text,
                    startPoint,
                    endPoint
                );
                endPoint.ch -= 2;
            }
        } else {
            var _text = cm.getSelection() || text;
            cm.replaceSelection(start + _text + end);
            if(curPoint) {
                startPoint = {line: curPoint.line, ch: curPoint.ch + 1};
                endPoint = {line: curPoint.line, ch: curPoint.ch + 5};
            } else {
                startPoint.ch += 1;
                endPoint.ch += 1;
            }
        }
        cm.setSelection(startPoint, endPoint);
        cm.focus();
    }

    /**
     * Action for toggling blockquote.
     */
    function toggleBlockquote(editor) {
        var cm = editor.codemirror;
        _toggleLine(cm, 'quote');
    }


    /**
     * Action for toggling blockcode.
     */
    function toggleBlockcode(editor) {
        var cm = editor.codemirror;
        if(cm.somethingSelected()) {    //如果有选择就加```   ```
            var code = cm.getSelection();
            var ncode = '\n```\n' + code + '\n```\n';
            cm.replaceSelection(ncode);
        } else {        //没选择就加`请输入代码`
            var cursorCurrent = cm.getCursor();
            cm.replaceRange('`请输入代码`', cursorCurrent);
            cm.setSelection(
                { line: cursorCurrent.line, ch: cursorCurrent.ch + 1},
                { line: cursorCurrent.line, ch: cursorCurrent.ch + 6}
            );
        }
        cm.focus();
    }


    /**
     * Action for toggling ul.
     */
    function toggleUnOrderedList(editor) {
        var cm = editor.codemirror;
        _toggleLine(cm, 'unordered-list');
    }


    /**
     * Action for toggling ol.
     */
    function toggleOrderedList(editor) {
        var cm = editor.codemirror;
        _toggleLine(cm, 'ordered-list');
    }

    /**
     * Action for toggling title.
     */
    function toggleTitle(editor) {
        var cm = editor.codemirror;
        var stat = getState(cm);
        var text = '标题文字';
        var start = '##';
        var end = '##';

        var startPoint, endPoint,curPoint;
        if(cm.getSelection()) {   // 有选中
            startPoint = cm.getCursor('from');
            endPoint = cm.getCursor('to');
        } else {    //没有选中
            curPoint = cm.getCursor();
        }
        if (!curPoint && cm.getRange({line: startPoint.line, ch: startPoint.ch - 2} ,startPoint) === '##' && cm.getRange(endPoint, {line: endPoint.line, ch: endPoint.ch + 2}) === '##') {
            if(curPoint) {    // 没有选中或选中的不全
                return;
            } else {
                text = cm.getSelection();
                startPoint.ch -= 2;
                endPoint.ch += 2;
                cm.replaceRange(text,
                    startPoint,
                    endPoint
                );
                endPoint.ch -= 4;
            }
        } else {
            var _text = cm.getSelection() || text;
            cm.replaceSelection(start + _text + end);
            if(curPoint) {
                startPoint = {line: curPoint.line, ch: curPoint.ch + 2};
                endPoint = {line: curPoint.line, ch: curPoint.ch + 6};
            } else {
                startPoint.ch += 2;
                endPoint.ch += 2;
            }
        }
        cm.setSelection(startPoint, endPoint);
        cm.focus();
    }


    //     var startPoint = cm.getCursor('from');
    //     var endPoint = cm.getCursor('to');
    //     if (stat.bold) {
    //         text = cm.getLine(startPoint.line);
    //         start = text.slice(0, startPoint.ch);
    //         end = text.slice(startPoint.ch);
    //
    //         start = start.replace(/^(.*)?(#|\_){2}(\S+.*)?$/, '$1$3');
    //         end = end.replace(/^(.*\S+)?(#|\_){2}(\s+.*)?$/, '$1$3');
    //         startPoint.ch -= 2;
    //         endPoint.ch -= 2;
    //         //cm.setLine(startPoint.line, start + end);  新版CM去掉了setLine方法
    //         cm.replaceRange(start + end,
    //             {line: startPoint.line, ch: 0},
    //             {line: startPoint.line + 1, ch: 0}
    //         );
    //     } else {
    //         if(cm.somethingSelected()) {
    //             text = cm.getSelection();
    //             cm.replaceSelection(start + text + end);
    //             startPoint.ch += 2;
    //             endPoint.ch += 2;
    //         } else {
    //             cm.replaceSelection(start + '标题文字' + end);
    //             var cursor = cm.getCursor();
    //             startPoint = {line: cursor.line, ch: cursor.ch - 6};
    //             endPoint = {line: cursor.line, ch: cursor.ch - 2};
    //         }
    //     }
    //     cm.setSelection(startPoint, endPoint);
    //     cm.focus();
    // }


    /**
     * Action for toggling ol.
     */
    function drawHr(editor) {
        var cm = editor.codemirror;
        var cursor = cm.getCursor();
        cm.replaceRange('\n----------\n', cursor);
        cm.setCursor({line: cursor.line + 2, ch: 0});
        cm.focus();
    }


    /**
     * Action for drawing a link.
     */
    function drawLink(editor) {
        var cm = editor.codemirror;
        var _isFull = document.isfullScreen || document.mozFullScreen || document.webkitIsFullScreen;
        sfModal({
            title: '插入链接',
            content: '<input type="text" id="editorLinkText" class="form-control text-28" placeholder="请输入链接地址">',
            closeText: '取消',
            wrapper: _isFull ? '.editor' : null,
            doneText: '插入',
            doneFn: function() {
                var startCursor = cm.getCursor('from');    //光标位置
                var endCursor = cm.getCursor('to');    //选择结束位置
                var selectText = cm.getSelection();    //当前选中的文本
                var link = $('#editorLinkText').val();    //插入的链接地址
                var lastLine = cm.getLine(cm.lineCount() - 1);    //最后一行的内容
                var reg = /^\s*\[(\d+)\]:/;    //获取最后一行计数值的正则
                var regResult = reg.exec(lastLine);    //缓存正则结果
                var i = 1;    //计数默认为1
                var replaceText = '[' + selectText + ']' + '[' + i + ']';
                var tailText = '\n\n  [' + i + ']: ' + link;    //最后追加
                if (regResult) {    //有插入过链接或图片
                    i = parseInt(regResult[1]) + 1;
                    tailText = '\n  [' + i + ']: ' + link;    //默认追加
                    replaceText = '[' + selectText + ']' + '[' + i + ']';
                }
                // 插入text
                cm.replaceSelection(replaceText);
                var content = cm.getValue();
                cm.setValue(content + tailText);
                sfModal('hide');
                cm.focus();
                if(!selectText) {
                    cm.setCursor({
                        line: startCursor.line,
                        ch: startCursor.ch + 1
                    });
                } else {
                    cm.setSelection({
                        line: startCursor.line,
                        ch: startCursor.ch +1
                    },{
                        line: endCursor.line,
                        ch: endCursor.ch +1
                    });
                }
            }
        });
    }


    /**
     * Action for drawing an img.
     */
    function drawImage(editor) {
        var cm = editor.codemirror;
        var imgLink = '';  //最后获取到的图片地址
        var _fileName = '';      //图片的文件名
        var _isFull = document.isfullScreen || document.mozFullScreen || document.webkitIsFullScreen;
        function insertPic() {
            var startCursor = cm.getCursor('from');    //光标位置
            var endCursor = cm.getCursor('to');    //选择结束位置
            var selectText = cm.getSelection() || _fileName;    //当前选中的文本
            var lastLine = cm.getLine(cm.lineCount() - 1);    //最后一行的内容
            var reg = /^\s*\[(\d+)\]:/;    //获取最后一行计数值的正则
            var regResult = reg.exec(lastLine);    //缓存正则结果
            //var i = 1;    //计数默认为1
            var i = Math.random();    //计数默认为1
            var replaceText = '![' + selectText + ']' + '[' + i + ']';
            var tailText = '\n\n  [' + i + ']: ' + imgLink;    //最后追加
            if (regResult) {    //有插入过链接或图片
                i = parseInt(regResult[1]) + 1;
                tailText = '\n  [' + i + ']: ' + imgLink;    //默认追加
                replaceText = '![' + selectText + ']' + '[' + i + ']';
            }
            // 插入text
            cm.replaceSelection(replaceText);
            var content = cm.getValue();
            cm.setValue(content + tailText);
            sfModal('hide');
            cm.focus();
            if(!selectText) {
                cm.setCursor({
                    line: startCursor.line,
                    ch: startCursor.ch + 2
                });
            } else {
                cm.setSelection({
                    line: startCursor.line,
                    ch: startCursor.ch + 2
                },{
                    line: endCursor.line,
                    ch: endCursor.ch + 2
                });
            }
        }
        sfModal({
            title: '插入图片',
            content: '<ul class="nav nav-tabs" role="tablist">\
    <li class="active"><a href="#localPic" role="tab" data-toggle="tab">本地上传</a></li>\
    <li><a href="#remotePic" role="tab" data-toggle="tab">远程地址获取</a></li>\
</ul>\
<div class="tab-content">\
    <div class="tab-pane fade in active pt20 form-horizontal" id="localPic">\
        <span class="text-muted">图片体积不得大于 4 MB</span>\
        <br>\
        <div class="widget-upload form-group">\
        <input type="file" id="editorUpload" name="image" class="widget-upload__file">\
        <div class="col-sm-8">\
        <input type="text" id="fileName" class="form-control col-sm-10 widget-upload__text" placeholder="拖动图片到这里" readonly />\
        </div>\
        <a href="javascript:void(0);" class="btn col-sm-2 btn-default">选择图片</a>\
        </div>\
    </div>\
    <div class="tab-pane fade pt20" id="remotePic">\
    <input type="url" name="img" id="remotePicUrl" class="form-control text-28" placeholder="请输入图片所在网址">\
    </div>\
</div>',
            closeText: '取消',
            doneText: '插入',
            wrapper: _isFull ? '.editor' : null,
            show: function() {
                // fileupload
                $('#editorUpload').fileUpload({
                    url: Editor.prototype.uploadPath,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        // 下面这句神奇的话会影响到拖动图片上传的成功与否，有待研究
                        // 这句话在拖动图片上传时会报错，但是不影响功能。任何修改都有很大可能导致此功能失败。
                        _fileName = $('#editorUpload').val()
                            //.split('akepath')[1].slice(1);
                            .split('akepath').slice(1);
                        //
                        $('#fileName').val(_fileName).addClass('loading');
                        $('.done-btn').attr('disabled', 'disabled');
                    },
                    complete: function () {
                        $('#fileName').removeClass('loading');
                        $('.done-btn').attr('disabled', false).click();
                    },
                    success: function (result) {
                        var status = result.status;
                        var message = result.message;
                        var url = result.url;
                        if(status != 0) {
                            sfModal(message);    // 坏味道 用于转义
                        } else {
                            var data = url;
                            imgLink = data;
                        }
                    }
                });
            },
            doneFn: function(e) {
                e.preventDefault();
                //远程图片
                if($('#remotePic').hasClass('active') && $('#remotePicUrl').val()) {
                    $('#remotePicUrl').addClass('loading');
                    $('.done-btn').attr('disabled', 'disabled');
                    imgLink = $('#remotePicUrl').val();
                    insertPic();
                } else {
                    insertPic();
                }
            }
        });
    }

    /**
     * Undo action.
     */
    function undo(editor) {
        var cm = editor.codemirror;
        cm.undo();
        cm.focus();
    }


    /**
     * Redo action.
     */
    function redo(editor) {
        var cm = editor.codemirror;
        cm.redo();
        cm.focus();
    }

    /**
     * Preview action.
     */
    var lastText = '';
    function goPreview(editor) {
        $('.CodeMirror-code').css('width', '100%');
        var cm = editor.codemirror;
        var wrapper = cm.getWrapperElement();
        var preview = wrapper.lastChild;
        $('.editor-preview').removeClass('onlive');     //清掉onlive
        $('.editor__menu--live').removeClass('muted');
        if (!/editor-preview/.test(preview.className)) {
            preview = document.createElement('div');    //创建preview的div
            preview.className = 'editor-preview fmt';
            wrapper.appendChild(preview);
            //变灰预览
            $('.editor__menu--preview').addClass('muted');
            $('.editor__menu li:lt(17)').addClass('invisible'); //预览时隐藏操作按钮
        }
        var text = cm.getValue();
        if(text === lastText) {
             setTimeout(function() {
                 $(preview).addClass('editor-preview-active')
             }, 1);
        } else {
            preview.innerHTML = marked(text);
            highLight($(preview));
                    /* When the preview button is clicked for the first time,
                     * give some time for the transition from editor.css to fire and the view to slide from right to left,
                     * instead of just appearing.
                     */
            setTimeout(function() {
                $(preview).addClass('editor-preview-active')
            }, 1);
        }
        $('.editor__menu--edit').removeClass('muted');
        $('.editor__menu--preview').addClass('muted');
        $('.editor__menu li:lt(17)').addClass('invisible');
        lastText = text;
    }

    /**
     * 编辑模式.
     */
    function goEdit(editor) {
        $('.CodeMirror-code').css('width', '100%');
        var cm = editor.codemirror;
        var wrapper = cm.getWrapperElement();
        var preview = wrapper.lastChild;
        $('.editor-preview').removeClass('onlive');     //清掉onlive
        $('.editor__menu--live').removeClass('muted');
        preview.className = preview.className.replace(
            /\s*editor-preview-active\s*/g, ''
        );
        $('.editor__menu--edit').addClass('muted');
        $('.editor__menu--preview').removeClass('muted');
        $('.editor__menu li:lt(17)').removeClass('invisible');
    }

    /**
     * 实况模式.
     */
    function goLive(editor) {
        var cm = editor.codemirror;
        var wrapper = cm.getWrapperElement();
        var preview = wrapper.lastChild;
        $('.editor__menu--edit, .editor__menu--preview').removeClass('muted');
        //变灰live
        $('.editor__menu--live').addClass('muted');
        if (!/editor-preview/.test(preview.className)) {    //没有preview过
            preview = document.createElement('div');    //创建preview的div
            preview.className = 'editor-preview fmt';
            wrapper.appendChild(preview);
        }
        var text = cm.getValue();
        var _w = $('.CodeMirror-code').width() / 2 - 15 + 'px';
        if(text === lastText) {
            setTimeout(function() {
                $(preview).addClass('editor-preview-active onlive');
                $('.CodeMirror-code').css('width', _w);
            }, 1);
        } else {
            preview.innerHTML = marked(text);
            setTimeout(function() {
                $(preview).addClass('editor-preview-active onlive');
                // var _w = $('.CodeMirror-code').width() / 2 - 15 + 'px';
                $('.CodeMirror-code').css('width', _w);
                highLight($(preview));
            }, 1);
        }
        lastText = text;
        $('.editor__menu li:lt(17)').removeClass('invisible');    //显示菜单按钮
    }


    function _replaceSelection(cm, active, start, end) {
        var text;
        var startPoint = cm.getCursor('start');
        var endPoint = cm.getCursor('end');
        if (active) {
            text = cm.getLine(startPoint.line);
            start = text.slice(0, startPoint.ch);
            end = text.slice(startPoint.ch);
            cm.replaceRange( start + end, {line: startPoint.line, ch: 0}, {line: startPoint.line + 1, ch: 0});
        } else {
            text = cm.getSelection();
            cm.replaceSelection(start + text + end);

            startPoint.ch += start.length;
            endPoint.ch += start.length;
        }
        cm.setSelection(startPoint, endPoint);
        cm.focus();
    }


    function _toggleLine(cm, name) {
        var stat = getState(cm);
        var startPoint = cm.getCursor('start');
        var endPoint = cm.getCursor('end');
        var repl = {
            quote: /^(\s*)\>\s+/,
            code: /^(\s*)```\n\s+/,
            header: /^(\s*)##/,
            'unordered-list': /^(\s*)(\*|\-|\+)\s+/,
            'ordered-list': /^(\s*)\d+\.\s+/
        };
        var map = {
            quote: '> ',
            code: '```\n\n```',
            header: '##标题##',
            'unordered-list': '* ',
            'ordered-list': '1. '
        };
        for (var i = startPoint.line; i <= endPoint.line; i++) {
            (function(i) {
                var text = cm.getLine(i);
                if (stat[name]) {
                    text = text.replace(repl[name], '$1');
                } else {
                    text = map[name] + text;
                }
                cm.replaceRange(text, {line: i, ch: 0}, {line: i+1, ch: 0});
            })(i);
        }
        cm.focus();
    }


    /* The right word count in respect for CJK. */
    function wordCount(data) {
        var pattern = /[a-zA-Z0-9_\u0392-\u03c9]+|[\u4E00-\u9FFF\u3400-\u4dbf\uf900-\ufaff\u3040-\u309f\uac00-\ud7af]+/g;
        var m = data.match(pattern);
        var count = 0;
        if (m === null) {
            return count;
        }
        for (var i = 0; i < m.length; i++) {
            if (m[i].charCodeAt(0) >= 0x4E00) {
                count += m[i].length;
            } else {
                count += 1;
            }
        }
        return count;
    }

    return Editor;
});

'use strict';

function temp(template, data) {
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

function sfModal(option) {
    if(typeof option !== 'object') {
        if(option === 'hide') {
            $('.sfmodal').modal('hide');
            return;
        } else if(option === 'toggle') {
            $('.sfmodal').modal('toggle');
            return;
        } else {
            option = {
                content : option,
                hideDone : true,
            };
        }
    }

    var OPT = $.extend({
        hideTitle  : false,
        title      : '警告：前方高能！',
        content    : '玩脱了',
        wrapper      : null,      //编辑器全屏时不能显示modal
        $content   : null,
        hideClose  : false,
        closeText  : '取消',
        // closeFn : function() {},
        hideDone   : false,
        doneText   : '确认',
        doneFn     : function() {
            $('.sfmodal').modal('hide');
        },
        show       : function() {},
        // 不明原因shown不触发
        shown      : function() {},
        hide       : function() {},
        hidden     : function() {},
        loaded     : function() {}
    }, option);

    var dom = '<div class="sfmodal modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">\
  <div class="modal-dialog">\
    <div class="modal-content">\
      '+(OPT.hideTitle ? '' : '<div class="modal-header">\
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\
        <h4 class="modal-title">{{title}}</h4>\
      </div>')+'<div class="modal-body">\
        <p class="sfModal-content">\
          </div>\
          <div class="modal-footer">'
            + (OPT.hideClose ? '' : '<button type="button" class="btn btn-default" data-dismiss="modal">{{closeText}}</button>')
            + (OPT.hideDone ? '' : '<button type="button" class="btn btn-primary done-btn">{{doneText}}</button>')+'</div>\
        </div>\
      </div>\
    </div>';

    // 删掉已经存在的modal
    if ($('.sfmodal').length > 0) {
        $('.sfmodal').remove();
        $('.modal-backdrop').remove();
    }
    // 有$wrap时插到$wrap里面
    if(OPT.wrapper) {
        $(OPT.wrapper).append(temp(dom, OPT));
        $(OPT.wrapper).append('<div class="modal-backdrop in"></div>');
    } else {
        $('body').append(temp(dom, OPT));
    }
    if(OPT.$content) {      // 优先使用$content
        $('.sfmodal .sfModal-content').append(OPT.$content);
    } else {
        $('.sfmodal .sfModal-content').html(OPT.content);
    }
    $('.sfmodal').modal({keyboard: true});
    $('.sfmodal')
        .on('show.bs.modal'  , OPT.show)
        .on('shown.bs.modal' , OPT.shown)
        .on('hide.bs.modal'  , function(e) {
            OPT.hide(e);
            if(OPT.wrapper) {
                $('.modal-backdrop').remove();
            }
        })
        .on('hidden.bs.modal', OPT.hidden)
        .on('loaded.bs.modal', OPT.loaded)
        .modal('show');     // 一定要先绑事件，然后再show
    $('.sfmodal .done-btn').click(function(e) {
        OPT.doneFn(e);
        if(OPT.wrapper) {
            $('.modal-backdrop').remove();
        }
    });
};

;(function($, window, document, undefined) {
    var HwLayer = function(ele, opt){
        var self = this;
        self.$element = ele,
        self.defaults = {
            width: 600,
            height: 'auto',
            tapLayer: false,  
            afterClose: function(){}
        },
        self.locked = false,
        self.options = $.extend({}, self.defaults, opt)
    }
    HwLayer.prototype = {
        init: function(){
            var self = this,
                ele = self.$element;

            //点击弹出层
            ele.on('click',function(e){
                e.preventDefault();
                self.showLayer();
            });

            $('.hwLayer-cancel,.hwLayer-close').on('click', function() {
               self.hideLayer();
            });
        },
        //展示弹出层
        showLayer: function(){ 
            var self = this;
            var layerid = self.$element.data('show-layer'); //获取层ID
			
            if(layerid == '' || layerid == undefined){
				//layerid = self.$element[0].id; //自动检测弹出层id
                var msg = '没有设置弹出层内容！';
                //console.log(msg);
                alert(msg);
            }else{
                var layer = $('#'+layerid),
                layerwrap = layer.find('.hw-layer-wrap');
                layer.fadeIn();

                //屏幕居中
                var layer_w,layer_h;
                var w = $(window).width();
                if(w<768){
                    layer_w = w-30;
                }else{
                    layer_w = self.options.width;
                }

                var h = $(window).height();
                if(layerwrap.outerHeight()>h){
                    layer_h = h-40;
                    layerwrap.css('overflow-y','auto');
                }else{
                    if(self.options.height=='auto'){
                        layer_h = layerwrap.outerHeight();
                    }else{
                        layer_h = self.options.height;
                    }
                }

                layerwrap.css({
                    'width': layer_w+'px',
                    'height': layer_h+'px',
                    'margin-top': -layer_h/2+'px',
                    'margin-left': -layer_w/2+'px'
                });
                 //点击或者触控弹出层外的半透明遮罩层，关闭弹出层
                // layer.on('click',  function(event) {
                //     if (event.target == this && self.options.tapLayer == true){
                //         self.hideLayer();
                //     }
                // });
            }

        },
        //隐藏弹出层
        hideLayer: function(){
            var self = this;
            $('.hw-overlay').fadeOut();
            self.options.afterClose.call(self);
        }
    }
    

    $.fn.hwLayer = function(options) {
        var hwLayer = new HwLayer(this, options);
        return this.each(function () {
            if (typeof(options) == 'string') {
                switch(options){
                     case 'close':
                        hwLayer.hideLayer();
                         break;
				// 	//case 'open':
				// 	//	hwLayer.showLayer();
				// 	//	break;
               }
            }else{
                hwLayer.init();
            }
        });
    };
})(jQuery, window, document);
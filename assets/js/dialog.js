var utwin =function(){ 
 return {
   open:function(id,txt=''){     
     var obj = $("#"+id);
     $("#mask-"+id).fadeIn(300);
     obj.addClass("utwin-ani-open");
     var height = obj.height();
     obj.css("margin-top", "-"+Math.ceil(height/2)+'px');  
     if(txt!=''){   
       obj.find(".utwin-text").html(txt);  
	   obj.find("#utwin-text").val(txt);
     }  
     obj.show();
     setTimeout(res=>{
      obj.removeClass("utwin-ani-open"); 
     },300)
   },
			alert:function(id,txt='',callback){
						this.open(id,txt); 
						let that = this;
						$("#"+id).find(".utwin-btn button").click(function(){  
										that.close(id);     
										if(typeof(callback)!='undefined'){ callback(); }
						})     
			},
   tips:function(id,txt='',callback,time=1600){
    this.open(id,txt); 
    let that = this;
    setTimeout(res=>{  
     that.close(id);    
     if(typeof(callback)!='undefined'){ callback(); }
    },time)     
  },
   confirm:function(id,txt='',confirm,concel){
      this.open(id,txt); 
      $("#"+id).find(".utwin-btn button").click(function(){
         utwin.close(id);
         if($(this).attr("class")=="ok"){           
          confirm();
         }else{
          concel();
         }
      })     
   },
   model:function(id){
      this.open(id); 
   },
  loading:function(id,txt){   
    this.open('loading',txt);
  },
  hideLoading(id,callback){
   this.close(id);
   if(typeof(callback)!='undefined'){ callback(); }
  },
  success:function(id,txt,callback,time=1600){
   this.open(id,txt);
   let that = this;
   setTimeout(res=>{  
     that.close(id);    
     if(typeof(callback)!='undefined'){ callback(); }
   },time)
  },
  error:function(id,txt,callback,time=1600){
   this.open(id,txt);
   let that = this;
   setTimeout(res=>{  
     that.close(id);    
     if(typeof(callback)!='undefined'){ callback(); }
   },time)
  },
  close:function(id){    
   var obj = $("#"+id);
   $("#mask-"+id).fadeOut(200);
   obj.addClass("utwin-ani-close");   
   setTimeout(res=>{
    obj.hide();
    obj.removeClass("utwin-ani-close"); 
   },300)
 },
 closeAll(){
    $(".utwin-mask").fadeOut(200);
    $(".utwin").addClass("utwin-ani-close");   
    setTimeout(res=>{
     $(".utwin").hide();
     $(".utwin").removeClass("utwin-ani-close"); 
    },300)
 }
  
 }
}();
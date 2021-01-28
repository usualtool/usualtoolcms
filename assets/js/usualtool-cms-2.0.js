/*Ajax Database:V2.0*/
/*HuangDou*/
/*QQ:292951110*/
/*UsualTool.com*/
$(function() {
    $('.M').hover(function() {
        $(this).addClass('active');
    },
    function() {
        $(this).removeClass('active');
    });
});
function selectcheckbox(form) {
    for (var i = 0; i < form.elements.length; i++) {
        var e = form.elements[i];
        if (e.name != 'chkall' && e.disabled != true) e.checked = form.chkall.checked;
    }
}
function Display(target, action) {
    var traget = document.getElementById(target);
    if (action == 'show') {
        traget.style.display = 'block';
    } else {
        traget.style.display = 'none';
    }
}
function  navclick(navid){
	var navid = navid;
	var name="navleft";
	SetCookie(name,navid);
	location.reload();
}
function  SetCookie(name,value){ 
	var Days = 3;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function delmatter(n){
	var obj = document.getElementById(""+n+"");
	obj.parentNode.removeChild(obj);
}
function delimg(o){  
	var src = $(o).next().attr("value");
	$.ajax({
		type: "POST",
		url: "ut-upload-all.php",
		data: "get=delimg&imgurl="+src+"",
		success: function(msg){
			$(o).parent().remove();
			console.log("success!");
		}
	});
}
function doupload(tdk,name){
	var tdk;
	var name;
	var formData = new FormData($("#form"+tdk+"")[0]);   
	formData.append("l",".");
	$.ajax({  
	url: 'ut-upload.php' ,  
	type: 'POST',  
	data: formData,  
	async: false,  
	cache: false,  
	contentType: false,  
	processData: false,  
	success: function(returndata) {  
		datas=returndata.split("|-|");
		document.getElementById(""+name+"").value=""+datas[1]+"";
		document.getElementById(""+name+"html").innerHTML=""+datas[0]+"<img src='"+datas[1]+"' width=80 height=80>";
	},  
	error: function (returndata) {  
	document.getElementById(""+name+"").value="";
	document.getElementById(""+name+"html").innerHTML="Upload Error!";
	}  
	});  
}
function modupload(tdk,name,folder='',url=''){
	var tdk;
	var name;
	var folder;
	var url;  
	var formData = new FormData();
	formData.append("file",document.getElementById(""+tdk+"").files[0]);
	formData.append("l",folder);
	$.ajax({
	url: 'ut-upload.php' ,  
	type: 'POST',  
	data: formData,  
	async: false,  
	cache: false,  
	contentType: false,  
	processData: false,  
	success: function(returndata) {  
		datas=returndata.split("|-|");
		document.getElementById(""+name+"").value=""+datas[1]+"";
	},  
	error: function (returndata) {  
	document.getElementById(""+name+"").value="Upload Error!";
	}  
	});  
}
function randomnumber(){
	var outTradeNo="";
	for(var i=0;i<2;i++){
	outTradeNo += Math.floor(Math.random()*4);
	}
	outTradeNo = new Date().getTime() + outTradeNo; 
	return outTradeNo;
}
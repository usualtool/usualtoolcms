/*UTCMS 2018年11月12日预埋语言组*/
/*UTCMS适应版本8.0,否则回报错*/
/*UTCMS本地化版本2.0*/
var name = "UTCMSLanguage";
if(window.ROOTPATH=='' || window.ROOTPATH==undefined || window.ROOTPATH==null){
    var jsonweb='';
}else{
	var jsonweb=""+window.ROOTPATH+"/";
	}
function changeLang(){
	var value = $("#utcmslanguage").children('option:selected').val();
	if(value=="big5"){
		SetCookie(name,"zh");
		SetCookie("chinaspeak","big5");
	}else{
		SetCookie(name,value);
		SetCookie("chinaspeak","");
	}
	location.reload();
}
function clickLang(lang){
	var lang = lang;
	if(lang=="big5"){
		SetCookie(name,"zh");
		SetCookie("chinaspeak","big5");
	}else{
		SetCookie(name,lang);
		SetCookie("chinaspeak","");
	}
	location.reload();
}
function SetCookie(name,value){ 
	var Days = 30;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function transbig(){document.body.innerHTML = document.body.innerHTML.transbig();}
String.prototype.transbig=function(){
	htmlobj=$.ajax({url:jsonweb+"lang/lg-"+getCookie("chinaspeak")+".json",async:false});
	var zhjsondata=(htmlobj.responseText);
	var obj = eval("("+zhjsondata+")");
	var s=obj["l"]["simplified"];
	var t=obj["l"]["traditional"];
	var k='';
	for(var i=0;i<this.length;i++) k+=(s.indexOf(this.charAt(i))==-1)?this.charAt(i):t.charAt(s.indexOf(this.charAt(i)))
	return k;
}
function JsonStr(word){
	var word;
	$.ajax({
		url:jsonweb+"lang/lg-"+getCookie(""+name+"")+".json",
		success:function(jsondata){
			var data = eval(jsondata);
			alert(data["l"][word]);
		}
	});
}
function getCookie(name){
var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
if(arr != null) return unescape(arr[2]); return null
}
(function($,undefined){
$(document).ready(function(){
var mylanguage = (navigator.language || navigator.browserLanguage).toLowerCase();
if (getCookie(name) != ""){
	if (getCookie(name) == "zh" && getCookie("chinaspeak") == "big5"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "zh"});transbig();}
	else if(getCookie(name) == "ja"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ja"});}
	else if(getCookie(name) == "en"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "en"});}
	else if(getCookie(name) == "ko"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ko"});}
	else if(getCookie(name) == "ru"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ru"});}
	else if(getCookie(name) == "fr"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "fr"});}
	else if(getCookie(name) == "bo"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "bo"});}
	else if(getCookie(name) == "ug"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ug"});}
	else if(getCookie(name) == "de"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "de"});}
	else if(getCookie(name) == "ar"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ar"});}
	else if(getCookie(name) == "pt"){$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "pt"});}
	else{$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "zh"});}
}else{
	if (mylanguage.indexOf("zh") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "zh"});}
	else if(mylanguage.indexOf("tw") > -1 || mylanguage.indexOf("hk") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "zh"});transbig();}
	else if(mylanguage.indexOf("en") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "en"});}
	else if(mylanguage.indexOf("ja") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ja"});}
	else if(mylanguage.indexOf("ko") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ko"});}
	else if(mylanguage.indexOf("ru") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ru"});}
	else if(mylanguage.indexOf("fr") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "fr"});}
	else if(mylanguage.indexOf("ug") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ug"});}
	else if(mylanguage.indexOf("de") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "de"});}
	else if(mylanguage.indexOf("ar") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "ar"});}
	else if(mylanguage.indexOf("pt") > -1) {$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "pt"});}
	else{$("[data-localize]").localize("lg", {pathPrefix: "lang", language: "zh"});}
}
}); 
})(jQuery);
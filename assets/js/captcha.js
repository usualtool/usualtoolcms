var captcha;
window.onload =function showCode(){lookCode();}
function lookCode(){
	captcha="";
	var codeLength= 4;
	var checkCode=document.getElementById("captcha");
	var random=new Array(0,1,2,3,4,5,6,7,8,9);
	for(var i=0;i<codeLength; i++) {
		var index=Math.floor(Math.random()*10);
		captcha+=random[index];
	}
	document.getElementById("captcha").innerHTML=captcha.toUpperCase();
}
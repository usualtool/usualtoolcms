function isEmail(str){  
	var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/; 
	if(myReg.test(str)) return true; 
	return false; 
}
function Regform(){
	if(formreg.username.value==""){
		JsonStr('enterusername');
		return false;
	}
	if(formreg.password.value==""){
		JsonStr('enterpassword');
		return false;  
	}
	if(formreg.passwords.value==""){
		JsonStr('enterpasswords');
		return false;  
	}
	if(formreg.password.value!==formreg.passwords.value){
		JsonStr('passworderr');
		return false;  
	}
	if(formreg.uemail.value==""){
		JsonStr('enteremail');
		return false;  
	}
	if(isEmail(formreg.uemail.value)==false){
		JsonStr('emailerr');
		return false;
	}
	if(formreg.telephone.value==""){
		JsonStr('entertelephone');
		return false;  
	}
	if(formreg.captchas.value==""){
		JsonStr('entercaptcha');
		return false;
	}
	if(formreg.captchas.value.toUpperCase()!=captcha){
		JsonStr('captchaerr');
		lookCode();
		return false;
	}
}
function Loginform(){
	if(formlogin.username.value==""){
		JsonStr('enterusername');
		return false;
	}
	if(formlogin.password.value==""){
		JsonStr('enterpassword');
		return false;  
	}
	if(formlogin.captchas.value==""){
		JsonStr('entercaptcha');
		return false;
	}
	if(formlogin.captchas.value.toUpperCase()!=captcha){
		JsonStr('captchaerr');
		lookCode();
		return false;
	}
}
function Contactform(){
	if(formcontact.qname.value==""){
		JsonStr('enteremail');
		return false;
	}
	if(isEmail(formcontact.qname.value)==false){
		JsonStr('emailerr');
		return false;
	}
	if(formcontact.title.value==""){
		JsonStr('entertitle');
		return false;  
	}
	if(formcontact.question.value==""){
		JsonStr('entercontent');
		return false;  
	}
}
function Mssform(){
	if(formmss.type.value==""){
		JsonStr('selecttype');
		return false;
	}
	if(formmss.author.value==""){
		JsonStr('enterauthor');
		return false;
	}
	if(formmss.title.value==""){
		JsonStr('entertitle');
		return false;  
	}
}
function Infoform(){
	if(formmss.tel.value==""){
		JsonStr('entertelephone');
		return false;
	}
	if(formmss.title.value==""){
		JsonStr('entertitle');
		return false;  
	}
}
<?php
class UsualToolWeChat{
    var $appline;
    var $appid;
    var $appsecret;
    var $token;
    function __construct($appline,$appid,$appsecret,$token){
        $this->appline=$appline;
        $this->appid=$appid;
        $this->appsecret=$appsecret;
        $this->token=$token;
    }
    function getToken(){
        $file = file_get_contents(ROOT_PATH."/jsonapi/token.json",true);
        $result = json_decode($file,true);
        if(time() > $result['expires']):
            $data = array();
            $data['access_token'] = $this->getNewToken();
            $data['expires']=time()+7000;
            $jsonStr =  json_encode($data);
            $fp = fopen(ROOT_PATH."/jsonapi/token.json","w");
            fwrite($fp, $jsonStr);
            fclose($fp);
            return $data['access_token'];
        else:
            return $result['access_token'];
        endif;
    }
    function getNewToken(){
        $url = "https://{$this->appline}/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $access_token_Arr =  $this->getData($url);
        return $access_token_Arr['access_token'];
    }
    function getData($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        curl_close($ch);
        return json_decode($out,true);
    }
    function postData($url,$data = null){         
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
        if(!empty($data)){  
            curl_setopt($ch, CURLOPT_POST, TRUE);  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
        }  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        $output = curl_exec($ch);  
        curl_close($ch);  
        return $output=json_decode($output,true);            
    } 
    function getUserInfo(){
        $url = "https://{$this->appline}/cgi-bin/user/get?access_token={$this->getToken()}";
        $res = $this->getData($url);
        $userInfoList = $res['data']['openid'];
        return $userInfoList;
    }
    function sendMsgToAll($pushtitle,$pushcontent,$pushlink,$pushpic){
        $userInfoList = $this->getUserInfo();
        $url = "https://{$this->appline}/cgi-bin/message/custom/send?access_token={$this->getToken()}";
        foreach($userInfoList as $val){
            $data = '{
            "touser":"'.$val.'",
            "msgtype":"link",
            "link":{
            "title":"'.$pushtitle.'",
            "description":"'.$pushcontent.'",
            "url":"'.$pushlink.'",
            "thumb_url": "'.$pushpic.'"
            }';
            $this->postData($url,$data);
        }
    }
    function delMenu(){
        $url = "https://{$this->appline}/cgi-bin/menu/delete?access_token={$this->getToken()}";
        $res = $this->getData($url);
        $rerult = $res['errmsg'];
        return $rerult;
    }
    function creatMenu($data){
        $url = "https://{$this->appline}/cgi-bin/menu/create?access_token={$this->getToken()}";
        $res = $this->postData($url,$data);
        $rerult=$res['errmsg'];
        return $rerult;
    }
    public function valid(){
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
        exit;
        }
    }
    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    public function responseMsg(){
        include(ROOT_PATH.'/'.'sql_db.php');
        $postStr = file_get_contents("php://input");
        if(!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            $msgname=$postObj->FromUserName;
            $msgtype=$RX_TYPE;
            if($msgtype=="event"):
                if($postObj->Event=="subscribe"):
                $msgcontent="已关注公众号。";
                endif;
            elseif($msgtype=="text"):
                $msgcontent=$postObj->Content;
            elseif($msgtype=="image"):
                $msgcontent=$postObj->PicUrl;
            elseif($msgtype=="voice"):
                $msgcontent=$postObj->MediaId;
                $this->getLocal("voice",$msgcontent);
            elseif($msgtype=="video"):
                $msgcontent=$postObj->MediaId;
                $this->getLocal("video",$msgcontent);
            elseif($msgtype=="location"):
                $msgcontent="纬度：".$postObj->Location_X."；经度：".$postObj->Location_Y."；缩放级别：".$postObj->Scale."位置：".$postObj->Label."";
            elseif($msgtype=="link"):
                $msgcontent="<a href='".$postObj->Url."'><b>".$postObj->Title."</b><br>".$postObj->Description."</a>";
            endif;
            $msgtime=$postObj->CreateTime;
            if(!empty($msgcontent)):
                $query="SELECT id,msgname,msgtype,msgcontent FROM `cms_wechat_message` WHERE msgname ='$msgname'";
                $qdata=mysqli_query($mysqli,$query);
                if(mysqli_num_rows($qdata)==1):
                    $qrow = mysqli_fetch_array($qdata);
                    $oldcontent=$qrow['msgcontent'];
                    $oldmsgtype=$qrow['msgtype'];
                    $newcontent="".$oldcontent."|usualtoolcms|".$msgcontent."";
                    $newmsgtype="".$oldmsgtype."|usualtoolcms|".$msgtype."";
                    $sqlmessage="UPDATE `cms_wechat_message` SET msgtype='$newmsgtype',msgcontent='$newcontent',msgtime='$msgtime' where msgname ='$msgname'";
                else:
                    $userinfodata=$this->getUser($msgname);
                    $userinfo=explode("|usualtoolcms|",$userinfodata);
                    $unionid=$userinfo[0];
                    $nickname=$userinfo[1];
                    $sex=$userinfo[2];
                    $headpic=$userinfo[3];
                    $sqlmessage="INSERT INTO `cms_wechat_message` (`msgname`,`unionid`,`nickname`,`sex`,`headpic`,`msgtype`,`msgcontent`,`msgtime`) VALUES ('$msgname','$unionid','$nickname','$sex','$headpic','$msgtype','$msgcontent','$msgtime')";
                endif;
                $mysqli->query($sqlmessage);
                $mysqli->close();
            endif;
            switch ($RX_TYPE){
                case "event":
                $result = $this->receiveevent($postObj);
                break;
                case "text":
                echo "success";
                exit;
                break;
                case "image":
                echo "success";
                exit;
                break;
                case "voice":
                echo "success";
                exit;
                break;
                case "video":
                echo "success";
                exit;
                break;
                case "location":
                echo "success";
                exit;
                break;
                case "link":
                echo "success";
                exit;
                break;
                default:
                echo "success";
                exit;
                break;
            }
            echo $result;
            exit;
        }else{
            echo "";
            exit;
        }
    }
    private function receiveevent($object){
        if($object->Event=="subscribe"):
            $content = "感谢您的关注。";
            $result = $this->transmitText($object, $content);
            return $result;
        else:
            echo "success";
            exit;
        endif;
    }
    private function receiveText($object){
        $content = "success";
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveImage($object){
        $content = "success";
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveVoice($object){
        $content = "success";
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveVideo($object){
        $content = "success";
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveLocation($object){
        $content = "success";
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveLink($object){
        $content = "success";
        $result = $this->transmitText($object, $content);
        return $result;
    }
    private function transmitText($object, $content){
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    function sendMsgToOne($touser,$content){
        $url = "https://{$this->appline}/cgi-bin/message/custom/send?access_token={$this->getToken()}";
        $data = '{
        "touser":"'.$touser.'",
        "msgtype":"text",
        "text":{
        "content":"'.$content.'"
        }
        }';
        $this->postData($url,$data);
    }
    function getUser($openid){
        $url = "https://{$this->appline}/cgi-bin/user/info?access_token={$this->getToken()}&openid={$openid}&lang=zh_CN";
        $res = $this->getData($url);
        $userinfo = "usualtoolcms|usualtoolcms|".$res['nickname']."|usualtoolcms|".$res['sex']."|usualtoolcms|".$res['headimgurl']."";
        return $userinfo;
    }
    function userBlack(){
        $url = "https://{$this->appline}/cgi-bin/tags/members/getblacklist?access_token={$this->getToken()}";
        $data='{"begin_openid":""}';
        $res = $this->postData($url,$data);
        $resx=json_encode($res);
        if(strpos($resx,'errmsg')!==false){
            return $res["errmsg"];
        }else{
            return $res['data']['openid'];
        }
    }
    function userSetblack($openid){
        $url = "https://{$this->appline}/cgi-bin/tags/members/batchblacklist?access_token={$this->getToken()}";
        $data='{
        "openid_list":["'.$openid.'"]
        }';
        $res = $this->postData($url,$data);
        return $res["errmsg"];
    }
    function userUnblack($openid){
        $url = "https://{$this->appline}/cgi-bin/tags/members/batchunblacklist?access_token={$this->getToken()}";
        $data='{
        "openid_list":["'.$openid.'"]
        }';
        $res = $this->postData($url,$data);
        return $res["errmsg"];
    }
    function getVoice($voiceid){
        $url = "https://{$this->appline}/cgi-bin/media/voice/queryrecoresultfortext?access_token={$this->getToken()}&voice_id={$voiceid}&lang=zh_CN";
        $res = $this->postData($url);
        $result =$res['result'];
        return $result;
    }
    function getLocal($type,$mediaid){
        $mediaurl = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$this->getToken()}&media_id={$mediaid}";
        $res = file_get_contents($mediaurl);
        if($type=="voice"){
        $respath = ROOT_PATH ."/modules/public/media/".$mediaid.".amr";
        }elseif($type=="video"){
        $respath = ROOT_PATH ."/modules/public/media/".$mediaid.".mp4";
        }
        file_put_contents($respath,$res);
    }
    function getMsg($stime,$etime,$msgid,$limit){
        $url = "https://api.weixin.qq.com/customservice/msgrecord/getmsglist?access_token={$this->getToken()}";
        $data = '{
        "starttime":{$stime},
        "endtime":{$etime},
        "msgid":{$msgid},
        "number":{$limit} 
        }';
        $res = $this->postData($url,$data);
        return $res;
    }
}
class UTWechatCrypt{
    private $appid;
	private $sessionKey;
	public function __construct( $appid, $sessionKey){
		$this->sessionKey = $sessionKey;
		$this->appid = $appid;
	}
	public function decryptData( $encryptedData, $iv, &$data){
		$aesKey=base64_decode($this->sessionKey);
		$aesIV=base64_decode($iv);
		$aesCipher=base64_decode($encryptedData);
		$result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
		$dataObj=json_decode( $result );
		if($dataObj==NULL){
			return "-41003";
		}
		if($dataObj->watermark->appid != $this->appid){
			return "-41003";
		}
		$data = $result;
		return 0;
	}
}
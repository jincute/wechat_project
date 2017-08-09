<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "yahaognb");

$wechatObj = new wechatCallbackapiTest();
$appid = "wxcba360c49dedc2a0";
$secret = "7fd85b9ed21748f08c4700caea62349b";

if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

//创建自定义菜单createMenu
function createMenu($url, $jsonData){
$ch = curl_init($url) ;
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result = curl_exec($ch) ;
curl_close($ch) ;
return $result;
}

function get_access_token($appid,$secret){  
        $access_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;  
        $json=http_request_json($access_url);//这个地方不能用file_get_contents  
        $data=json_decode($json,true);  
        if($data['access_token']){  
            return $data['access_token'];  
        }else{  
            return "获取access_token错误";  
        }         
    }  
//因为url是https 所有请求不能用file_get_contents,用curl请求json 数据  
    
function http_request_json($access_url){    
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$access_url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        $result = curl_exec($ch);  
        curl_close($ch);  
        return $result;    
    }  


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".get_access_token($appid, $secret);
$data = '{
"button":
	[
        {"type":"click",
		 "name":"雅皓义齿",
         "sub_button":[
			{
			"type":"click",
			"name":"关于雅皓",
			"key":"V1001_YAHAO"
			},
			{
			"type":"click",
			"name":"最新消息",
			"key":"V1001_NEWS"
			},
			{
			"type":"click",
			"name":"近期活动",
			"key":"V1001_ACT"
			}]
		},
		{"type":"click",
		 "name":"相关查询",
		 "sub_button":[
		   {
		   "type":"click",
		   "name":"订单查询",
           "key":"V2001_INQUIRY"
		   },
		   {
		   "type":"click",
		   "name":"快递查询",
		   "key":"V2002_DELIVER"
		   }]
		},
		{"type":"click",
		 "name":"联系我们",
         "sub_button":[
		    {
               "type":"view",
               "name":"雅皓主页",
               "url":"http://www.yahaognb.com.cn"
            },
           {	
               "type":"click",
               "name":"联系雅皓",
               "key":"V3001_CONTACT"
            },
            {
               "type":"click",
               "name":"医技沟通",
               "key":"V3003_SERVICE"
            }]
		}
	]
}';
createMenu($url,$data);

function post_text($content){
	$url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".get_access_token($appid, $secret);
	$post_data ='{
    "touser": "onZJuuENxS-l0rOouoS8tsUBBnBE", 
    "msgtype": "text", 
    "text": {
        "content": "Msg"
		}	
	}';
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_POST, 1);  
    curl_setopt($ch, CURLOPT_URL,$url);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result=curl_exec($ch);  
	curl_close($ch);
	return $result;
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
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

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        logger("R ".$postStr);
        //extract post data
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $resultStr = $this->receiveText($postObj);
					post_text($postStr);
                    break;
                default:
                    $resultStr = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            logger("T ".$resultStr);
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "你好，欢迎关注雅皓义齿！\n微信公众号: yahaognb \n目前开发使用中的功能有：\n1、订单查询\n2、快递查询\n如在使用过程中有任何问题欢迎微信客服";
				$resultStr = $this->transmitText($object, $contentStr);
				break;
			case "CLICK":
				switch ($object->EventKey) 
				{
					case "V1001_YAHAO":
					include('about.php');
					$resultStr = aboutYahao($object);
					break;
					case "V1001_NEWS":
					include('news.php');
					$resultStr = recentNews($object);
					break;
					case "V1001_ACT":
					$contentStr = "2014年01月没有安排相关活动";
					$resultStr = $this->transmitText($object, $contentStr);
					break;
					case "V2001_INQUIRY":
					$contentStr = "请发送'订单+保证卡号'，例如：订单A1000 进行查询，查询时间为9点到21点";
					$resultStr = $this->transmitText($object, $contentStr);
					break;
					case "V2002_DELIVER":
					$contentStr = "请发送'快递+快递单号'，例如：快递116092904962 进行查询，默认为顺丰快递";
					$resultStr = $this->transmitText($object, $contentStr);
					break;
					case "V3001_CONTACT":
					$contentStr = "电话：0757-85939392\n传真：0757-85939391\n邮箱: yahaognb@163.com\nQQ: 731533491\n地 址：广东省佛山市南海区黄岐广佛路238号C7座2楼";
					$resultStr = $this->transmitText($object, $contentStr);
					break;
					case "V3003_SERVICE":
					$contentStr = "欢迎发送图片以及相关建议！我们会及时登记并做处理！感谢您对我们工作的支持！";
					$resultStr = $this->transmitText($object, $contentStr);
					break;
				}
				break;
        }
        return $resultStr;
    }
	
    private function receiveText($object)
    {
        $funcFlag = 0;
        $keyword = trim($object->Content);
        $resultStr = "";
        $contentStr = "";

	if($keyword == "?" || $keyword == "？"){
            $contentStr = "你好，微信公众号: yahaognb[雅皓义齿] \n目前使用中的功能有：\n1、订单查询\n2、快递查询\n如在使用过程中有任何问题欢迎微信客服。\n建议尝试取消关注后再重新关注看看是否有解决问题";
			$resultStr = $this->transmitText($object, $contentStr, $funcFlag);
			createMenu($url,$data);
			return $resultStr;
		}
		else if(substr($keyword, 0, 6)== "快递"){
			//$contentStr = "正在开发中，敬请期待！";
			include('deliver.php');
			$deliverNum = substr($keyword, 6);
			$contentStr = searchDeliverStatus($deliverNum);
			$resultStr = $this->transmitText($object, $contentStr);
			return $resultStr;
		}
		else if(substr($keyword, 0, 6)== "订单"){
			include('search.php');
			$orderNum = substr($keyword, 6);
			$contentStr = searchGuaranteeCard($orderNum);
            $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
			//$contentStr =$orderNum;
			//$resultStr = $this->transmitText($object, $contentStr);
			return $resultStr;
		}
		else{
			$contentStr = "您的信息已提交，我们会尽快处理！\n1、如需查询订单状态，请输入：订单+订单号，如：订单A1000\n2、如需查询快递状态，请输入：快递+快递号，如：快递116092904962";
			$resultStr = $this->transmitText($object, $contentStr);
			return $resultStr;
		}
    }


    private function transmitText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
}

function logger($log_content)
{

}
?>
<?php
function aboutYahao($object)
{
	$newTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>
							</Articles>
							<FuncFlag>1</FuncFlag>
							</xml> ";
	$fromUsername = $object->FromUserName;
    $toUsername = $object->ToUserName;
	$time = time();
	$msgType = "news";
	$title = "雅皓义齿公司";
	$data  = date('Y-m-d');
	$desription = "佛山市南海雅皓义齿有限公司是由德国牙科材料经销商投资建立的大型义齿加工厂";
	$image = "http://mmbiz.qpic.cn/mmbiz/SADmBpAiaec37SCwC4TvJBibnF6Sna4SdozIzmH4iazus7rBFicI0z1jRicaZTibZs885hycnDwClpBs93aj1x1TfleQ/0";
	$turl = "http://mp.weixin.qq.com/s?__biz=MjM5NjU5MzEzMg==&mid=10015151&idx=1&sn=e6963ae0d8f058a0974f73a774645a48#rd";
    $resultStr = sprintf($newTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl);
    return $resultStr;
}
?>
<?php
function recentNews($object)
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
	$title = "雅皓义齿客户端上线啦！";
	$data  = date('Y-m-d');
	$desription = "最新消息";
	$image = "http://mmbiz.qpic.cn/mmbiz/SADmBpAiaec3gtugOxfDicHT6ECIp3TsvTLUDpa7NtdZktgOX7C8pniaEF1vU5yEeNVoaE88uQqy1McroofgeOLzg/0";
	$turl = "http://mp.weixin.qq.com/s?__biz=MjM5NjU5MzEzMg==&mid=200181789&idx=1&sn=14273aafb15d4f6b2e1bfb4dd6f68485#rd";
    $resultStr = sprintf($newTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl);
    return $resultStr;
}
?>
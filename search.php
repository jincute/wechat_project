﻿﻿<?php
date_default_timezone_set('Asia/Hong_Kong');  //set time zone
//var_dump(searchGuaranteeCard("H8303"));
function searchGuaranteeCard($content)
{
	$curHour = date('H',time());
    if (($curHour < 9) || ($curHour > 20)){
  	return "您好，该公共账号提供的查询时间为早上9点到晚上9点。\n给您带来不便,敬请见谅。";
    }else{
		$rawData = guaranteeCard($content);
		$xmldata = iconv("GB2312","UTF-8",$rawData);
		
		preg_match("#productName='(.*?)'#is", $xmldata, $pName);
		if (strpos($xmldata, "生产中")){
			return "产品名称: ".$pName[1]."\n当前状态: 生产中\n如需继续查询，请输入'订单+保证卡号'，例如'订单A1000'";
		}else if (strpos($xmldata, "已出厂")){
			preg_match("#deliverTime='(.*?)'#is", $xmldata, $dTime);
		    preg_match("#expressCompany='(.*?)'#is", $xmldata, $eComp);
			preg_match("#billNumber='(.*?)'#is", $xmldata, $bNum);
			return "产品名称: ".$pName[1]."\n当前状态: 已出厂\n出厂日期: ".$dTime[1]."\n快递公司: ".$eComp[1]."\n快递号码: ".$bNum[1]."\n如需继续查询，请输入保证卡号，如: A1000";
		}else{
			return "查找的数据不存在。\n请输入'订单+保证卡号'，例如'订单A1000'";
		}
      }
}

function guaranteeCard($cardNumber)
{
	$headers = array(
		"User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Accept-Language: en-us,en;q=0.5",
		//"Accept-Encoding: gzip, deflate",
		"Referer: http://yahaognb.vicp.cc/"
	);
	$url= "http://yahaognb.vicp.cc:8888/erpIndexAction.do?act=getCard&keyword=".$cardNumber;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	$output = curl_exec($ch);
	curl_close($ch);
	
    if ($output === FALSE){
        return "cURL Error: ". curl_error($ch);
    }
    return $output;
}
?>
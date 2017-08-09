<?php
function searchDeliverStatus($content)
{
$typeCom = "shunfeng";//快递公司
$deliverNum = $content;

if(isset($typeCom)&&isset($deliverNum))
{
$AppKey='b497af71b9a8ba17';//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
$url= 'http://www.kuaidi100.com/applyurl?key='.$AppKey.'&com='.$typeCom.'&nu='.$deliverNum;//生成完整的请求URL

//优先使用curl模式发送数据
　　if(function_exists('curl_init') == 1){
　　$curl = curl_init();
　　curl_setopt ($curl, CURLOPT_URL, $url);
　　curl_setopt ($curl, CURLOPT_HEADER,0);
　　curl_setopt ($curl, CURLOPT_RETURNTRANSFER,1);
　　curl_setopt ($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
　　curl_setopt ($curl, CURLOPT_TIMEOUT,5);
　　$get_content = curl_exec($curl);
　　curl_close ($curl);
　　}else{
　　include("snoopy.php");//该文件为PHP请求所需要的类文件，请忽略
　　$snoopy = new snoopy();
　　$snoopy->referer ='google';//请将google替换成google的完整网址
　　$snoopy->fetch($url);
　　$get_content = $snoopy->results;
　　}
　　$get_content=iconv('UTF-8','GB2312//IGNORE', $get_content);

//注释：第二步：将上面获得的返回结果传入iframe的src值，并将iframe显示出来，代码参考：
　　$results = print_r('<iframe src="'.$get_content.'" width="580"height="380"><br/>' . $powered);

else{
	$results = "查询失败，请重试";
　　}
return $results;
}
}
?>

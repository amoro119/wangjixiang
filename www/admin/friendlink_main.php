<?php
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie('ENV_GOBACK_URL', $dedeNowurl, time()+3600, '/');

if(empty($keyword)) $keyword = '';
if(empty($ischeck)) {
	$ischeck = 0;
	$ischeckSql = '';
} else {
	if($ischeck==-1) $ischeckSql = " And ischeck < 1 ";
	else $ischeckSql = " And ischeck='$ischeck' ";
}

$selCheckArr = array(0=>'不限类型', -1=>'未审核', 1=>'内页', 2=>'首页');

$sql = "Select * From `#@__flink` where  CONCAT(`url`,`webname`,`email`) like '%$keyword%' $ischeckSql order by dtime desc";

$dlist = new DataListCP();
$dlist->SetParameter('keyword', $keyword);
$dlist->SetParameter('ischeck', $ischeck);
$dlist->SetTemplet(DEDEADMIN.'/templets/friendlink_main.htm');
$dlist->SetSource($sql);
$dlist->display();

function GetPic($pic)
{
	if($pic=='') return '无图标';
	else return "<img src='$pic' width='159' height='52' border='0' />";
}

function GetSta($sta)
{
	if($sta==1) return '内页';
	if($sta==2) return '首页';
	else return '未审核';
}

?>
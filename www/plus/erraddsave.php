<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC.'/memberlogin.class.php');

$htmltitle = "�����ύ";
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
if(empty($dopost))
{
	$row = $dsql->GetOne(" SELECT `title` FROM `#@__archives` WHERE `id` ='$aid'");
	$title = $row['title'];
	require_once(DEDEROOT."/templets/plus/erraddsave.htm");
}
elseif($dopost == "saveedit")
{
	$cfg_ml = new MemberLogin();
	$title = HtmlReplace($title);
	$type = isset($type) && is_numeric($type) ? $type : 0;
	$mid = isset($cfg_ml->M_ID) ? $cfg_ml->M_ID : 0;
	$err = trimMsg(cn_substr($err,2000),1);
	$oktxt = trimMsg(cn_substr($erradd,2000),1);
	$time = time();
	$query = "INSERT INTO `#@__erradd`(aid,mid,title,type,errtxt,oktxt,sendtime)
                  VALUES ('$aid','$mid','$title','$type','$err','$oktxt','$time'); ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("лл���Ա���վ��֧�֣����ǻᾡ�촦�����Ľ��飡","javascript:window.close();");
	exit();
}
?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Feedback');
$id = isset($id) && is_numeric($id) ? $id : 0;
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL'])? "feedback_main.php" : $_COOKIE['ENV_GOBACK_URL'];
if(empty($dopost))
{
	$dopost = "";
}
if($dopost=='edit')
{
	$msg = cn_substrR($msg,2500);
	$adminmsg = trim($adminmsg);
	if($adminmsg!="")
	{
		$adminmsg = cn_substrR($adminmsg,1500);
		$adminmsg = str_replace("<","&lt;",$adminmsg);
		$adminmsg = str_replace(">","&gt;",$adminmsg);
		$adminmsg = str_replace("  ","&nbsp;&nbsp;",$adminmsg);
		$adminmsg = str_replace("\r\n","<br/>\n",$adminmsg);
		$msg = $msg."<br/>\n"."<font color=red>����Ա�ظ��� $adminmsg</font>\n";
	}
	$query = "update `#@__feedback` set username='$username',msg='$msg',ischeck=1 where id=$id";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ��ظ�һ�����ԣ�",$ENV_GOBACK_URL);
	exit();
}
$query = "select * from `#@__feedback` where id=$id";
$row = $dsql->GetOne($query);
include DedeInclude('templets/feedback_edit.htm');

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Pm');
if(!isset($action))
{
	$action = '';
}
if($action=="post")
{
	$floginid = 'admin';
	$fromid = 0;
	$toid = 0;
	$tologinid = 0;
	$sendtime = time();
	$writetime = time();
	$subject = cn_substrR(HtmlReplace($subject),70);
	$message = cn_substrR(HtmlReplace($message),1000);
	if(!isset($subject)||empty($subject))
	{
		ShowMsg('���ű��ⲻ��Ϊ��!','-1');
		exit();
	}else if(!isset($message)||empty($message))
	{
		ShowMsg('����д��������!','-1');
		exit();
	}
	
	#api{{
	if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
	{
		uc_pm_send(0,'',$subject,$message);
		ShowMsg('�����ѳɹ�����','-1'); exit;
	}
	#/aip}}
	
	$rs = $dsql->ExecuteNoneQuery("INSERT INTO #@__member_pms(floginid,fromid,toid,tologinid,folder,hasview,subject,sendtime,writetime,message,isadmin) VALUES('$floginid','$fromid','$toid','$tologinid','outbox','0','$subject','$sendtime','$writetime','$message','1');");
	ShowMsg('�����ѳɹ�����','-1');
	exit();
}
require_once(DEDEADMIN."/templets/member_pmall.htm");

?>
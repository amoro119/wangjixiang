<?php
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
require_once(DEDEMEMBER.'/inc/inc_archives_functions.php');
$menutype = 'content';
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
if(empty($dopost)) $dopost = '';
if($dopost=='')
{
	$arow = $dsql->GetOne("Select * From `#@__uploads` where aid='$aid ';");
	if(!is_array($arow))
	{
		ShowMsg('����������', '-1');
		exit();
	}
	if($arow['mid']!=$cfg_ml->M_ID)
	{
		ShowMsg("��û���޸����������Ȩ�ޣ�","-1");
		exit();
	}
	include(DEDEMEMBER."/templets/uploads_edit.htm");
	exit();
}
else if($dopost=='save')
{
	$title = HtmlReplace($title,2);
	if($mediatype==1) $utype = 'image';
	else if($mediatype==2)
	{
		$utype = 'flash';
	}
	else if($mediatype==3)
	{
		$utype = 'media';
	}
	else
	{
		$utype = 'addon';
	}
	$title = HtmlReplace($title,2);
	$exname = ereg_replace("(.*)/","",$oldurl);
	$exname = ereg_replace("\.(.*)$","",$exname);
	$filename = MemberUploads('addonfile',$oldurl,$cfg_ml->M_ID,$utype,$exname,-1,-1,true);
	SaveUploadInfo($title,$filename,$mediatype);
	ShowMsg("�ɹ��޸��ļ���","uploads_edit.php?aid=$aid");
}
?>
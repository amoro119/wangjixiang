<?php
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
require_once(DEDEMEMBER.'/inc/inc_archives_functions.php');
if(empty($dopost)) $dopost = '';
if(empty($mediatype)) $mediatype = 1;
$menutype = 'content';
if($dopost=='')
{
	include(DEDEMEMBER."/templets/uploads_add.htm");
}
else if($dopost=='save')
{
	$cfg_ml->CheckUserSpace();
	if($mediatype==1)
	{
		$utype = 'image';
	}
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
	$filename = MemberUploads('addonfile','',$cfg_ml->M_ID,$utype,'',-1,-1,true);
	SaveUploadInfo($title,$filename,$mediatype);
	$bkurl = "uploads_select.php?f=".$f."&mediatype=".$mediatype."&keyword=".urlencode($keyword)."&filename=".$filename;
	if($filename=='')
	{
		ShowMsg("�ϴ��ļ�ʧ�ܣ�","-1");
	}
	else
	{
		ShowMsg("�ɹ��ϴ�һ���ļ���",$bkurl);
	}
}
?>
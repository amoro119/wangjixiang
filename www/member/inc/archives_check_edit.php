<?php
if(!defined('DEDEMEMBER')) exit('dedecms');

require_once(DEDEINC."/image.func.php");
require_once(DEDEINC."/oxwindow.class.php");

$flag = '';
$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
$userip = GetIP();
$ckhash = md5($aid.$cfg_cookie_encode);
if($ckhash!=$idhash)
{
	ShowMsg('У���������ûȨ���޸Ĵ��ĵ���������Ϸ���','-1');
	exit();
}
$svali = GetCkVdValue();
if(preg_match("/3/",$safe_gdopen)){
	if(strtolower($vdcode)!=$svali || $svali=='')
	{
		ResetVdValue();
		ShowMsg('��֤�����', '-1');
		exit();
	}
	
}
if($typeid==0)
{
	ShowMsg('��ָ���ĵ���������Ŀ��','-1');
	exit();
}
$query = "Select tp.ispart,tp.channeltype,tp.issend,ch.issend as cissend,ch.sendrank,ch.arcsta,ch.addtable,ch.usertype
         From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id='$typeid' ";
$cInfos = $dsql->GetOne($query);
$addtable = $cInfos['addtable'];

//�����Ŀ�Ƿ���Ͷ��Ȩ��
if($cInfos['issend']!=1 || $cInfos['ispart']!=0|| $cInfos['channeltype']!=$channelid || $cInfos['cissend']!=1)
{
	ShowMsg("����ѡ�����Ŀ��֧��Ͷ�壡","-1");
	exit();
}

//�ĵ���Ĭ��״̬
if($cInfos['arcsta']==0)
{
	$ismake = 0;
	$arcrank = 0;
}
else if($cInfos['arcsta']==1)
{
	$ismake = -1;
	$arcrank = 0;
}
else
{
	$ismake = 0;
	$arcrank = -1;
}

//�Ա�������ݽ��д���
$title = cn_substrR(HtmlReplace($title,1),$cfg_title_maxlen);
$writer =  cn_substrR(HtmlReplace($writer,1),20);
if(empty($description)) $description = '';
$description = cn_substrR(HtmlReplace($description,1),250);
$keywords = cn_substrR(HtmlReplace($tags,1),30);
$mid = $cfg_ml->M_ID;
$isadmin = ($cfg_ml->fields['matt']==10 ? true : false);
//�����ϴ�������ͼ
$litpic = MemberUploads('litpic', $oldlitpic, $mid, 'image', '', $cfg_ddimg_width, $cfg_ddimg_height, false, $isadmin);
if($litpic != '')
{
	SaveUploadInfo($title, $litpic, 1);
}
else
{
	$litpic =$oldlitpic;
}
?>
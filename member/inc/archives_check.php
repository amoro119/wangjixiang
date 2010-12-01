<?php
if(!defined('DEDEMEMBER'))	exit('dedecms');

include_once(DEDEINC.'/image.func.php');
include_once(DEDEINC.'/oxwindow.class.php');

$svali = GetCkVdValue();
if(preg_match("/3/",$safe_gdopen)){
	if(strtolower($vdcode)!=$svali || $svali=='')
	{
		ResetVdValue();
		ShowMsg('��֤�����', '-1');
		exit();
	}
	
}

$faqkey = isset($faqkey) && is_numeric($faqkey) ? $faqkey : 0;
$safe_faq_send = isset($safe_faq_send) && is_numeric($safe_faq_send) ? $safe_faq_send : 0;
if($safe_faq_send == '1')
{
	if($safefaqs[$faqkey]['answer'] != $safeanswer || $safeanswer=='')
	{
		ShowMsg('��֤����𰸴���', '-1');
		exit();
	}
}

$flag = '';
$autokey = $remote = $dellink = $autolitpic = 0;
$userip = GetIP();

if($typeid==0)
{
	ShowMsg('��ָ���ĵ���������Ŀ��', '-1');
	exit();
}

$query = "Select tp.ispart,tp.channeltype,tp.issend,ch.issend as cissend,ch.sendrank,ch.arcsta,ch.addtable,ch.usertype
          From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id='$typeid' ";
$cInfos = $dsql->GetOne($query);

//�����Ŀ�Ƿ���Ͷ��Ȩ��
if($cInfos['issend']!=1 || $cInfos['ispart']!=0  || $cInfos['channeltype']!=$channelid || $cInfos['cissend']!=1)
{
	ShowMsg("����ѡ�����Ŀ��֧��Ͷ�壡","-1");
	exit();
}

//���Ƶ���趨��Ͷ�����Ȩ��
if($cInfos['sendrank'] > $cfg_ml->M_Rank )
{
	$row = $dsql->GetOne("Select membername From #@__arcrank where rank='".$cInfos['sendrank']."' ");
	ShowMsg("�Բ�����Ҫ[".$row['membername']."]���������Ƶ�������ĵ���","-1","0",5000);
	exit();
}

if($cInfos['usertype'] !='' && $cInfos['usertype'] != $cfg_ml->M_MbType)
{
	ShowMsg("�Բ�����Ҫ[".$cInfos['usertype']."]���������Ƶ�������ĵ���","-1","0",5000);
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
$money = 0;
$flag = $shorttitle = $color = $source = '';
$sortrank = $senddate = $pubdate = time();
$title = cn_substrR(HtmlReplace($title,1),$cfg_title_maxlen);
$writer =  cn_substrR(HtmlReplace($writer,1),20);
if(empty($description)) $description = '';
$description = cn_substrR(HtmlReplace($description,1),250);
$keywords = cn_substrR(HtmlReplace($tags,1),30);
$mid = $cfg_ml->M_ID;

//�����ϴ�������ͼ
$litpic = MemberUploads('litpic','',$cfg_ml->M_ID,'image','',$cfg_ddimg_width,$cfg_ddimg_height,false);
if($litpic!='')
{
	SaveUploadInfo($title,$litpic,1);
}

//����ĵ��Ƿ��ظ�
if($cfg_mb_cktitle=='Y')
{
	$row = $dsql->GetOne("Select * From `#@__archives` where title like '$title' ");
	if(is_array($row))
	{
		ShowMsg("�Բ����벻Ҫ�����ظ��ĵ���","-1","0",5000);
		exit();
	}
}

?>
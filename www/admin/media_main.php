<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/common.func.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(empty($dopost))
{
	$dopost = '';
}

//�ļ�ʽ������
if($dopost=='filemanager')
{
	if(file_exists('./file_manage_main.php'))
	{
		header("location:file_manage_main.php?activepath=$cfg_medias_dir");
	}
	else
	{
		ShowMsg("�Ҳ����ļ��������������Ѿ�ж��!","-1");
	}
	exit();
}

//���ݿ����
//-----------------------------------

if(empty($keyword))
{
	$keyword = "";
}
$addsql = " where (u.title like '%$keyword%' Or u.url like '%$keyword%') ";
if(empty($membertype))
{
	$membertype = 0;
}
if($membertype==1)
{
	$addsql .= " And u.mid>0 ";
}
else if($membertype==2)
{
	$addsql .= " And u.mid>0 ";
}

if(empty($mediatype))
{
	$mediatype = 0;
}
if($mediatype>1)
{
	$addsql .= " And u.mediatype='$membertype' ";
}
$sql = "Select u.aid,u.title,u.url,u.mediatype,u.filesize,u.mid,u.uptime
,a.userid as adminname,m.userid as membername
From #@__uploads u
Left join #@__admin a on  a.id = u.mid
Left join #@__member m on m.mid = u.mid
$addsql order by u.aid desc";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("mediatype",$mediatype);
$dlist->SetParameter("keyword",$keyword);
$dlist->SetParameter("membertype",$membertype);
$dlist->SetTemplate(DEDEADMIN."/templets/media_main.htm");
$dlist->SetSource($sql);
$dlist->Display();

function MediaType($tid,$nurl)
{
	if($tid==1)
	{
		return "ͼƬ<a href=\"javascript:;\" onClick=\"ChangeImage('$nurl');\"><img src='../include/dialog/img/picviewnone.gif' name='picview' border='0' alt='Ԥ��'></a>";
	}
	else if($tid==2)
	{
		return "FLASH";
	}
	else if($tid==3)
	{
		return "��Ƶ/��Ƶ";
	}
	else
	{
		return "����/����";
	}
}

function GetFileSize($fs)
{
	$fs = $fs/1024;
	return trim(sprintf("%10.1f",$fs)." K");
}

function UploadAdmin($adminid,$mid)
{
	if($adminid!='')
	{
		return $adminid;
	}
	else
	{
		return $mid;
	}
}

?>
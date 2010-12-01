<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('plus_�ļ�������');
require(DEDEINC."/oxwindow.class.php");
require_once(DEDEADMIN.'/file_class.php');
$activepath = str_replace("..","",$activepath);
$activepath = ereg_replace("^/{1,}","/",$activepath);
if($activepath == "/")
{
	$activepath = "";
}
if($activepath == "")
{
	$inpath = $cfg_basedir;
}
else
{
	$inpath = $cfg_basedir.$activepath;
}

//�ļ��������������߼������ļ�
$fmm = new FileManagement();
$fmm->Init();

/*---------------
function __rename();
----------------*/
if($fmdo=="rename")
{
	$fmm->RenameFile($oldfilename,$newfilename);
}

//�½�Ŀ¼

/*---------------
function __newdir();
----------------*/
else if($fmdo=="newdir")
{
	$fmm->NewDir($newpath);
}

//�ƶ��ļ�

/*---------------
function __move();
----------------*/
else if($fmdo=="move")
{
	$fmm->MoveFile($filename,$newpath);
}

//ɾ���ļ�

/*---------------
function __delfile();
----------------*/
else if($fmdo=="del")
{
	$fmm->DeleteFile($filename);
}

//�ļ��༭

/*---------------
function __saveEdit();
----------------*/
else if($fmdo=="edit")
{
	$filename = str_replace("..","",$filename);
	$file = "$cfg_basedir$activepath/$filename";
	$str = stripslashes($str);
	$fp = fopen($file,"w");
	fputs($fp,$str);
	fclose($fp);
	if(empty($backurl))
	{
		ShowMsg("�ɹ�����һ���ļ���","file_manage_main.php?activepath=$activepath");
	}
	else
	{
		ShowMsg("�ɹ������ļ���",$backurl);
	}
	exit();
}
/*
�ļ��༭�����ӻ�ģʽ
function __saveEditView();
else if($fmdo=="editview")
{
	$filename = str_replace("..","",$filename);
	$file = "$cfg_basedir$activepath/$filename";
	$str = eregi_replace('&quot;','\\"',$str);
	$str = stripslashes($str);
	$fp = fopen($file,"w");
	fputs($fp,$str);
	fclose($fp);
	if(empty($backurl))
	{
		$backurl = "file_manage_main.php?activepath=$activepath";
	}
	ShowMsg("�ɹ������ļ���",$backurl);
	exit();
}
*/
//�ļ��ϴ�
/*---------------
function __upload();
----------------*/
else if($fmdo=="upload")
{
	$j=0;
	for($i=1;$i<=50;$i++)
	{
		$upfile = "upfile".$i;
		$upfile_name = "upfile".$i."_name";
		if(!isset(${$upfile}) || !isset(${$upfile_name}))
		{
			continue;
		}
		$upfile = ${$upfile};
		$upfile_name = ${$upfile_name};
		if(is_uploaded_file($upfile))
		{
			if(!file_exists($cfg_basedir.$activepath."/".$upfile_name))
			{
				move_uploaded_file($upfile,$cfg_basedir.$activepath."/".$upfile_name);
			}
			@unlink($upfile);
			$j++;
		}
	}
	ShowMsg("�ɹ��ϴ� $j ���ļ���: $activepath","file_manage_main.php?activepath=$activepath");
	exit();
}

//�ռ���
else if($fmdo=="space")
{
	if($activepath=="")
	{
		$ecpath = "����Ŀ¼";
	}
	else
	{
		$ecpath = $activepath;
	}
	$titleinfo = "Ŀ¼ <a href='file_manage_main.php?activepath=$activepath'><b><u>$ecpath</u></b></a> �ռ�ʹ��״����<br/>";
	$wintitle = "�ļ�����";
	$wecome_info = "�ļ�����::�ռ��С��� [<a href='file_manage_main.php?activepath=$activepath'>�ļ������</a>]</a>";
	$activepath=$cfg_basedir.$activepath;
	$space = new SpaceUse;
	$space->checksize($activepath);
	$total=$space->totalsize;
	$totalkb=$space->setkb($total);
	$totalmb=$space->setmb($total);
	$win = new OxWindow();
	$win->Init("","js/blank.js","POST");
	$win->AddTitle($titleinfo);
	$win->AddMsgItem("����$totalmb M<br/>����$totalkb KB<br/>����$total �ֽ�");
	$winform = $win->GetWindow("");
	$win->Display();
}

?>
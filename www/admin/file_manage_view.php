<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('plus_�ļ�������');
require_once(DEDEINC."/oxwindow.class.php");
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

//��ʾ���Ʋ�
//�����ļ���
if($fmdo=="rename")
{
	if($activepath=="")
	{
		$ndirstring = "��Ŀ¼";
	}
	$ndirstring = $activepath;
	$wintitle = "�ļ�����";
	$wecome_info = "�ļ�����::�����ļ��� [<a href='file_manage_main.php?activepath=$activepath'>�ļ������</a>]</a>";
	$win = new OxWindow();
	$win->Init("file_manage_control.php","js/blank.js","POST");
	$win->AddHidden("fmdo",$fmdo);
	$win->AddHidden("activepath",$activepath);
	$win->AddHidden("filename",$filename);
	$win->AddTitle("�����ļ�������ǰ·����$ndirstring");
	$win->AddItem("�����ƣ�","<input name='oldfilename' type='input' id='oldfilename' size='40' value='$filename'>");
	$win->AddItem("�����ƣ�","<input name='newfilename' type='input' size='40' id='newfilename'>");
	$winform = $win->GetWindow("ok");
	$win->Display();
}

//�½�Ŀ¼
else if($fmdo=="newdir")
{
	if($activepath=="")
	{
		$activepathname="��Ŀ¼";
	}
	else
	{
		$activepathname=$activepath;
	}
	$wintitle = "�ļ�����";
	$wecome_info = "�ļ�����::�½�Ŀ¼ [<a href='file_manage_main.php?activepath=$activepath'>�ļ������</a>]</a>";
	$win = new OxWindow();
	$win->Init("file_manage_control.php","js/blank.js","POST");
	$win->AddHidden("fmdo",$fmdo);
	$win->AddHidden("activepath",$activepath);
	$win->AddTitle("��ǰĿ¼ $activepathname ");
	$win->AddItem("��Ŀ¼��","<input name='newpath' type='input' id='newpath'>");
	$winform = $win->GetWindow("ok");
	$win->Display();
}

//�ƶ��ļ�
else if($fmdo=="move")
{
	$wintitle = "�ļ�����";
	$wecome_info = "�ļ�����::�ƶ��ļ� [<a href='file_manage_main.php?activepath=$activepath'>�ļ������</a>]</a>";
	$win = new OxWindow();
	$win->Init("file_manage_control.php","js/blank.js","POST");
	$win->AddHidden("fmdo",$fmdo);
	$win->AddHidden("activepath",$activepath);
	$win->AddHidden("filename",$filename);
	$win->AddTitle("��λ��ǰ�治��'/'��ʾ����ڵ�ǰλ�ã���'/'��ʾ����ڸ�Ŀ¼��");
	$win->AddItem("���ƶ��ļ���",$filename);
	$win->AddItem("��ǰλ�ã�",$activepath);
	$win->AddItem("��λ�ã�","<input name='newpath' type='input' id='newpath' size='40'>");
	$winform = $win->GetWindow("ok");
	$win->Display();
}

//ɾ���ļ�
else if($fmdo=="del")
{
	$wintitle = "�ļ�����";
	$wecome_info = "�ļ�����::ɾ���ļ� [<a href='file_manage_main.php?activepath=$activepath'>�ļ������</a>]</a>";
	$win = new OxWindow();
	$win->Init("file_manage_control.php","js/blank.js","POST");
	$win->AddHidden("fmdo",$fmdo);
	$win->AddHidden("activepath",$activepath);
	$win->AddHidden("filename",$filename);
	if(@is_dir($cfg_basedir.$activepath."/$filename"))
	{
		$wmsg = "��ȷ��Ҫɾ��Ŀ¼��$filename ��";
	}
	else
	{
		$wmsg = "��ȷ��Ҫɾ���ļ���$filename ��";
	}
	$win->AddTitle("ɾ���ļ�ȷ��");
	$win->AddMsgItem($wmsg,"50");
	$winform = $win->GetWindow("ok");
	$win->Display();
}

//�༭�ļ�
else if($fmdo=="edit")
{
	if(!isset($backurl))
	{
		$backurl = "";
	}

	$activepath = str_replace("..","",$activepath);
	$filename = str_replace("..","",$filename);
	$file = "$cfg_basedir$activepath/$filename";
	$content = "";
	if(is_file($file))
	{
		$fp = fopen($file,"r");
		$content = fread($fp,filesize($file));
		fclose($fp);
		$content = htmlspecialchars($content);
	}
	$contentView = "<textarea name='str' style='width:100%;height:450px;background:#F3FCE2;'>$content</textarea>\r\n";
	$GLOBALS['filename'] = $filename;
	$ctp = new DedeTagParse();
	$ctp->LoadTemplate(DEDEADMIN."/templets/file_edit.htm");
	$ctp->display();
}
/*�༭�ļ������ӻ�ģʽ
else if($fmdo=="editview")
{
	if(!isset($backurl))
	{
		$backurl = "";
	}
	if(!isset($ishead))
	{
		$ishead = "";
	}
	$activepath = str_replace("..","",$activepath);
	$filename = str_replace("..","",$filename);
	$file = "$cfg_basedir$activepath/$filename";
	$fp = fopen($file,"r");
	@$content = fread($fp,filesize($file));
	fclose($fp);
	if((eregi("<html",$content) && eregi("<body",$content)) || $ishead == "yes")
	{
		$contentView = GetEditor("str",$content,"500","Default","string","true");
	}
	else
	{
		$contentView = GetEditor("str",$content,"500","Default","string","false");
	}
	$GLOBALS['filename'] = $filename;
	$ctp = new DedeTagParse();
	$ctp->LoadTemplate(DEDEADMIN."/templets/file_edit_view.htm");
	$ctp->display();
}
*/
//�½��ļ�
else if($fmdo=="newfile")
{
	$content = "";
	$GLOBALS['filename'] = "newfile.txt";
	$contentView = "<textarea name='str' style='width:100%;height:400'></textarea>\r\n";
	$ctp = new DedeTagParse();
	$ctp->LoadTemplate(DEDEADMIN."/templets/file_edit.htm");
	$ctp->display();
}

//�ϴ��ļ�
else if($fmdo=="upload")
{
	$ctp = new DedeTagParse();
	$ctp->LoadTemplate(DEDEADMIN."/templets/file_upload.htm");
	$ctp->display();
}
?>
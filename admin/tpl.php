<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('plus_�ļ�������');

$action = isset($action) ? trim($action) : '';

if(empty($acdir)) $acdir = $cfg_df_style;
$templetdir = $cfg_basedir.$cfg_templets_dir;
$templetdird = $templetdir.'/'.$acdir;
$templeturld = $cfg_templeturl.'/'.$acdir;
if(empty($filename))	$filename = '';
$filename = ereg_replace("[/\\]",'',$filename);
if(ereg("\.",$acdir))
{
	ShowMsg('Not Allow dir '.$acdir.'!','-1');
	exit();
}

/*
function edit_new_tpl() { }
�༭ģ��
*/
if($action == 'edit' || $action == 'newfile')
{
	if($filename == '' && $action == 'edit')
	{
		ShowMsg('δָ��Ҫ�༭���ļ�', '-1');
		exit();
	}
	if(!file_exists($templetdird.'/'.$filename)  && $action == 'edit')
	{
		$action = 'newfile';
	}

	//��ȡ�ļ�����
	//$content = htmlspecialchars(trim(file_get_contents($truePath.$filename)));
	if($action == 'edit')
	{
		$fp = fopen($templetdird.'/'.$filename,'r');
		$content = fread($fp,filesize($templetdird.'/'.$filename));
		fclose($fp);
		$content = eregi_replace("<textarea","##textarea",$content);
		$content = eregi_replace("</textarea","##/textarea",$content);
		$content = eregi_replace("<form","##form",$content);
		$content = eregi_replace("</form","##/form",$content);
	}
	else
	{
		if(empty($filename)) $filename = 'newtpl.htm';
		$content = '';
	}

	//��ȡ��ǩ������Ϣ
	$helps = $dtags = array();
	$tagHelpDir = DEDEINC.'/taglib/help/';
	$dir = dir($tagHelpDir);
	while(false !== ($entry = $dir->read()))
	{
		if($entry != '.' && $entry != '..' && !is_dir($tagHelpDir.$entry))
		{
			$dtags[] = str_replace('.txt', '', $entry);
		}
	}
	$dir->close();
	foreach($dtags as $tag)
	{
		//$helpContent = file_get_contents($tagHelpDir.$tag.'.txt');
		$fp = fopen($tagHelpDir.$tag.'.txt','r');
		$helpContent = fread($fp,filesize($tagHelpDir.$tag.'.txt'));
		fclose($fp);
		$helps[$tag] = explode('>>dede>>', $helpContent);
	}

	include DEDEADMIN.'/templets/tpl_edit.htm';
	exit();
}
/*---------------------------
function save_tpl() { }
����༭ģ��
--------------------------*/
else if($action == 'saveedit')
{
	if($filename == '')
	{
		ShowMsg('δָ��Ҫ�༭���ļ����ļ������Ϸ�', '-1');
		exit();
	}
	if(!ereg("\.htm$",$filename))
	{
		ShowMsg('DEDEģ���ļ����ļ���������.htm��β��', '-1');
		exit();
	}
	$content = stripslashes($content);
	$content = eregi_replace("##textarea","<textarea",$content);
	$content = eregi_replace("##/textarea","</textarea",$content);
	$content = eregi_replace("##form","<form",$content);
	$content = eregi_replace("##/form","</form",$content);
	$truefile = $templetdird.'/'.$filename;
	$fp = fopen($truefile,'w');
	fwrite($fp, $content);
	fclose($fp);
	ShowMsg('�ɹ��޸Ļ��½��ļ�', 'templets_main.php?acdir='.$acdir);
	exit();
}
/*---------------------------
function del_tpl() { }
ɾ��ģ��
--------------------------*/
else if ($action == 'del')
{
	$truefile = $templetdird.'/'.$filename;
	if(unlink($truefile))
	{
		ShowMsg('ɾ���ļ��ɹ�','templets_main.php?acdir='.$acdir);
		exit();
	}
	else
	{
		ShowMsg('ɾ���ļ�ʧ��','-1');
		exit();
	}
}
/*----------------------
function _upload() {}
�ϴ���ģ��
-----------------------*/
else if ($action == 'upload')
{
	require_once(dirname(__FILE__).'/../include/oxwindow.class.php');
	$acdir = str_replace('.', '', $acdir);
	$win = new OxWindow();
	$win->Init("tpl.php","js/blank.js","POST' enctype='multipart/form-data' ");
	$win->mainTitle = "ģ�����";
	$wecome_info = "<a href='templets_main.php'>ģ�����</a> &gt;&gt; �ϴ�ģ��";
	$win->AddTitle('��ѡ��Ҫ�ϴ����ļ�:');
	$win->AddHidden("action",'uploadok');
	$msg = "
	<table width='600' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='96' height='60'>��ѡ���ļ���</td>
    <td width='504'>
	    <input name='acdir' type='hidden' value='$acdir'  />
	    <input name='upfile' type='file' id='upfile' style='width:380px' />
	  </td>
  </tr>
 </table>
	";
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('ok','');
	$win->Display();
	exit();
}
/*----------------------
function _upload() {}
�ϴ���ģ��
-----------------------*/
else if ($action == 'uploadok')
{
	if( !is_uploaded_file($upfile) )
	{
		ShowMsg("ò����ʲô��û���ϴ�Ŷ��","javascript:;");
		exit();
	}
	else
	{
		if( !ereg("\.(htm|html)$", $upfile_name) )
		{
			ShowMsg("DedeCmsģ��ֻ���� .htm �� .html��չ����","-1");
		  exit();
		}
		if( ereg("[\\/]",$upfile_name) )
		{
			ShowMsg("ģ���ļ����зǷ��ַ�����ֹ�ϴ���","-1");
		  exit();
		}
		move_uploaded_file($upfile, $templetdird.'/'.$upfile_name);
		@unlink($upfile);
		ShowMsg("�ɹ��ϴ�һ��ģ�壡","templets_main.php?acdir=$acdir");
		exit();
	}
	exit();
}
/*---------------------------
function edittag() { }
�޸ı�ǩ��Ƭ
--------------------------*/
else if($action=='edittag' || $action=='addnewtag')
{
	if($action=='addnewtag')
	{
$democode = '<'."?php
if(!defined('DEDEINC'))
{
	exit(\"Request Error!\");
}
function lib_demotag(&\$ctag,&\$refObj)
{
	global \$dsql,\$envs;
	
	//���Դ���
	\$attlist=\"row|12,titlelen|24\";
	FillAttsDefault(\$ctag->CAttribute->Items,\$attlist);
	extract(\$ctag->CAttribute->Items, EXTR_SKIP);
	\$revalue = '';
	
	//�����д�Ĵ��룬������echo֮���﷨�������շ���ֵ����\$revalue
	//------------------------------------------------------
	
	\$revalue = 'Hello Word!';
	
	//------------------------------------------------------
	return \$revalue;
}
?".'>';
			$filename = "demotag.lib.php";
			$title = "�½���ǩ";
	}
	else
	{
		if(!eregi("^[a-z0-9_-]{1,}\.lib\.php$",$filename))
		{
			ShowMsg('�ļ����Ǳ�׼�ı�ǩ��Ƭ�ļ����������ڴ˱༭��','-1');
			exit();
		}
		$fp = fopen(DEDEINC.'/taglib/'.$filename,'r');
		$democode = fread($fp,filesize(DEDEINC.'/taglib/'.$filename));
		fclose($fp);
		$title = "�޸ı�ǩ";
	}
	include DEDEADMIN.'/templets/tpl_edit_tag.htm';
	exit();
}
/*---------------------------
function savetagfile() { }
�����ǩ��Ƭ�޸�
--------------------------*/
else if($action=='savetagfile')
{
	if(!eregi("^[a-z0-9_-]{1,}\.lib\.php$",$filename))
	{
		ShowMsg('�ļ������Ϸ�����������в�����','-1');
		exit();
	}
	require_once(DEDEINC.'/oxwindow.class.php');
	$tagname = eregi_replace("\.lib\.php$","",$filename);
	$content = stripslashes($content);
	$truefile = DEDEINC.'/taglib/'.$filename;
	$fp = fopen($truefile,'w');
	fwrite($fp, $content);
	fclose($fp);
	$msg = "
	<form name='form1' action='tag_test_action.php' target='blank' method='post'>
  	<input type='hidden' name='dopost' value='make' />
		<b>���Ա�ǩ��</b>(��Ҫʹ�û��������Ĳ����ڴ˲���)<br/>
		<textarea name='partcode' cols='150' rows='6' style='width:90%;'>{dede:{$tagname} }{/dede:{$tagname}}</textarea><br />
		<input name='imageField1' type='image' class='np' src='img/button_ok.gif' width='60' height='22' border='0' />
	</form>
	";
	$wintitle = "�ɹ��޸�/�����ļ���";
	$wecome_info = "<a href='templets_tagsource.php'>��ǩԴ����Ƭ����</a> &gt;&gt; �޸�/�½���ǩ";
	$win = new OxWindow();
	$win->AddTitle("�޸�/�½���ǩ��");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
	exit();
}
?>
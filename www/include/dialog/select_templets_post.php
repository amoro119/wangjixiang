<?php
require_once(dirname(__FILE__)."/config.php");
$cfg_txttype = "htm|html|tpl|txt";
if(empty($uploadfile))
{
	$uploadfile = "";
}
if(!is_uploaded_file($uploadfile))
{
	ShowMsg("��û��ѡ���ϴ����ļ�!","-1");
	exit();
}
if(!ereg("^text",$uploadfile_type))
{
	ShowMsg("���ϴ��Ĳ����ı����͸���!","-1");
	exit();
}
if(!eregi("\.(".$cfg_txttype.")",$uploadfile_name))
{
	ShowMsg("�����ϴ���ģ���ļ����Ͳ��ܱ�ʶ��ֻ����htm��html��tpl��txt��չ����","-1");
	exit();
}
if($filename!='')
{
	$filename = trim(ereg_replace("[ \r\n\t\*\%\\/\?><\|\":]{1,}",'',$filename));
}
else
{
	$uploadfile_name = trim(ereg_replace("[ \r\n\t\*\%\\/\?><\|\":]{1,}",'',$uploadfile_name));
	$filename = $uploadfile_name;
	if($filename=='' || !eregi("\.(".$cfg_txttype.")",$filename))
	{
		ShowMsg("�����ϴ����ļ��������⣬�����ļ������Ƿ��ʺϣ�","-1");
		exit();
	}
}
$fullfilename = $cfg_basedir.$activepath."/".$filename;
move_uploaded_file($uploadfile,$fullfilename) or die("�ϴ��ļ��� $fullfilename ʧ�ܣ�");
@unlink($uploadfile);
ShowMsg("�ɹ��ϴ��ļ���","select_templets.php?comeback=".urlencode($filename)."&f=$f&activepath=".urlencode($activepath)."&d=".time());
exit();
?>
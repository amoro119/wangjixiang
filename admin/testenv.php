<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
if(empty($action))
{
	$action = '';
}
$needDir = "$cfg_medias_dir|
$cfg_image_dir|
$ddcfg_image_dir|
$cfg_user_dir|
$cfg_soft_dir|
$cfg_other_medias|
$cfg_medias_dir/flink|
$cfg_cmspath/data|
$cfg_cmspath/data/$cfg_backup_dir|
$cfg_cmspath/data/textdata|
$cfg_cmspath/data/sessions|
$cfg_cmspath/data/tplcache|
$cfg_cmspath/data/admin|
$cfg_cmspath/data/enums|
$cfg_cmspath/data/mark|
$cfg_cmspath/data/module|
$cfg_cmspath/data/rss|
$cfg_special|
$cfg_cmspath$cfg_arcdir";
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
<title>ϵͳĿ¼Ȩ�޼��������</title>
<link href='img/base.css' rel='stylesheet' type='text/css'>
</head>
<body background='img/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D1DDAA">
<tr>
    <td height="28" background="img/tbg.gif">
    	<b>ϵͳĿ¼Ȩ�޼��������</b>
    </td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" valign="top">
<?php
echo '<meta http-equiv="Content-Type" content="text/html; charset='.$cfg_soft_lang.'">';
if(($isSafeMode || $cfg_ftp_mkdir=='Y') && $cfg_ftp_host=='')
{
	echo "�������վ���PHP���ô������ƣ�����ֻ��ͨ��FTP��ʽ����Ŀ¼���������������ں�ָ̨��FTP��صı�����<br>";
	echo "<a href='sys_info.php'>&lt;&lt;�޸�ϵͳ����&gt;&gt;</a>";
	exit();
}
if($action=='')
{
	echo "�����򽫼������Ŀ¼�Ƿ���ڣ������Ƿ����д���Ȩ�ޣ������Դ�������ģ�<br>";
	echo "������������ʹ�õ���windowsϵͳ����������д˲�����<br>";
	echo "'/include' Ŀ¼�� '��ǰĿ¼/templets' �ļ���������FTP���ֹ�����Ȩ��Ϊ��д��(0777)<br>";
	echo "<pre>".str_replace('|','',$needDir)."</pre>";
	echo "</td></tr>\r\n<tr><td bgcolor='#F9FCEF' height='32px' style='padding-left:20px'>\r\n<a href='testenv.php?action=ok' class='np coolbg'>&lt;&lt;��ʼ���&gt;&gt;</a> &nbsp; <a href='index_body.php' class='np coolbg'>&lt;&lt;������ҳ&gt;&gt;</a>";
}
else
{
	$needDirs = explode('|',$needDir);
	$needDir = '';
	foreach($needDirs as $needDir)
	{
		$needDir = trim($needDir);
		$needDir = str_replace("\\","/",$needDir);
		$needDir = ereg_replace("/{1,}","/",$needDir);
		if(CreateDir($needDir))
		{
			echo "�ɹ����Ļ򴴽���{$needDir} <br>";
		}
		else
		{
			echo "���Ļ򴴽�Ŀ¼��{$needDir} <font color='red'>ʧ�ܣ�</font> <br>";
		}
	}
	echo "<br>������ָ��Ļ򴴽��������Ŀ����<a href='testenv.php?action=ok&play=".time()."'><u>����</u></a>���ֶ���½��FTP�������Ŀ¼��Ȩ��Ϊ777��666<br>";
	echo "</td></tr>\r\n<tr><td bgcolor='#F9FCEF' height='32px' style='padding-left:20px'>\r\n<a href='index_body.php' class='np coolbg'>&lt;&lt;������ҳ&gt;&gt;</a>";
	CloseFtp();
}
?>
</td>
</tr>
</table>
</body>
</html>
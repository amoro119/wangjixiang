<?php
//��ҳ�����ڼ���û���¼���������Ҫ�ֹ�����ϵͳ���ã������common.inc.php
require_once(dirname(__FILE__)."/../common.inc.php");
require_once(dirname(__FILE__)."/../userlogin.class.php");

//��õ�ǰ�ű����ƣ�������ϵͳ��������$_SERVER�����������и������ѡ��
$dedeNowurl   =  '';
$s_scriptName = '';
$isUrlOpen = @ini_get('allow_url_fopen');

$dedeNowurl = GetCurUrl();
$dedeNowurls = explode("?",$dedeNowurl);
$s_scriptName = $dedeNowurls[0];


//�����û���¼״̬
$cuserLogin = new userLogin();

if($cuserLogin->getUserID() <=0 )
{
	if(empty($adminDirHand))
	{
		ShowMsg("<b>��ʾ���������̨����Ŀ¼���ܵ�¼</b><br /><form>�������̨����Ŀ¼����<input type='hidden' name='gotopage' value='".urlencode($dedeNowurl)."' /><input type='text' name='adminDirHand' value='dede' style='width:120px;' /><input style='width:80px;' type='submit' name='sbt' value='ת���¼' /></form>", "javascript:;");
		exit();
	}
	$gurl = "../../{$adminDirHand}/login.php?gotopage=".urlencode($dedeNowurl);
	echo "<script language='javascript'>location='$gurl';</script>";
	exit();
}

//����Զ��վ���򴴽�FTP��
if($cfg_remote_site=='Y')
{
	require_once(DEDEINC.'/ftp.class.php');
	if(file_exists(DEDEDATA."/cache/inc_remote_config.php"))
	{
		require_once DEDEDATA."/cache/inc_remote_config.php";
	}
	if(empty($remoteuploads)) $remoteuploads = 0;
	if(empty($remoteupUrl)) $remoteupUrl = '';
	//��ʼ��FTP����
	$ftpconfig = array(
	    'hostname'=>$rmhost, 
	    'port'=>$rmport,
	    'username'=>$rmname,
	    'password'=>$rmpwd

	);
	$ftp = new FTP; 
	$ftp->connect($ftpconfig);
}

?>
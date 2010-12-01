<?php
@set_time_limit(0);
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/../include/dedemodule.class.php");
CheckPurview('sys_module');
if(empty($action)) $action = '';

if($action=='')
{
	$modules = array();
	require_once(dirname(__FILE__)."/templets/module_make.htm");
	exit();
}
/*---------
//���Hash��
function GetHash()
----------*/
else if($action=='gethash')
{
	echo md5($modulname.$email);
	exit();
}
/*-------------
//������Ŀ
function Makemodule()
--------------*/
else if($action=='make')
{
	$filelist = str_replace("\r","\n",trim($filelist));
	$filelist = trim(ereg_replace("[\n]{1,}","\n",$filelist));
	if($filelist==''){
		ShowMsg("�Բ�����û��ָ��ģ����ļ��б���˲��ܴ�����Ŀ��","-1");
		exit();
	}

	//ȥ��ת��
	foreach($_POST as $k=>$v) $$k = stripslashes($v);

	if(!isset($autosetup)) $autosetup = 0;
	if(!isset($autodel)) $autodel = 0;
	$mdir = DEDEROOT.'/data/module';
	$hashcode = md5($modulname.$email);
	$moduleFilename = $mdir.'/'.$hashcode.'.xml';
	$menustring = base64_encode($menustring);
	$dm = new DedeModule($mdir);

	if($dm->HasModule($hashcode))
	{
		$dm->Clear();
		ShowMsg("�Բ�����ָ��ͬ��ģ���Ѿ����ڣ���˲��ܴ�����Ŀ��<br>�����Ҫ�������ģ�飬����ɾ����module/{$hashcode}.xml","-1");
		exit();
	}

	$readmef = $setupf = $uninstallf = '';

	if(empty($readmetxt))
	{
		move_uploaded_file($readme,$mdir."/{$hashcode}-r.html") or die("��û��д˵�����ϴ�˵���ļ���");
		$readmef = $dm->GetEncodeFile($mdir."/{$hashcode}-r.html",true);
	}
	else
	{
		$readmetxt = "<p style='line-height:150%'>".$readmetxt;
		$readmetxt = ereg_replace("[\r\n]{1,}","<br />\r\n",$readmetxt);
		$readmetxt .= "</p>";
		$readmef = base64_encode(trim($readmetxt));
	}
	
	if($autosetup==0)
	{
	  move_uploaded_file($setup,$mdir."/{$hashcode}-s.php") or die("��û�ϴ�����ϵͳ�޷���setup�ļ��ƶ��� module Ŀ¼��");
	  $setupf = $dm->GetEncodeFile($mdir."/{$hashcode}-s.php",true);
	}

  if($autodel==0)
	{
	  move_uploaded_file($uninstall,$mdir."/{$hashcode}-u.php") or die("��û�ϴ�����ϵͳ�޷���uninstall�ļ��ƶ��� module Ŀ¼��");
	  $uninstallf = $dm->GetEncodeFile($mdir."/{$hashcode}-u.php",true);
	}

	if(trim($setupsql40)=='') $setupsql40 = '';
	else $setupsql40 = base64_encode(trim($setupsql40));

	//if(trim($setupsql41)=='') $setupsql41 = '';
	//else $setupsql41 = base64_encode(trim($setupsql41));

	if(trim($delsql)=='') $delsql = '';
	else $delsql = base64_encode(trim($delsql));

	$modulinfo = "<module>
<baseinfo>
name={$modulname}
team={$team}
time={$mtime}
email={$email}
url={$url}
hash={$hashcode}
indexname={$indexname}
indexurl={$indexurl}
ismember={$ismember}
autosetup={$autosetup}
autodel={$autodel}
lang={$lang}
moduletype={$moduletype}
</baseinfo>
<systemfile>
<menustring>
$menustring
</menustring>
<readme>
{$readmef}
</readme>
<setupsql40>
$setupsql40
</setupsql40>
<delsql>
$delsql
</delsql>
<setup>
{$setupf}
</setup>
<uninstall>
{$uninstallf}
</uninstall>
<oldfilelist>
$filelist
</oldfilelist>
</systemfile>
";

$filelists = explode("\n",$filelist);
foreach($filelists as $v)
{
  $v = trim($v);
  if(!empty($v)) $dm->MakeEncodeFileTest(dirname(__FILE__),$v);
}
//�����������밲װ��
$fp = fopen($moduleFilename,'w');
fwrite($fp,$modulinfo);
fwrite($fp,"<modulefiles>\r\n");
foreach($filelists as $v)
{
  $v = trim($v);
  if(!empty($v)) $dm->MakeEncodeFile(dirname(__FILE__),$v,$fp);
}
fwrite($fp,"</modulefiles>\r\n");
fwrite($fp,"</module>\r\n");
fclose($fp);
ShowMsg("�ɹ���һ����ģ����б��룡","module_main.php");
exit();
}/*-------------
//�޸���Ŀ
function editModule()
--------------*/
else if($action=='edit')
{
	$filelist = str_replace("\r","\n",trim($filelist));
	$filelist = trim(ereg_replace("[\n]{1,}","\n",$filelist));
	if($filelist==""){
		ShowMsg("�Բ�����û��ָ��ģ����ļ��б���˲��ܴ�����Ŀ��","-1");
		exit();
	}

	//�Ѿ�ȥ��ת��
	foreach($_POST as $k=>$v) $$k = stripslashes($v);
	if(!isset($autosetup)) $autosetup = 0;
	if(!isset($autodel)) $autodel = 0;
	$mdir = DEDEROOT.'/data/module';
	$hashcode = $hash;
	$moduleFilename = $mdir.'/'.$hashcode.'.xml';
	$modulname = str_replace('=','',$modulname);
	$email = str_replace('=','',$email);
	$team = str_replace('=','',$team);
	$indexurl = str_replace('=','',$indexurl);
	$menustring = base64_encode($menustring);

	$dm = new DedeModule($mdir);

  $readmef = base64_encode($readmetxt);

  $setupf = $uninstallf = '';
  //����setup�ļ�
  if(is_uploaded_file($setup)) {
	  move_uploaded_file($setup,$mdir."/{$hashcode}-s.php") or die("��û�ϴ�����ϵͳ�޷���setup�ļ��ƶ��� module Ŀ¼��");
	  $setupf = $dm->GetEncodeFile($mdir."/{$hashcode}-s.php",true);
	}
	else {
		if($autosetup==0) $setupf = base64_encode($dm->GetSystemFile($hashcode,'setup'));
	}

	 //����uninstall�ļ�
	if(is_uploaded_file($uninstall)) {
		move_uploaded_file($uninstall,$mdir."/{$hashcode}-u.php") or die("��û�ϴ�����ϵͳ�޷���uninstall�ļ��ƶ��� module Ŀ¼��");
    $uninstallf = $dm->GetEncodeFile($mdir."/{$hashcode}-u.php",true);
  }
  else {
  	if($autodel==0) $uninstallf = base64_encode($dm->GetSystemFile($hashcode,'uninstall'));
  }

	if(trim($setupsql40)=='') $setupsql40 = '';
	else $setupsql40 = base64_encode(trim($setupsql40));

	//if(trim($setupsql41)=='') $setupsql41 = '';
	//else $setupsql41 = base64_encode(trim($setupsql41));

	if(trim($delsql)=='') $delsql = '';
	else $delsql = base64_encode(trim($delsql));

	$modulinfo = "<module>
<baseinfo>
name={$modulname}
team={$team}
time={$mtime}
email={$email}
url={$url}
hash={$hashcode}
indexname={$indexname}
indexurl={$indexurl}
ismember={$ismember}
autosetup={$autosetup}
autodel={$autodel}
lang={$lang}
moduletype={$moduletype}
</baseinfo>
<systemfile>
<menustring>
$menustring
</menustring>
<readme>
{$readmef}
</readme>
<setupsql40>
$setupsql40
</setupsql40>
<delsql>
$delsql
</delsql>
<setup>
{$setupf}
</setup>
<uninstall>
{$uninstallf}
</uninstall>
<oldfilelist>
$filelist
</oldfilelist>
</systemfile>
";

if($rebuild=='yes')
{
	$filelists = explode("\n",$filelist);
	foreach($filelists as $v)
	{
  	$v = trim($v);
  	if(!empty($v)) $dm->MakeEncodeFileTest(dirname(__FILE__),$v);
	}
	//�����������밲װ��
	$fp = fopen($moduleFilename,'w');
	fwrite($fp,$modulinfo."\r\n");
	fwrite($fp,"<modulefiles>\r\n");
	foreach($filelists as $v)
	{
  	$v = trim($v);
  	if(!empty($v)) $dm->MakeEncodeFile(dirname(__FILE__),$v,$fp);
	}
	fwrite($fp,"</modulefiles>\r\n");
	fwrite($fp,"</module>\r\n");
	fclose($fp);
}
else
{
	$fxml = $dm->GetFileXml($hashcode);
	$fp = fopen($moduleFilename,'w');
	fwrite($fp,$modulinfo."\r\n");
	fwrite($fp,$fxml);
	fclose($fp);
}
ShowMsg("�ɹ���ģ�����±��룡","module_main.php");
exit();
}

//ClearAllLink();
?>
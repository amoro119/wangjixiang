<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");
if(empty($typeid))
{
	$typeid = 0;
}
$isremote = empty($isremote)? 0 : $isremote;
$serviterm=empty($serviterm)? "" : $serviterm;
if(empty($templet))
{
	$templet = "plus/js.htm";
}
if(empty($uptype))
{
	$uptype = "all";
}
if($cfg_remote_site=='Y' && $isremote=="1")
{	
	if($serviterm!=""){
		list($servurl,$servuser,$servpwd) = explode(',',$serviterm);
		$config=array( 'hostname' => $servurl, 'username' => $servuser, 'password' => $servpwd,'debug' => 'TRUE');
	}else{
		$config=array();
	}
	if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
}
if($uptype == "all")
{
	$row = $dsql->GetOne("Select id From #@__arctype where id>'$typeid' And ispart<>2 order by id asc limit 0,1;");
	if(!is_array($row))
	{
		echo "��������ļ����£�";
		exit();
	}
	else
	{
		$pv = new PartView($row['id']);
		$pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
		$pv->SaveToHtml($cfg_basedir.$cfg_cmspath."/data/js/".$row['id'].".js",$isremote);
		$typeid = $row['id'];;
		ShowMsg("�ɹ�����".$cfg_cmspath."/data/js/".$row['id'].".js���������в�����","makehtml_js_action.php?typeid=$typeid&isremote=$isremote&serviterm=$serviterm",0,100);
		exit();
	}
}else{
	$pv = new PartView($typeid);
	$pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
	$pv->SaveToHtml($cfg_basedir.$cfg_cmspath."/data/js/".$typeid.".js",$isremote);
	echo "�ɹ�����".$cfg_cmspath."/data/js/".$typeid.".js��";
	echo "Ԥ����";
	echo "<hr>";
	echo "<script src='".$cfg_cmspath."/data/js/".$typeid.".js'></script>";
	exit();
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");
if(empty($dopost))
{
	$dopost = '';
}

if($dopost=="view")
{
	$pv = new PartView();
	$templet = str_replace("{style}",$cfg_df_style,$templet);
	$pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
	$pv->Display();
	exit();
}
else if($dopost=="make")
{
	$remotepos = empty($remotepos)? '/index.html' : $remotepos;
	$isremote = empty($isremote)? 0 : $isremote;
	$serviterm = empty($serviterm)? "" : $serviterm;
	$homeFile = DEDEADMIN."/".$position;
	$homeFile = str_replace("\\","/",$homeFile);
	$homeFile = str_replace("//","/",$homeFile);
	$fp = fopen($homeFile,"w") or die("��ָ�����ļ��������⣬�޷������ļ�");
	fclose($fp);
	if($saveset==1)
	{
		$iquery = "update `#@__homepageset` set templet='$templet',position='$position' ";
		$dsql->ExecuteNoneQuery($iquery);
	}
	$templet = str_replace("{style}",$cfg_df_style,$templet);
	$pv = new PartView();
	$GLOBALS['_arclistEnv'] = 'index';
	$pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
	$pv->SaveToHtml($homeFile);
	
	echo "�ɹ�������ҳHTML��".$homeFile."<br />";
	if($serviterm ==""){
	  $config=array();
	}else{
		list($servurl,$servuser,$servpwd) = explode(',',$serviterm);
		$config=array( 'hostname' => $servurl, 'username' => $servuser, 'password' => $servpwd,'debug' => 'TRUE');
	}
	//�������Զ��վ�����ϴ�
  if($cfg_remote_site=='Y')
  {
  	if($ftp->connect($config) && $isremote == 1)
  	{
   	  if($ftp->upload($position, $remotepos, 'ascii')) echo "Զ�̷����ɹ�!"."<br />";
    }
  }
	echo "<a href='$position' target='_blank'>���...</a>";
	exit();
}
$row  = $dsql->GetOne("Select * From #@__homepageset");
include DedeInclude('templets/makehtml_homepage.htm');

?>
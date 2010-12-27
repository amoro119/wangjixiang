<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('plus_�ļ�������');

if(empty($acdir)) $acdir = $cfg_df_style;
$templetdir = $cfg_basedir.$cfg_templets_dir;
$templetdird = $templetdir.'/'.$acdir;
$templeturld = $cfg_templeturl.'/'.$acdir;

if(ereg("\.",$acdir))
{
	ShowMsg('Not Allow dir '.$acdir.'!','-1');
	exit();
}

//��ȡĬ���ļ�˵����Ϣ
function GetInfoArray($filename)
{
	$arrs = array();
	$dlist = file($filename);
	foreach($dlist as $d)
	{
		$d = trim($d);
		if($d!='')
		{
			list($dname,$info) = explode(',',$d);
			$arrs[$dname] = $info;
		}
	}
	return $arrs;
}

$dirlists  = GetInfoArray($templetdir.'/templet-dirlist.inc');
$filelists = GetInfoArray($templetdir.'/templet-filelist.inc');
$pluslists = GetInfoArray($templetdir.'/templet-pluslist.inc');
$fileinfos = ($acdir=='plus' ? $pluslists : $filelists);

include DedeInclude('templets/templets_default.htm');
?>
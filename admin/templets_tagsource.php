<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('plus_�ļ�������');

$libdir = DEDEINC.'/taglib';
$helpdir = DEDEINC.'/taglib/help';

//��ȡĬ���ļ�˵����Ϣ
function GetHelpInfo($tagname)
{
	global $helpdir;
	$helpfile = $helpdir.'/'.$tagname.'.txt';
	if(!file_exists($helpfile))
  {
    return '�ñ�ǩû������Ϣ';
  }
  $fp = fopen($helpfile,'r');
  $helpinfo = fgets($fp,64);
  fclose($fp);
	return $helpinfo;
}

include DedeInclude('templets/templets_tagsource.htm');
?>
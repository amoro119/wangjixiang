<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/arc.specview.class.php");
if(strlen($art_shortname)>6) exit("art_shortname too long!");
$specfile = dirname(__FILE__)."spec_1".$art_shortname;
//����Ѿ����뾲̬�б���ֱ�������һ���ļ�
if(file_exists($specfile))
{
	include($specfile);
	exit();
}
else
{
  $sp = new SpecView();
  $sp->Display();
}
?>
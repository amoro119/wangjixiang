<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/oxwindow.class.php");
CheckPurview('sys_Source');
if(empty($dopost))
{
	$dopost = '';
}
if(empty($allsource))
{
	$allsource = '';
}
else
{
	$allsource = stripslashes($allsource);
}
$m_file = DEDEDATA."/admin/source.txt";

//����
if($dopost=='save')
{
	$fp = fopen($m_file,'w');
	flock($fp,3);
	fwrite($fp,$allsource);
	fclose($fp);
	echo "<script>alert('Save OK!');</script>";
}
//����
if(empty($allsource) && filesize($m_file)>0)
{
	$fp = fopen($m_file,'r');
	$allsource = fread($fp,filesize($m_file));
	fclose($fp);
}
$wintitle = "������Դ����";
$wecome_info = "������Դ����";
$win = new OxWindow();
$win->Init('article_source_edit.php','js/blank.js','POST');
$win->AddHidden('dopost','save');
$win->AddTitle("ÿ�б���һ����Դ��");
$win->AddMsgItem("<textarea name='allsource' id='allsource' style='width:100%;height:300px'>$allsource</textarea>");
$winform = $win->GetWindow('ok');
$win->Display();

?>
<?php
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/oxwindow.class.php');
CheckPurview('sys_Writer');
if(empty($dopost))
{
	$dopost = '';
}
if(empty($allwriter))
{
	$allwriter = '';
}
else
{
	$allwriter = stripslashes($allwriter);
}
$m_file = DEDEDATA."/admin/writer.txt";

//����
if($dopost=="save")
{
	$fp = fopen($m_file,'w');
	flock($fp,3);
	fwrite($fp,$allwriter);
	fclose($fp);
	echo "<script>alert('Save OK!');</script>";
}

//����
if(empty($allwriter) && filesize($m_file)>0)
{
	$fp = fopen($m_file,'r');
	$allwriter = fread($fp,filesize($m_file));
	fclose($fp);
}
$wintitle = "�������߹���";
$wecome_info = "�������߹���";
$win = new OxWindow();
$win->Init('article_writer_edit.php','js/blank.js','POST');
$win->AddHidden('dopost','save');
$win->AddTitle("�����������ð�Ƕ��š�,���ֿ���");
$win->AddMsgItem("<textarea name='allwriter' id='allwriter' style='width:100%;height:300px'>$allwriter</textarea>");
$winform = $win->GetWindow('ok');
$win->Display();

?>
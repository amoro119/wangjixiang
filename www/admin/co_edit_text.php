<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('co_EditNote');
if(empty($job))
{
	$job='';
}
if($job=='')
{
	require_once(DEDEINC."/oxwindow.class.php");
	$wintitle = "���Ĳɼ�����";
	$wecome_info = "<a href='co_main.php'><u>�ɼ������</u></a>::���Ĳɼ����� - ר�Ҹ���ģʽ";
	$win = new OxWindow();
	$win->Init("co_edit_text.php","js/blank.js","POST");
	$win->AddHidden("job","yes");
	$win->AddHidden("nid",$nid);
	$row = $dsql->GetOne("Select * From `#@__co_note` where nid='$nid' ");
	$win->AddTitle("�����������Ϣ���ã�");
	$win->AddMsgItem("<textarea name='listconfig' style='width:100%;height:200px'>{$row['listconfig']}</textarea>");
	$win->AddTitle("�ֶ����ã�");
	$win->AddMsgItem("<textarea name='itemconfig' style='width:100%;height:300px'>{$row['itemconfig']}</textarea>");
	$winform = $win->GetWindow("ok");
	$win->Display();
	exit();
}
else
{
	CheckPurview('co_EditNote');
	$query = "update `#@__co_note` set listconfig='$listconfig',itemconfig='$itemconfig' where nid='$nid' ";
	$rs = $dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ��޸�һ������!","co_main.php");
	exit();
}
?>
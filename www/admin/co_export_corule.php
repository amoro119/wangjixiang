<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('co_EditNote');
require_once(DEDEINC."/oxwindow.class.php");
$nid = ereg_replace('[^0-9]','',$nid);
$row = $dsql->GetOne("Select * From `#@__co_note` where nid='$nid'");
$noteconfig = "{dede:listconfig}\r\n".$row['listconfig']."\r\n{/dede:listconfig}\r\n\r\n";
$noteconfig .= "{dede:itemconfig}\r\n".$row['itemconfig']."\r\n{/dede:itemconfig}";
if(empty($extype) || $extype=='base64')
{
	$noteconfig = "BASE64:".base64_encode($noteconfig).":END";
	$exmsg =  " &nbsp; <a href='co_export_corule.php?nid={$nid}&extype=text'>������Ϊ��ͨ��ʽ��</a> ";
}
else
{
	$exmsg =  " &nbsp; <a href='co_export_corule.php?nid={$nid}&extype=base64'>������ΪBase64��ʽ��</a> ";
}
$wintitle = "�����ɼ�����";
$wecome_info = "<a href='co_main.php'><u>�ɼ��ڵ����</u></a>::�����ɼ����� $exmsg";
$win = new OxWindow();
$win->Init();
$win->AddTitle("����Ϊ���� [{$row['notename']}] ���ı����ã�����Թ����������ѣ�");
$winform = $win->GetWindow("hand","<textarea name='config' style='width:100%;height:450px;word-wrap: break-word;word-break:break-all;'>".$noteconfig."</textarea>");
$win->Display();

?>
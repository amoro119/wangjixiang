<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_plus');
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$sql = "Select aid,plusname,writer,isshow From #@__plus order by aid asc";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/plus_main.htm");
$dlist->SetSource($sql);
$dlist->display();

function GetSta($sta,$id,$title)
{
	if($sta==1)
	{
		return " &nbsp; <a href='plus_edit.php?dopost=edit&aid=$id'><u>�޸�</u></a> &nbsp; ����  &gt; <a href='plus_edit.php?dopost=hide&aid=$id'><u>����</u></a> &nbsp; <a href='plus_edit.php?dopost=delete&aid=$id&title=".urlencode($title)."'><u>ɾ��</u></a>";
	}
	else
	{
		return " &nbsp; <a href='plus_edit.php?aid=$id'><u>�޸�</u></a> &nbsp; ���� &gt; <a href='plus_edit.php?dopost=show&aid=$id'><u>����</u></a> &nbsp; <a href='plus_edit.php?dopost=delete&aid=$id&title=".urlencode($title)."'><u>���</u></a>";
	}
}
?>
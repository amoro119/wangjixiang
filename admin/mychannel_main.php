<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_List');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$sql = "Select id,nid,typename,addtable,isshow,issystem From `#@__channeltype` order by id desc";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/mychannel_main.htm");
$dlist->SetSource($sql);
$dlist->display();

function GetSta($sta,$id)
{
	if($sta==1)
	{
		return ($id!=-1 ? "����  &gt; <a href='mychannel_edit.php?dopost=hide&id=$id'><u>����</u></a>" : "�̶���Ŀ");
	}
	else
	{
		return "���� &gt; <a href='mychannel_edit.php?dopost=show&id=$id'><u>����</u></a>";
	}
}

function IsSystem($s)
{
	return $s==1 ? "ϵͳ" : "�Զ�";
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Log');
if(empty($dopost))
{
	ShowMsg("��ûָ���κβ�����","javascript:;");
	exit();
}

//���������־
if($dopost=="clear")
{
	$dsql->ExecuteNoneQuery("Delete From #@__log");
	ShowMsg("�ɹ����������־��","log_list.php");
	exit();
}
else if($dopost=="del")
{
	$bkurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "log_list.php";
	$ids = explode('`',$ids);
	$dquery = "";
	foreach($ids as $id)
	{
		if($dquery=="")
		{
			$dquery .= " lid='$id' ";
		}
		else
		{
			$dquery .= " Or lid='$id' ";
		}
	}
	if($dquery!="") $dquery = " where ".$dquery;
	$dsql->ExecuteNoneQuery("Delete From #@__log $dquery");
	ShowMsg("�ɹ�ɾ��ָ������־��",$bkurl);
	exit();
}
else
{
	ShowMsg("�޷�ʶ���������","javascript:;");
	exit();
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Group');
if(empty($dopost))
{
	$dopost = "";
}
if($dopost=='save')
{
	if($rank==10)
	{
		ShowMsg("��������Ա��Ȩ�޲��������!","sys_group.php");
		exit();
	}
	$purview = "";
	if(is_array($purviews))
	{
		foreach($purviews as $p)
		{
			$purview .= "$p ";
		}
		$purview = trim($purview);
	}
	$dsql->ExecuteNoneQuery("Update `#@__admintype` set typename='$typename',purviews='$purview' where CONCAT(`rank`)='$rank'");
	ShowMsg("�ɹ������û����Ȩ��!","sys_group.php");
	exit();
}
else if($dopost=='del')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__admintype` where CONCAT(`rank`)='$rank' And system='0';");
	ShowMsg("�ɹ�ɾ��һ���û���!","sys_group.php");
	exit();
}
$groupRanks = Array();
$groupSet = $dsql->GetOne("Select * From `#@__admintype` where CONCAT(`rank`)='{$rank}' ");
$groupRanks = explode(' ',$groupSet['purviews']);
include DedeInclude('templets/sys_group_edit.htm');

//����Ƿ��Ѿ��д�Ȩ��
function CRank($n)
{
	global $groupRanks;
	return in_array($n,$groupRanks) ? ' checked' : '';
}

?>
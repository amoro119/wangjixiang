<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Group');
if(!empty($dopost))
{
	$row = $dsql->GetOne("Select * From #@__admintype where rank='".$rankid."'");
	if(is_array($row))
	{
		ShowMsg("�������������ļ���ֵ�Ѵ��ڣ��������ظ�!","-1");
		exit();
	}
	if($rankid > 10)
	{
		ShowMsg('�鼶��ֵ���ܴ���10�� ����һ��Ȩ�����þ���Ч!', '-1');
		exit();
	}
	$AllPurviews = '';
	if(is_array($purviews))
	{
		foreach($purviews as $pur)
		{
			$AllPurviews = $pur.' ';
		}
		$AllPurviews = trim($AllPurviews);
	}
	$dsql->ExecuteNoneQuery("INSERT INTO #@__admintype(rank,typename,system,purviews) VALUES ('$rankid','$groupname', 0, '$AllPurviews');");
	ShowMsg("�ɹ�����һ���µ��û���!","sys_group.php");
	exit();
}
include DedeInclude('templets/sys_group_add.htm');

?>
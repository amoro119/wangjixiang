<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('sys_Task');
if(empty($dopost)) $dopost = '';

if($dopost=='save')
{
	$starttime = empty($starttime) ? 0 : GetMkTime($starttime);
	$endtime = empty($endtime) ? 0 : GetMkTime($endtime);
	$runtime = $h.':'.$m;
	$Query = "Insert Into `#@__sys_task`(`taskname`,`dourl`,`islock`,`runtype`,`runtime`,`starttime`,`endtime`,`freq`,`lastrun`,`description`,`parameter`,`settime`)
		Values('$taskname', '$dourl', '$nislock', '$runtype', '$runtime', '$starttime', '$endtime','$freq', '0', '$description','$parameter', '".time()."'); ";
	$rs = $dsql->ExecuteNoneQuery($Query);
	if($rs) 
	{
		ShowMsg('�ɹ�����һ������!', 'sys_task.php');
	}
	else
	{
		ShowMsg('��������ʧ��!'.$dsql->GetError(), 'javascript:;');
	}
	exit();
}

include DedeInclude('templets/sys_task_add.htm');

?>
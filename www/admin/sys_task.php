<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Task');
if(empty($dopost))	$dopost = '';

//ɾ��
if($dopost=='del')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__sys_task` where id='$id' ");
	ShowMsg("�ɹ�ɾ��һ������", "sys_task.php");
	exit();
}

include DedeInclude('templets/sys_task.htm');

?>
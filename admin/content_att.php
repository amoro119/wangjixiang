<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Att');
if(empty($dopost))
{
	$dopost = '';
}
//�������
if($dopost=="save")
{
	$startID = 1;
	$endID = $idend;
	for(; $startID<=$endID; $startID++)
	{
		$att = ${'att_'.$startID};
		$attname = ${'attname_'.$startID};
		$sortid = ${'sortid_'.$startID};
		$query = "update `#@__arcatt` set `attname`='$attname',`sortid`='$sortid' where att='$att' ";
		$dsql->ExecuteNoneQuery($query);
	}
	echo "<script> alert('�ɹ������Զ��ĵ������Ա�'); </script>";
}

include DedeInclude('templets/content_att.htm');

?>
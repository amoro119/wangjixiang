<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Data');
if(empty($dopost))
{
	$dopost = '';
}
if($dopost=="viewinfo") //�鿴��ṹ
{
	echo "[<a href='#' onclick='javascript:HideObj(\"_mydatainfo\")'><u>�ر�</u></a>]\r\n<xmp>";
	if(empty($tablename))
	{
		echo "û��ָ��������";
	}
	else
	{
		$dsql->SetQuery("SHOW CREATE TABLE ".$dsql->dbName.".".$tablename);
		$dsql->Execute('me');
		$row2 = $dsql->GetArray('me',MYSQL_BOTH);
		$ctinfo = $row2[1];
		echo trim($ctinfo);
	}
	echo '</xmp>';
	exit();
}
else if($dopost=="opimize") //�Ż���
{
	echo "[<a href='#' onclick='javascript:HideObj(\"_mydatainfo\")'><u>�ر�</u></a>]\r\n<xmp>";
	if(empty($tablename))
	{
		echo "û��ָ��������";
	}
	else
	{
		$rs = $dsql->ExecuteNoneQuery("OPTIMIZE TABLE `$tablename` ");
		if($rs)
		{
			echo "ִ���Ż��� $tablename  OK��";
		}
		else
		{
			echo "ִ���Ż��� $tablename  ʧ�ܣ�ԭ���ǣ�".$dsql->GetError();
		}
	}
	echo '</xmp>';
	exit();
}
else if($dopost=="repair") //�޸���
{
	echo "[<a href='#' onclick='javascript:HideObj(\"_mydatainfo\")'><u>�ر�</u></a>]\r\n<xmp>";
	if(empty($tablename))
	{
		echo "û��ָ��������";
	}
	else
	{
		$rs = $dsql->ExecuteNoneQuery("REPAIR TABLE `$tablename` ");
		if($rs)
		{
			echo "�޸��� $tablename  OK��";
		}
		else
		{
			echo "�޸��� $tablename  ʧ�ܣ�ԭ���ǣ�".$dsql->GetError();
		}
	}
	echo '</xmp>';
	exit();
}

//��ȡϵͳ���ڵı���Ϣ
$otherTables = Array();
$dedeSysTables = Array();
$channelTables = Array();
$dsql->SetQuery("Select addtable From `#@__channeltype` ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$channelTables[] = $row->addtable;
}
$dsql->SetQuery("Show Tables");
$dsql->Execute('t');
while($row = $dsql->GetArray('t',MYSQL_BOTH))
{
	if(ereg("^{$cfg_dbprefix}",$row[0])||in_array($row[0],$channelTables))
	{
		$dedeSysTables[] = $row[0];
	}
	else
	{
		$otherTables[] = $row[0];
	}
}
$mysql_version = $dsql->GetVersion();
include DedeInclude('templets/sys_data.htm');

function TjCount($tbname,&$dsql)
{
	$row = $dsql->GetOne("Select count(*) as dd From $tbname");
	return $row['dd'];
}
?>
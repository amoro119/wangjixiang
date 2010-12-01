<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
if(empty($dopost))
{
	$dopost = "";
}
$configfile = DEDEDATA.'/config.cache.inc.php';

//�������ú���
function ReWriteConfig()
{
	global $dsql,$configfile;
	if(!is_writeable($configfile))
	{
		echo "�����ļ�'{$configfile}'��֧��д�룬�޷��޸�ϵͳ���ò�����";
		exit();
	}
	$fp = fopen($configfile,'w');
	flock($fp,3);
	fwrite($fp,"<"."?php\r\n");
	$dsql->SetQuery("Select `varname`,`type`,`value`,`groupid` From `#@__sysconfig` order by aid asc ");
	$dsql->Execute();
	while($row = $dsql->GetArray())
	{
		if($row['type']=='number')
		{
			if($row['value']=='') $row['value'] = 0;
			fwrite($fp,"\${$row['varname']} = ".$row['value'].";\r\n");
		}
		else
		{
			fwrite($fp,"\${$row['varname']} = '".str_replace("'",'',$row['value'])."';\r\n");
		}
	}
	fwrite($fp,"?".">");
	fclose($fp);
}

//�������õĸĶ�
if($dopost=="save")
{
	foreach($_POST as $k=>$v)
	{
		if(ereg("^edit___",$k))
		{
			$v = cn_substrR(${$k}, 1024);
		}
		else
		{
			continue;
		}
		$k = ereg_replace("^edit___","",$k);
		$dsql->ExecuteNoneQuery("Update `#@__sysconfig` set `value`='$v' where varname='$k' ");
	}
	ReWriteConfig();
	ShowMsg("�ɹ�����վ�����ã�","sys_info.php");
	exit();
}

//�����±���
else if($dopost=='add')
{
	if($vartype=='bool' && ($nvarvalue!='Y' && $nvarvalue!='N'))
	{
		ShowMsg("��������ֵ����Ϊ'Y'��'N'!","-1");
		exit();
	}
	if(trim($nvarname)=='' || eregi('[^a-z_]', $nvarname) )
	{
		ShowMsg("����������Ϊ�ղ��ұ���Ϊ[a-z_]���!","-1");
		exit();
	}
	$row = $dsql->GetOne("Select varname From `#@__sysconfig` where varname like '$nvarname' ");
	if(is_array($row))
	{
		ShowMsg("�ñ��������Ѿ�����!","-1");
		exit();
	}
	$row = $dsql->GetOne("Select aid From `#@__sysconfig` order by aid desc ");
	$aid = $row['aid']+1;
	$inquery = "INSERT INTO `#@__sysconfig`(`aid`,`varname`,`info`,`value`,`type`,`groupid`)
    VALUES ('$aid','$nvarname','$varmsg','$nvarvalue','$vartype','$vargroup')";
	$rs = $dsql->ExecuteNoneQuery($inquery);
	if(!$rs)
	{
		ShowMsg("��������ʧ�ܣ������зǷ��ַ���","sys_info.php?gp=$vargroup");
		exit();
	}
	if(!is_writeable($configfile))
	{
		ShowMsg("�ɹ���������������� $configfile �޷�д�룬��˲��ܸ��������ļ���","sys_info.php?gp=$vargroup");
		exit();
	}else
	{
		ReWriteConfig();
		ShowMsg("�ɹ�������������������ļ���","sys_info.php?gp=$vargroup");
		exit();
	}
}
include DedeInclude('templets/sys_info.htm');

?>
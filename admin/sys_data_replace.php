<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('sys_Data');
if(empty($action))
{
	$action = '';
}
if(empty($action))
{
	require_once(DEDEADMIN."/templets/sys_data_replace.htm");
	exit();
}

/*-------------------------------
//�г����ݿ������ֶ�
function __getfields()
--------------------------------*/
else if($action=='getfields')
{
	AjaxHead();
	$dsql->GetTableFields($exptable);
	echo "<div style='border:1px solid #ababab;background-color:#FEFFF0;margin-top:6px;padding:3px;line-height:160%'>";
	echo "��(".$exptable.")���е��ֶΣ�<br>";
	while($row = $dsql->GetFieldObject())
	{
		echo "<a href=\"javascript:pf('{$row->name}')\"><u>".$row->name."</u></a>\r\n";
	}
	echo "</div>";
	exit();
}

/*-------------------------------
//�����û����ã���ջ�Ա����
function __Apply()
--------------------------------*/
else if($action=='apply')
{
	$validate = empty($validate) ? '' : strtolower($validate);
	$svali = GetCkVdValue();
	if($validate=="" || $validate!=$svali)
	{
		ShowMsg("��ȫȷ���벻��ȷ!","javascript:;");
		exit();
	}
	if($exptable==''||$rpfield=='')
	{
		ShowMsg("��ָ�����ݱ���ֶΣ�","javascript:;");
		exit();
	}
	if($rpstring=='')
	{
		ShowMsg("��ָ�����滻���ݣ�","javascript:;");
		exit();
	}
	if($rptype=='replace')
	{
		$condition = empty($condition) ? '' : " where $condition ";
		$rs = $dsql->ExecuteNoneQuery("Update $exptable set $rpfield=Replace($rpfield,'$rpstring','$tostring') $condition ");
		$dsql->executenonequery("OPTIMIZE TABLE `$exptable`");
		if($rs)
		{
			ShowMsg("�ɹ���������滻��","javascript:;");
			exit();
		}
		else
		{
			ShowMsg("�����滻ʧ�ܣ�","javascript:;");
			exit();
		}
	}
	else
	{
		$condition = empty($condition) ? '' : " And $condition ";
		$rpstring = stripslashes($rpstring);
		$rpstring2 = str_replace("\\","\\\\",$rpstring);
		$rpstring2 = str_replace("'","\\'",$rpstring2);
		$dsql->SetQuery("Select $keyfield,$rpfield From $exptable where $rpfield REGEXP '$rpstring2'  $condition ");
		$dsql->Execute();
		$tt = $dsql->GetTotalRow();
		if($tt==0)
		{
			ShowMsg("������ָ���������Ҳ����κζ�����","javascript:;");
			exit();
		}
		$oo = 0;
		while($row = $dsql->GetArray())
		{
			$kid = $row[$keyfield];
			$rpf = eregi_replace($rpstring,$tostring,$row[$rpfield]);
			$rs = $dsql->ExecuteNoneQuery("Update $exptable set $rpfield='$rpf' where $keyfield='$kid' ");
			if($rs)
			{
				$oo++;
			}
		}
		$dsql->executenonequery("OPTIMIZE TABLE `$exptable`");
		ShowMsg("���ҵ� $tt ����¼���ɹ��滻�� $oo ����","javascript:;");
		exit();
	}
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
if(empty($dopost))
{
	$dopost = "";
}

//�������
if($dopost=="save")
{
	$startID = 1;
	$endID = $idend;
	for(;$startID<=$endID;$startID++)
	{
		$query = '';
		$aid = ${'ID_'.$startID};
		$pname =   ${'pname_'.$startID};
		$rank =    ${'rank_'.$startID};
		$money =   ${'money_'.$startID};
		$exptime = ${'exptime_'.$startID};
		if(isset(${'check_'.$startID}))
		{
			if($pname!='')
			{
				$query = "update #@__member_type set pname='$pname',money='$money',rank='$rank',exptime='$exptime' where aid='$aid'";
			}
		}
		else
		{
			$query = "Delete From #@__member_type where aid='$aid' ";
		}
		if($query!='')
		{
			$dsql->ExecuteNoneQuery($query);
		}
	}

	//�����¼�¼
	if(isset($check_new) && $pname_new!='')
	{
		$query = "Insert Into #@__member_type(rank,pname,money,exptime) Values('{$rank_new}','{$pname_new}','{$money_new}','{$exptime_new}');";
		$dsql->ExecuteNoneQuery($query);
	}
	header("Content-Type: text/html; charset={$cfg_soft_lang}");
	echo "<script> alert('�ɹ����»�Ա��Ʒ�����'); </script>";
}
$arcranks = array();
$dsql->SetQuery("Select * From #@__arcrank where rank>10 ");
$dsql->Execute();
while($row=$dsql->GetArray())
{
	$arcranks[$row['rank']] = $row['membername'];
}

$times = array();
$times[7] = 'һ��';
$times[30] = 'һ����';
$times[90] = '������';
$times[183] = '����';
$times[366] = 'һ��';
$times[32767] = '����';

require_once(DEDEADMIN."/templets/member_type.htm");

?>
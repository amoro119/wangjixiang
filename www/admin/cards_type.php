<?php
require_once(dirname(__FILE__).'/config.php');
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
		$tid = ${'ID_'.$startID};
		$pname =   ${'pname_'.$startID};
		$money =    ${'money_'.$startID};
		$num =   ${'num_'.$startID};
		if(isset(${'check_'.$startID}))
		{
			if($pname!='')
			{
				$query = "update #@__moneycard_type set pname='$pname',money='$money',num='$num' where tid='$tid'";
				$dsql->ExecuteNoneQuery($query);
				$query = "update #@__moneycard_record set money='$money',num='$num' where ctid='$tid' ; ";
				$dsql->ExecuteNoneQuery($query);
			}
		}
		else
		{
			$query = "Delete From #@__moneycard_type where tid='$tid' ";
			$dsql->ExecuteNoneQuery($query);
			$query = "Delete From #@__moneycard_record where ctid='$tid' And isexp<>-1 ; ";
			$dsql->ExecuteNoneQuery($query);
		}
	}

	//�����¼�¼
	if(isset($check_new) && $pname_new!='')
	{
		$query = "Insert Into #@__moneycard_type(num,pname,money) Values('{$num_new}','{$pname_new}','{$money_new}');";
		$dsql->ExecuteNoneQuery($query);
	}
	header("Content-Type: text/html; charset={$cfg_soft_lang}");
	echo "<script> alert('�ɹ����µ㿨��Ʒ�����'); </script>";
}

require_once(DEDEADMIN."/templets/cards_type.htm");

?>
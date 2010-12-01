<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('shops_Operations');
require_once(DEDEINC.'/datalistcp.class.php');
if(isset($dopost))
{
	CheckPurview('shops_Operations_cpanel');
	if($dopost == 'up')
	{
		$nids = explode('`',$nid);
		$wh = '';
		foreach($nids as $n)
		{
			if($wh=='')
			{
				$wh = " WHERE oid='$n' ";
			}
			else
			{
				$wh .= " OR oid='$n' ";
			}
		}
		$sql="UPDATE #@__shops_orders SET `state`='1' $wh ";
		$dsql->ExecuteNoneQuery($sql);
	}
	elseif($dopost == 'push')
	{
		$nids = explode('`',$nid);
		$wh = '';
		foreach($nids as $n)
		{
			if($wh=='')
			{
				$wh = " WHERE oid='$n' ";
			}
			else
			{
				$wh .= " OR oid='$n' ";
			}
		}
		$sql="UPDATE #@__shops_orders SET `state`='2' $wh ";
		$dsql->ExecuteNoneQuery($sql);
	}
	elseif($dopost == 'ok')
	{
		$nids = explode('`',$nid);
		$wh = '';
		foreach($nids as $n)
		{
			if($wh=='')
			{
				$wh = " WHERE oid='$n' ";
			}
			else
			{
				$wh .= " OR oid='$n' ";
			}
		}
		$sql="UPDATE #@__shops_orders SET `state`='4' $wh ";
		$dsql->ExecuteNoneQuery($sql);
	}
	elseif($dopost == 'delete')
	{
		$nids = explode('`',$nid);
		foreach($nids as $n)
		{
			$query = "DELETE FROM `#@__shops_products` WHERE oid='$n'";
			$query2 = "DELETE FROM `#@__shops_orders` WHERE oid='$n'";
			$query3 = "DELETE FROM `#@__shops_userinfo` WHERE oid='$n'";
			$dsql->ExecuteNoneQuery($query);
			$dsql->ExecuteNoneQuery($query2);
			$dsql->ExecuteNoneQuery($query3);
		}
		ShowMsg("�ɹ�ɾ��ָ���Ķ�����¼��",$ENV_GOBACK_URL);
		exit();

	}
	else
	{
		ShowMsg("������Ĳ�����Χ��",$ENV_GOBACK_URL);
		exit();
	}
	ShowMsg("�ɹ�����ָ���Ķ�����¼��",$ENV_GOBACK_URL);
	exit();
}
$addsql = '';
if(empty($oid))
{
	$oid = 0;
}
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
if(isset($buyid))
{
	$buyid 	= ereg_replace("[^-0-9A-Z]","",$buyid);
	$addsql = "WHERE s.oid='".$buyid."'";
}
if(isset($sta))
{
	$addsql = "WHERE s.`state`='$sta'";
}
$sql = "SELECT s.`oid`,s.`cartcount`,s.`price`,s.`state`,s.`stime`,s.priceCount,s.dprice,s.paytype,u.`consignee`,u.`tel`,s.`userid` FROM #@__shops_orders AS s LEFT JOIN #@__shops_userinfo AS u ON s.oid=u.oid $addsql ORDER BY `stime` DESC";
$dlist = new DataListCP();
$dlist->SetParameter("oid",$oid);
if(isset($sta))
{
	$dlist->SetParameter("sta",$sta);
}
$tplfile = DEDEADMIN."/templets/shops_operations.htm";

//�������˳���ܸ���
$dlist->SetTemplate($tplfile);      //����ģ��
$dlist->SetSource($sql);            //�趨��ѯSQLexit('dd');
$dlist->Display();

function GetSta($sta)
{
	if($sta==0)
	{
		return 'δ����';
	}
	else if($sta==1)
	{
		return '�Ѹ���';
	}
	else if($sta==2)
	{
		return '�ѷ���';
	}
	else if($sta==3)
	{
		return '��ȷ��';
	}
	else
	{
		return '�����';
	}
}

function GetsType($pid)
{
	global $dsql;
	$pid = intval($pid);
	$row = $dsql->GetOne("SELECT name FROM #@__payment WHERE id='$pid'");
	if(is_array($row))
	{
		return $row['name'];
	}
	else
	{
		return '-';
	}
}

function GetMemberID($mid)
{
	global $dsql;
	if($mid==0)
	{
		return '0';
	}
	$row = $dsql->GetOne("Select userid From #@__member where mid='$mid' ");
	if(is_array($row))
	{
		return "<a href='member_view.php?id={$mid}'>".$row['userid']."</a>";
	}
	else
	{
		return '0';
	}
}

?>
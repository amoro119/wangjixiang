<?php 
require_once(dirname(__FILE__)."/config.php");
include_once DEDEINC.'/datalistcp.class.php';
$menutype = 'mydede';
$menutype_son = 'op';
if(!isset($dopost))
{
	$dopost = '';
}
if($dopost=='')
{
	$do = isset($do) ? trim($do) : '';
	$oid = isset($oid) ? eregi_replace("[^-0-9A-Z]","",$oid) : '';
	$addsql = '';
	if(!empty($oid))
	{
		if($do=='ok')
		{
			$dsql->ExecuteNoneQuery("UPDATE #@__shops_orders SET `state`='4' WHERE oid='$oid'");
			ShowMsg("��ȷ�϶�����",'shops_products.php?oid='.$oid);
			exit();
		}
		
		$row = $dsql->GetOne("SELECT * FROM #@__shops_userinfo WHERE userid='".$cfg_ml->M_ID."' AND oid='$oid'");
		if(!isset($row['oid']))
		{
			ShowMsg("���������ڣ�",-1);
			exit();
		}
		$row['des'] = stripslashes($row['des']);
		$rs = $dsql->GetOne("SELECT * FROM #@__shops_orders WHERE userid='".$cfg_ml->M_ID."' AND oid='$oid'");
		$row['state'] = $rs['state'];
		$row['stime'] = $rs['stime'];
		$row['cartcount'] = $rs['cartcount'];
		$row['price'] = $rs['price'];
		$row['uprice'] = $rs['price']/$rs['cartcount'];
		$row['dprice'] = $rs['dprice'];
		$row['priceCount'] = $rs['priceCount'];
		$rs = $dsql->GetOne("SELECT `dname` FROM #@__shops_delivery WHERE pid='$rs[pid]' LIMIT 0,1");
		$row['dname'] = $rs['dname'];
		unset($rs);
		$addsql = " AND oid='".$oid."'";
	}
	
	$sql = "SELECT * FROM #@__shops_products WHERE userid='".$cfg_ml->M_ID."' $addsql ORDER BY aid ASC";
	$dl = new DataListCP();
	$dl->pageSize = 20;
	if(!empty($oid)) $dl->SetParameter('oid',$oid);
	//�������˳���ܸ���
	$dl->SetTemplate(dirname(__FILE__)."/templets/shops_products.htm");      //����ģ��
	$dl->SetSource($sql);            //�趨��ѯSQL
	$dl->Display(); 
}
elseif($dopost=='del')
{
	$ids = explode(',',$ids);
	if(isset($ids) && is_array($ids))
	{
		foreach($ids as $id)
		{
			$id = preg_replace("/^[a-z][0-9]$/","",$id);
			$query = "DELETE FROM `#@__shops_products` WHERE oid='$id' AND userid='{$cfg_ml->M_ID}'";
			$query2 = "DELETE FROM `#@__shops_orders` WHERE oid='$id' AND userid='{$cfg_ml->M_ID}'";
			$query3 = "DELETE FROM `#@__shops_userinfo` WHERE oid='$id' AND userid='{$cfg_ml->M_ID}'";
			$dsql->ExecuteNoneQuery($query);
			$dsql->ExecuteNoneQuery($query2);
			$dsql->ExecuteNoneQuery($query3);
		}
		ShowMsg("�ɹ�ɾ��ָ���Ľ��׼�¼!","shops_products.php");
	  exit();
	}
}

function GetSta($sta,$oid)
{
	global $dsql;
	$row = $dsql->GetOne("SELECT paytype FROM #@__shops_orders WHERE oid='$oid'");
	$payname = $dsql->GetOne("SELECT name,fee FROM #@__payment WHERE id='{$row['paytype']}'");
	if($sta==0)
	{
		return $payname['name']." ������:".$payname['fee']."Ԫ";
	}
	elseif($sta==1)
	{
		return '�Ѹ���,�ȷ���';
	}
	elseif($sta==2)
	{
		return '<a href="shops_products.php?do=ok&oid='.$oid.'">ȷ��</a>';
	}
	else
	{
		return '�����';
	}
}

function carTime($oid)
{
	global $dsql;
	$row = $dsql->GetOne("SELECT stime FROM #@__shops_orders WHERE oid='$oid'");
	return Mydate('Y-m-d h:i:s',$row['stime']);
}
?>
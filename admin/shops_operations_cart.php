<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckPurview('shops_Operations');
if(!isset($oid)){
	exit("<a href='javascript:window.close()'>��Ч����!</a>");
}
$oid 	= ereg_replace("[^-0-9A-Z]","",$oid);
if(empty($oid)){
	exit("<a href='javascript:window.close()'>��Ч������!</a>");
}

$row = $dsql->GetOne("SELECT * FROM #@__shops_userinfo WHERE oid='$oid'");

$sql="SELECT o.*,p.title,p.price as uprice,d.dname FROM #@__shops_orders as o left join #@__shops_products as p on o.oid=p.oid left join #@__shops_delivery as d on d.pid=o.pid WHERE o.oid='$oid'";

$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("oid",$oid);
$dlist->SetTemplate(DEDEADMIN."/templets/shops_operations_cart.htm");
$dlist->SetSource($sql);
$dlist->Display();
$dlist->Close();

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
		return '<font color="red">�Ѹ���,�ȷ���</font>';
	}
	elseif($sta==2)
	{
		return '<a href="shops_products.php?do=ok&oid='.$oid.'">ȷ��</a>';
	}
	else
	{
		return '<font color="red">�����</font>';
	}
}
?>
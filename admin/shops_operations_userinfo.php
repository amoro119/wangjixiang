<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('shops_Operations');
if(!isset($oid)){
	exit("<a href='javascript:window.close()'>��Ч����!</a>");
}
$oid 	= ereg_replace("[^-0-9A-Z]","",$oid);
if(empty($oid)){
	exit("<a href='javascript:window.close()'>��Ч������!</a>");
}
$rows = $dsql->GetOne("SELECT * FROM #@__shops_userinfo WHERE oid='$oid' LIMIT 0,1");
if(!is_array($rows)){
	$dsql->Close();
	exit("<a href='javascript:window.close()'>�ö�����û����û���Ϣ!</a>");
}

$row = $dsql->GetOne("SELECT pid,dprice FROM #@__shops_orders WHERE oid='$oid'");
if(is_array($row)){
	$rs = $dsql->GetOne("SELECT dname FROM #@__shops_delivery WHERE pid='$row[pid]'");
	$rows['dname'] = $rs['dname'];
	$rows['dprice'] = $row['dprice'];
}
$rows['des'] = stripslashes($rows['des']);
include DEDEADMIN."/templets/shops_operations_userinfo.htm";
unset($rows);
?>
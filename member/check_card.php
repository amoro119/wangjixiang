<?php 
require_once(dirname(__FILE__)."/config.php");
$svali = GetCkVdValue();

if(strtolower($vdcode)!=$svali || $svali=="")
{
	ShowMsg("��֤�����","-1");
	exit();
}

$cardid = ereg_replace("[^0-9A-Za-z-]","",$cardid);
if(empty($cardid))
{
	ShowMsg("����Ϊ�գ�","-1");
	exit();
}

$row = $dsql->GetOne("SELECT * FROM #@__moneycard_record WHERE cardid='$cardid' ");

if(!is_array($row))
{
	ShowMsg("���Ŵ��󣺲����ڴ˿��ţ�","-1");
	exit();
}

if($row['isexp']==-1)
{
	ShowMsg("�˿����Ѿ�ʧЧ�������ٴ�ʹ�ã�","-1");
	exit();
}

$hasMoney = $row['num'];
$dsql->ExecuteNoneQuery("UPDATE #@__moneycard_record SET uid='".$cfg_ml->M_ID."',isexp='-1',utime='".time()."' WHERE cardid='$cardid' ");
$dsql->ExecuteNoneQuery("UPDATE #@__member SET money=money+$hasMoney WHERE mid='".$cfg_ml->M_ID."'");

ShowMsg("��ֵ�ɹ����㱾�����ӵĽ��Ϊ��{$hasMoney} ����",-1);
exit();
?>
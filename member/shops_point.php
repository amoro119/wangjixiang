<?php 
require_once(dirname(__FILE__)."/config.php");
if(isset($oid))
{
	$oid = eregi_replace("[^-0-9A-Z]","",$oid);
	$rs = $dsql->GetOne("SELECT paytype,priceCount FROM #@__shops_orders WHERE userid='".$cfg_ml->M_ID."' AND oid='$oid'");
	if($rs['paytype']!=5)
	{
		ShowMsg("������֧�ָ�֧����ʽ��","javascript:;");
		exit();
	}
	$priceCount = $row['priceCount'];
	
	$members = $dsql->GetOne("SELECT `money` FROM #@__member WHERE mid='".$cfg_ml->M_ID."'");
	if($members['money'] < $priceCount)
	{
		ShowMsg("֧��ʧ�ܵ���������","-1");
		exit();
	}

	if($dsql->ExecuteNoneQuery("UPDATE `#@__shops_orders` SET `state`='1' WHERE `oid`='$oid' AND `userid`='".$cfg_ml->M_ID."' AND `state`<1"))
	{
		$res = $dsql->ExecuteNoneQuery("UPDATE #@__member SET money=money-$priceCount WHERE mid='{$cfg_ml->M_ID}'");
		ShowMsg("�µ�,֧���ɹ�,�ȴ��̼ҷ�����","../member/shops_products.php?oid=".$oid);
		exit();
	}
	else
	{
		ShowMsg("֧��ʧ��,����ϵ����Ա��","-1");
		exit();
	}
}
else
{
	exit("403 Forbidden!");
}
?>
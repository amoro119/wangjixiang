<?php 
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'op';
setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
if(!isset($dopost))
{
	$dopost = '';
}
function GetSta($sta){
	if($sta==0) return 'δ����';
	else if($sta==1) return '�Ѹ���';
	else return '�����';
}
if($dopost=='')
{
	$sql = "Select * From `#@__member_operation` where mid='".$cfg_ml->M_ID."' And product<>'archive' order by aid desc";
	$dlist = new DataListCP();
	$dlist->pageSize = 20;
	$dlist->SetTemplate(DEDEMEMBER."/templets/operation.htm");    
	$dlist->SetSource($sql);
	$dlist->Display(); 
}
elseif($dopost=='del')
{
	$ids = ereg_replace("[^0-9,]","",$ids);
	$query = "Delete From `#@__member_operation` where aid in($ids) And mid='{$cfg_ml->M_ID}'";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�ɾ��ָ���Ľ��׼�¼!","operation.php");
	exit();
}
?>

<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_User');
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
if(empty($rank))
{
	$rank = '';
}
else
{
	$rank = " where CONCAT(#@__admin.usertype)='$rank' ";
}
$dsql->SetQuery("select rank,typename From `#@__admintype` ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$adminRanks[$row->rank] = $row->typename;
}
$query = "Select #@__admin.*,#@__arctype.typename From #@__admin left join #@__arctype on #@__admin.typeid=#@__arctype.id $rank ";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/sys_admin_user.htm");
$dlist->SetSource($query);
$dlist->Display();

function GetUserType($trank)
{
	global $adminRanks;
	if(isset($adminRanks[$trank]))
	{
		return $adminRanks[$trank];
	}
	else
	{
		return "��������";
	}
}

function GetChannel($c)
{
	if($c==""||$c==0)
	{
		return "����Ƶ��";
	}
	else
	{
		return $c;
	}
}

?>
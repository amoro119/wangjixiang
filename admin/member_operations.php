<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Operations');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($buyid))
{
	$buyid = '';
}
$addsql = " where buyid like '%$buyid%' ";
if(isset($sta))
{
	$addsql .= " And sta='$sta' ";
}
$sql = "Select * From `#@__member_operation` $addsql order by aid desc";
$dlist = new DataListCP();

//�趨ÿҳ��ʾ��¼����Ĭ��25����
$dlist->pageSize = 25;
$dlist->SetParameter("buyid",$buyid);
if(isset($sta))
{
	$dlist->SetParameter("sta",$sta);
}
$dlist->dsql->SetQuery("Select * From #@__moneycard_type ");
$dlist->dsql->Execute('ts');
while($rw = $dlist->dsql->GetArray('ts'))
{
	$TypeNames[$rw['tid']] = $rw['pname'];
}
$tplfile = DEDEADMIN."/templets/member_operations.htm";

//�������˳���ܸ���
$dlist->SetTemplate($tplfile);      //����ģ��
$dlist->SetSource($sql);            //�趨��ѯSQL
$dlist->Display();                  //��ʾ

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

function GetPType($tname)
{
	if($tname=='card') return '������';
	else if($tname=='archive') return '��������';
	else if($tname=='stc') return '�һ����';
	else return '��Ա����';
}

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
	else
	{
		return '�����';
	}
}

?>
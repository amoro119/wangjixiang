<?php
require_once(dirname(__FILE__)."/config.php");

//Ȩ�޼��
CheckPurview('sys_Feedback');
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/typelink.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

function IsCheck($st)
{
	return $st==1 ? "[�����]" : "<font color='red'>[δ���]</font>";
}

if(!empty($job))
{
	$ids = ereg_replace("[^0-9,]",'',$fid);
	if(empty($ids))
	{
		ShowMsg("��ûѡ���κ�ѡ�",$_COOKIE['ENV_GOBACK_URL'],0,500);
		exit;
	}
}
else
{
	$job = '';
}

//ɾ������
if( $job == 'del' )
{
		$query = "Delete From `#@__feedback` where id in($ids) ";
		$dsql->ExecuteNoneQuery($query);
		ShowMsg("�ɹ�ɾ��ָ��������!",$_COOKIE['ENV_GOBACK_URL'],0,500);
		exit();
}
//ɾ����ͬIP����������
else if( $job == 'delall' )
{
		$dsql->SetQuery("Select ip From `#@__feedback` where id in ($ids) ");
		$dsql->Execute();
		$ips = '';
		while($row = $dsql->GetArray())
		{
			$ips .= ($ips=='' ? " ip = '{$row['ip']}' " : " Or ip = '{$row['ip']}' ");
		}
		if($ips!='')
		{
			$query = "Delete From `#@__feedback` where $ips ";
			$dsql->ExecuteNoneQuery($query);
		}
		ShowMsg("�ɹ�ɾ��ָ����ͬIP����������!",$_COOKIE['ENV_GOBACK_URL'],0,500);
		exit();
}
//�������
else if($job=='check')
{
		$query = "Update `#@__feedback` set ischeck=1 where id in($ids) ";
		$dsql->ExecuteNoneQuery($query);
		ShowMsg("�ɹ����ָ������!",$_COOKIE['ENV_GOBACK_URL'],0,500);
		exit();
}
//�������
else
{
	$bgcolor = '';
	$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
	$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
	$keyword = !isset($keyword) ? '' : $keyword;
	$ip = !isset($ip) ? '' : $ip;
	
	$tl = new TypeLink($typeid);
	$openarray = $tl->GetOptionArray($typeid,$admin_catalogs,0);
	
	$addsql = ($typeid != 0  ? " And typeid in (".GetSonIds($typeid).")" : '');
	$addsql .= ($aid != 0  ? " And aid=$aid " : '');
	$addsql .= ($ip != ''  ? " And ip like '$ip' " : '');
	$querystring = "select * from `#@__feedback` where msg like '%$keyword%' $addsql order by dtime desc";
	
	$dlist = new DataListCP();
	$dlist->pageSize = 15;
	$dlist->SetParameter('aid', $aid);
	$dlist->SetParameter('ip', $ip);
	$dlist->SetParameter('typeid', $typeid);
	$dlist->SetParameter('keyword', $keyword);
	$dlist->SetTemplate(DEDEADMIN.'/templets/feedback_main.htm');
	$dlist->SetSource($querystring);
	$dlist->Display();
}
?>
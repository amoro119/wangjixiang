<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.freelist.class.php");
if(empty($startid))
{
	$startid = 0;
}
$ci = " aid >= $startid ";
if(!empty($endid) && $endid>=$startid)
{
	$ci .= " And aid <= $endid ";
}
header("Content-Type: text/html; charset={$cfg_soft_lang}");
$dsql->SetQuery("Select aid From #@__freelist where $ci");
$dsql->Execute();
while($row=$dsql->GetArray())
{
	$idArray[] = $row['aid'];
}
if(!isset($pageno))
{
	$pageno=0;
}
if(empty($idArray))
{
	$idArray = '';
}
$totalpage=count($idArray);
if(isset($idArray[$pageno]))
{
	$lid = $idArray[$pageno];
}else
{
	ShowMsg( "��������ļ�������", 'javascript:;');
	exit();
}
$lv = new FreeList($lid);
$ntotalpage = $lv->TotalPage;
if(empty($mkpage))
{
	$mkpage = 1;
}
if(empty($maxpagesize))
{
	$maxpagesize = 50;
}

//�����Ŀ���ĵ�̫�࣬�ֶ����θ���
if($ntotalpage<=$maxpagesize)
{
	$lv->MakeHtml();
	$finishType = true;
}else
{
	$lv->MakeHtml($mkpage,$maxpagesize);
	$finishType = false;
	$mkpage = $mkpage + $maxpagesize;
	if( $mkpage >= ($ntotalpage+1) )
	{
		$finishType = true;
	}
}
$lv->Close();
$nextpage = $pageno+1;
if($nextpage==$totalpage)
{
	ShowMsg( "��������ļ�������", 'javascript:;');
}
else
{
	if($finishType)
	{
		$gourl = "makehtml_freelist_action.php?maxpagesize=$maxpagesize&startid=$startid&endid=$endid&pageno=$nextpage";
		ShowMsg("�ɹ������б�".$tid."���������в�����",$gourl,0,100);
	}
	else
	{
		$gourl = "makehtml_freelist_action.php?mkpage=$mkpage&maxpagesize=$maxpagesize&startid=$startid&endid=$endid&pageno=$pageno";
		ShowMsg("�б�".$tid."���������в���...",$gourl,0,100);
	}
}
$dsql->ExecuteNoneQuery("Update `#@__freelist` set  nodefault='1' where aid='$startid';");

?>
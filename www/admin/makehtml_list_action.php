<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEROOT."/data/cache/inc_catalog_base.inc");
require_once(DEDEINC."/channelunit.func.php");

if(!isset($upnext)) $upnext = 1;
if(empty($gotype)) $gotype = '';
if(empty($pageno)) $pageno = 0;
if(empty($mkpage)) $mkpage = 1;
if(empty($typeid)) $typeid = 0;
if(!isset($uppage)) $uppage = 0;
if(empty($maxpagesize)) $maxpagesize = 50;
$adminID = $cuserLogin->getUserID();

$isremote = (empty($isremote)  ? 0 : $isremote);
$serviterm = empty($serviterm)? "" : $serviterm;

//����ȡ������ĿID
//��ͨ���ɻ�һ������ʱ����������Ŀ
if($gotype=='' || $gotype=='mkallct')
{
	if($upnext==1 || $typeid==0)
	{
		if($typeid>0) {
			$tidss = GetSonIds($typeid,0);
			$idArray = explode(',',$tidss);
		} else {
			foreach($cfg_Cs as $k=>$v) $idArray[] = $k;
		}
	} else {
		$idArray = array();
		$idArray[] = $typeid;
	}
}
//һ�����´���������
else if($gotype=='mkall')
{
	$uppage = 1;
	$mkcachefile = DEDEROOT."/data/mkall_cache_{$adminID}.php";
	$idArray = array();
	if(file_exists($mkcachefile)) require_once($mkcachefile);
}

//��ǰ������Ŀ��ID
$totalpage=count($idArray);
if(isset($idArray[$pageno]))
{
	$tid = $idArray[$pageno];
}
else
{
	if($gotype=='')
	{
		ShowMsg("��������б���£�","javascript:;");
		exit();
	}
	else if($gotype=='mkall' || $gotype=='mkallct')
	{
		ShowMsg("���������Ŀ�б���£���������������Ż���","makehtml_all.php?action=make&step=10");
		exit();
	}
}

if($pageno==0 && $mkpage==1) //��ջ���
{
	$dsql->ExecuteNoneQuery("Delete From `#@__arccache` ");
}

$reurl = '';

//������������¼����Ŀ
if(!empty($tid))
{
	if(!isset($cfg_Cs[$tid]))
	{
		showmsg('û�и���Ŀ����, ���ܻ����ļ�(/data/cache/inc_catalog_base.inc)û�и���, �����Ƿ���д��Ȩ��');
		exit();
	}
	if($cfg_Cs[$tid][1]>0)
	{
		 require_once(DEDEINC."/arc.listview.class.php");
		 $lv = new ListView($tid);
		 $position= MfTypedir($lv->Fields['typedir']);
	}
	else
	{
			require_once(DEDEINC."/arc.sglistview.class.php");
			$lv = new SgListView($tid);		
	}
	if($lv->TypeLink->TypeInfos['ispart']==0 && $lv->TypeLink->TypeInfos['isdefault']!=-1) $ntotalpage = $lv->TotalPage;
	else $ntotalpage = 1;
	if($cfg_remote_site=='Y' && $isremote=="1")
	{	
		if($serviterm!=""){
			list($servurl,$servuser,$servpwd) = explode(',',$serviterm);
			$config=array( 'hostname' => $servurl, 'username' => $servuser, 'password' => $servpwd,'debug' => 'TRUE');
		}else{
			$config=array();
		}
		if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
	}
	//�����Ŀ���ĵ�̫�࣬�ֶ����θ���
	if($ntotalpage <= $maxpagesize || $lv->TypeLink->TypeInfos['ispart']!=0 || $lv->TypeLink->TypeInfos['isdefault']==-1)
	{
		$reurl = $lv->MakeHtml('','',$isremote);
		$finishType = true;
	}
	else
	{
		$reurl = $lv->MakeHtml($mkpage,$maxpagesize,$isremote);
		$finishType = false;
		$mkpage = $mkpage + $maxpagesize;
		if( $mkpage >= ($ntotalpage+1) ) $finishType = true;
	}

}

$nextpage = $pageno+1;

if($nextpage >= $totalpage && $finishType)
{
	
	if($gotype=='')
	{
		if(empty($reurl)) { $reurl = '../plus/list.php?tid='.$tid; }
		ShowMsg("���������Ŀ�б���£�<a href='$reurl' target='_blank'>�����Ŀ</a>","javascript:;");
		exit();
	}
	else if($gotype=='mkall' || $gotype=='mkallct')
	{
		ShowMsg("���������Ŀ�б���£���������������Ż���","makehtml_all.php?action=make&step=10");
		exit();
	}
}
else
{
	if($finishType)
	{
		$gourl = "makehtml_list_action.php?gotype={$gotype}&uppage=$uppage&maxpagesize=$maxpagesize&typeid=$typeid&pageno=$nextpage&isremote={$isremote}&serviterm={$serviterm}";
		ShowMsg("�ɹ�������Ŀ��".$tid."���������в�����",$gourl,0,100);
		exit();
	}
	else
	{
		$gourl = "makehtml_list_action.php?gotype={$gotype}&uppage=$uppage&mkpage=$mkpage&maxpagesize=$maxpagesize&typeid=$typeid&pageno=$pageno&isremote={$isremote}&serviterm={$serviterm}";
		ShowMsg("��Ŀ��".$tid."���������в���...",$gourl,0,100);
		exit();
	}
}
?>
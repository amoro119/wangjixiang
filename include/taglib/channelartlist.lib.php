<?php

if(!defined('DEDEINC')) exit('Request Error!');
require_once(DEDEINC.'/arc.partview.class.php');

function lib_channelartlist(&$ctag,&$refObj)
{
	global $dsql,$envs,$_sys_globals;

	//���������ԡ�innertext
	$attlist = 'typeid|0,row|20,cacheid|';
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$innertext = trim($ctag->GetInnerText());
	$artlist = '';
	//��ȡ�̶��Ļ����
	$cacheid = trim($cacheid);
	if($cacheid !='') {
		$artlist = GetCacheBlock($cacheid);
		if($artlist!='') return $artlist;
	}
	
	if(empty($typeid))
	{
		$typeid = ( !empty($refObj->TypeLink->TypeInfos['id']) ?  $refObj->TypeLink->TypeInfos['id'] : 0 );
	}
	
	if($innertext=='') $innertext = GetSysTemplets('part_channelartlist.htm');
	$totalnum = $row;
	if(empty($totalnum)) $totalnum = 20;

	//������ID��������Ϣ
	$typeids = array();
	if($typeid==0 || $typeid=='top') {
		$tpsql = " reid=0 And ispart<>2 And ishidden<>1 And channeltype>0 ";
	}
	else
	{
		if(!ereg(',',$typeid)) {
			$tpsql = " reid='$typeid' And ispart<>2 And ishidden<>1 ";
		}
		else {
			$tpsql = " id in($typeid) And ispart<>2 And ishidden<>1 ";
		}
	}
	$dsql->SetQuery("Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath 
	                                        from `#@__arctype` where $tpsql order by sortrank asc limit $totalnum");
	$dsql->Execute();
	while($row = $dsql->GetArray()) {
		$typeids[] = $row;
	}

	if(!isset($typeids[0])) return '';

	$GLOBALS['itemindex'] = 0;
	$GLOBALS['itemparity'] = 1;
	for($i=0;isset($typeids[$i]);$i++)
	{
		$GLOBALS['itemindex']++;
		$pv = new PartView($typeids[$i]['id']);
		$pv->Fields['typeurl'] = GetOneTypeUrlA($typeids[$i]);
		$pv->SetTemplet($innertext,'string');
		$artlist .= $pv->GetResult();
		$GLOBALS['itemparity'] = ($GLOBALS['itemparity']==1 ? 2 : 1);
	}
	//ע�������������Է�ֹ���������б�ʹ��
	$GLOBALS['envs']['typeid'] = $_sys_globals['typeid'];
	$GLOBALS['envs']['reid'] = '';
	if($cacheid !='') {
		WriteCacheBlock($cacheid, $artlist);
	}
	return $artlist;
}
?>
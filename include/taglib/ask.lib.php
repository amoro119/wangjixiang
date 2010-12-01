<?php

if(!defined('DEDEINC')) exit('Request Error!');

function lib_ask(&$ctag,&$refObj)
{
	global $dsql, $envs, $cfg_dbprefix, $cfg_cmsurl;
	//���Դ���
	$attlist="row|6,qtype|new,tid|0,titlelen|24";
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	
	if( !$dsql->IsTable("{$cfg_dbprefix}ask") ) return 'û��װȦ��ģ��';

	if(!ereg("/$", $cfg_cmsurl)) $cfg_ask_url = $cfg_cmsurl."/ask";
	else $cfg_ask_url = $cfg_cmsurl."ask";
	
	$innertext = $ctag->GetInnerText();
  if(trim($innertext)=='') $innertext = GetSysTemplets("asks.htm");
	
	$qtypeQuery = '';
	if($tid>0) $tid = " (tid=$tid Or tid2='$tid') And ";
	else $tid = '';
	//�Ƽ�����
	if($qtype=='commend') $qtypeQuery = " $tid digest=1 order by dateline desc ";
	//�½������
	else if($qtype=='ok') $qtypeQuery = " $tid status=1 order by solvetime desc ";
	//�߷�����
	else if($qtype=='high') $qtypeQuery = " $tid status=0 order by reward desc ";
	//������
	else $qtypeQuery = " $tid status=0 order by disorder desc, dateline desc ";

	$ctp = new DedeTagParse();
	$ctp->SetNameSpace('field', '[', ']');

  $solvingask = '';
  $query = "select id, tid, tidname, tid2, tid2name, title from `#@__ask` where $qtypeQuery  limit 0, $row";
  $dsql->Execute('me',$query);
  while($rs = $dsql->GetArray('me'))
  {
	    $rs['title'] = cn_substr($rs['title'], $titlelen);
	    $ctp->LoadSource($innertext);
	    if($rs['tid2name'] != '')
	    {
	    	$rs['tid'] = $rs['tid2'];
	    	$rs['tidname'] = $rs['tid2name'];
	    }
  	  $rs['url'] = $cfg_ask_url."/question.php?id={$rs['id']}";
      $rs['typeurl'] = $cfg_ask_url."/browser.php?tid={$rs['tid']}";
  	  foreach($ctp->CTags as $tagid=>$ctag) {
		    if(!empty($rs[strtolower($ctag->GetName())])) {
		    	$ctp->Assign($tagid,$rs[$ctag->GetName()]);
		    }
		  }
		  $solvingask .= $ctp->GetResult();
  }
  return $solvingask;
}

?>
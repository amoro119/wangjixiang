<?php

if(!defined('DEDEINC')) exit('Request Error!');

//����ǩ�������������Ϣ��������
function lib_infoguide(&$ctag,&$refObj)
{
	global $dsql,$nativeplace,$infotype,$hasSetEnumJs,$cfg_cmspath,$cfg_mainsite;
	
	//���Դ���
	//$attlist="row|12,titlelen|24";
	//FillAttsDefault($ctag->CAttribute->Items,$attlist);
	//extract($ctag->CAttribute->Items, EXTR_SKIP);
	
	$cmspath = ( (empty($cfg_cmspath) || ereg('[/$]', $cfg_cmspath)) ? $cfg_cmspath.'/' : $cfg_cmspath );
	
	if(empty($refObj->Fields['typeid']))
	{
		$row = $dsql->GetOne("Select id From `#@__arctype` where channeltype='-8' And reid = '0' ");
		$typeid = (is_array($row) ? $row['id'] : 0);
		if(empty($typeid))
		{
			return '��ָ��һ����Ŀ����Ϊ��������Ϣ���������޷�ʹ�������������';
		}
	}
	else
	{
		$typeid = $refObj->Fields['typeid'];
	}
	
	$innerText = trim($ctag->GetInnerText());
	if(empty($innerText)) $innerText = GetSysTemplets("info_guide.htm");
	$ctp = new DedeTagParse();
	$ctp->SetNameSpace('field','[',']');
	$ctp->LoadSource($innerText);

	$revalue = $seli = '';
	
	$fields = array('nativeplace'=>'','infotype'=>'','typeid'=>$typeid);
	
	if($hasSetEnumJs !='has' )
	{
		$revalue .= '<script language="javascript" type="text/javascript" src="'.$cfg_mainsite.$cmspath.'images/enums.js"></script>'."\r\n";
		$GLOBALS['hasSetEnumJs'] = 'hasset';
	}
	
	$fields['nativeplace'] = $fields['infotype'] = '';
	
	if(empty($nativeplace)) $nativeplace = 0;
	if(empty($infotype)) $infotype = 0;
	
	$fields['nativeplace'] .= "<input type='hidden' id='hidden_nativeplace' name='nativeplace' value='{$nativeplace}' />\r\n";
	$fields['nativeplace'] .= "<span class='infosearchtxt'>������</span><span id='span_nativeplace'></span>\r\n";
	$fields['nativeplace'] .= "<span id='span_nativeplace_son'></span><br />\r\n";
	$fields['nativeplace'] .= "<script language='javascript' type='text/javascript' src='{$cfg_mainsite}{$cmspath}data/enums/nativeplace.js'></script>\r\n";
	$fields['nativeplace'] .= '<script language="javascript">MakeTopSelect("nativeplace", '.$nativeplace.');</script>'."\r\n";
	
	$fields['infotype'] .= "<input type='hidden' id='hidden_infotype' name='infotype' value='{$infotype}' />\r\n";
	$fields['infotype'] .= "<span class='infosearchtxt'>���ͣ�</span><span id='span_infotype'></span>\r\n";
	$fields['infotype'] .= "<span id='span_infotype_son'></span><br />\r\n";
	$fields['infotype'] .= "<script language='javascript' type='text/javascript' src='{$cfg_mainsite}{$cmspath}data/enums/infotype.js'></script>\r\n";
	$fields['infotype'] .= '<script language="javascript">MakeTopSelect("infotype", '.$infotype.');</script>'."\r\n";
	
	if(is_array($ctp->CTags))
	{
		foreach($ctp->CTags as $tagid=>$ctag)
		{
			if(isset($fields[$ctag->GetName()])) {
				$ctp->Assign($tagid,$fields[$ctag->GetName()]);
			}
		}
		$revalue .= $ctp->GetResult();
	}
	
	return $revalue;
}
?>
<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
function lib_demotag(&$ctag,&$refObj)
{
	global $dsql,$envs;
	
	//���Դ���
	$attlist="row|12,titlelen|24";
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$revalue = '';
	
	//�����д�Ĵ��룬������echo֮���﷨�������շ���ֵ����$revalue
	//------------------------------------------------------
	
	$revalue = 'Hello Word!';
	
	//------------------------------------------------------
	return $revalue;
}
?>
<?php

if(!defined('DEDEINC')) exit("Request Error!");

if(!file_exists(DEDEDATA.'/enums/system.php'))
{
	WriteEnumsCache();
}

//����ö�ٻ���
function WriteEnumsCache($egroup='')
{
	global $dsql;
	$egroups = array();
	if($egroup=='') {
		$dsql->SetQuery("Select egroup From `#@__sys_enum` group by egroup ");
	}
	else {
		$dsql->SetQuery("Select egroup From `#@__sys_enum` where egroup='$egroup' group by egroup ");
	}
	$dsql->Execute('enum');
	while($nrow = $dsql->GetArray('enum')) {
		$egroups[] = $nrow['egroup'];
	}
	foreach($egroups as $egroup)
	{
		$cachefile = DEDEDATA.'/enums/'.$egroup.'.php';
		$fp = fopen($cachefile,'w');
		fwrite($fp,'<'."?php\r\nglobal \$em_{$egroup}s;\r\n\$em_{$egroup}s = array();\r\n");
		$dsql->SetQuery("Select ename,evalue,issign From `#@__sys_enum` where egroup='$egroup' order by disorder asc, evalue asc ");
		$dsql->Execute('enum');
		$issign = -1;
		while($nrow = $dsql->GetArray('enum'))
		{
			fwrite($fp,"\$em_{$egroup}s[{$nrow['evalue']}] = '{$nrow['ename']}';\r\n");
			if($issign==-1) $issign = $nrow['issign'];
		}
		fwrite($fp,'?'.'>');
		fclose($fp);
		if(empty($issign)) WriteEnumsJs($egroup);
	}
	return '�ɹ���������ö�ٻ��棡';
}

//��ȡ�������������ݵĸ���������
function GetEnumsTypes($v)
{
	$rearr['top'] = $rearr['son'] = 0;
	if($v==0) return $rearr;
	if($v%500==0) {
		$rearr['top'] = $v;
	}
	else {
		$rearr['son'] = $v;
		$rearr['top'] = $v - ($v%500);
	}
	return $rearr;
}

//��ȡö�ٵ�select��
function GetEnumsForm($egroup,$evalue=0,$formid='',$seltitle='')
{
	$cachefile = DEDEDATA.'/enums/'.$egroup.'.php';
	include($cachefile);
	if($formid=='')
	{
		$formid = $egroup;
	}
	$forms = "<select name='$formid' id='$formid' class='enumselect'>\r\n";
	$forms .= "\t<option value='0' selected='selected'>--��ѡ��--{$seltitle}</option>\r\n";
	foreach(${'em_'.$egroup.'s'} as $v=>$n)
	{
		$prefix = ($v > 500 && $v%500 != 0) ? '���� ' : '';
		if($v==$evalue)
		{
			$forms .= "\t<option value='$v' selected='selected'>$prefix$n</option>\r\n";
		}
		else
		{
			$forms .= "\t<option value='$v'>$prefix$n</option>\r\n";
		}
	}
	$forms .= "</select>";
	return $forms;
}

//��ȡһ������
function getTopData($egroup)
{
	$data = array();
	$cachefile = DEDEDATA.'/enums/'.$egroup.'.php';
	include($cachefile);
	foreach(${'em_'.$egroup.'s'} as $k=>$v)
	{
		if($k >= 500 && $k%500 == 0) {
			$data[$k] = $v;
		}
	}
	return $data;
}


//��ȡ���ݵ�JS����(��������)
function GetEnumsJs($egroup)
{
	global ${'em_'.$egroup.'s'};
	include_once(DEDEDATA.'/enums/'.$egroup.'.php');
	$jsCode = "<!--\r\n";
	$jsCode .= "em_{$egroup}s=new Array();\r\n";
	foreach(${'em_'.$egroup.'s'} as $k => $v)
	{
		$jsCode .= "em_{$egroup}s[$k]='$v';\r\n";
	}
	$jsCode .= "-->";
	return $jsCode;
}

function WriteEnumsJs($egroup)
{
	$jsfile = DEDEDATA.'/enums/'.$egroup.'.js';
	$fp = fopen($jsfile, 'w');
	fwrite($fp, GetEnumsJs($egroup));
	fclose($fp);
}


//��ȡö�ٵ�ֵ
function GetEnumsValue($egroup,$evalue=0)
{
	include_once(DEDEDATA.'/enums/'.$egroup.'.php');
	if(isset(${'em_'.$egroup.'s'}[$evalue])) {
		return ${'em_'.$egroup.'s'}[$evalue];
	}
	else {
		return "����";
	}
}



?>
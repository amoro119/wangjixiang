<?php
require_once(dirname(__FILE__)."/config.php");
if(!isset($dopost))
{
	$dopost = "";
}
if($dopost=='save')
{
	$ConfigFile = DEDEINC."/config_passport.php";
	$vars = array('cfg_pp_need','cfg_pp_encode','cfg_pp_login','cfg_pp_exit','cfg_pp_reg');
	$configstr = "";
	foreach($vars as $v)
	{
		${$v} = str_replace("'","",${$v});
		$configstr .= "\${$v} = '".str_replace("'","",stripslashes(${'edit___'.$v}))."';\r\n";
	}
	$configstr = '<'.'?'."\r\n".$configstr.'?'.'>';
	$fp = fopen($ConfigFile,"w") or die("д���ļ� $ConfigFile ʧ�ܣ�����Ȩ�ޣ�");
	fwrite($fp,$configstr);
	fclose($fp);
	echo "<script>alert('�޸�ͨ��֤���óɹ���');window.location='sys_passport.php?".time()."';</script>\r\n";
}
include DedeInclude('templets/sys_passport.htm');

?>
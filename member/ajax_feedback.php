<?php
require_once(dirname(__FILE__).'/config.php');
AjaxHead();
if($myurl == '')
{
	exit('');
}
else
{
	$uid  = $cfg_ml->M_LoginID;
	$face = $cfg_ml->fields['face'] == '' ? $GLOBALS['cfg_memberurl'].'/images/nopic.gif' : $cfg_ml->fields['face'];
	echo "�û�����{$cfg_ml->M_UserName} <input name=\"notuser\" type=\"checkbox\" id=\"notuser\" value=\"1\" />��������\r\n";
	if($cfg_feedback_ck=='Y')
	{
		echo "��֤�룺<input name=\"validate\" type=\"text\" id=\"validate\" size=\"10\" style=\"height:18px;width:60px;margin-right:6px;\" class=\"nb\" />";
		echo "<img src='{$cfg_cmsurl}/include/vdimgck.php' style='cursor:pointer' id='validateimg' onclick=\"this.src=this.src+'?'\"  title='����Ҹ���ͼƬ' alt='����Ҹ���ͼƬ' />\r\n";
	}
}
?>


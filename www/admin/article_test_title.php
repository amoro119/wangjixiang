<?php
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
if(empty($t) || $cfg_check_title=='N')
{
	exit;
}
$row = $dsql->GetOne("Select id From `#@__archives` where title like '$t' ");
if(is_array($row))
{
	echo "��ʾ��ϵͳ�Ѿ����ڱ���Ϊ '<a href='../plus/view.php?aid={$row['id']}' style='color:red' target='_blank'><u>$t</u></a>' ���ĵ���[<a href='#' onclick='javascript:HideObj(\"mytitle\")'>�ر�</a>]";
}

?>
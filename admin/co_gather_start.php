<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/dedecollection.class.php");
if(!empty($nid))
{
	$ntitle = '�ɼ�ָ���ڵ㣺';
	$nid = intval($nid);
	$co = new DedeCollection();
	$co->LoadNote($nid);
	$row = $dsql->GetOne("Select count(aid) as dd From `#@__co_htmls` where nid='$nid'; ");
	if($row['dd']==0)
	{
		$unum = "û�м�¼�����û�вɼ�������ڵ㣡";
	}
	else
	{
		$unum = "���� {$row['dd']} ����ʷ������ַ��<a href='javascript:SubmitNew();'>[<u>����������ַ�����ɼ�</u>]</a>";
	}
}
else
{
	$ntitle = '���ʽ�ɼ���';
	$unum = "ûָ���ɼ��ڵ㣬��ʹ�ü�������ݲɼ�ģʽ��";
}
include DedeInclude('templets/co_gather_start.htm');

?>
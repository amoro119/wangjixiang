<?php

require_once(dirname(__FILE__)."/../config.php");

/*
ģ��˵�һ���ڲ�Ҫֱ�ӸĴ��ļ���ֱ�ӱ�����#@__sys_module���ɣ���ʽΪ
<m:top name='�ʴ�ģ�����' c='6,' display='block' rank=''>
<m:item name='�ʴ���Ŀ����' link='ask_type.php' rank='' target='main' />
<m:item name='�ʴ��������' link='ask_admin.php' rank='' target='main' />
<m:item name='�ʴ�𰸹���' link='ask_answer.php' rank='' target='main' />
</m:top>
����˵���������ģ��ʱָ��
*/

//����ģ��˵�
$moduleset = '';
$dsql->SetQuery("Select * From `#@__sys_module` order by id desc");
$dsql->Execute();
while($row = $dsql->GetObject()) {
	$moduleset .= $row->menustring."\r\n";
}

//�������˵�
$plusset = '';
$dsql->SetQuery("Select * From `#@__plus` where isshow=1 order by aid asc");
$dsql->Execute();
while($row = $dsql->GetObject()) {
	$row->menustring = str_replace('plus_��������', 'plus_��������ģ��', $row->menustring);
	$plusset .= $row->menustring."\r\n";
}

$adminMenu = '';
if($cuserLogin->getUserType() >= 10)
{
	$adminMenu = "<m:top name='ģ�����' c='6,' display='block'>
  <m:item name='ģ�����' link='module_main.php' rank='sys_module' target='main' />
  <m:item name='�ϴ���ģ��' link='module_upload.php' rank='sys_module' target='main' />
  <m:item name='ģ��������' link='module_make.php' rank='sys_module' target='main' />
  </m:top>";
}

$menusMoudle = "
-----------------------------------------------
$adminMenu
<m:top item='7' name='�������' display='block'>
  <m:item name='���������' link='plus_main.php' rank='10' target='main' />
  $plusset
</m:top>

$moduleset
-----------------------------------------------
";

?>
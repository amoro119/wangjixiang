<?php
require_once(dirname(__FILE__).'/config.php');

//���Ȩ�����
CheckPurview('t_Del,t_AccDel');
require_once(DEDEINC.'/typeunit.class.admin.php');
require_once(DEDEINC.'/oxwindow.class.php');
$id = trim(ereg_replace('[^0-9]','',$id));

//�����Ŀ�������
CheckCatalog($id,"����Ȩɾ������Ŀ��");
if(empty($dopost))
{
	$dopost='';
}
if($dopost=='ok')
{
	$ut = new TypeUnit();
	$ut->DelType($id,$delfile);
	UpDateCatCache();
	ShowMsg("�ɹ�ɾ��һ����Ŀ��","catalog_main.php");
	exit();
}
$dsql->SetQuery("Select typename,typedir From #@__arctype where id=".$id);
$row = $dsql->GetOne();
$wintitle = "ɾ����Ŀȷ��";
$wecome_info = "<a href='catalog_main.php'>��Ŀ����</a> &gt;&gt; ɾ����Ŀȷ��";
$win = new OxWindow();
$win->Init('catalog_del.php','js/blank.js','POST');
$win->AddHidden('id',$id);
$win->AddHidden('dopost','ok');
$win->AddTitle("��ҪȷʵҪɾ����Ŀ�� [{$row['typename']}] ��");
$win->AddItem('��Ŀ���ļ�����Ŀ¼��',$row['typedir']);
$win->AddItem('�Ƿ�ɾ���ļ���',"<input type='radio' name='delfile' class='np' value='no' checked='1' />�� &nbsp;<input type='radio' name='delfile' class='np' value='yes' />��");
$winform = $win->GetWindow('ok');
$win->Display();

?>
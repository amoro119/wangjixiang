<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('temp_Other');
require_once(DEDEINC."/typelink.class.php");

if(empty($dopost)) $dopost = '';
$aid = intval($aid);
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? 'mytag_main.php' : $_COOKIE['ENV_GOBACK_URL'];

if($dopost=='delete')
{
	$dsql->ExecuteNoneQuery("Delete From #@__mytag where aid='$aid'");
	ShowMsg("�ɹ�ɾ��һ���Զ����ǣ�",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=="saveedit")
{
	$starttime = GetMkTime($starttime);
	$endtime = GetMkTime($endtime);
	$query = "Update `#@__mytag`
	 set
	 typeid='$typeid',
	 timeset='$timeset',
	 starttime='$starttime',
	 endtime='$endtime',
	 normbody='$normbody',
	 expbody='$expbody'
	 where aid='$aid' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�����һ���Զ����ǣ�",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=="getjs")
{
	require_once(DEDEINC."/oxwindow.class.php");
	$jscode = "<script src='{$cfg_phpurl}/mytag_js.php?aid=$aid' language='javascript'></script>";
	$showhtml = "<xmp style='color:#333333;background-color:#ffffff'>\r\n\r\n$jscode\r\n\r\n</xmp>";
	$showhtml .= "<b>Ԥ����</b><iframe name='testfrm' frameborder='0' src='mytag_edit.php?aid={$aid}&dopost=testjs' id='testfrm' width='100%' height='250'></iframe>";
	$wintitle = "���Ƕ���-��ȡJS";
	$wecome_info = "<a href='mytag_main.php'><u>���Ƕ���</u></a>::��ȡJS";
	$win = new OxWindow();
	$win->Init();
	$win->AddTitle('����Ϊѡ�����ǵ�JS���ô��룺');
	$winform = $win->GetWindow('hand', $showhtml);
	$win->Display();
	exit();
}
else if($dopost=="testjs")
{
	echo "<body bgcolor='#ffffff'>";
	echo "<script src='{$cfg_phpurl}/mytag_js.php?aid=$aid&nocache=1' language='javascript'></script>";
	exit();
}
$row = $dsql->GetOne("Select * From `#@__mytag` where aid='$aid'");
include DedeInclude('templets/mytag_edit.htm');

?>
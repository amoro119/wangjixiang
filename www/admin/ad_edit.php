<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('plus_������');
require_once(DEDEINC.'/typelink.class.php');
if(empty($dopost))
{
	$dopost = '';
}
$aid = ereg_replace('[^0-9]','',$aid);
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? "ad_main.php" : $_COOKIE['ENV_GOBACK_URL'];

if($dopost=='delete')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__myad` where aid='$aid' ");
	ShowMsg("�ɹ�ɾ��һ������룡",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=="getjs")
{
	require_once(DEDEINC.'/oxwindow.class.php');
	$jscode = "<script src='{$cfg_phpurl}/ad_js.php?aid=$aid' language='javascript'></script>";
	$showhtml = "<xmp style='color:#333333;background-color:#ffffff'>\r\n\r\n$jscode\r\n\r\n</xmp>";
	$showhtml .= "Ԥ����<iframe name='testfrm' frameborder='0' src='ad_edit.php?aid={$aid}&dopost=testjs' id='testfrm' width='100%' height='200'></iframe>";
	$wintitle = "������-��ȡJS";
	$wecome_info = "<a href='ad_main.php'><u>������</u></a>::��ȡJS";
	$win = new OxWindow();
	$win->Init();
	$win->AddTitle("����Ϊѡ������JS���ô��룺");
	$winform = $win->GetWindow("hand",$showhtml);
	$win->Display();
	exit();
}
else if($dopost=='testjs')
{
	echo "<script src='{$cfg_phpurl}/ad_js.php?aid=$aid&nocache=1' language='javascript'></script>";
	exit();
}
else if($dopost=='saveedit')
{
	$starttime = GetMkTime($starttime);
	$endtime = GetMkTime($endtime);
	$query = "Update `#@__myad`
	 set
	 typeid='$typeid',
	 adname='$adname',
	 timeset='$timeset',
	 starttime='$starttime',
	 endtime='$endtime',
	 normbody='$normbody',
	 expbody='$expbody'
	 where aid='$aid'
	 ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�����һ������룡",$ENV_GOBACK_URL);
	exit();
}
$row = $dsql->GetOne("Select * From `#@__myad` where aid='$aid'");
include DedeInclude('templets/ad_edit.htm');

?>
<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('temp_Other');
require_once(DEDEINC."/typelink.class.php");
if(empty($dopost))
{
	$dopost = "";
}
if($dopost=="save")
{
	$tagname = trim($tagname);
	$row = $dsql->GetOne("Select typeid From #@__mytag where typeid='$typeid' And tagname like '$tagname'");
	if(is_array($row))
	{
		ShowMsg("����ͬ��Ŀ���Ѿ�����ͬ���ı�ǣ�","-1");
		exit();
	}
	$starttime = GetMkTime($starttime);
	$endtime = GetMkTime($endtime);
	$inQuery = "Insert Into #@__mytag(typeid,tagname,timeset,starttime,endtime,normbody,expbody)
	 Values('$typeid','$tagname','$timeset','$starttime','$endtime','$normbody','$expbody'); ";
	$dsql->ExecuteNoneQuery($inQuery);
	ShowMsg("�ɹ�����һ���Զ����ǣ�","mytag_main.php");
	exit();
}
$startDay = time();
$endDay = AddDay($startDay,30);
$startDay = GetDateTimeMk($startDay);
$endDay = GetDateTimeMk($endDay);
include DedeInclude('templets/mytag_add.htm');

?>
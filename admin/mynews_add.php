<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('plus_վ�����ŷ���');
if(empty($dopost))
{
	$dopost = "";
}
if($dopost=="save")
{
	$dtime = GetMkTime($sdate);
	$query = "Insert Into `#@__mynews`(title,writer,senddate,body)
	 Values('$title','$writer','$dtime','$body')";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�����һ��վ�����ţ�","mynews_main.php");
	exit();
}
include DedeInclude('templets/mynews_add.htm');

?>
<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(empty($keywords))
{
	$keywords = "";
}

$sql = "Select * from #@__keywords order by rank desc";
$dlist = new DataListCP();
$dlist->SetTemplate(DEDEADMIN."/templets/article_keywords_select.htm");
$dlist->pageSize = 300;
$dlist->SetParameter("f",$f);
$dlist->SetSource($sql);
$dlist->Display();

function GetSta($sta)
{
	if($sta==1)
	{
		return "����";
	}
	else
	{
		return "<font color='red'>����</font>";
	}
}

function GetMan($sta)
{
	if($sta==1)
	{
		return "<u>����</u>";
	}
	else
	{
		return "<u>����</u>";
	}
}

?>
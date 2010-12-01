<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Keyword');
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
if(empty($dopost))
{
	$dopost = '';
}

//������������
if($dopost=='saveall')
{
	$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? "article_keywords_main.php" : $_COOKIE['ENV_GOBACK_URL'];
	if(!isset($aids))
	{
		ShowMsg("��û��ѡ��Ҫ���ĵĶ�����",$ENV_GOBACK_URL);
		exit();
	}
	foreach($aids as $aid)
	{
		$rpurl = ${'rpurl_'.$aid};
		$rpurlold = ${'rpurlold_'.$aid};
		$keyword = ${'keyword_'.$aid};

		//ɾ����Ŀ
		if(!empty(${'isdel_'.$aid}))
		{
			$dsql->ExecuteNoneQuery("Delete From `#@__keywords` where aid='$aid'");
			continue;
		}

		//������Ŀ
		$staold = ${'staold_'.$aid};
		$sta = empty(${'isnouse_'.$aid}) ? 1 : 0;
		if($staold!=$sta)
		{
			$query1 = "update `#@__keywords` set sta='$sta',rpurl='$rpurl' where aid='$aid' ";
			$dsql->ExecuteNoneQuery($query1);
			continue;
		}

		//����������ַ
		if($rpurl!=$rpurlold)
		{
			$query1 = "update `#@__keywords` set rpurl='$rpurl' where aid='$aid' ";
			$dsql->ExecuteNoneQuery($query1);
		}
	}
	ShowMsg("���ָ���ĸ��ģ�",$ENV_GOBACK_URL);
	exit();
}

//���ӹؼ���
else if($dopost=='add')
{
	$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? "-1" : $_COOKIE['ENV_GOBACK_URL'];
	$keyword = trim($keyword);
	$rank = ereg_replace('[^0-9]','',$rank);
	if($keyword=='')
	{
		ShowMsg("�ؼ��ֲ���Ϊ�գ�",-1);
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__keywords` where keyword like '$keyword'");
	if(is_array($row))
	{
		ShowMsg("�ؼ����Ѵ��ڿ��У�","-1");
		exit();
	}
	$inquery = "INSERT INTO `#@__keywords`(keyword,rank,sta,rpurl) VALUES ('$keyword','$rank','1','$rpurl');";
	$dsql->ExecuteNoneQuery($inquery);
	ShowMsg("�ɹ�����һ���ؼ��֣�",$ENV_GOBACK_URL);
	exit();
}
if(empty($keyword))
{
	$keyword = '';
	$addquery = '';
}
else
{
	$addquery = " where keyword like '%$keyword%' ";
}

$sql = "Select * from `#@__keywords` $addquery order by rank desc";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("keyword",$keyword);
$dlist->SetTemplate(DEDEADMIN."/templets/article_keywords_main.htm");
$dlist->SetSource($sql);
$dlist->Display();

function GetSta($sta)
{
	if($sta==1)
	{
		return '';
	}
	else
	{
		return ' checked="1" ';
	}
}
?>
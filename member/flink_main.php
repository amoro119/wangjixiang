<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if($cfg_mb_lit=='Y')
{
	ShowMsg("����ϵͳ�����˾�����Ա�ռ䣬����ʵĹ��ܲ����ã�","-1");
	exit();
}
if(empty($dopost))
{
	$dopost = '';
}
if($dopost=="addnew")
{
	AjaxHead();
	$row = $dsql->GetOne("Select count(*) as dd From `#@__member_flink` where mid='".$cfg_ml->M_ID."' ");
	if($row['dd']>=50)
	{
		echo "<font color='red'>������ַʧ�ܣ���Ϊ�Ѿ��ﵽ��ʮ����ַ�����ޣ�</font>";
		GetLinkList($dsql);
		exit();
	}
	if(!eregi("^http://",$url))
	{
		$url = "http://".HtmlReplace($url, 2);
	}
	$title = HtmlReplace($title);
	$inquery = "INSERT INTO `#@__member_flink`(mid,title,url) VALUES(".$cfg_ml->M_ID.",'$title','$url'); ";
	$dsql->ExecuteNoneQuery($inquery);
	echo "<font color='red'>�ɹ�����һ���ӣ�</font>";
	GetLinkList($dsql);
	exit();
}
else if($dopost=="del")
{
	AjaxHead();
	$aid = intval($aid);
	if(empty($aid))
	{
		exit("<font color='red'>��������</font>");
	}
	$dsql->ExecuteNoneQuery("Delete From 	`#@__member_flink` where aid='$aid' And mid='".$cfg_ml->M_ID."';");
	echo "<font color='red'>�ɹ�ɾ�����ӣ�{$aid}</font>";
	GetLinkList($dsql);
}
else if($dopost=="update")
{
	AjaxHead();
	$aid = intval($aid);
	if(!eregi("^http://",$url))
	{
		$url = "http://".HtmlReplace($url,2);
	}
	$title = HtmlReplace($title);
	$upquery = "Update `#@__member_flink` set title='$title',url='$url' where aid='$aid' And mid='".$cfg_ml->M_ID."'; ";
	$rs = $dsql->ExecuteNoneQuery($upquery);
	if($rs)
	{
		echo "<font color='red'>�ɹ��������ӣ�{$title}</font>";
		GetLinkList($dsql);
		exit();
	}
	else
	{
		echo "<font color='red'>�������ӣ�{$title} ʧ�ܣ�</font>";
		GetLinkList($dsql);
		exit();
	}
}
else if($dopost=="reload")
{
	AjaxHead();
	GetLinkList($dsql);
	exit();
}

//Ĭ�Ͻ���
else
{
	require_once(dirname(__FILE__)."/templets/flink_main.htm");
	exit();
}

//����
function GetLinkList($dsql)
{
	global $cfg_ml;
	$dsql->SetQuery("Select * From `#@__member_flink` where mid='".$cfg_ml->M_ID."' order by aid desc");
	$dsql->Execute();
	$j=0;
	while($row = $dsql->GetArray())
	{
		$j++;
		$line = "
<div class='item flink'>
  <div class='itemHead' >
    <div class='fRight'>
      <span class='itemDigg'><a href='#' onclick='UpdateType({$row['aid']})'>[����]</a></span>
      <span class='itemManage'><a href='#' onclick='DelType({$row['aid']})'>[ɾ��]</a></span>
    </div>
    <span class='itemTitle'>���ƣ�<input name='title{$row['aid']}' type='text' id='title{$row['aid']}' value='{$row['title']}' class='intxt' /></span>
    <div class='mt5'>��ַ��<input name='url{$row['aid']}' type='text' id='url{$row['aid']}' value='{$row['url']}' class='intxt' /></div>
  </div>
</div>
<hr class='dotted' />";
		echo $line;
	}
	if($j==0)
	{
		echo "�����κ�����";
	}
}
?>
<?php
require_once(dirname(__FILE__)."/config.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
if(empty($pagesize))
{
	$pagesize = 30;
}
if(empty($pageno))
{
	$pageno = 1;
}
if(empty($dopost))
{
	$dopost = '';
}
if(empty($orderby))
{
	$orderby = 'aid';
}

//�����б�
if($dopost=='getlist')
{
	AjaxHead();
	GetKeywordList($dsql,$pageno,$pagesize,$orderby);
	exit();
}

//�����ֶ�
else if($dopost=='update')
{
	$aid = ereg_replace("[^0-9]","",$aid);
	$count = ereg_replace("[^0-9]","",$count);
	$keyword = trim($keyword);
	$spwords = trim($spwords);
	$dsql->ExecuteNoneQuery("Update `#@__search_keywords` set keyword='$keyword',spwords='$spwords',count='$count' where aid='$aid';");
	AjaxHead();
	GetKeywordList($dsql,$pageno,$pagesize,$orderby);
	exit();
}

//ɾ���ֶ�
else if($dopost=='del')
{
	$aid = ereg_replace("[^0-9]","",$aid);
	$dsql->ExecuteNoneQuery("Delete From `#@__search_keywords` where aid='$aid';");
	AjaxHead();
	GetKeywordList($dsql,$pageno,$pagesize,$orderby);
	exit();
}

//����ɾ���ֶ�
else if($dopost=='delall')
{
	foreach($aids as $aid)
	{
		$dsql->ExecuteNoneQuery("Delete From `#@__search_keywords` where aid='$aid';");
	}
	ShowMsg("ɾ���ɹ���",$ENV_GOBACK_URL);
	exit();
}

//��һ�ν������ҳ��
if($dopost=='')
{
	$row = $dsql->GetOne("Select count(*) as dd From `#@__search_keywords` ");
	$totalRow = $row['dd'];
	include(DEDEADMIN."/templets/search_keywords_main.htm");
}

//����ض��Ĺؼ����б�
function GetKeywordList($dsql,$pageno,$pagesize,$orderby='aid')
{
	global $cfg_phpurl;
	$start = ($pageno-1) * $pagesize;
	$printhead ="<form name='form3' action=\"search_keywords_main.php\" method=\"post\">
    <input name=\"dopost\" type=\"hidden\" value=\"\">
	<table width='98%' border='0' cellpadding='1' cellspacing='1' bgcolor='#D1DDAA' style='margin-bottom:3px'>
    <tr align='center' bgcolor='#E9F4D5' height='24'>
	  <td width='5%'>ѡ��</td>
      <td width='6%' height='23'><a href='#' onclick=\"ReloadPage('aid')\"><u>ID</u></a></td>
      <td width='20%'>�ؼ���</td>
      <td width='35%'>�ִʽ��</td>
      <td width='6%'><a href='#' onclick=\"ReloadPage('count')\"><u>Ƶ��</u></a></td>
      <td width='6%'><a href='#' onclick=\"ReloadPage('result')\"><u>���</u></a></td>
      <td width='16%'><a href='#' onclick=\"ReloadPage('lasttime')\"><u>�������ʱ��</u></a></td>
      <td>����</td>
    </tr>\r\n
	";
	echo $printhead;
	if($orderby=='result') $orderby = $orderby." asc";
	else $orderby = $orderby." desc";
	$dsql->SetQuery("Select * From #@__search_keywords order by $orderby limit $start,$pagesize ");
	$dsql->Execute();
	while($row = $dsql->GetArray())
	{
		$line = "
	
      <tr align='center' bgcolor='#FFFFFF' onMouseMove=\"javascript:this.bgColor='#FCFEDA';\" onMouseOut=\"javascript:this.bgColor='#FFFFFF';\">
	  <td height='24'><input name=\"aids[]\" type=\"checkbox\" class=\"np\" value=\"{$row['aid']}\" /></td>
      <td height='24'>{$row['aid']}</td>
      <td><input name='keyword' type='text' id='keyword{$row['aid']}' value='{$row['keyword']}' class='ininput'></td>
      <td><input name='spwords' type='text' id='spwords{$row['aid']}' value='{$row['spwords']}' class='ininput'></td>
      <td><input name='count' type='text' id='count{$row['aid']}' value='{$row['count']}' class='ininput'></td>
      <td><a href='{$cfg_phpurl}/search.php?kwtype=0&keyword=".urlencode($row['keyword'])."&searchtype=titlekeyword' target='_blank'><u>{$row['result']}</u></a></td>
      <td>".MyDate("Y-m-d H:i:s",$row['lasttime'])."</td>
      <td>
      <a href='#' onclick='UpdateNote({$row['aid']})'>����</a> |
      <a href='#' onclick='DelNote({$row['aid']})'>ɾ��</a>
      </td>
    </tr>
	";
		echo $line;
	}
	echo "  <tr align='left' bgcolor='#E9F4D5' height='24'>
			<td colspan='8'>
	        <a href='javascript:selAll()' class='coolbg np'>��ѡ</a>
	        <a href='javascript:noselAll()' class='coolbg np'>ȡ��</a>
            <a href='javascript:delall()' class='coolbg np'>ɾ��</a>
		   </td>
		   </tr>\r\n";
	echo "</table></form>\r\n";
}

?>
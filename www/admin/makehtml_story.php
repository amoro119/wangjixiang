<?php

/**
 * Enter description here...
 *
 * @author Administrator
 * @package defaultPackage
 * @rcsfile 	$RCSfile: makehtml_story.php,v $
 * @revision 	$Revision: 1.1 $
 * @date 	$Date: 2008/12/11 02:09:55 $
 */

require_once(dirname(__FILE__)."/config.php");
require_once(DEDEROOT.'/book/include/story.view.class.php');
CheckPurview('sys_MakeHtml');
if(!isset($action)) $action = '';
if(empty($startid)) $startid = 0;

//����HTML����
if($action=='make')
{
	if(empty($start)) $start = 0;
	$addquery = " bid>='$startid' ";
	if(!empty($endid) && $endid>$startid ) $addquery .= " And bid<='$endid' ";
	if(!empty($catid)) $addquery .= " And (catid='$catid' Or bcatid='$catid') ";
	if(empty($makenum)) $makenum = 50;
	$dsql->SetQuery("Select SQL_CALC_FOUND_ROWS bid From #@__story_books where $addquery limit $start,$makenum ");
	$dsql->Execute();
	$n = 0;
	$row = $dsql->GetOne("SELECT FOUND_ROWS() as dd ");
	$limitrow = $row['dd'];
	while($row = $dsql->GetObject()){
		$start++;
		$bv = new BookView($row->bid,'book');
		$artUrl = $bv->MakeHtml(false);
		//echo "����: <a href='$artUrl' target='_blank'>{$bv->Fields['bookname']}</a> OK��<br />\r\n";
		//echo $row->bid." - ";
	}
	if($start>=$limitrow){
		ShowMsg("�������HTML�ĸ��£�","javascript:;");
	}
	else{
		$hasMake = $limitrow - $start;
		if($limitrow>0) $proportion = 100 - ceil(($hasMake / $limitrow) * 100);
		ShowMsg("�Ѹ�������{$proportion}% ����������������...","makehtml_story.php?start={$start}&action=make&startid={$startid}&endid={$endid}&catid={$catid}&makenum={$makenum}");
	}
	exit();
}
//��ȡ������Ŀ
$dsql->SetQuery("Select id,classname,pid,rank,booktype From #@__story_catalog order by rank asc");
$dsql->Execute();
$ranks = Array();
$btypes = Array();
$stypes = Array();
$booktypes = Array();
while($row = $dsql->GetArray()){
	if($row['pid']==0) $btypes[$row['id']] = $row['classname'];
	else $stypes[$row['pid']][$row['id']] = $row['classname'];
	$ranks[$row['id']] = $row['rank'];
	if($row['booktype']=='0') $booktypes[$row['id']] = 'С˵';
	else $booktypes[$row['id']] = '����';
}
$lastid = $row['id'];

require_once(dirname(__FILE__)."/templets/makehtml_story.htm");

?>
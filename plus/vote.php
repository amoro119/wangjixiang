<?php
require(dirname(__FILE__)."/../include/common.inc.php");
require(DEDEINC."/dedevote.class.php");
if(empty($dopost)) $dopost = '';

$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
if($aid==0) die(" Request Error! ");

if($aid==0)
{
	ShowMsg("ûָ��ͶƱ��Ŀ��ID��","-1");
	exit();
}
$vo = new DedeVote($aid);
$rsmsg = '';

if($dopost=='send')
{
  if(!empty($voteitem))
  {
  	$rsmsg = "<br />&nbsp;�㷽�ŵ�ͶƱ״̬��".$vo->SaveVote($voteitem)."<br />";
  }
  else
  {
  	$rsmsg = "<br />&nbsp;��ղ�ûѡ���κ�ͶƱ��Ŀ��<br />";
  }
}

$voname = $vo->VoteInfos['votename'];
$totalcount = $vo->VoteInfos['totalcount'];
$starttime = GetDateMk($vo->VoteInfos['starttime']);
$endtime = GetDateMk($vo->VoteInfos['endtime']);
$votelist = $vo->GetVoteResult("98%",30,"30%");

//��ʾģ��(��PHP�ļ�)
include(DEDETEMPLATE.'/plus/vote.htm');

?>
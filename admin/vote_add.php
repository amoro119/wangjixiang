<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('plus_ͶƱģ��');
if(empty($dopost))
{
	$dopost = "";
}

if($dopost=="save")
{
	$starttime = GetMkTime($starttime);
	$endtime = GetMkTime($endtime);
	$voteitems = "";
	$j=0;
	for($i=1;$i<=15;$i++)
	{
		if(!empty(${"voteitem".$i}))
		{
			$j++;
			$voteitems .= "<v:note id=\\'$j\\' count=\\'0\\'>".${"voteitem".$i}."</v:note>\r\n";
		}
	}
	$inQuery = "Insert into #@__vote(votename,starttime,endtime,totalcount,ismore,votenote)
	Values('$votename','$starttime','$endtime','0','$ismore','$voteitems'); ";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		ShowMsg("����ͶƱʧ�ܣ����������Ƿ�Ƿ���","-1");
		exit();
	}
	ShowMsg("�ɹ�����һ��ͶƱ��","vote_main.php");
	exit();
}
$startDay = time();
$endDay = AddDay($startDay,30);
$startDay = GetDateTimeMk($startDay);
$endDay = GetDateTimeMk($endDay);
include DedeInclude('templets/vote_add.htm');

?>
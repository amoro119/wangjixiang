<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('co_PlayNote');
require_once(DEDEINC.'/dedecollection.class.php');
if($totalnum==0)
{
	ShowMsg('��ȡ������ַΪ�㣺�����ǹ��򲻶Ի�û���������ݣ�','javascript:;');
	exit();
}
if(!isset($oldstart))
{
	$oldstart = $startdd;
}
if(empty($notckpic))
{
	$notckpic = 0;
}
if($totalnum > $startdd+$pagesize)
{
	$limitSql = " limit $startdd,$pagesize ";
}
else
{
	$limitSql = " limit $startdd,".($totalnum - $startdd);
}
if($totalnum - $startdd < 1)
{
	if(empty($nid))
	{
		$dsql->ExecuteNoneQuery("Update `#@__co_note` set cotime='".time()."'; ");
	}
	else
	{
		$dsql->ExecuteNoneQuery("Update `#@__co_note` set cotime='".time()."' where nid='$nid'; ");
	}
	ShowMsg('��ɵ�ǰ��������','javascript:;');
	exit();
}
$co = new DedeCollection();
if(!empty($nid))
{
	$co->LoadNote($nid);
}

//ûָ���ɼ�IDʱ������������
if(!empty($nid))
{
	$dsql->SetQuery("Select aid,nid,url,isdown,litpic From `#@__co_htmls` where nid=$nid $limitSql ");
}
else
{
	$dsql->SetQuery("Select aid,nid,url,isdown,litpic From `#@__co_htmls` $limitSql ");
}
$dsql->Execute(99);
$tjnum = $startdd;
while($row = $dsql->GetObject(99))
{
	if($row->isdown==0)
	{
		if(empty($nid))
		{
			$co->LoadNote($row->nid);
		}
		$co->DownUrl($row->aid,$row->url,$row->litpic);
	}
	$tjnum++;
	if($sptime>0) sleep($sptime);
}
if($totalnum-$oldstart!=0)
{
	$tjlen = ceil( (($tjnum-$oldstart)/($totalnum-$oldstart)) * 100 );
	$dvlen = $tjlen * 2;
	$tjsta = "<div style='width:200;height:15;border:1px solid #898989;text-align:left'><div style='width:$dvlen;height:15;background-color:#829D83'></div></div>";
	$tjsta .= "<br/>��ɵ�ǰ����ģ�$tjlen %������ִ������...";
}
if($tjnum < $totalnum)
{
	ShowMsg($tjsta,"co_gather_start_action.php?notckpic=$notckpic&sptime=$sptime&nid=$nid&oldstart=$oldstart&totalnum=$totalnum&startdd=".($startdd+$pagesize)."&pagesize=$pagesize","",0);
	exit();
}
else
{
	if(empty($nid))
	{
		$dsql->ExecuteNoneQuery("Update `#@__co_note` set cotime='".time()."'; ");
	}
	else
	{
		$dsql->ExecuteNoneQuery("Update `#@__co_note` set cotime='".time()."' where nid='$nid'; ");
	}
	ShowMsg("��ɵ�ǰ��������","javascript:;");
	exit();
}

?>
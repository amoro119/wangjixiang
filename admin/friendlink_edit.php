<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('plus_��������ģ��');
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL']) ? 'friendlink_main.php' : $_COOKIE['ENV_GOBACK_URL'];
if(empty($dopost))
{
	$dopost = "";
}
if(isset($allid))
{
	$aids = explode(',',$allid);
	if(count($aids)==1)
	{
		$id = $aids[0];
		$dopost = "delete";
	}
}
if($dopost=="delete")
{
	$id = ereg_replace("[^0-9]","",$id);
	$dsql->ExecuteNoneQuery("Delete From `#@__flink` where id='$id'");
	ShowMsg("�ɹ�ɾ��һ�����ӣ�",$ENV_GOBACK_URL);
	exit();
}
else if($dopost=="delall")
{
	$aids = explode(',',$aids);
	if(isset($aids) && is_array($aids))
	{
		foreach($aids as $aid)
		{
			$aid = ereg_replace("[^0-9]","",$aid);
			$dsql->ExecuteNoneQuery("Delete From `#@__flink` where id='$aid'");
		}
		ShowMsg("�ɹ�ɾ��ָ�����ӣ�",$ENV_GOBACK_URL);
		exit();
	}
	else
	{
		ShowMsg("��ûѡ���κ����ӣ�",$ENV_GOBACK_URL);
		exit();
	}
}
else if($dopost=="saveedit")
{
	$id = ereg_replace("[^0-9]","",$id);
	$query = "Update `#@__flink` set sortrank='$sortrank',url='$url',webname='$webname',logo='$logo',msg='$msg',
	              email='$email',typeid='$typeid',ischeck='$ischeck' where id='$id' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�����һ�����ӣ�",$ENV_GOBACK_URL);
	exit();
}
$myLink = $dsql->GetOne("Select #@__flink.*,#@__flinktype.typename From #@__flink left join #@__flinktype on #@__flink.typeid=#@__flinktype.id where #@__flink.id=$id");
include DedeInclude('templets/friendlink_edit.htm');

?>
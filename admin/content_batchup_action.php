<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_ArcBatch');
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEADMIN."/inc/inc_batchup.php");
@set_time_limit(0);

//typeid,startid,endid,seltime,starttime,endtime,action,newtypeid
//��������
//check del move makehtml
//��ȡID����
if(empty($startid))
{
	$startid = 0;
}
if(empty($endid))
{
	$endid = 0;
}
if(empty($seltime))
{
	$seltime = 0;
}
if(empty($typeid))
{
	$typeid = 0;
}
//����HTML����������ҳ�洦��
if($action=="makehtml")
{
	$jumpurl  = "makehtml_archives_action.php?endid=$endid&startid=$startid";
	$jumpurl .= "&typeid=$typeid&pagesize=20&seltime=$seltime";
	$jumpurl .= "&stime=".urlencode($starttime)."&etime=".urlencode($endtime);
	header("Location: $jumpurl");
	exit();
}

$gwhere = " where 1 ";
if($startid >0 )
{
	$gwhere .= " And id>= $startid ";
}
if($endid > $startid)
{
	$gwhere .= " And id<= $endid ";
}

$idsql = '';
if($typeid!=0)
{
	$ids = GetSonIds($typeid);
	$gwhere .= " And typeid in($ids) ";
}
if($seltime==1)
{
	$t1 = GetMkTime($starttime);
	$t2 = GetMkTime($endtime);
	$gwhere .= " And (senddate >= $t1 And senddate <= $t2) ";
}

//�������
if(!empty($heightdone))
{
	$action=$heightdone;
}

//ָ�����
if($action=='check')
{
	if(empty($startid) || empty($endid) || $endid < $startid)
	{
		ShowMsg('�ò�������ָ����ʼID��','javascript:;');
		exit();
	}
	$jumpurl  = "makehtml_archives_action.php?endid=$endid&startid=$startid";
	$jumpurl .= "&typeid=$typeid&pagesize=20&seltime=$seltime";
	$jumpurl .= "&stime=".urlencode($starttime)."&etime=".urlencode($endtime);
	$dsql->SetQuery("Select id,arcrank From `#@__arctiny` $gwhere");
	$dsql->Execute('c');
	while($row = $dsql->GetObject('c'))
	{
		if($row->arcrank==-1)
		{
			$dsql->ExecuteNoneQuery("Update `#@__arctiny` set arcrank=0 where id='{$row->id}'");
			$dsql->ExecuteNoneQuery("Update `#@__archives` set arcrank=0 where id='{$row->id}'");
		}
	}
	ShowMsg("������ݿ����˴���׼������HTML...",$jumpurl);
	exit();
}

//����ɾ��
else if($action=='del')
{
	if(empty($startid) || empty($endid) || $endid < $startid)
	{
		ShowMsg('�ò�������ָ����ʼID��','javascript:;');
		exit();
	}
	$dsql->SetQuery("Select id From `#@__archives` $gwhere");
	$dsql->Execute('x');
	$tdd = 0;
	while($row = $dsql->GetObject('x'))
	{
		if(DelArc($row->id))
		{
			$tdd++;
		}
	}
	ShowMsg("�ɹ�ɾ�� $tdd ����¼��","javascript:;");
	exit();
}

//ɾ���ձ����ĵ�
else if($action=='delnulltitle')
{
	$dsql->SetQuery("Select id From `#@__archives` where trim(title)='' ");
	$dsql->Execute('x');
	$tdd = 0;
	while($row = $dsql->GetObject('x'))
	{
		if(DelArc($row->id))
		{
			$tdd++;
		}
	}
	ShowMsg("�ɹ�ɾ�� $tdd ����¼��","javascript:;");
	exit();
}

//ɾ������������
else if($action=='delnullbody')
{
	$dsql->SetQuery("Select aid From `#@__addonarticle` where LENGTH(body) < 10 ");
	$dsql->Execute('x');
	$tdd = 0;
	while($row = $dsql->GetObject('x'))
	{
		if(DelArc($row->aid))
		{
			$tdd++;
		}
	}
	ShowMsg("�ɹ�ɾ�� $tdd ����¼��","javascript:;");
	exit();
}

//��������ͼ����
else if($action=='modddpic')
{
	$dsql->ExecuteNoneQuery("Update `#@__archives` set litpic='' where trim(litpic)='litpic' ");
	ShowMsg("�ɹ���������ͼ����","javascript:;");
	exit();
}

//�����ƶ�
else if($action=='move')
{
	if(empty($typeid))
	{
		ShowMsg('�ò�������ָ����Ŀ��','javascript:;');
		exit();
	}
	$typeold = $dsql->GetOne("Select * From #@__arctype where id='$typeid'; ");
	$typenew = $dsql->GetOne("Select * From #@__arctype where id='$newtypeid'; ");
	if(!is_array($typenew))
	{
		ShowMsg("�޷�����ƶ���������Ŀ����Ϣ��������ɲ�����","javascript:;");
		exit();
	}
	if($typenew['ispart']!=0)
	{
		ShowMsg("�㲻�ܰ������ƶ����������б����Ŀ��","javascript:;");
		exit();
	}
	if($typenew['channeltype']!=$typeold['channeltype'])
	{
		ShowMsg("���ܰ������ƶ����������Ͳ�ͬ����Ŀ��","javascript:;");
		exit();
	}
	$gwhere .= " And channel='".$typenew['channeltype']."' And title like '%$keyword%'";

	$ch = $dsql->GetOne("Select addtable From `#@__channeltype` where id={$typenew['channeltype']} ");
	$addtable = $ch['addtable'];

	$dsql->SetQuery("Select id From `#@__archives` $gwhere");
	$dsql->Execute('m');
	$tdd = 0;
	while($row = $dsql->GetObject('m'))
	{
		$rs = $dsql->ExecuteNoneQuery("Update `#@__arctiny` set typeid='$newtypeid' where id='{$row->id}'");
		$rs = $dsql->ExecuteNoneQuery("Update `#@__archives` set typeid='$newtypeid' where id='{$row->id}'");
		if($addtable!='')
		{
			$dsql->ExecuteNoneQuery("Update `$addtable` set typeid='$newtypeid' where aid='{$row->id}' ");
		}
		if($rs)
		{
			$tdd++;
		}
		DelArc($row->id,true);
	}

	if($tdd>0)
	{
		$jumpurl  = "makehtml_archives_action.php?endid=$endid&startid=$startid";
		$jumpurl .= "&typeid=$newtypeid&pagesize=20&seltime=$seltime";
		$jumpurl .= "&stime=".urlencode($starttime)."&etime=".urlencode($endtime);
		ShowMsg("�ɹ��ƶ� $tdd ����¼��׼����������HTML...",$jumpurl);
	}
	else
	{
		ShowMsg("��ɲ�����û�ƶ��κ�����...","javascript:;");
	}
}

//ɾ���ձ�������
else if($action=='delnulltitle')
{
	$dsql->SetQuery("Select id From #@__archives where trim(title)='' ");
	$dsql->Execute('x');
	$tdd = 0;
	while($row = $dsql->GetObject('x'))
	{
		if(DelArc($row->id))
		{
			$tdd++;
		}
	}
	ShowMsg("�ɹ�ɾ�� $tdd ����¼��","javascript:;");
	exit();
}

//��������ͼ����
else if($action=='modddpic')
{
	$dsql->ExecuteNoneQuery("Update #@__archives set litpic='' where trim(litpic)='litpic' ");
	ShowMsg("�ɹ���������ͼ����","javascript:;");
	exit();
}
?>
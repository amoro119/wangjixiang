<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('co_PlayNote');
require_once(DEDEINC.'/dedecollection.class.php');
if(empty($islisten))
{
	$islisten = 0;
}
if(empty($glstart))
{
	$glstart = 0;
}
if(empty($totalnum))
{
	$totalnum = 0;
}
if(empty($notckpic))
{
	$notckpic = 0;
}
$nid = (isset($nid) ? intval($nid) : 0);

//����������ַ��δ��������ģʽ
/*-----------------------------
function Download_not_down() { }
------------------------------*/
if($islisten==0)
{
	$mrow = $dsql->GetOne("Select count(*) as dd From `#@__co_htmls` where nid='$nid' ");
	$totalnum = $mrow['dd'];
	$gurl = "co_gather_start_action.php?notckpic=$notckpic&islisten=$islisten&nid=$nid&startdd=$startdd&pagesize=$pagesize&sptime=$sptime";
	if($totalnum <= 0)
	{
		ShowMsg("��ָ����ģʽΪ��<font color='red'>[����������ַ��δ��������]</font>��<br />ʹ�����ģʽ�ڵ�����Ѿ���������ַ��������ʹ������ģʽ��","javascript:;");
		exit();
	}
	else
	{
		ShowMsg("���ڵ���������ת����ҳ�ɼ�...",$gurl."&totalnum=$totalnum");
		exit();
	}
}

//���ʽ�ɼ�����������ݣ�
/*-----------------------------
function Download_new() { }
------------------------------*/
else if($islisten==1)
{
	$gurl = "co_gather_start_action.php?notckpic=$notckpic&islisten=1&nid=$nid&startdd=$startdd&pagesize=$pagesize&sptime=$sptime";
	$gurlList = "co_getsource_url_action.php?islisten=1&nid=0&pagesize=$pagesize&sptime=$sptime";
	//���ר�Žڵ�
	if(!empty($nid))
	{
		$co = new DedeCollection();
		$co->LoadNote($nid);
		$limitList = $co->GetSourceUrl(1,0,100);
		$row = $co->dsql->GetOne("Select count(aid) as dd From `#@__co_htmls` where nid='$nid' ");
		$totalnum = $row['dd'];
		if($totalnum==0)
		{
			ShowMsg("����ڵ���û������������....","javascript:;");
			exit();
		}
		else
		{
			ShowMsg("�ѻ������������ַ��ת����ҳ�ɼ�...",$gurl."&totalnum=$totalnum");
			exit();
		}
	}
	//������нڵ�
	else
	{
		$curpos = (isset($curpos) ? intval($curpos) : 0);
		$row = $dsql->GetOne("Select nid From `#@__co_note` order by nid asc limit $curpos,1");
		$nnid = $row['nid'];
		if(!is_array($row))
		{
			ShowMsg("������нڵ���....","co_gather_start_action.php?notckpic=0&sptime=0&nid=0&startdd=0&pagesize=5&totalnum=".$totalnum);
			exit();
		}
		else
		{
			$co = new DedeCollection();
			$co->LoadNote($nnid);
			$limitList = $co->GetSourceUrl(1,0,100);
			$curpos++;
			ShowMsg("�Ѽ��ڵ�( {$nnid} )��������һ���ڵ�...",$gurlList."&curpos=$curpos");
			exit();
		}
	}
}

//����������������ģʽ
/*-----------------------------
function Download_all() { }
------------------------------*/
else
{
	$gurl = "co_gather_start_action.php?notckpic=$notckpic&islisten=$islisten&nid=$nid&startdd=$startdd&pagesize=$pagesize&sptime=$sptime";
	$gurlList = "co_getsource_url_action.php?islisten=$islisten&nid=$nid&startdd=$startdd&pagesize=$pagesize&sptime=$sptime";
	$co = new DedeCollection();
	$co->LoadNote($nid);
	$limitList = $co->GetSourceUrl($islisten,$glstart,$pagesize);
	if($limitList==0)
	{
		$row = $co->dsql->GetOne("Select count(aid) as dd From `#@__co_htmls` where nid='$nid'");
		$totalnum = $row['dd'];
		ShowMsg("�ѻ������������ַ��ת����ҳ�ɼ�...",$gurl."&totalnum=$totalnum");
		exit();
	}
	if($limitList>0)
	{
		ShowMsg("�ɼ��б�ʣ�ࣺ{$limitList} ��ҳ�棬�����ɼ�...",$gurlList."&glstart=".($glstart+$pagesize),0,100);
		exit();
	}
}

?>
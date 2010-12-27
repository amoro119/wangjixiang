<?php
require_once (dirname(__FILE__) . "/../include/common.inc.php");
require_once DEDEINC.'/shopcar.class.php';
$cart = new MemberShops();

$do = isset($do) ? trim($do) : 'add';

if($do == 'add')
{
	/*
	function addItem();				add a product to car
	*/
	$buynum = isset($buynum) && is_numeric($buynum) ? $buynum : 1;
	$id =empty($id)? "" : intval($id);
	$buynum = ($buynum < 1) ? 1 : $buynum;
	$rs = $dsql->GetOne("SELECT id,channel,title FROM #@__archives WHERE id='$id'");
	if(!is_array($rs))
	{
		ShowMsg("����Ʒ�Ѳ����ڣ�","-1");
		exit();
	}
	$cts = GetChannelTable($rs['channel']);
	$rows = $dsql->GetOne("SELECT aid as id,trueprice as price,units FROM `$cts[addtable]` WHERE aid='$id'");
	if(!is_array($rows))
	{
		ShowMsg("����Ʒ�Ѳ����ڣ�","-1");
		exit();
	}
	$rows['buynum'] = $buynum;
	$rows['title'] 	= $rs['title'];
	$cart->addItem($id, $rows);
	ShowMsg("����Ӽӵ����ﳵ,<a href='car.php'>�鿴���ﳵ</a>","car.php");
	exit();
}
elseif($do == 'del')
{
	/*
	function delItem();				del products from car
	*/
	if(!isset($ids))
	{
		ShowMsg("��ѡ��Ҫɾ������Ʒ��","-1");
		exit;
	}
	if(is_array($ids))
	{
		foreach($ids as $id)
		{
			$id = intval($id);
			$cart->delItem($id);
		}
	}
	else
	{
		$ids = intval($ids);
		$cart->delItem($ids);
	}
	ShowMsg("�ѳɹ�ɾ�����ﳵ�е���Ʒ,<a href='car.php'>�鿴���ﳵ</a>","car.php");
	exit;
}
elseif($do == 'clear')
{
	/*
	function clearItem();		clear car products all!
	*/
	$cart->clearItem();
	ShowMsg("���ﳵ����Ʒ��ȫ����գ�","car.php");
	exit;
}
elseif($do == 'update')
{
	/*
	function updateItem();		update car products number!
	*/
	if(isset($ids) && is_array($ids))
	{
		foreach($ids as $id){
			$id = intval($id);
			$rs = $dsql->GetOne("SELECT id,channel,title FROM #@__archives WHERE id='$id'");
			if(!is_array($rs)) continue;
			$cts = GetChannelTable($rs['channel']);
			$rows = $dsql->GetOne("SELECT aid as id,trueprice as price,units FROM `$cts[addtable]` WHERE aid='$id'");
			if(!is_array($rows)) continue;
			$rows['buynum'] = intval(${'buynum'.$id});
			if($rows['buynum'] < 1)
			{
				//����赥λ����С��1��ʱ����,���Ƴ����ﳵ
				$cart->delItem($id);
				continue;
			}
			$rows['title'] 	= $rs['title'];
			$cart->addItem($id, $rows);
		}
	}
	ShowMsg("���ﳵ����Ʒ��ȫ�����£�","car.php");
	exit;
}
?>
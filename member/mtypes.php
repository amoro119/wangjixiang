<?php
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
$dopost = isset($dopost) ? trim($dopost) : '';
$menutype = 'config';
if($dopost == '')
{
	if(empty($channelid)) $channelid = 0;
	$channelid = intval($channelid);
	$mtypearr = array();
	$addquery = '';
	if(!empty($channelid)) $addquery = " And channelid='$channelid' ";
	$query = "select * from `#@__mtypes` where mid='{$cfg_ml->M_ID}' $addquery ";
	$dsql->SetQuery($query);
	$dsql->Execute();
	while($row = $dsql->GetArray())
	{
		$mtypearr[] = $row;
	}
	$tpl = new DedeTemplate();
	$tpl->LoadTemplate(DEDEMEMBER.'/templets/mtypes.htm');
	$tpl->Display();
	exit();
}
elseif ($dopost == 'add')
{
	$mtypename = HtmlReplace(trim($mtypename));
	$channelid = intval($channelid);
	if(empty($channelid)) $channelid = 1;
	if(strlen($mtypename) > 40 || strlen($mtypename) < 2)
	{
		ShowMsg('�������Ʊ�����������ֽ�����40���ֽ�', '-1');
		exit();
	}
	$query = "insert into `#@__mtypes`(mtypename, channelid, mid) values ('$mtypename', '$channelid', '$cfg_ml->M_ID'); ";
	if($dsql->ExecuteNoneQuery($query))
	{
		ShowMsg('���ӷ���ɹ�', 'mtypes.php');
	}
	else
	{
		ShowMsg('���ӷ���ʧ��', '-1');
	}
	exit();
}
elseif ($dopost == 'save')
{
	if(isset($mtypeidarr) && is_array($mtypeidarr))
	{
		$delids = '0';
		$mtypeidarr = array_filter($mtypeidarr, 'is_numeric');
		foreach($mtypeidarr as $delid)
		{
			$delids .= ','.$delid;
			unset($mtypename[$delid]);
		}
		$query = "delete from `#@__mtypes` where mtypeid in ($delids) and mid='$cfg_ml->M_ID';";
		$dsql->ExecNoneQuery($query);
	}
	foreach ($mtypename as $id => $name)
	{
		$name = HtmlReplace($name);
		$query = "update `#@__mtypes` set mtypename='$name' where mtypeid='$id' and mid='$cfg_ml->M_ID'";
		$dsql->ExecuteNoneQuery($query);
	}
	ShowMsg('�����޸����','mtypes.php');
}

?>
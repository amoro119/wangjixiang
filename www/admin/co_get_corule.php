<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('co_AddNote');
if(empty($job))
{
	$job='';
}
if($job=='')
{
	require_once(DEDEINC."/../include/oxwindow.class.php");
	$wintitle = "����ɼ�����";
	$wecome_info = "<a href='co_main.php'><u>�ɼ������</u></a>::����ɼ�����";
	$win = new OxWindow();
	$win->Init("co_get_corule.php","js/blank.js","POST");
	$win->AddHidden("job","yes");
	$win->AddTitle("��������������Ҫ������ı����ã�(������base64����[֧�ֲ�����Ĺ��򣬵������ݾɰ����])");
	$win->AddMsgItem("<textarea name='notes' style='width:100%;height:300px'></textarea>");
	$winform = $win->GetWindow("ok");
	$win->Display();
	exit();
}
else
{
	CheckPurview('co_AddNote');
	require_once(DEDEINC."/dedetag.class.php");
	$notes = trim($notes);

	//��Base64��ʽ�Ĺ�����н���
	if(ereg('^BASE64:',$notes))
	{
		if(!ereg(':END$',$notes))
		{
			ShowMsg('�ù��򲻺Ϸ���Base64��ʽ�Ĳɼ�����Ϊ��BASE64:base64����������:END !','-1');
			exit();
		}
		$notess = explode(':',$notes);
		$notes = $notess[1];
		$notes = base64_decode(ereg_replace("[\r\n\t ]",'',$notes)) OR die('�����ַ����д���');
	}
	else
	{
		$notes = stripslashes($notes);
	}
	$dtp = new DedeTagParse();
	$dtp->LoadString($notes);
	if(!is_array($dtp->CTags))
	{
		ShowMsg('�ù��򲻺Ϸ����޷�����!','-1');
		exit();
	}
	$ctag1 = $dtp->GetTagByName('listconfig');
	$ctag2 = $dtp->GetTagByName('itemconfig');
	$listconfig = $ctag1->GetInnerText();
	$itemconfig = addslashes($ctag2->GetInnerText());
	$dtp->LoadString($listconfig);
	$listconfig = addslashes($listconfig);
	$noteinfo = $dtp->GetTagByName('noteinfo');
	if(!is_object($noteinfo))
	{
		ShowMsg("�ù��򲻺Ϸ����޷�����!","-1");
		exit();
	}
	foreach($noteinfo->CAttribute->Items as $k=>$v)
	{
		$$k = addslashes($v);
	}
	$uptime = time();
	if(empty($freq))
	{
		$freq = 1;
	}
	if(empty($extypeid))
	{
		$extypeid = 0;
	}
	if(empty($islisten))
	{
		$islisten = 0;
	}
	$inquery = " INSERT INTO `#@__co_note`(`channelid`,`notename`,`sourcelang`,`uptime`,`cotime`,`pnum`,`isok`,`usemore`,`listconfig`,`itemconfig`)
               VALUES ('$channelid','$notename','$sourcelang','$uptime','0','0','0','$usemore','$listconfig','$itemconfig'); ";
	$rs = $dsql->ExecuteNoneQuery($inquery);
	if(!$rs)
	{
		ShowMsg("������Ϣʱ���ִ���".$dsql->GetError(),"-1");
		exit();
	}
	ShowMsg("�ɹ�����һ������!","co_main.php");
	exit();
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_plus');
$aid = ereg_replace("[^0-9]","",$aid);
if($dopost=="show")
{
	$dsql->ExecuteNoneQuery("update #@__plus set isshow=1 where aid='$aid';");
	ShowMsg("�ɹ�����һ�����,��ˢ�µ����˵�!","plus_main.php");
	exit();
}
else if($dopost=="hide")
{
	$dsql->ExecuteNoneQuery("update #@__plus set isshow=0 where aid='$aid';");
	ShowMsg("�ɹ�����һ�����,��ˢ�µ����˵�!","plus_main.php");
	exit();
}
else if($dopost=="delete")
{
	if(empty($job))
	{
		$job="";
	}
	if($job=="") //ȷ����ʾ
	{
		require_once(DEDEINC."/oxwindow.class.php");
		$wintitle = "ɾ�����";
		$wecome_info = "<a href='plus_main.php'>�������</a>::ɾ�����";
		$win = new OxWindow();
		$win->Init("plus_edit.php","js/blank.js","POST");
		$win->AddHidden("job","yes");
		$win->AddHidden("dopost",$dopost);
		$win->AddHidden("aid",$aid);
		$win->AddTitle("��ȷʵҪɾ��'".$title."'��������");
		$win->AddMsgItem("<font color='red'>���棺������ɾ������ɾ���˵��Ҫ�ɾ�ɾ������ģ�����ɾ����<br /><br /> <a href='module_main.php?moduletype=plus'>ģ�����&gt;&gt;</a> </font>");
		$winform = $win->GetWindow("ok");
		$win->Display();
		exit();
	}
	else if($job=="yes") //����
	{
		$dsql->ExecuteNoneQuery("Delete From #@__plus where aid='$aid';");
		ShowMsg("�ɹ�ɾ��һ�����,��ˢ�µ����˵�!","plus_main.php");
		exit();
	}
}
else if($dopost=="saveedit") //�������
{
	$inquery = "Update #@__plus set plusname='$plusname',menustring='$menustring',filelist='$filelist' where aid='$aid';";
	$dsql->ExecuteNoneQuery($inquery);
	ShowMsg("�ɹ����Ĳ��������!","plus_main.php");
	exit();
}
$row = $dsql->GetOne("Select * From #@__plus where aid='$aid'");
include DedeInclude('templets/plus_edit.htm');

?>
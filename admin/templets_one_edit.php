<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('temp_One');
if(empty($dopost))
{
	$dopost = "";
}
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
if($dopost=="saveedit")
{
	include_once(DEDEINC."/arc.sgpage.class.php");
	$uptime = time();
	$body = str_replace('&quot;','\\"',$body);
	$filename = ereg_replace("^/","",$nfilename);

	//����������ļ�����ɾ�����ļ�
	if($oldfilename!=$filename)
	{
		$oldfilename = $cfg_basedir.$cfg_cmspath."/".$oldfilename;
		if(is_file($oldfilename))
		{
			unlink($oldfilename);
		}
	}
	if($likeidsel!=$oldlikeid )
	{
		$likeid = $likeidsel;
	}
	$inQuery = "
	 update `#@__sgpage` set
	 title='$title',
	 keywords='$keywords',
	 description='$description',
	 likeid='$likeid',
	 ismake='$ismake',
	 filename='$filename',
	 template='$template',
	 uptime='$uptime',
	 body='$body'
	 where aid='$aid'; ";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		ShowMsg("����ҳ������ʱʧ�ܣ����鳤���Ƿ������⣡","-1");
		exit();
	}
	$sg = new sgpage($aid);
	$sg->SaveToHtml();
	ShowMsg("�ɹ��޸�һ��ҳ�棡","templets_one.php");
	exit();
}
else if($dopost=="delete")
{
	$row = $dsql->GetOne("Select filename From `#@__sgpage` where aid='$aid'");
	$filename = ereg_replace("/{1,}","/",$cfg_basedir.$cfg_cmspath."/".$row['filename']);
	$dsql->ExecuteNoneQuery(" Delete From `#@__sgpage` where aid='$aid' ");
	if(is_file($filename))
	{
		unlink($filename);
	}
	ShowMsg("�ɹ�ɾ��һ��ҳ�棡","templets_one.php");
	exit();
}
else if($dopost=="make")
{
	include_once(DEDEINC."/arc.sgpage.class.php");
	$row = $dsql->GetOne("Select filename From `#@__sgpage` where aid='$aid'");
	$fileurl = $cfg_cmsurl.'/'.ereg_replace("/{1,}","/",$row['filename']);
	$sg = new sgpage($aid);
	$sg->SaveToHtml();
	ShowMsg("�ɹ�����һ��ҳ�棡",$fileurl);
	exit();
}
else if($dopost=="mkall")
{
	include_once(DEDEINC."/arc.sgpage.class.php");
	$dsql->Execute("ex","Select aid From `#@__sgpage` ");
	$i = 0;
	while($row = $dsql->GetArray("ex"))
	{
		$sg = new sgpage($row['aid']);
		$sg->SaveToHtml();
		$i++;
	}
	ShowMsg("�ɹ����� $i ��ҳ�棡",'-1');
	exit();
}
else if($dopost=="mksel")
{
	if(empty($ids))
	{
		$ids = '';
	}
	include_once(DEDEINC."/arc.sgpage.class.php");
	$i = 0;
	if($ids == 0)
	{
		ShowMsg('��û��ѡ����Ҫ���µ��ĵ���','-1');
		exit();
	}
	elseif(is_array($ids))
	{
		foreach($ids as $aid)
		{
			$sg = new sgpage($aid);
			$sg->SaveToHtml();
			$i++;
		}
		ShowMsg("�ɹ����� $i ��ҳ�棡",'-1');
		exit();
	}
}
$row = $dsql->GetOne("Select  * From `#@__sgpage` where aid='$aid' ");
include(DEDEADMIN."/templets/templets_one_edit.htm");

?>
<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('plus_��������ģ��');
if(empty($dopost))
{
	$dopost="";
}
if($dopost=="add")
{
	$dtime = time();
	if(is_uploaded_file($logoimg))
	{
		$names = split("\.",$logoimg_name);
		$shortname = ".".$names[count($names)-1];
		if(!eregi("(jpg|gif|png)$",$shortname))
		{
			$shortname = '.gif';
		}
		$filename = MyDate("ymdHis",time()).mt_rand(1000,9999).$shortname;
		$imgurl = $cfg_medias_dir."/flink";
		if(!is_dir($cfg_basedir.$imgurl))
		{
			MkdirAll($cfg_basedir.$imgurl,$cfg_dir_purview);
			CloseFtp();
		}
		$imgurl = $imgurl."/".$filename;
		move_uploaded_file($logoimg,$cfg_basedir.$imgurl) or die("�����ļ���:".$cfg_basedir.$imgurl."ʧ��");
		@unlink($logoimg);
	}
	else
	{
		$imgurl = $logo;
	}
	
	//ǿ�Ƽ���û��������ӷ����Ƿ����ݽṹ����
	if(empty($typeid) || ereg("[^0-9]", $typeid))
	{
		$typeid = 0;
		$dsql->ExecuteNoneQuery("ALTER TABLE `#@__flinktype` CHANGE `ID` `id` MEDIUMINT( 8 ) UNSIGNED DEFAULT NULL AUTO_INCREMENT; ");
	}
	
	$query = "Insert Into `#@__flink`(sortrank,url,webname,logo,msg,email,typeid,dtime,ischeck)
            Values('$sortrank','$url','$webname','$imgurl','$msg','$email','$typeid','$dtime','$ischeck'); ";
	$rs = $dsql->ExecuteNoneQuery($query);
	$burl = empty($_COOKIE['ENV_GOBACK_URL']) ? "friendlink_main.php" : $_COOKIE['ENV_GOBACK_URL'];
	if($rs)
	{
		ShowMsg("�ɹ�����һ������!",$burl,0,500);
		exit();
	}
	else
	{
		ShowMsg("��������ʱ��������ٷ�������ԭ��".$dsql->GetError(),"javascript:;");
		exit();
	}
}
include DedeInclude('templets/friendlink_add.htm');

?>
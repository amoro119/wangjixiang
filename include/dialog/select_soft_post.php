<?php
if(!isset($cfg_basedir))
{
	include_once(dirname(__FILE__).'/config.php');
}
if(empty($uploadfile)) $uploadfile = '';
if(empty($uploadmbtype)) $uploadmbtype = '�������';
if(empty($bkurl)) $bkurl = 'select_soft.php';
$newname = ( empty($newname) ? '' : ereg_replace("[\\ \"\*\?\t\r\n<>':/|]", "", $newname) );

if(!is_uploaded_file($uploadfile))
{
	ShowMsg("��û��ѡ���ϴ����ļ���ѡ����ļ���С��������!", "-1");
	exit();
}

//�����������֧�ֵĸ���
$cfg_softtype = $cfg_softtype;
$cfg_softtype = str_replace('||', '|', $cfg_softtype);
$uploadfile_name = trim(ereg_replace("[ \r\n\t\*\%\\/\?><\|\":]{1,}",'',$uploadfile_name));
if(!eregi("\.(".$cfg_softtype.")", $uploadfile_name))
{
	ShowMsg("�����ϴ���{$uploadmbtype}��������б������ϵͳ����չ���޶������ã�","");
	exit();
}

$nowtme = time();
if($activepath==$cfg_soft_dir)
{
	$newdir = MyDate($cfg_addon_savetype, $nowtme);
	$activepath = $activepath.'/'.$newdir;
	if(!is_dir($cfg_basedir.$activepath))
	{
		MkdirAll($cfg_basedir.$activepath,$cfg_dir_purview);
		CloseFtp();
	}
}

//�ļ�����ǰΪ�ֹ�ָ���� �����Զ�����
if(!empty($newname))
{
	$filename = $newname;
	if(!ereg("\.", $filename)) $fs = explode('.', $uploadfile_name);
	else $fs = explode('.', $filename);
	if(eregi($cfg_not_allowall, $fs[count($fs)-1]))
	{
		ShowMsg("��ָ�����ļ�����ϵͳ��ֹ��",'javascript:;');
		exit();
	}
	if(!ereg("\.", $filename)) $filename = $filename.'.'.$fs[count($fs)-1];
}else{
	$filename = $cuserLogin->getUserID().'-'.dd2char(MyDate('ymdHis',$nowtme));
	$fs = explode('.', $uploadfile_name);
	if(eregi($cfg_not_allowall, $fs[count($fs)-1]))
	{
		ShowMsg("���ϴ���ĳЩ���ܴ��ڲ���ȫ���ص��ļ���ϵͳ�ܾ�������",'javascript:;');
		exit();
	}
	$filename = $filename.'.'.$fs[count($fs)-1];
}

$fullfilename = $cfg_basedir.$activepath.'/'.$filename;
$fullfileurl = $activepath.'/'.$filename;
move_uploaded_file($uploadfile,$fullfilename) or die("�ϴ��ļ��� $fullfilename ʧ�ܣ�");
@unlink($uploadfile);
if($cfg_remote_site=='Y' && $remoteuploads == 1)
{
	//����Զ���ļ�·��
	$remotefile = str_replace(DEDEROOT, '', $fullfilename);
	$localfile = '../..'.$remotefile;
	//����Զ���ļ���
	$remotedir = preg_replace('/[^\/]*\.('.$cfg_softtype.')/', '', $remotefile);
	$ftp->rmkdir($remotedir);
	$ftp->upload($localfile, $remotefile);
}

if($uploadfile_type == 'application/x-shockwave-flash')
{
	$mediatype=2;
}
else if(eregi('image',$uploadfile_type))
{
	$mediatype=1;
}
else if(eregi('audio|media|video',$uploadfile_type))
{
	$mediatype=3;
}
else
{
	$mediatype=4;
}

$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
   VALUES ('0','$filename','$fullfileurl','$mediatype','0','0','0','{$uploadfile_size}','{$nowtme}','".$cuserLogin->getUserID()."'); ";

$dsql->ExecuteNoneQuery($inquery);
$fid = $dsql->GetLastID();
AddMyAddon($fid, $fullfileurl);

ShowMsg("�ɹ��ϴ��ļ���",$bkurl."?comeback=".urlencode($filename)."&f=$f&activepath=".urlencode($activepath)."&d=".time());
exit();
?>
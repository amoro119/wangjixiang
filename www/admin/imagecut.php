<?php
require_once(dirname(__FILE__).'/config.php');
$action = isset($action) ? trim($action) : '';
if(empty($action))
{
	if(!@is_file($cfg_basedir.$file))
	{
		ShowMsg("�Բ��𣬱���ѡ��վ�ڵ�ͼƬ���ܽ��вü���<br />���'<a href='/include/dialog/select_images.php?f=form1.picname&imgstick=small'>վ��ѡ��</a>', �ϴ���ѡ��һ��ͼƬ��Ȼ����ܽ��вü���", "../include/dialog/select_images.php?f=form1.picname&imgstick=small", 0 , 10000);
		exit();
	}
	include DEDEADMIN.'/templets/imagecut.htm';
	exit();
}
elseif($action == 'cut')
{
	require_once(DEDEINC.'/image.func.php');

	if(!@is_file($cfg_basedir.$file))
	{
		ShowMsg('�Բ���������ѡ��ü�ͼƬ��', '-1');
		exit();
	}
	if(empty($width))
	{
		ShowMsg('�Բ�����ѡ��ü�ͼƬ�ĳߴ磡', '-1');
		exit();
	}
	if(empty($height))
	{
		ShowMsg('�Բ�����ѡ��ü�ͼƬ�ĳߴ磡', '-1');
		exit();
	}
	$imginfo = getimagesize($cfg_basedir.$file);
	$imgw = $imginfo[0];
	$imgh = $imginfo[1];
	$temp = 400/$imgw;
	$newwidth = 400;
	$newheight = $imgh * $temp;
	$srcFile = $cfg_basedir.$file;
	$thumb = imagecreatetruecolor($newwidth, $newheight);
	$thumba = imagecreatetruecolor($width, $height);

	switch($imginfo['mime'])
	{
		case 'image/jpeg':
			$source = imagecreatefromjpeg($srcFile);
			break;
		case 'image/gif':
			$source = imagecreatefromgif($srcFile);
			break;
		case 'image/png':
			$source = imagecreatefrompng($srcFile);
			break;
		default:
			ShowMsg('�Բ��𣬲ü�ͼƬ���Ͳ�֧����ѡ����������ͼƬ��', '-1');
			break;
	}

	imagecopyresized($thumb, $source, 0, 0, 0, 0 , $newwidth, $newheight, $imgw, $imgh);
	imagecopy($thumba, $thumb, 0, 0, $left, $top, $newwidth, $newheight);

	$ddn = substr($srcFile, -3);
	
	$ddpicok = $reObjJs = '';
	if( empty($isupload) )
	{
		$ddpicok = ereg_replace("\.".$ddn."$", '-lp.'.$ddn, $file);
		$reObjJs = "		var backObj = window.opener.document.form1.picname;
		var prvObj = window.opener.document.getElementById('divpicview');\r\n";
	}
	else
	{
		$ddpicok = $file;
		$reObjJs = "		var backObj = window.opener.parent.document.form1.picname;
		var prvObj = window.opener.parent.document.getElementById('divpicview');\r\n";
	}
	
	$ddpicokurl = $cfg_basedir.$ddpicok;

	switch($imginfo['mime'])
	{
		case 'image/jpeg':
			imagejpeg($thumba, $ddpicokurl, 85);
			break;
		case 'image/gif':
			imagegif($thumba, $ddpicokurl);
			break;
		case 'image/png':
			imagepng($thumba, $ddpicokurl);
			break;
		default:
			ShowMsg("�Բ��𣬲ü�ͼƬ���Ͳ�֧����ѡ����������ͼƬ��", "-1");
			break;
	}
	
	//������ü���ʽ�ٴ���СͼƬ���޶���С
	if($newwidth > $cfg_ddimg_width || $newheight > $cfg_ddimg_height)
	{
		ImageResize($ddpicokurl, $cfg_ddimg_width, $cfg_ddimg_height);
	}
	
	//���������ͼ�м����� ���渽����Ϣ
	if( empty($isupload) )
	{
		 $inquery = "INSERT INTO `#@__uploads`(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
        VALUES ('$ddpicok','$ddpicok','1','0','0','0','".filesize($ddpicokurl)."','".time()."','".$cuserLogin->getUserID()."'); ";
		 $dsql->ExecuteNoneQuery($inquery);
		 $fid = $dsql->GetLastID();
		 AddMyAddon($fid, $ddpicok);
	}
	
?>
<SCRIPT language=JavaScript>
function ReturnImg(reimg)
{
	<?php echo $reObjJs; ?>
	backObj.value = reimg;
	if(prvObj)
	{
		prvObj.style.width = '150px';
		prvObj.innerHTML = "<img src='"+reimg+"?n' width='150' />";
	}
	if(document.all) {
		window.opener=true;
	}
	window.close();
}
ReturnImg("<?php echo $ddpicok; ?>");
</SCRIPT>
<?php
}
?>
<?php
require_once(dirname(__FILE__)."/config.php");
include(DEDEDATA.'/mark/inc_photowatermark_config.php');
if(empty($activepath))
{
	$activepath = '';
}
if(empty($imgstick))
{
	$imgstick = '';
}
$activepath = str_replace('.','',$activepath);
$activepath = ereg_replace('/{1,}','/',$activepath);
if(strlen($activepath) < strlen($cfg_medias_dir))
{
	$activepath = $cfg_medias_dir;
}
$inpath = $cfg_basedir.$activepath;
$activeurl = '..'.$activepath;
if(empty($f))
{
	$f = 'form1.picname';
}
if(empty($v))
{
	$v = 'picview';
}
if(empty($comeback))
{
	$comeback = '';
}

?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
<title>ͼƬ�����</title>
<link href='../../plus/img/base.css' rel='stylesheet' type='text/css'>
<style>
.linerow {border-bottom: 1px solid #CBD8AC;}
.napisdiv {left:40;top:3;width:150px;height:100px;position:absolute;z-index:3;display:none;}
</style>
<script>
function nullLink(){ return; }
function ChangeImage(surl){ document.getElementById('picview').src = surl; }
</script>
</head>
<body background='img/allbg.gif' leftmargin='0' topmargin='0'>
<div id="floater" class="napisdiv">
<a href="javascript:nullLink();" onClick="document.getElementById('floater').style.display='none';"><img src='img/picviewnone.gif' id='picview' border='0' alt='�����ر�Ԥ��'></a>
</div>
<SCRIPT language=JavaScript src="js/float.js"></SCRIPT>
<SCRIPT language=JavaScript>
function nullLink(){ return; }
function ChangeImage(surl){ document.getElementById('floater').style.display='block';document.getElementById('picview').src = surl; }
function TNav()
{
	if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
  else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
  else return "OT";
}
function ReturnImg(reimg)
{
	window.opener.document.<?php echo $f?>.value=reimg;
	if(window.opener.document.getElementById('div<?php echo $v?>'))
  {
  	 if(TNav()=='IE'){
  	 	 window.opener.document.getElementById('div<?php echo $v?>').filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = reimg;
  	 	 window.opener.document.getElementById('div<?php echo $v?>').style.width = '150px';
  	 	 window.opener.document.getElementById('div<?php echo $v?>').style.height = '100px';
  	 }
  	 else
  	 	 window.opener.document.getElementById('div<?php echo $v?>').style.backgroundImage = "url("+reimg+")";
  }
	else if(window.opener.document.getElementById('<?php echo $v?>')){
		window.opener.document.getElementById('<?php echo $v?>').src = reimg;
	}
	if(document.all) window.opener=true;
  window.close();
}
</SCRIPT>
<table width='100%' border='0' cellspacing='0' cellpadding='0' align="center">
<tr>
<td colspan='4' align='right'>
<table width='100%' border='0' cellpadding='0' cellspacing='1' bgcolor='#CBD8AC'>
<tr bgcolor='#FFFFFF'>
<td colspan='4'>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
<tr bgcolor="#CCCCCC">
<td width="8%" align="center" class='linerow' bgcolor='#EEF4EA'><strong>Ԥ��</strong></td>
<td width="47%" align="center" background="img/wbg.gif" class='linerow'><strong>�������ѡ��ͼƬ</strong></td>
<td width="15%" align="center" bgcolor='#EEF4EA' class='linerow'><strong>�ļ���С</strong></td>
<td width="30%" align="center" background="img/wbg.gif" class='linerow'><strong>����޸�ʱ��</strong></td>
</tr>
<tr>
<td class='linerow' colspan='4' bgcolor='#F9FBF0'>
�����V��Ԥ��ͼƬ�����ͼƬ��ѡ��ͼƬ����ʾͼƬ������ͼƬ�ر�Ԥ����
</td>
</tr>
<?php
$dh = dir($inpath);
$ty1="";
$ty2="";
while($file = $dh->read()) {

	//-----�����ļ���С�ʹ���ʱ��
	if($file!="." && $file!=".." && !is_dir("$inpath/$file")){
		$filesize = filesize("$inpath/$file");
		$filesize=$filesize/1024;
		if($filesize!="")
		if($filesize<0.1){
			@list($ty1,$ty2)=split("\.",$filesize);
			$filesize=$ty1.".".substr($ty2,0,2);
		}
		else{
			@list($ty1,$ty2)=split("\.",$filesize);
			$filesize=$ty1.".".substr($ty2,0,1);
		}
		$filetime = filemtime("$inpath/$file");
		$filetime = MyDate("Y-m-d H:i:s",$filetime);
	}

	if($file == ".") continue;
	else if($file == ".."){
		if($activepath == "") continue;
		$tmp = eregi_replace("[/][^/]*$","",$activepath);
		$line = "\n<tr>
   <td class='linerow' colspan='2'>
   <a href='select_images.php?imgstick=$imgstick&v=$v&f=$f&activepath=".urlencode($tmp)."'><img src=img/dir2.gif border=0 width=16 height=16 align=absmiddle>�ϼ�Ŀ¼</a></td>
   <td colspan='2' class='linerow'> ��ǰĿ¼:$activepath</td>
   </tr>
   ";
		echo $line;
	}
	else if(is_dir("$inpath/$file")){
		if(eregi("^_(.*)$",$file)) continue; #����FrontPage��չĿ¼��linux����Ŀ¼
		if(eregi("^\.(.*)$",$file)) continue;
		$line = "\n<tr>
   <td bgcolor='#F9FBF0' class='linerow' colspan='2'>
   <a href='select_images.php?imgstick=$imgstick&v=$v&f=$f&activepath=".urlencode("$activepath/$file")."'><img src=img/dir.gif border=0 width=16 height=16 align=absmiddle>$file</a></td>
   <td class='linerow'>��</td>
   <td bgcolor='#F9FBF0' class='linerow'>��</td>
   </tr>";
		echo "$line";
	}
	else if(eregi("\.(gif|png)",$file)){

		$reurl = "$activeurl/$file";
		$reurl = ereg_replace("^\.\.","",$reurl);
		if($cfg_remote_site=='Y' && $remoteuploads == 1)
	 	{
	 	  $reurl  = $remoteupUrl.$reurl;
		}else{
			$reurl = $reurl;
		}

		if($file==$comeback) $lstyle = " style='color:red' ";
		else  $lstyle = "";

		$line = "\n<tr>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>
   <a href=\"#\" onClick=\"ChangeImage('$reurl');\"><img src='img/picviewnone.gif' width='16' height='16' border='0' align=absmiddle></a>
   </td>
   <td class='linerow' bgcolor='#F9FBF0'>
   <a href=# onclick=\"ReturnImg('$reurl');\" $lstyle><img src=img/gif.gif border=0 width=16 height=16 align=absmiddle>$file</a></td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
		echo "$line";
	}
	else if(eregi("\.(jpg)",$file)){

		$reurl = "$activeurl/$file";
		$reurl = ereg_replace("^\.\.","",$reurl);
		if($cfg_remote_site=='Y' && $remoteuploads == 1)
	 	{
	 	  $reurl  = $remoteupUrl.$reurl;
		}else{
			$reurl = $reurl;
		}

		if($file==$comeback) $lstyle = " style='color:red' ";
		else  $lstyle = "";

		$line = "\n<tr>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>
   <a href=\"#\" onClick=\"ChangeImage('$reurl');\"><img src='img/picviewnone.gif' width='16' height='16' border='0' align=absmiddle></a>
   </td>
   <td class='linerow' bgcolor='#F9FBF0'>
   <a href=# onclick=\"ReturnImg('$reurl');\" $lstyle><img src=img/jpg.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
		echo "$line";
	}
}//End Loop
$dh->close();
?>
<tr>
<td colspan='4' bgcolor='#E8F1DE'>

<table width='100%'>
<form action='select_images_post.php' method='POST' enctype="multipart/form-data" name='myform'>
<input type='hidden' name='activepath' value='<?php echo $activepath?>'>
<input type='hidden' name='f' value='<?php echo $f?>'>
<input type='hidden' name='v' value='<?php echo $v?>'>
<input type='hidden' name='imgstick' value='<?php echo $imgstick?>'>
<input type='hidden' name='job' value='upload'>
<tr>
<td background="img/tbg.gif" bgcolor="#99CC00">
  &nbsp;�ϡ����� <input type='file' name='imgfile' style='width:250px'/>
  <input type='checkbox' name='needwatermark' value='1' class='np' <?php if($photo_markup=='1') echo "checked"; ?> />ˮӡ
  <input type='checkbox' name='resize' value='1' class='np' />��С
  ��<input type='text' style='width:30' name='iwidth' value='<?php echo $cfg_ddimg_width?>' />
  �ߣ�<input type='text' style='width:30' name='iheight' value='<?php echo $cfg_ddimg_height?>' />
  <input type='submit' name='sb1' value='ȷ��' />
</td>
</tr>
</form>
</table>

</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
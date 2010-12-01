<?php
require_once(dirname(__FILE__).'/../../../dialog/config.php');
require_once(dirname(__FILE__).'/../../../image.func.php');

if(empty($dopost)) $dopost = '';
if(empty($imgwidthValue)) $imgwidthValue = 400;
if(empty($imgheightValue)) $imgheightValue = 300;
if(empty($urlValue)) $urlValue = '';
if(empty($imgsrcValue)) $imgsrcValue = '';
if(empty($imgurl)) $imgurl = '';
if(empty($dd)) $dd = '';
$imgHtml = '';

if($dopost=='upload')
{
	$imgHtml = '';
	$oknum = 0;
	$alttitle = empty($alttitle) ? 0 : 1;
	for($i=1; $i <= $totalform; $i++)
	{
		$imgfile = ${'imgfile'.$i};
		$altname = empty(${'alt'.$i}) ? '' : ${'alt'.$i};
		$altname = ereg_replace("[\"']", '', $altname);
		if(!is_uploaded_file($imgfile))
		{
			continue;
		}
		else
		{
			$imgfile_name = ${'imgfile'.$i.'_name'};
			$imgfile_type = ${'imgfile'.$i.'_type'};
		}
	
		if(!eregi("\.(jpg|gif|png|bmp|pjpeg|jpeg|wbmp)$",$imgfile_name)) {
			continue;
		}
		
		$sparr = Array('image/pjpeg','image/jpeg','image/gif','image/png','image/xpng','image/wbmp');
		$imgfile_type = strtolower(trim($imgfile_type));
		if(!in_array($imgfile_type,$sparr)) {
			continue;
		}
	
		$nowtme = time();
		$y = MyDate($cfg_addon_savetype, $nowtme);

		$filename = $cuserLogin->getUserID().'_'.MyDate('ymdHis',$nowtme).'_'.$i;
		
		if(!is_dir($cfg_basedir.$cfg_image_dir."/$y"))
		{
			MkdirAll($cfg_basedir.$cfg_image_dir."/$y", $cfg_dir_purview);
			CloseFtp();
		}
	
		$fs = explode('.', $imgfile_name);
		if(eregi("php|asp|pl|shtml|jsp|cgi", $fs[count($fs)-1])) {
			continue;
		}
	
		$bfilename = $cfg_image_dir."/$y/".$filename.".".$fs[count($fs)-1];
		$litfilename = $cfg_image_dir."/$y/".$filename."_lit.".$fs[count($fs)-1];
		$dbbigfile = $filename.".".$fs[count($fs)-1];
		$dblitfile = $filename."_lit.".$fs[count($fs)-1];
		$fullfilename = $cfg_basedir.$bfilename;
		$full_litfilename = $cfg_basedir.$litfilename;
	
		if(file_exists($fullfilename)) {
			ShowMsg("��Ŀ¼�Ѿ�����ͬ�����ļ�������ģ�","-1");
			exit();
		}
	
		@move_uploaded_file($imgfile,$fullfilename);
		if($cfg_remote_site=='Y' && $remoteuploads == 1)
		{
  		//����Զ���ļ�·��
  		$remotefile = str_replace(DEDEROOT, '', $fullfilename);
      $localfile = '../../../..'.$remotefile;
      //����Զ���ļ���
      $remotedir = preg_replace('/[^\/]*\.(jpg|gif|bmp|png)/', '', $remotefile);
			$ftp->rmkdir($remotedir);
			$ftp->upload($localfile, $remotefile);
		}

		if($dd=='yes')
		{
			copy($fullfilename,$full_litfilename);
			if(in_array($imgfile_type,$cfg_photo_typenames))
			{
				if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($full_litfilename,$w,$h);
				else @ImageResize($full_litfilename,$w,$h);
			}
			$urlValue = $bfilename;
			$imgsrcValue = $litfilename;
      
			if($cfg_remote_site=='Y' && $remoteuploads == 1)
			{
	  		//����Զ���ļ�·��
	  		$remotefile = str_replace(DEDEROOT, '', $imgsrcValue);
	      $localfile = '../../../..'.$remotefile;
	      //����Զ���ļ���
	      $remotedir = preg_replace('/[^\/]*\.(jpg|gif|bmp|png)/', '', $remotefile);
				$ftp->rmkdir($remotedir);
				$ftp->upload($localfile, $remotefile);
			}
		
			$info = '';
			$sizes = getimagesize($full_litfilename,$info);
			$imgwidthValue = $sizes[0];
			$imgheightValue = $sizes[1];
			$imgsize = filesize($full_litfilename);
			$inquery = "INSERT INTO `#@__uploads`(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
       VALUES ('Сͼ{$dblitfile}','$imgsrcValue','1','$imgwidthValue','$imgheightValue','0','{$imgsize}','{$nowtme}','".$cuserLogin->getUserID()."');
     	";
			$dsql->ExecuteNoneQuery($inquery);
			$fid = $dsql->GetLastID();
		 	AddMyAddon($fid, $imgsrcValue);
		}
		else
		{
			$imgsrcValue = $bfilename;
			$urlValue = $bfilename;
			$info = '';
			$sizes = getimagesize($fullfilename,$info);
			$imgwidthValue = $sizes[0];
			$imgheightValue = $sizes[1];
			$imgsize = filesize($fullfilename);
		}
	
		$bsizes = getimagesize($fullfilename,$info);
		$bimgwidthValue = $bsizes[0];
		$bimgheightValue = $bsizes[1];
		$bimgsize = filesize($fullfilename);
	
		$inquery = "INSERT INTO `#@__uploads`(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
    	VALUES ('{$dbbigfile}','$bfilename','1','$bimgwidthValue','$bimgheightValue','0','{$bimgsize}','{$nowtme}','".$cuserLogin->getUserID()."');
  	";
		$dsql->ExecuteNoneQuery($inquery);
		$fid = $dsql->GetLastID();
		AddMyAddon($fid, $bfilename);

		if(in_array($imgfile_type,$cfg_photo_typenames))
		{
			WaterImg($fullfilename,'up');
		}
		
		$oknum++;
		
		
	 if($cfg_remote_site=='Y' && $remoteuploads == 1)
	 {
	 	  $imgsrcValue = $remoteupUrl.$imgsrcValue;
	 	  $urlValue = $remoteupUrl.$urlValue;
	 	  $imgHtml .=  "<img src=\"$imgsrcValue\" width=\"$imgwidthValue\" border=\"0\" height=\"$imgheightValue\" alt=\"$altname\" style=\"cursor:pointer\" onclick=\"window.open('$urlValue')\" /><br />\r\n";
	 } else {
			if($cfg_multi_site=='N')
			{
				$imgHtml .=  "<img src=\"$imgsrcValue\" width=\"$imgwidthValue\" border=\"0\" height=\"$imgheightValue\" alt=\"$altname\" style=\"cursor:pointer\" onclick=\"window.open('$urlValue')\" /><br />\r\n";
			}
			else
			{
				if(empty($cfg_basehost)) $cfg_basehost = 'http://'.$_SERVER["HTTP_HOST"];
				$imgHtml .=  "<img src=\"$imgsrcValue\" width=\"$imgwidthValue\" border=\"0\" height=\"$imgheightValue\" alt=\"$altname\" style=\"cursor:pointer\" onclick=\"window.open('$urlValue')\" /><br />\r\n";
			}
	  }
		
		if($alttitle==1 && !empty($altname)) {
			$imgHtml .= "$altname<br />\r\n";
		}
		
	}
}
?>
<HTML>
<HEAD>
<title>����ͼƬ</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style>
td { font-size:12px; }
input { font-size:12px; }
.spdiv { height:3px; margin-top:3px; border-top:1px dashed #333; font-size:1px; }
</style>
<script language=javascript>
var oEditor	= window.parent.InnerDialogLoaded() ;
var oDOM		= oEditor.FCK.EditorDocument ;
var FCK = oEditor.FCK;
var picnum = 1;

function ImageOK()
{
	var inImg,ialign,iurl,imgwidth,imgheight,ialt,isrc,iborder;
	ialign = document.form1.ialign.value;
	iborder = document.form1.border.value;
	imgwidth = document.form1.imgwidth.value;
	imgheight = document.form1.imgheight.value;
	ialt = document.form1.alt.value;
	
	<?php
	if($cfg_remote_site=='Y' && $remoteuploads == 1)
	{
		//�������Զ�̲���������Զ�̸���,���Զ�̵�ַ���и���
		echo "var remotehost = '$remoteupUrl';\r\n";
	?>
		if(document.form1.imgsrc.value.indexOf('ttp:') <= 0)
		{
			isrc = remotehost + document.form1.imgsrc.value;
		}
		else
		{
			isrc = document.form1.imgsrc.value;
		}
		if(document.form1.imgsrc.value.indexOf('ttp:') <= 0 && document.form1.imgsrc.value != '') {
			iurl = remotehost + document.form1.url.value;
		}
		else
		{
			iurl = document.form1.url.value;
		}
	<?php	
	} else {
		if($cfg_multi_site=='N')
		{
		?>
			isrc = document.form1.imgsrc.value;
			iurl = document.form1.url.value;
		<?php
	  }
	  else
	  {
		  echo "var basehost = '$cfg_basehost';\r\n";
			?>
			if(document.form1.imgsrc.value.indexOf('ttp:') <= 0)
			{
				isrc = basehost + document.form1.imgsrc.value;
			}
			else
			{
				isrc = document.form1.imgsrc.value;
			}
			if(document.form1.imgsrc.value.indexOf('ttp:') <= 0 && document.form1.imgsrc.value != '') {
				iurl = basehost + document.form1.url.value;
			}
			else
			{
				iurl = document.form1.url.value;
			}
		<?php
		}
	}
	?>
	
	if(ialign!=0) ialign = " align=\""+ialign+"\"";
	inImg  = "<img src=\""+ isrc +"\" width=\""+ imgwidth;
	inImg += "\" height=\""+ imgheight +"\" border=\""+ iborder +"\" alt=\""+ ialt +"\""+ialign+" />";
	if(iurl!="") inImg = "<a href=\""+ iurl +"\" target=\"_blank\">"+ inImg +"</a>\r\n";
	//FCK.InsertHtml(inImg);
	var newCode = FCK.CreateElement('DIV');
  newCode.innerHTML = inImg;
  window.parent.Cancel();
}

function ImageOK2()
{
	var iimghtml = document.form1.imghtml.value;
	//FCK.InsertHtml(iimghtml);
	var newCode = FCK.CreateElement('DIV');
  newCode.innerHTML = iimghtml;
  window.parent.Cancel();
}

function SelectMedia(fname)
{
   if(document.all){
     var posLeft = window.event.clientY-100;
     var posTop = window.event.clientX-400;
   }
   else{
     var posLeft = 100;
     var posTop = 100;
   }
   window.open("../../../dialog/select_images.php?f="+fname+"&imgstick=big", "popUpImgWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=400,left="+posLeft+", top="+posTop);
}
function SeePic(imgid,fobj)
{
   if(!fobj) return;
   if(fobj.value != "" && fobj.value != null)
   {
     var cimg = document.getElementById(imgid);
     if(cimg) cimg.src = fobj.value;
   }
}
function UpdateImageInfo()
{
	var imgsrc = document.form1.imgsrc.value;
	if(imgsrc!="")
	{
	  var imgObj = new Image();
	  imgObj.src = imgsrc;
	  document.form1.himgheight.value = imgObj.height;
	  document.form1.himgwidth.value = imgObj.width;
	  document.form1.imgheight.value = imgObj.height;
	  document.form1.imgwidth.value = imgObj.width;
  }
}
function UpImgSizeH()
{
   var ih = document.form1.himgheight.value;
   var iw = document.form1.himgwidth.value;
   var iih = document.form1.imgheight.value;
   var iiw = document.form1.imgwidth.value;
   if(ih!=iih && iih>0 && ih>0 && document.form1.autoresize.checked)
   {
      document.form1.imgwidth.value = Math.ceil(iiw * (iih/ih));
   }
}
function UpImgSizeW()
{
   var ih = document.form1.himgheight.value;
   var iw = document.form1.himgwidth.value;
   var iih = document.form1.imgheight.value;
   var iiw = document.form1.imgwidth.value;
   if(iw!=iiw && iiw>0 && iw>0 && document.form1.autoresize.checked)
   {
      document.form1.imgheight.value = Math.ceil(iih * (iiw/iw));
   }
}

function AddForm()
{
	picnum++;
	document.getElementById('moreupload').innerHTML += "<div class='spdiv'>&nbsp;</div>ͼƬ"+picnum+"��<input name='imgfile"+picnum+"' type='file' id='imgfile"+picnum+"' class='binput' />\r\n";
	document.getElementById('moreupload').innerHTML += "<br />ALT��Ϣ��<input type='text' name='alt"+picnum+"' value='' style='width:60%' /><br />\r\n";
	document.form1.totalform.value = picnum;
}

</script>
<link href="base.css" rel="stylesheet" type="text/css" />
<base target="_self" />
</HEAD>
<body bgcolor="#EBF6CD" leftmargin="4" topmargin="2">
<form enctype="multipart/form-data" name="form1" id="form1" method="post">
<?php
//�ϴ��󷵻ص�����
if($imgHtml != '')
{
?>
<table width="100%" border="0">
	<tr>
		<td nowrap='1'>
		<fieldset>
			<legend>�ϴ���õ���ͼƬHTML��Ϣ</legend>
			<textarea name='imghtml' style='width:100%;height:250px;'><?php echo $imgHtml; ?></textarea>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td nowrap='1'>&nbsp;</td>
				</tr>
				<tr height="28">
					<td align='center'>
            <input type="button" name="imgok" id="imgok" onclick='ImageOK2()' value=" ���뵽�༭��  " style="height:24px" class="binput" />
          </td>
				</tr>
			</table>
			</fieldset>
		</td>
	</tr>
</table>			
<?php
//Ĭ����ʾ����
} else {
?>
<input type="hidden" name="dopost" value="upload" />
<input type="hidden" name="himgheight" value="<?php echo $imgheightValue?>" />
<input type="hidden" name="himgwidth" value="<?php echo $imgwidthValue?>" />
<input type="hidden" name="arctitle" id="arctitle" value="" />
<input type="hidden" name="totalform" value="1" />
<table width="100%" border="0">
	<tr>
		<td nowrap='1'>
		<fieldset>
			<legend>�ϴ���ͼƬ</legend>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr height="30">
					<td align="right" valign='top' nowrap='1'>ͼ Ƭ��</td>
					<td nowrap='1'>
						ͼƬ1��<input name="imgfile1" type="file" id="imgfile1" class="binput" />
						<br />
						ALT��Ϣ��<input type='text' name='alt1' value='' style='width:60%' />
					  <div id='moreupload'></div>
					  <div style='height:3px;margin-top:3px;font-size:1px'>&nbsp;</div>
					</td>
				</tr>
				<tr height="30">
					<td align="right" nowrap='1' style='border-top:1px dashed #333;padding-top:3px'>ѡ �</td>
					<td nowrap='1' style='border-top:1px dashed #333;padding-top:3px'>
						<input type="checkbox" name="dd" value="yes" />��������ͼ &nbsp;
						����ͼ���
						<input name="w" type="text" value="<?php echo $cfg_ddimg_width?>" size="3" />
						����ͼ�߶�
						<input name="h" type="text" value="<?php echo $cfg_ddimg_height?>" size="3" />
						<br />
						<input type='checkbox' name='alttitle' value='1' />��ͼƬ��һ�м���ALT������Ϊ˵������
					</td>
				</tr>
				<tr height="36">
					<td colspan="2">
						&nbsp;
            <input type="button" name="addupload" id="addupload" onclick='AddForm()' value=" �����ϴ���  " style="height:24px" class="binput" />
            &nbsp;
            <input type="submit" name="picSubmit" id="picSubmit" value=" �� ��  " style="height:24px" class="binput" />
            &nbsp;
            <input type='checkbox' name='needwatermark' value='1' class='np' <?php if($photo_markup=='1') echo "checked"; ?> />
            ͼƬ�Ƿ��ˮӡ
            </td>
				</tr>
			</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset>
				<legend>����ͼƬ</legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="65" height="25" align="right">��ַ��</td>
            <td colspan="2">
              <input name="imgsrc" type="text" id="imgsrc" size="30" onChange="SeePic('picview',this);" value="<?php echo $imgsrcValue?>" />
              <input onClick="SelectMedia('form1.imgsrc');" type="button" name="selimg" value=" ���������... " class="binput" style="width:100px" />
             </td>
          </tr>
          <tr>
            <td height="25" align="right">��ȣ�</td>
            <td colspan="2" nowrap>
							<input type="text"  id="imgwidth" name="imgwidth" size="8" value="<?php echo $imgwidthValue?>" onChange="UpImgSizeW()" />
              &nbsp;&nbsp;
              �߶�: <input name="imgheight" type="text" id="imgheight" size="8" value="<?php echo $imgheightValue?>" onChange="UpImgSizeH()" />
              <input type="button" name="Submit" value="ԭʼ" class="binput" style="width:40" onClick="UpdateImageInfo()" />
              <input name="autoresize" type="checkbox" id="autoresize" value="1" checked='1' />
              ����
            </td>
          </tr>
          <tr>
            <td height="25" align="right">�߿�</td>
            <td colspan="2" nowrap>
              <input name="border" type="text" id="border" size="4" value="0" />
              &nbsp;�������:
              <input name="alt" type="text" id="alt" size="10" />
            </td>
          </tr>
          <tr>
            <td height="25" align="right">���ӣ�</td>
            <td width="166" nowrap><input name="url" type="text" id="url" size="30"   value="<?php echo $urlValue?>" /></td>
            <td width="155" align="center" nowrap='1'>&nbsp;</td>
          </tr>
					<tr>
            <td height="25" align="right">
            ���룺
            </td>
            <td nowrap='1'>
            <select name="ialign" id="ialign">
                <option value="0" selected>Ĭ��</option>
                <option value="right">�Ҷ���</option>
                <option value="center">�м�</option>
                <option value="left">�����</option>
                <option value="top">����</option>
                <option value="bottom">�ײ�</option>
              </select>
            </td>
            <td align="right" nowrap='1'>
            	<input onClick="ImageOK();" type="button" name="Submit2" value=" ȷ�� " class="binput" />
            </td>
          </tr>
        </table>
      </fieldset>
		</td>
	</tr>
</table>
<?php
}
?>
</form>
</body>
</HTML>
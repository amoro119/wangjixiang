<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
include(DEDEDATA.'/mark/inc_photowatermark_config.php');
//����û�ϵͳ֧�ֵ�ͼƬ��ʽ
global $cfg_photo_type,$cfg_photo_typenames,$cfg_photo_support;
$cfg_photo_type['gif'] = false;
$cfg_photo_type['jpeg'] = false;
$cfg_photo_type['png'] = false;
$cfg_photo_type['wbmp'] = false;
$cfg_photo_typenames = Array();
$cfg_photo_support = '';
if(function_exists("imagecreatefromgif") && function_exists("imagegif"))
{
	$cfg_photo_type["gif"] = true;
	$cfg_photo_typenames[] = "image/gif";
	$cfg_photo_support .= "GIF ";
}
if(function_exists("imagecreatefromjpeg") && function_exists("imagejpeg"))
{
	$cfg_photo_type["jpeg"] = true;
	$cfg_photo_typenames[] = "image/pjpeg";
	$cfg_photo_typenames[] = "image/jpeg";
	$cfg_photo_support .= "JPEG ";
}
if(function_exists("imagecreatefrompng") && function_exists("imagepng"))
{
	$cfg_photo_type["png"] = true;
	$cfg_photo_typenames[] = "image/png";
	$cfg_photo_typenames[] = "image/xpng";
	$cfg_photo_support .= "PNG ";
}
if(function_exists("imagecreatefromwbmp") && function_exists("imagewbmp"))
{
	$cfg_photo_type["wbmp"] = true;
	$cfg_photo_typenames[] = "image/wbmp";
	$cfg_photo_support .= "WBMP ";
}

//��ͼƬ�Զ����ɺ�������Դ֧��bmp��gif��jpg��png
//�����ɵ�Сͼֻ��jpg��png��ʽ
function ImageResize($srcFile,$toW,$toH,$toFile="")
{
	global $cfg_photo_type;
	if($toFile=='') $toFile = $srcFile;
	$info = '';
	$srcInfo = GetImageSize($srcFile,$info);
	switch ($srcInfo[2])
	{
		case 1:
			if(!$cfg_photo_type['gif']) return false;
			$im = imagecreatefromgif($srcFile);
			break;
		case 2:
			if(!$cfg_photo_type['jpeg']) return false;
			$im = imagecreatefromjpeg($srcFile);
			break;
		case 3:
			if(!$cfg_photo_type['png']) return false;
			$im = imagecreatefrompng($srcFile);
			break;
		case 6:
			if(!$cfg_photo_type['bmp']) return false;
			$im = imagecreatefromwbmp($srcFile);
			break;
	}
	$srcW=ImageSX($im);
	$srcH=ImageSY($im);
	if($srcW<=$toW && $srcH<=$toH ) return true;
	$toWH=$toW/$toH;
	$srcWH=$srcW/$srcH;
	if($toWH<=$srcWH)
	{
		$ftoW=$toW;
		$ftoH=$ftoW*($srcH/$srcW);
	}
	else
	{
		$ftoH=$toH;
		$ftoW=$ftoH*($srcW/$srcH);
	}
	if($srcW>$toW||$srcH>$toH)
	{
		if(function_exists("imagecreatetruecolor"))
		{
			@$ni = imagecreatetruecolor($ftoW,$ftoH);
			if($ni)
			{
				imagecopyresampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
			else
			{
				$ni=imagecreate($ftoW,$ftoH);
				imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
		}
		else
		{
			$ni=imagecreate($ftoW,$ftoH);
			imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
		}
		switch ($srcInfo[2])
		{
			case 1:
				imagegif($ni,$toFile);
				break;
			case 2:
				imagejpeg($ni,$toFile,85);
				break;
			case 3:
				imagepng($ni,$toFile);
				break;
			case 6:
				imagebmp($ni,$toFile);
				break;
			default:
				return false;
		}
		imagedestroy($ni);
	}
	imagedestroy($im);
	return true;
}

//���GD�İ汾
function gdversion()
{
	//û����php.ini����������������GDĬ������2.0���ϰ汾
	if(!function_exists('phpinfo'))
	{
		if(function_exists('imagecreate'))
		{
			return '2.0';
		}
		else
		{
			return 0;
		}
	}
	else
	{
		ob_start();
		phpinfo(8);
		$module_info = ob_get_contents();
		ob_end_clean();
		if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches))
		{
			$gdversion_h = $matches[1];
		}
		else
		{
			$gdversion_h = 0;
		}
		return $gdversion_h;
	}
}

//ͼƬ�Զ���ˮӡ����
function WaterImg($srcFile,$fromGo='up')
{
	include(DEDEDATA.'/mark/inc_photowatermark_config.php');
	require_once(DEDEINC.'/image.class.php');
	if( isset($GLOBALS['needwatermark']) )
	{
		$photo_markup = $photo_markdown = empty($GLOBALS['needwatermark']) ? '0' : '1';
	}
	if($photo_markup != '1' || ($fromGo=='collect' && $photo_markdown!='1') )
	{
		return;
	}
	$info = '';
	$srcInfo = @getimagesize($srcFile,$info);
	$srcFile_w    = $srcInfo[0];
	$srcFile_h    = $srcInfo[1];
		
	if($srcFile_w < $photo_wwidth || $srcFile_h < $photo_wheight)
	{
		return;
	}
	if($fromGo=='up' && $photo_markup=='0')
	{
		return;
	}
	if($fromGo=='down' && $photo_markdown=='0')
	{
		return;
	}
 	$trueMarkimg = DEDEDATA.'/mark/'.$photo_markimg;
	if(!file_exists($trueMarkimg) || empty($photo_markimg))
	{
		$trueMarkimg = "";
	}
	if($photo_waterpos == 0)
	{
		$photo_waterpos = rand(1, 9);
	}
	$cfg_watermarktext = array();
	if($photo_marktype == '2')
	{
		if(file_exists(DEDEDATA.'/mark/simhei.ttf'))
		{
			$cfg_watermarktext['fontpath'] =  DEDEDATA.'/mark/simhei.ttf';
		}
		else
		{
			return ;
		}
	}
	$cfg_watermarktext['text'] = $photo_watertext;
	$cfg_watermarktext['size'] = $photo_fontsize;
	$cfg_watermarktext['angle'] = '0';
	$cfg_watermarktext['color'] = '255,255,255';
	$cfg_watermarktext['shadowx'] = '0';
	$cfg_watermarktext['shadowy'] = '0';
	$cfg_watermarktext['shadowcolor'] = '0,0,0';
	$photo_marktrans = 85;
	$img = new image($srcFile,0, $cfg_watermarktext, $photo_waterpos, $photo_diaphaneity, $photo_wheight, $photo_wwidth, $photo_marktype, $photo_marktrans,$trueMarkimg);
	$img->watermark(0);
}

//��Կհ׵ط������
function ImageResizeNew($srcFile, $toW, $toH, $toFile='', $issave=true)
{
	global $cfg_photo_type, $cfg_ddimg_bgcolor;
	if($toFile=='') $toFile = $srcFile;
	$info = '';
	$srcInfo = GetImageSize($srcFile,$info);
	switch ($srcInfo[2])
	{
		case 1:
			if(!$cfg_photo_type['gif']) return false;
			$img = imagecreatefromgif($srcFile);
			break;
		case 2:
			if(!$cfg_photo_type['jpeg']) return false;
			$img = imagecreatefromjpeg($srcFile);
			break;
		case 3:
			if(!$cfg_photo_type['png']) return false;
			$img = imagecreatefrompng($srcFile);
			break;
		case 6:
			if(!$cfg_photo_type['bmp']) return false;
			$img = imagecreatefromwbmp($srcFile);
			break;
	}

	$width = imageSX($img);
	$height = imageSY($img);

	if (!$width || !$height) {
		return false;
	}

	$target_width = $toW;
	$target_height = $toH;
	$target_ratio = $target_width / $target_height;

	$img_ratio = $width / $height;

	if ($target_ratio > $img_ratio) {
		$new_height = $target_height;
		$new_width = $img_ratio * $target_height;
	} else {
		$new_height = $target_width / $img_ratio;
		$new_width = $target_width;
	}

	if ($new_height > $target_height) {
		$new_height = $target_height;
	}
	if ($new_width > $target_width) {
		$new_height = $target_width;
	}

	$new_img = ImageCreateTrueColor($target_width, $target_height);
	
	if($cfg_ddimg_bgcolor==0) $bgcolor = ImageColorAllocate($new_img, 0xff, 0xff, 0xff);
	else $bgcolor = 0;
	
	if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, $bgcolor))
	{
		return false;
	}

	if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height))
	{
		return false;
	}
	
	//����ΪĿ���ļ�
	if($issave)
	{
		switch ($srcInfo[2])
		{
			case 1:
				imagegif($new_img, $toFile);
				break;
			case 2:
				imagejpeg($new_img, $toFile,100);
				break;
			case 3:
				imagepng($new_img, $toFile);
				break;
			case 6:
				imagebmp($new_img, $toFile);
				break;
			default:
				return false;
		}
	}
	//������
	else
	{
		switch ($srcInfo[2])
		{
			case 1:
				imagegif($new_img);
				break;
			case 2:
				imagejpeg($new_img);
				break;
			case 3:
				imagepng($new_img);
				break;
			case 6:
				imagebmp($new_img);
				break;
			default:
				return false;
		}
	}
	imagedestroy($new_img);
	imagedestroy($img);
	return true;
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
require_once(DEDEINC."/image.func.php");
if($cfg_photo_support=='')
{
	echo "���ϵͳû��װGD�⣬������ʹ�ñ����ܣ�";
	exit();
}
$ImageWaterConfigFile = DEDEDATA."/mark/inc_photowatermark_config.php";
if(empty($action))
{
	$action = "";
}
if($action=="save")
{
	$vars = array('photo_markup','photo_markdown','photo_marktype','photo_wwidth','photo_wheight','photo_waterpos','photo_watertext','photo_fontsize','photo_fontcolor','photo_marktrans','photo_diaphaneity');
	$configstr = $shortname = "";
	foreach($vars as $v)
	{
		${$v} = str_replace("'", "", ${'get_'.$v});
		$configstr .= "\${$v} = '".${$v}."';\r\n";
	}
	if(is_uploaded_file($newimg))
	{
		$imgfile_type = strtolower(trim($newimg_type));
		if(!in_array($imgfile_type,$cfg_photo_typenames))
		{
			ShowMsg("�ϴ���ͼƬ��ʽ������ʹ�� {$cfg_photo_support}��ʽ������һ�֣�","-1");
			exit();
		}
		if($imgfile_type=='image/xpng')
		{
			$shortname = ".png";
		}
		else if($imgfile_type=='image/gif')
		{
			$shortname = ".gif";
		}
		else
		{
			$shortname = ".gif";
		}
		$photo_markimg = 'mark'.$shortname;
		@move_uploaded_file($newimg,DEDEDATA."/mark/".$photo_markimg);
	}
	$configstr .= "\$photo_markimg = '{$photo_markimg}';\r\n";
	$configstr = "<"."?php\r\n".$configstr."?".">\r\n";
	$fp = fopen($ImageWaterConfigFile,"w") or die("д���ļ� $ImageWaterConfigFile ʧ�ܣ�����Ȩ�ޣ�");
	fwrite($fp,$configstr);
	fclose($fp);
	echo "<script>alert('�޸����óɹ���');</script>\r\n";
}
require_once($ImageWaterConfigFile);
include DedeInclude('templets/sys_info_mark.htm');

?>
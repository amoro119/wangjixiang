<?php
require_once(DEDEINC.'/dedehttpdown.class.php');
require_once(DEDEINC.'/image.func.php');
require_once(DEDEINC.'/archives.func.php');
require_once(DEDEINC.'/arc.partview.class.php');
$backurl = !empty($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : '';
$backurl = ereg('content_', $backurl) ? "<a href='$backurl'>[<u>������б�ҳ</u>]</a> &nbsp;" : '';
if(!isset($_NOT_ARCHIVES))
{
	require_once(DEDEINC.'/customfields.func.php');
}
//���HTML����ⲿ��Դ�����ͼ��
function GetCurContentAlbum($body, $rfurl, &$firstdd)
{
	global $dsql,$cfg_multi_site,$cfg_basehost,$cfg_ddimg_width;
	global $cfg_basedir,$pagestyle,$cuserLogin,$cfg_addon_savetype;
	require_once(DEDEINC.'/dedecollection.func.php');
	if(empty($cfg_ddimg_width))	$cfg_ddimg_width = 320;
	$rsimg = '';
	$cfg_uploaddir = $GLOBALS['cfg_image_dir'];
	$cfg_basedir = $GLOBALS['cfg_basedir'];
	$basehost = 'http://'.$_SERVER['HTTP_HOST'];
	$img_array = array();
	preg_match_all("/(src)=[\"|'| ]{0,}(http:\/\/([^>]*)\.(gif|jpg|png))/isU",$body,$img_array);
	$img_array = array_unique($img_array[2]);
	$imgUrl = $cfg_uploaddir.'/'.MyDate($cfg_addon_savetype, time());
	$imgPath = $cfg_basedir.$imgUrl;
	if(!is_dir($imgPath.'/'))
	{
		MkdirAll($imgPath,$GLOBALS['cfg_dir_purview']);
		CloseFtp();
	}
	$milliSecond = 'co'.dd2char( MyDate('ymdHis',time())) ;
	foreach($img_array as $key=>$value)
	{
		$value = trim($value);
		if(eregi($basehost,$value) || !eregi("^http://", $value) 
		|| ($cfg_basehost != $basehost && eregi($cfg_basehost,$value)))
		{
			continue;
		}
		$itype =  substr($value, -4, 4);
		if( !eregi("\.(gif|jpg|png)",$itype) )
		{
			$itype = ".jpg";
		}
		$rndFileName = $imgPath.'/'.$milliSecond.'-'.$key.$itype;
		$iurl = $imgUrl.'/'.$milliSecond.'-'.$key.$itype;
		//���ز������ļ�
		$rs = DownImageKeep($value, $rfurl, $rndFileName, '', 0, 30);
		if($rs)
		{
			$info = '';
			$imginfos = GetImageSize($rndFileName, $info);
			$fsize = filesize($rndFileName);
			$filename = $milliSecond.'-'.$key.$itype;
			//����ͼƬ������Ϣ
			$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
			VALUES ('0','$filename','$iurl','1','{$imginfos[0]}','$imginfos[1]','0','$fsize','".time()."','".$cuserLogin->getUserID()."'); ";
			$dsql->ExecuteNoneQuery($inquery);
			$fid = $dsql->GetLastID();
			AddMyAddon($fid, $iurl);
			if($pagestyle > 2)
			{
				$litpicname = GetImageMapDD($iurl, $cfg_ddimg_width);
			}
			else
			{
				$litpicname = $iurl;
			}
			if(empty($firstdd) && !empty($litpicname))
			{
				$firstdd = $litpicname;
				if(!file_exists($cfg_basedir.$firstdd))
				{
					$firstdd = $iurl;
				}
			}
			@WaterImg($rndFileName, 'down');
			$rsimg .= "{dede:img ddimg='$litpicname' text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
		}
	}
	return $rsimg;
}

//�������body����ⲿ��Դ
function GetCurContent($body)
{
	global $cfg_multi_site,$cfg_basehost,$cfg_basedir,$cfg_image_dir;
	$cfg_uploaddir = $cfg_image_dir;
	$htd = new DedeHttpDown();
	$basehost = "http://".$_SERVER["HTTP_HOST"];
	$img_array = array();
	preg_match_all("/src=[\"|'|\s]{0,}(http:\/\/([^>]*)\.(gif|jpg|png))/isU",$body,$img_array);
	$img_array = array_unique($img_array[1]);
	$imgUrl = $cfg_uploaddir.'/'.MyDate("ymd",time());
	$imgPath = $cfg_basedir.$imgUrl;
	if(!is_dir($imgPath.'/'))
	{
		MkdirAll($imgPath,$GLOBALS['cfg_dir_purview']);
		CloseFtp();
	}
	$milliSecond = MyDate('His',time());
	foreach($img_array as $key=>$value)
	{
		if(eregi($basehost,$value))
		{
			continue;
		}
		if($cfg_basehost!=$basehost && eregi($cfg_basehost,$value))
		{
			continue;
		}
		if(!eregi("^http://",$value))
		{
			continue;
		}
		$htd->OpenUrl($value);
		$itype = $htd->GetHead("content-type");
		$itype = substr($value,-4,4);
		if(!eregi("\.(jpg|gif|png)",$itype))
		{
			if($itype=='image/gif')
			{
				$itype = ".gif";
			}
			else if($itype=='image/png')
			{
				$itype = ".png";
			}
			else
			{
				$itype = '.jpg';
			}
		}
		$milliSecondN = dd2char($milliSecond.mt_rand(1000,8000));
		$value = trim($value);
		$rndFileName = $imgPath.'/'.$milliSecondN.'-'.$key.$itype;
		$fileurl = $imgUrl.'/'.$milliSecondN.'-'.$key.$itype;
		$rs = $htd->SaveToBin($rndFileName);
		if($rs)
		{
			if($cfg_multi_site == 'Y')
			{
				$fileurl = $cfg_basehost.$fileurl;
			}
			$body = str_replace($value,$fileurl,$body);
			@WaterImg($rndFileName, 'down');
		}
	}
	$htd->Close();
	return $body;
}

//��ȡһ��Զ��ͼƬ
function GetRemoteImage($url,$uid=0)
{
	global $cfg_basedir, $cfg_image_dir, $cfg_addon_savetype;
	$cfg_uploaddir = $cfg_image_dir;
	$revalues = Array();
	$ok = false;
	$htd = new DedeHttpDown();
	$htd->OpenUrl($url);
	$sparr = Array("image/pjpeg", "image/jpeg", "image/gif", "image/png", "image/xpng", "image/wbmp");
	if(!in_array($htd->GetHead("content-type"),$sparr))
	{
		return '';
	}
	else
	{
		$imgUrl = $cfg_uploaddir.'/'.MyDate($cfg_addon_savetype, time());
		$imgPath = $cfg_basedir.$imgUrl;
		CreateDir($imgUrl);
		$itype = $htd->GetHead("content-type");
		if($itype=="image/gif")
		{
			$itype = '.gif';
		}
		else if($itype=="image/png")
		{
			$itype = '.png';
		}
		else if($itype=="image/wbmp")
		{
			$itype = '.bmp';
		}
		else
		{
			$itype = '.jpg';
		}
		$rndname = dd2char($uid.'_'.MyDate('mdHis',time()).mt_rand(1000,9999));
		$rndtrueName = $imgPath.'/'.$rndname.$itype;
		$fileurl = $imgUrl.'/'.$rndname.$itype;
		$ok = $htd->SaveToBin($rndtrueName);
		@WaterImg($rndtrueName, 'down');
		if($ok)
		{
			$data = GetImageSize($rndtrueName);
			$revalues[0] = $fileurl;
			$revalues[1] = $data[0];
			$revalues[2] = $data[1];
		}
	}
	$htd->Close();
	return ($ok ? $revalues : '');
}

//��ȡһ��Զ��Flash�ļ�
function GetRemoteFlash($url,$uid=0)
{
	global $cfg_addon_savetype, $cfg_media_dir, $cfg_basedir;
	$cfg_uploaddir = $cfg_media_dir;
	$revalues = '';
	$sparr = 'application/x-shockwave-flash';
	$htd = new DedeHttpDown();
	$htd->OpenUrl($url);
	if($htd->GetHead("content-type")!=$sparr)
	{
		return '';
	}
	else
	{
		$imgUrl = $cfg_uploaddir.'/'.MyDate($cfg_addon_savetype, time());
		$imgPath = $cfg_basedir.$imgUrl;
		CreateDir($imgUrl);
		$itype = '.swf';
		$milliSecond = $uid.'_'.MyDate('mdHis',time());
		$rndFileName = $imgPath.'/'.$milliSecond.$itype;
		$fileurl = $imgUrl.'/'.$milliSecond.$itype;
		$ok = $htd->SaveToBin($rndFileName);
		if($ok)
		{
			$revalues = $fileurl;
		}
	}
	$htd->Close();
	return $revalues;
}

//���Ƶ��ID
function CheckChannel($typeid,$channelid)
{
	global $dsql;
	if($typeid==0)
	{
		return true;
	}
	$row = $dsql->GetOne("Select ispart,channeltype From `#@__arctype` where id='$typeid' ");
	if($row['ispart']!=0 || $row['channeltype']!=$channelid)
	{
		return false;
	}
	else
	{
		return true;
	}
}

//��⵵��Ȩ��
function CheckArcAdmin($aid,$adminid)
{
	global $dsql;
	$row = $dsql->GetOne("Select mid From `#@__archives` where id='$aid' ");
	if($row['mid']!=$adminid)
	{
		return false;
	}
	else
	{
		return true;
	}
}

//�ĵ��Զ���ҳ
function SpLongBody($mybody,$spsize,$sptag)
{
	if(strlen($mybody)<$spsize)
	{
		return $mybody;
	}
	$mybody = stripslashes($mybody);
	$bds = explode('<',$mybody);
	$npageBody = '';
	$istable = 0;
	$mybody = '';
	foreach($bds as $i=>$k)
	{
		if($i==0)
		{
			$npageBody .= $bds[$i]; continue;
		}
		$bds[$i] = "<".$bds[$i];
		if(strlen($bds[$i])>6)
		{
			$tname = substr($bds[$i],1,5);
			if(strtolower($tname)=='table')
			{
				$istable++;
			}
			else if(strtolower($tname)=='/tabl')
			{
				$istable--;
			}
			if($istable>0)
			{
				$npageBody .= $bds[$i]; continue;
			}
			else
			{
				$npageBody .= $bds[$i];
			}
		}
		else
		{
			$npageBody .= $bds[$i];
		}
		if(strlen($npageBody)>$spsize)
		{
			$mybody .= $npageBody.$sptag;
			$npageBody = '';
		}
	}
	if($npageBody!='')
	{
		$mybody .= $npageBody;
	}
	return addslashes($mybody);
}

//����ָ��ID���ĵ�
function MakeArt($aid, $mkindex=false, $ismakesign=false,$isremote=0)
{
	global $envs, $typeid;
	require_once(DEDEINC.'/arc.archives.class.php');
	if($ismakesign) $envs['makesign'] = 'yes';
	$arc = new Archives($aid);
	$reurl = $arc->MakeHtml($isremote);
	return $reurl;
}

//ȡ��һ��ͼƬΪ����ͼ
function GetDDImgFromBody(&$body)
{
	$litpic = '';
	preg_match_all("/(src)=[\"|'| ]{0,}([^>]*\.(gif|jpg|bmp|png))/isU",$body,$img_array);
	$img_array = array_unique($img_array[2]);
	if(count($img_array)>0)
	{
		$picname = preg_replace("/[\"|'| ]{1,}/", '', $img_array[0]);
		if(ereg("_lit\.",$picname))
		{
			$litpic = $picname;
		}
		else
		{
			$litpic = GetDDImage('ddfirst',$picname,1);
		}
	}
	return $litpic;
}

//�������ͼ
function GetDDImage($litpic,$picname,$isremote)
{
	global $cuserLogin,$cfg_ddimg_width,$cfg_ddimg_height,$cfg_basedir,$ddcfg_image_dir,$cfg_addon_savetype;
	$ntime = time();
	if( ($litpic != 'none' || $litpic != 'ddfirst') && 
	 !empty($_FILES[$litpic]['tmp_name']) && is_uploaded_file($_FILES[$litpic]['tmp_name']))
	{
		//����û������ϴ�����ͼ
		$istype = 0;
		$sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png");
		$_FILES[$litpic]['type'] = strtolower(trim($_FILES[$litpic]['type']));
		if(!in_array($_FILES[$litpic]['type'],$sparr))
		{
			ShowMsg("�ϴ���ͼƬ��ʽ������ʹ��JPEG��GIF��PNG��ʽ������һ�֣�","-1");
			exit();
		}
		$savepath = $ddcfg_image_dir.'/'.MyDate($cfg_addon_savetype, $ntime);

		CreateDir($savepath);
		$fullUrl = $savepath.'/'.dd2char(MyDate('mdHis',$ntime).$cuserLogin->getUserID().mt_rand(1000,9999));
		if(strtolower($_FILES[$litpic]['type'])=="image/gif")
		{
			$fullUrl = $fullUrl.".gif";
		}
		else if(strtolower($_FILES[$litpic]['type'])=="image/png")
		{
			$fullUrl = $fullUrl.".png";
		}
		else
		{
			$fullUrl = $fullUrl.".jpg";
		}

		@move_uploaded_file($_FILES[$litpic]['tmp_name'],$cfg_basedir.$fullUrl);
		$litpic = $fullUrl;

		if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($cfg_basedir.$fullUrl,$cfg_ddimg_width,$cfg_ddimg_height);
		else @ImageResize($cfg_basedir.$fullUrl,$cfg_ddimg_width,$cfg_ddimg_height);
		
		$img = $cfg_basedir.$litpic;

	}
	else
	{

		$picname = trim($picname);
		if($isremote==1 && eregi("^http://",$picname))
		{
			$litpic = $picname;

			$ddinfos = GetRemoteImage($litpic,$cuserLogin->getUserID());

			if(!is_array($ddinfos))
			{
				$litpic = '';
			}
			else
			{
				$litpic = $ddinfos[0];
				if($ddinfos[1] > $cfg_ddimg_width || $ddinfos[2] > $cfg_ddimg_height)
				{
					if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($cfg_basedir.$litpic,$cfg_ddimg_width,$cfg_ddimg_height);
					else @ImageResize($cfg_basedir.$litpic,$cfg_ddimg_width,$cfg_ddimg_height);
				}
			}
		}
		else
		{
			if($litpic=='ddfirst' && !eregi("^http://",$picname))
			{
				$oldpic = $cfg_basedir.$picname;
				$litpic = str_replace('.','-lp.',$picname);
				if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($oldpic,$cfg_ddimg_width,$cfg_ddimg_height,$cfg_basedir.$litpic);
				else @ImageResize($oldpic,$cfg_ddimg_width,$cfg_ddimg_height,$cfg_basedir.$litpic);
				if(!is_file($cfg_basedir.$litpic)) $litpic = '';
			}
			else
			{
				$litpic = $picname;
				return $litpic;
			}
		}
	}
	if($litpic=='litpic' || $litpic=='ddfirst')
	{
		$litpic = '';
	}
	return $litpic;
}

//���һ�����ӱ�
function GetFormItemA($ctag)
{
	return GetFormItem($ctag,'admin');
}

//����ͬ���͵�����
function GetFieldValueA($dvalue,$dtype,$aid=0,$job='add',$addvar='')
{
	return GetFieldValue($dvalue,$dtype,$aid,$job,$addvar,'admin');
}

//��ô�ֵ�ı�(�༭ʱ��)
function GetFormItemValueA($ctag,$fvalue)
{
	return GetFormItemValue($ctag,$fvalue,'admin');
}

//�����Զ����(���ڷ���)
function PrintAutoFieldsAdd(&$fieldset,$loadtype='all')
{
	$dtp = new DedeTagParse();
	$dtp->SetNameSpace('field','<','>');
	$dtp->LoadSource($fieldset);
	$dede_addonfields = '';
	if(is_array($dtp->CTags))
	{
		foreach($dtp->CTags as $tid=>$ctag)
		{
			if($loadtype!='autofield'
			|| ($loadtype=='autofield' && $ctag->GetAtt('autofield')==1) )
			{
				$dede_addonfields .= ( $dede_addonfields=="" ? $ctag->GetName().",".$ctag->GetAtt('type') : ";".$ctag->GetName().",".$ctag->GetAtt('type') );
				echo  GetFormItemA($ctag);
			}
		}
	}
	echo "<input type='hidden' name='dede_addonfields' value=\"".$dede_addonfields."\">\r\n";
}

//�����Զ����(���ڱ༭)
function PrintAutoFieldsEdit(&$fieldset,&$fieldValues,$loadtype='all')
{
	$dtp = new DedeTagParse();
	$dtp->SetNameSpace("field","<",">");
	$dtp->LoadSource($fieldset);
	$dede_addonfields = "";
	if(is_array($dtp->CTags))
	{
		foreach($dtp->CTags as $tid=>$ctag)
		{
			if($loadtype!='autofield'
			|| ($loadtype=='autofield' && $ctag->GetAtt('autofield')==1) )
			{
				$dede_addonfields .= ( $dede_addonfields=='' ? $ctag->GetName().",".$ctag->GetAtt('type') : ";".$ctag->GetName().",".$ctag->GetAtt('type') );
				echo GetFormItemValueA($ctag,$fieldValues[$ctag->GetName()]);
			}
		}
	}
	echo "<input type='hidden' name='dede_addonfields' value=\"".$dede_addonfields."\">\r\n";
}

//����HTML�ı�
//ɾ����վ�����ӡ��Զ�ժҪ���Զ���ȡ����ͼ
function AnalyseHtmlBody($body,&$description,&$litpic,&$keywords,$dtype='')
{
	global $autolitpic,$remote,$dellink,$autokey,$cfg_basehost,$cfg_auot_description,$id,$title,$cfg_soft_lang;
	$autolitpic = (empty($autolitpic) ? '' : $autolitpic);
	$body = stripslashes($body);

	//Զ��ͼƬ���ػ�
	if($remote==1)
	{
		$body = GetCurContent($body);
	}

	//ɾ����վ������
	if($dellink==1)
	{
		$basehost = "http://".$_SERVER['HTTP_HOST'];
		$body = str_replace($cfg_basehost,'#basehost#',$body);
		$body = str_replace($basehost,'#2basehost2#',$body);
		$body = preg_replace("/(<a[ \t\r\n]{1,}href=[\"']{0,}http:\/\/[^\/]([^>]*)>)|(<\/a>)/isU","",$body);
		$body = str_replace('#basehost#',$cfg_basehost,$body);
		$body = str_replace('#2basehost2#',$basehost,$body);
	}

	//�Զ�ժҪ
	if($description=='' && $cfg_auot_description>0)
	{
		$description = cn_substr(html2text($body),$cfg_auot_description);
		$description = trim(preg_replace('/#p#|#e#/','',$description));
		$description = addslashes($description);
	}

	//�Զ���ȡ����ͼ
	if($autolitpic==1 && $litpic=='')
	{
		$litpic = GetDDImgFromBody($body);
	}

	//�Զ���ȡ�ؼ���
	if($autokey==1 && $keywords=='')
	{
		$subject = $title;
		$message = $body;
		include_once(DEDEINC.'/splitword.class.php');
		$keywords = '';
		$sp = new SplitWord($cfg_soft_lang, $cfg_soft_lang);
		$sp->SetSource($subject, $cfg_soft_lang, $cfg_soft_lang);
		$sp->StartAnalysis();
		$titleindexs = preg_replace("/#p#|#e#/",'',$sp->GetFinallyIndex());
		$sp->SetSource(Html2Text($message), $cfg_soft_lang, $cfg_soft_lang);
		$allindexs = preg_replace("/#p#|#e#/",'',$sp->GetFinallyIndex());
		if(is_array($allindexs) && is_array($titleindexs))
		{
			foreach($titleindexs as $k => $v)
			{
				if(strlen($keywords.$k)>=60)
				{
					break;
				}
				else
				{
					$keywords .= $k.',';
				}
			}
			foreach($allindexs as $k => $v)
			{
				if(strlen($keywords.$k)>=60)
				{
					break;
				}
				else if(!in_array($k,$titleindexs))
				{
					$keywords .= $k.',';
				}
			}
		}
		$sp = null;
		$keywords = $cfg_soft_lang == 'utf-8' ? addslashes(gb2utf8($keywords)) : addslashes($keywords);
	}
	$body = GetFieldValueA($body,$dtype,$id);
	$body = addslashes($body);
	return $body;
}

//ͼ�����ͼ��Сͼ
function GetImageMapDD($filename, $maxwidth)
{
	global $cuserLogin, $dsql, $cfg_ddimg_height, $cfg_ddimg_full;
	$ddn = substr($filename, -3);
	$ddpicok = ereg_replace("\.".$ddn."$", "-lp.".$ddn, $filename);
	$toFile = $GLOBALS['cfg_basedir'].$ddpicok;
	
	if($cfg_ddimg_full=='Y') ImageResizeNew($GLOBALS['cfg_basedir'].$filename, $maxwidth, $cfg_ddimg_height, $toFile);
	else ImageResize($GLOBALS['cfg_basedir'].$filename, $maxwidth, $cfg_ddimg_height, $toFile);
	
	//����ͼƬ������Ϣ
	$fsize = filesize($toFile);
	$ddpicoks = explode('/', $ddpicok);
	$filename = $ddpicoks[count($ddpicoks)-1];
	$inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
					VALUES ('0','$filename','$ddpicok','1','0','0','0','$fsize','".time()."','".$cuserLogin->getUserID()."'); ";
	$dsql->ExecuteNoneQuery($inquery);
	$fid = $dsql->GetLastID();
	AddMyAddon($fid, $ddpicok);
	
	return $ddpicok;
}

//------------------------
//�ϴ�һ��δ�������ͼƬ
//------------------------
/*
//����һ upname �ϴ�������
//������ handurl �ֹ���д����ַ
//������ ddisremote �Ƿ�����Զ��ͼƬ 0 ����, 1 ����
//������ ntitle ע������ ������� title �ֶοɲ���
*/
function UploadOneImage($upname,$handurl='',$isremote=1,$ntitle='')
{
	global $cuserLogin,$cfg_basedir,$cfg_image_dir,$title, $dsql;
	if($ntitle!='')
	{
		$title = $ntitle;
	}
	$ntime = time();
	$filename = '';
	$isrm_up = false;
	$handurl = trim($handurl);

	//����û������ϴ���ͼƬ
	if(!empty($_FILES[$upname]['tmp_name']) && is_uploaded_file($_FILES[$upname]['tmp_name']))
	{
		$istype = 0;
		$sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png");
		$_FILES[$upname]['type'] = strtolower(trim($_FILES[$upname]['type']));
		if(!in_array($_FILES[$upname]['type'],$sparr))
		{
			ShowMsg("�ϴ���ͼƬ��ʽ������ʹ��JPEG��GIF��PNG��ʽ������һ�֣�","-1");
			exit();
		}
		if(!empty($handurl) && !eregi("^http://",$handurl) && file_exists($cfg_basedir.$handurl) )
		{
			if(!is_object($dsql))
			{
				$dsql = new DedeSql();
			}
			$dsql->ExecuteNoneQuery("Delete From `#@__uploads` where url like '$handurl' ");
			$fullUrl = eregi_replace("\.([a-z]*)$","",$handurl);
		}
		else
		{
			$savepath = $cfg_image_dir.'/'.strftime("%Y-%m",$ntime);
			CreateDir($savepath);
			$fullUrl = $savepath.'/'.strftime("%d",$ntime).dd2char(strftime("%H%M%S",$ntime).'0'.$cuserLogin->getUserID().'0'.mt_rand(1000,9999));
		}
		if(strtolower($_FILES[$upname]['type'])=="image/gif")
		{
			$fullUrl = $fullUrl.".gif";
		}
		else if(strtolower($_FILES[$upname]['type'])=="image/png")
		{
			$fullUrl = $fullUrl.".png";
		}
		else
		{
			$fullUrl = $fullUrl.".jpg";
		}

		//����
		@move_uploaded_file($_FILES[$upname]['tmp_name'],$cfg_basedir.$fullUrl);
		$filename = $fullUrl;

		//ˮӡ
		@WaterImg($imgfile,'up');
		$isrm_up = true;
	}

	//Զ�̻�ѡ�񱾵�ͼƬ
	else
	{
		if($handurl=='')
		{
			return '';
		}

		//Զ��ͼƬ��Ҫ�󱾵ػ�
		if($isremote==1 && eregi("^http://",$handurl))
		{
			$ddinfos = GetRemoteImage($handurl,$cuserLogin->getUserID());
			if(!is_array($ddinfos))
			{
				$litpic = "";
			}
			else
			{
				$filename = $ddinfos[0];
			}
			$isrm_up = true;

			//����ͼƬ��Զ�̲�Ҫ�󱾵ػ�
		}
		else
		{
			$filename = $handurl;
		}
	}
	$imgfile = $cfg_basedir.$filename;
	if(is_file($imgfile) && $isrm_up && $filename!='')
	{
		$info = "";
		$imginfos = GetImageSize($imgfile,$info);

		//�����ϴ���ͼƬ��Ϣ���浽ý���ĵ���������
		$inquery = "
        INSERT INTO #@__uploads(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
        VALUES ('$title','$filename','1','".$imginfos[0]."','".$imginfos[1]."','0','".filesize($imgfile)."','".time()."','".$cuserLogin->getUserID()."');
    ";
		$dsql->ExecuteNoneQuery($inquery);
	}
	return $filename;
}

function GetUpdateTest()
{
	global $arcID, $typeid, $cfg_make_andcat, $cfg_makeindex, $cfg_make_prenext;
	$revalue = $dolist = '';
	if($cfg_makeindex=='Y' || $cfg_make_andcat=='Y' || $cfg_make_prenext=='Y')
	{
		if($cfg_make_prenext=='Y' && !empty($typeid)) $dolist = 'makeprenext';
		if($cfg_makeindex=='Y') $dolist .= empty($dolist) ? 'makeindex' : ',makeindex';
		if($cfg_make_andcat=='Y') $dolist .= empty($dolist) ? 'makeparenttype' : ',makeparenttype';
		$dolists = explode(',', $dolist);
		$jumpUrl = "task_do.php?typeid={$typeid}&aid={$arcID}&dopost={$dolists[0]}&nextdo=".ereg_replace($dolists[0]."[,]{0,1}", '', $dolist);
		$revalue = "<table width='80%' style='border:1px dashed #cdcdcd;margin-left:20px;margin-bottom:15px' id='tgtable' align='left'><tr><td bgcolor='#EBF5C9'>&nbsp;<strong>���ڽ���������ݸ��£������ǰ��Ҫ��������������</strong>\r\n</td></tr>\r\n";
		$revalue .= "<tr><td>\r\n<iframe name='stafrm' frameborder='0' id='stafrm' width='100%' height='200px' src='$jumpUrl'></iframe>\r\n</td></tr>\r\n";
		$revalue .= "</table>";
	}
	else
	{
		$revalue = '';
	}
	return $revalue;
}

?>
<?php
if(!defined('DEDEINC'))
{
	exit('dedecms');
}

//���һ�����ӱ�(����ʱ��)
function GetFormItem($ctag,$admintype='admin')
{
	global $dsql;
	$fieldname = $ctag->GetName();
	$fieldType = 	$ctag->GetAtt('type');
	$formitem = $formitem = GetSysTemplets("custom_fields_{$admintype}.htm");
	$innertext = trim($ctag->GetInnerText());
	if($innertext!='')
	{
		$formitem = $innertext;
	}
	
	if($fieldType=='select')
	{
		$myformItem = '';
		$items = explode(',',$ctag->GetAtt("default"));
		$myformItem = "<select name='$fieldname' style='width:150px'>";
		foreach($items as $v)
		{
			$v = trim($v);
			if($v!='') {
				$myformItem.= "<option value='$v'>$v</option>\r\n";
			}
		}
		$myformItem .= "</select>\r\n";
		$innertext = $myformItem;
	}
	else if($fieldType=='stepselect')
	{
			global $hasSetEnumJs,$cfg_cmspath;
			$cmspath = ( (empty($cfg_cmspath) || ereg('[/$]', $cfg_cmspath)) ? $cfg_cmspath.'/' : $cfg_cmspath );
			$myformItem = '';
			$myformItem .= "<input type='hidden' id='hidden_{$fieldname}' name='{$fieldname}' value='0' />\r\n";
			$myformItem .= "<span id='span_{$fieldname}'></span>\r\n";
			$myformItem .= "<span id='span_{$fieldname}_son'></span>\r\n";
			if($hasSetEnumJs != 'hasset')
			{
				$myformItem .= '<script language="javascript" type="text/javascript" src="'.$cmspath.'images/enums.js"></script>'."\r\n";
				$GLOBALS['hasSetEnumJs'] = 'hasset';
			}
			$myformItem .= "<script language='javascript' type='text/javascript' src='{$cmspath}data/enums/{$fieldname}.js'></script>\r\n";
			$myformItem .= '<script language="javascript" type="text/javascript">MakeTopSelect("'.$fieldname.'", 0);</script>'."\r\n";
			$formitem = str_replace('~name~', $ctag->GetAtt('itemname'), $formitem);
			$formitem = str_replace('~form~', $myformItem, $formitem);
			return $formitem;
	}
	else if($fieldType=='radio')
	{
		$myformItem = '';
		$items = explode(',',$ctag->GetAtt("default"));
		$i = 0;
		foreach($items as $v)
		{
			$v = trim($v);
			if($v!='')
			{
				$myformItem .= ($i==0 ? "<input type='radio' name='$fieldname' class='np' value='$v' checked>$v\r\n" : "<input type='radio' name='$fieldname' class='np' value='$v'>$v\r\n");
				$i++;
			}
		}
		$innertext = $myformItem;
	}
	else if($fieldType=='checkbox')
	{
		$myformItem = '';
		$items = explode(',',$ctag->GetAtt("default"));
		foreach($items as $v)
		{
			$v = trim($v);
			if($v!='')
			{
				if($admintype == 'membermodel')
				{
					$myformItem .= "<label><input type='checkbox' name='{$fieldname}[]' class='np' value='$v'>$v</label>\r\n";
				} else {
          $myformItem .= "<input type='checkbox' name='{$fieldname}[]' class='np' value='$v'>$v\r\n";
				}
				
			}
		}
		$innertext = $myformItem;
	}
	else if($fieldType=='htmltext'||$fieldType=='textdata')
	{
		$dfvalue = ($ctag->GetAtt('default')!='' ? $ctag->GetAtt('default') : '');
		$dfvalue = str_replace('{{', '<', $dfvalue);
		$dfvalue = str_replace('}}', '>', $dfvalue);
		if($admintype=='admin')
		{
			$innertext = GetEditor($fieldname, $dfvalue, 350, 'Basic', 'string');
		}
		else if($admintype=='diy')
		{
			$innertext = GetEditor($fieldname, $dfvalue, 350, 'Diy', 'string');
		}
		else
		{
			$innertext = GetEditor($fieldname, $dfvalue, 350, 'Member', 'string');
		}
	}
	else if($fieldType=="multitext")
	{
		$innertext = "<textarea name='$fieldname' id='$fieldname' style='width:90%;height:80'></textarea>\r\n";
	}
	else if($fieldType=="datetime")
	{
		$nowtime = GetDateTimeMk(time());
		$innertext = "<input name=\"$fieldname\" value=\"$nowtime\" type=\"text\" id=\"$fieldname\" style=\"width:250px\" class=\"intxt\"  />";
	}
	else if($fieldType=='img'||$fieldType=='imgfile')
	{
		if($admintype=='diy') {
			$innertext = "<input type='file' name='$fieldname' id='$fieldname' style='width:300px;height:22px;line-height:22px' />\r\n";
		}
		else {
			$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300px' class='text' /> <input name='".$fieldname."_bt' type='button' class='inputbut' value='���...' onClick=\"SelectImage('form1.$fieldname','big')\" />\r\n";
		}
	}
	else if($fieldType=='media')
	{
		if($admintype=='diy')
		{
			$innertext = "<input type='hidden' name='$fieldname' id='$fieldname' value='' />��֧�ֵ�����\r\n";
		}
		else
		{
			$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300px' class='text' /> <input name='".$fieldname."_bt' type='button' class='inputbut' value='���...' onClick=\"SelectMedia('form1.$fieldname')\" />\r\n";
		}
	}
	else if($fieldType=='addon')
	{
		if($admintype=='diy')
		{
			$innertext = "<input type='file' name='$fieldname' id='$fieldname' style='width:300px;height:22px;line-height:22px' />\r\n";
		}
		else
		{
			$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300px' class='text' /> <input name='".$fieldname."_bt' type='button' class='inputbut' value='���...' onClick=\"SelectSoft('form1.$fieldname')\" />\r\n";
		}
	}
	else if($fieldType=='int'||$fieldType=='float')
	{
		$dfvalue = ($ctag->GetAtt('default')!='' ? $ctag->GetAtt('default') : '0');
		$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:100px'  class='intxt' value='$dfvalue' /> (��д��ֵ)\r\n";
	}
	else
	{
		$dfvalue = ($ctag->GetAtt('default')!='' ? $ctag->GetAtt('default') : '');
		$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:250px'  class='intxt' value='$dfvalue' />\r\n";
	}
	$formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
	$formitem = str_replace("~form~",$innertext,$formitem);
	return $formitem;
}

//����ͬ���͵�����
function GetFieldValue($dvalue, $dtype, $aid=0, $job='add', $addvar='', $admintype='admin', $fieldname='')
{
	global $cfg_basedir, $cfg_cmspath, $adminid, $cfg_ml, $cfg_cookie_encode;
	if(!empty($adminid))
	{
		$adminid = $adminid;
	}
	else
	{
		$adminid = isset($cfg_ml) ? $cfg_ml->M_ID : 1;
	}
	if($dtype=='int')
	{
		if($dvalue=='')
		{
			return 0;
		}
		return GetAlabNum($dvalue);
	}
	else if($dtype=='stepselect')
	{
		return intval($dvalue);
	}
	else if($dtype=='float')
	{
		if($dvalue=='')
		{
			return 0;
		}
		return GetAlabNum($dvalue);
	}
	else if($dtype=='datetime')
	{
		if($dvalue=='')
		{
			return 0;
		}
		return GetMkTime($dvalue);
	}
	else if($dtype=='checkbox')
	{
		$okvalue = '';
		if(is_array($dvalue))
		{
			$okvalue = join(',',$dvalue);
		}
		return $okvalue;
	}
	else if($dtype=="htmltext")
	{
		if($admintype=='member' || $admintype=='diy')
		{
			$dvalue = HtmlReplace($dvalue,-1);
		}
		return $dvalue;
	}
	else if($dtype=="multitext")
	{
		if($admintype=='member' || $admintype=='diy')
		{
			$dvalue = HtmlReplace($dvalue,0);
		}
		return $dvalue;
	}
	else if($dtype=="textdata")
	{
			$ipath = $cfg_cmspath."/data/textdata";
			$tpath = ceil($aid/5000);
			if(!is_dir($cfg_basedir.$ipath))
			{
				MkdirAll($cfg_basedir.$ipath,$GLOBALS['cfg_dir_purview']);
			}
			if(!is_dir($cfg_basedir.$ipath.'/'.$tpath))
			{
				MkdirAll($cfg_basedir.$ipath.'/'.$tpath,$GLOBALS['cfg_dir_purview']);
			}
			$ipath = $ipath.'/'.$tpath;
			$filename = "{$ipath}/{$aid}-".cn_substr(md5($cfg_cookie_encode),0,16).".txt";

			//��ԱͶ�����ݰ�ȫ����
			if($admintype=='member' || $admintype=='diy')
			{
				$dvalue = HtmlReplace($dvalue,-1);
			}
			$fp = fopen($cfg_basedir.$filename,"w");
			fwrite($fp,stripslashes($dvalue));
			fclose($fp);
			CloseFtp();
			return $filename;
	}
	else if($dtype=='img' || $dtype=='imgfile')
	{
		if($admintype=='diy')
		{
			$iurl = MemberUploads($fieldname,'', 0, 'image', '', -1, -1, false);
			return $iurl;
		}
		$iurl = stripslashes($dvalue);
		if(trim($iurl)=='')
		{
			return '';
		}
		$iurl = trim(str_replace($GLOBALS['cfg_basehost'],"",$iurl));
		$imgurl = "{dede:img text='' width='' height=''} ".$iurl." {/dede:img}";
		if(eregi("^http://",$iurl) && $GLOBALS['cfg_isUrlOpen'])
		{
			//Զ��ͼƬ
			$reimgs = '';
			if($GLOBALS['cfg_isUrlOpen'])
			{
				$reimgs = GetRemoteImage($iurl,$adminid);
				if(is_array($reimgs))
				{
					if($dtype=='imgfile')
					{
						$imgurl = $reimgs[1];
					}
					else
					{
						$imgurl = "{dede:img text='' width='".$reimgs[1]."' height='".$reimgs[2]."'} ".$reimgs[0]." {/dede:img}";
					}
				}
			}
			else
			{
				if($dtype=='imgfile')
				{
					$imgurl = $iurl;
				}
				else
				{
					$imgurl = "{dede:img text='' width='' height=''} ".$iurl." {/dede:img}";
				}
			}
		}
		else if($iurl != '')
		{
			//վ��ͼƬ
			$imgfile = $cfg_basedir.$iurl;
			if(is_file($imgfile))
			{
				$info = '';
				$imginfos = GetImageSize($imgfile,$info);
				if($dtype=="imgfile")
				{
					$imgurl = $iurl;
				}
				else
				{
					$imgurl = "{dede:img text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}";
				}
			}
		}
		return addslashes($imgurl);
	}
	else if($dtype=='addon' && $admintype=='diy') 
	{
		$dvalue = MemberUploads($fieldname,'', 0, 'addon', '', -1, -1, false);
		return $dvalue;
	}
	else
	{
		if($admintype=='member' || $admintype=='diy')
		{
			$dvalue = HtmlReplace($dvalue,1);
		}
		return $dvalue;
	}
}

//��ô�ֵ�ı�(�༭ʱ��)
function GetFormItemValue($ctag, $fvalue, $admintype='admin', $fieldname='')
{
	global $cfg_basedir,$dsql;
	$fieldname = $ctag->GetName();
	$formitem = $formitem = GetSysTemplets("custom_fields_{$admintype}.htm");
	$innertext = trim($ctag->GetInnerText());
	if($innertext!='')
	{
		$formitem = $innertext;
	}
	$ftype = $ctag->GetAtt('type');
	$myformItem = '';
	if(eregi('select|radio|checkbox',$ftype))
	{
		$items = explode(',',$ctag->GetAtt('default'));
	}
	if($ftype=='select')
	{
		$myformItem = "<select name='$fieldname' style='width:150px'>";
		if(is_array($items))
		{
			foreach($items as $v)
			{
				$v = trim($v);
				if($v=='')
				{
					continue;
				}
				$myformItem.= ($fvalue==$v ? "<option value='$v' selected>$v</option>\r\n" : "<option value='$v'>$v</option>\r\n");
			}
		}
		$myformItem .= "</select>\r\n";
		$innertext = $myformItem;
	}
	else if($ctag->GetAtt("type")=='stepselect')
	{
		global $hasSetEnumJs,$cfg_cmspath;
		$cmspath = ( (empty($cfg_cmspath) || ereg('[/$]', $cfg_cmspath)) ? $cfg_cmspath.'/' : $cfg_cmspath );
		$myformItem = '';
		$myformItem .= "<input type='hidden' id='hidden_{$fieldname}' name='{$fieldname}' value='{$fvalue}' />\r\n";
		$myformItem .= "<span id='span_{$fieldname}'></span>\r\n";
		$myformItem .= "<span id='span_{$fieldname}_son'></span>\r\n";
		if($hasSetEnumJs != 'hasset')
		{
			$myformItem .= '<script language="javascript" type="text/javascript" src="'.$cmspath.'images/enums.js"></script>'."\r\n";
			$GLOBALS['hasSetEnumJs'] = 'hasset';
		}
		$myformItem .= "<script language='javascript' type='text/javascript' src='{$cmspath}data/enums/{$fieldname}.js'></script>\r\n";
		$myformItem .= "<script language='javascript' type='text/javascript'>MakeTopSelect('$fieldname', $fvalue);</script>\r\n";
		$formitem = str_replace('~name~', $ctag->GetAtt('itemname'), $formitem);
		$formitem = str_replace('~form~', $myformItem, $formitem);
		return $formitem;
	}
	else if($ftype=='radio')
	{
		if(is_array($items))
		{
			foreach($items as $v)
			{
				$v = trim($v);
				if($v=='') continue;
				$myformItem.= ($fvalue==$v ? "<input type='radio' name='$fieldname' class='np' value='$v' checked='checked' />$v\r\n" : "<input type='radio' name='$fieldname' class='np' value='$v' />$v\r\n");
			}
		}
		$innertext = $myformItem;
	}

	//checkbox
	else if($ftype=='checkbox')
	{
		$myformItem = '';
		$fvalues = explode(',',$fvalue);
		if(is_array($items))
		{
			foreach($items as $v)
			{
				$v = trim($v);
				if($v=='')
				{
					continue;
				}
				if(in_array($v,$fvalues))
				{
					$myformItem .= "<input type='checkbox' name='{$fieldname}[]' class='np' value='$v' checked='checked' />$v\r\n";
				}
				else
				{
					$myformItem .= "<input type='checkbox' name='{$fieldname}[]' class='np' value='$v' />$v\r\n";
				}
			}
		}
		$innertext = $myformItem;
	}

	//�ı����ݵ����⴦��
	else if($ftype=="textdata")
	{
		if(is_file($cfg_basedir.$fvalue))
		{
			$fp = fopen($cfg_basedir.$fvalue,'r');
			$okfvalue = '';
			while(!feof($fp)){ $okfvalue .= fgets($fp,1024); }
			fclose($fp);
		}
		else
		{
			$okfvalue = '';
		}
		if($admintype=='admin')
		{
			$myformItem = GetEditor($fieldname,$okfvalue,350,'Basic','string')."\r\n <input type='hidden' name='{$fieldname}_file' value='{$fvalue}' />\r\n ";
		}
		else
		{
			$myformItem = GetEditor($fieldname,$okfvalue,350,'Member','string')."\r\n <input type='hidden' name='{$fieldname}_file' value='{$fvalue}' />\r\n ";
		}
		$innertext = $myformItem;
	}
	else if($ftype=="htmltext")
	{
		if($admintype=='admin')
		{
			$myformItem = GetEditor($fieldname,$fvalue,350,'Basic','string')."\r\n ";
		}
		else
		{
			$myformItem = GetEditor($fieldname,$fvalue,350,'Member','string')."\r\n ";
		}
		$innertext = $myformItem;
	}
	else if($ftype=="multitext")
	{
		$innertext = "<textarea name='$fieldname' id='$fieldname' style='width:90%;height:80px'>$fvalue</textarea>\r\n";
	}
	else if($ftype=="datetime")
	{
		$nowtime = GetDateTimeMk($fvalue);
		$innertext = "<input name=\"$fieldname\" value=\"$nowtime\" type=\"text\" id=\"$fieldname\" style=\"width:250px\" class=\"intxt\" />";
	}
	else if($ftype=="img")
	{
		$ndtp = new DedeTagParse();
		$ndtp->LoadSource($fvalue);
		if(!is_array($ndtp->CTags))
		{
			$ndtp->Clear();
			$fvalue =  "";
		}
		else
		{
			$ntag = $ndtp->GetTag("img");
			$fvalue = trim($ntag->GetInnerText());
		}
		$innertext = "<input type='text' name='$fieldname' value='$fvalue' id='$fieldname' style='width:300px'  class='text' /> <input name='".$fieldname."_bt' class='inputbut' type='button' value='���...' onClick=\"SelectImage('form1.$fieldname','big')\" />\r\n";
	}
	else if($ftype=="imgfile")
	{
		$innertext = "<input type='text' name='$fieldname' value='$fvalue' id='$fieldname' style='width:300px'  class='text' /> <input name='".$fieldname."_bt' class='inputbut' type='button' value='���...' onClick=\"SelectImage('form1.$fieldname','big')\" />\r\n";
	}
	else if($ftype=="media")
	{
		$innertext = "<input type='text' name='$fieldname' value='$fvalue' id='$fieldname' style='width:300px'  class='text' /> <input name='".$fieldname."_bt' class='inputbut' type='button' value='���...' onClick=\"SelectMedia('form1.$fieldname')\" />\r\n";
	}
	else if($ftype=="addon")
	{
		$innertext = "<input type='text' name='$fieldname' id='$fieldname' value='$fvalue' style='width:300px'  class='text' /> <input name='".$fieldname."_bt' class='inputbut' type='button' value='���...' onClick=\"SelectSoft('form1.$fieldname')\" />\r\n";
	}
	else if($ftype=="int"||$ftype=="float")
	{
		$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:100px'  class='intxt' value='$fvalue' /> (��д��ֵ)\r\n";
	}
	else
	{
		$innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:250px'  class='intxt' value='$fvalue' />\r\n";
	}
	$formitem = str_replace('~name~',$ctag->GetAtt('itemname'),$formitem);
	$formitem = str_replace('~form~',$innertext,$formitem);
	return $formitem;
}
?>
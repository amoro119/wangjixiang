<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

function SpGetPinyin($str,$ishead=0,$isclose=1)
{
	global $pinyins;
	$restr = '';
	$str = trim($str);
	$slen = strlen($str);
	if($slen<2)
	{
		return $str;
	}
	if(count($pinyins)==0)
	{
		$fp = fopen(DEDEINC.'/data/pinyin.dat','r');
		while(!feof($fp))
		{
			$line = trim(fgets($fp));
			$pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
		}
		fclose($fp);
	}
	for($i=0;$i<$slen;$i++)
	{
		if(ord($str[$i])>0x80)
		{
			$c = $str[$i].$str[$i+1];
			$i++;
			if(isset($pinyins[$c]))
			{
				if($ishead==0)
				{
					$restr .= $pinyins[$c];
				}
				else
				{
					$restr .= $pinyins[$c][0];
				}
			}else
			{
				$restr .= "_";
			}
		}else if( eregi("[a-z0-9]",$str[$i]) )
		{
			$restr .= $str[$i];
		}
		else
		{
			$restr .= "_";
		}
	}
	if($isclose==0)
	{
		unset($pinyins);
	}
	return $restr;
}

function SpCreateDir($spath)
{
	global $cfg_dir_purview,$cfg_basedir,$cfg_ftp_mkdir,$isSafeMode;
	if($spath=='')
	{
		return true;
	}
	$flink = false;
	$truepath = $cfg_basedir;
	$truepath = str_replace("\\","/",$truepath);
	$spaths = explode("/",$spath);
	$spath = "";
	foreach($spaths as $spath)
	{
		if($spath=="")
		{
			continue;
		}
		$spath = trim($spath);
		$truepath .= "/".$spath;
		if(!is_dir($truepath) || !is_writeable($truepath))
		{
			if(!is_dir($truepath))
			{
				$isok = MkdirAll($truepath,$cfg_dir_purview);
			}
			else
			{
				$isok = ChmodAll($truepath,$cfg_dir_purview);
			}
			if(!$isok)
			{
				echo "�������޸�Ŀ¼��".$truepath." ʧ�ܣ�<br>";
				CloseFtp();
				return false;
			}
		}
	}
	CloseFtp();
	return true;
}

function SpGetEditor($fname,$fvalue,$nheight="350",$etype="Basic",$gtype="print",$isfullpage="false")
{
	if(!isset($GLOBALS['cfg_html_editor']))
	{
		$GLOBALS['cfg_html_editor']='fck';
	}
	if($gtype=="")
	{
		$gtype = "print";
	}
	if($GLOBALS['cfg_html_editor']=='fck')
	{
		require_once(DEDEINC.'/FCKeditor/fckeditor.php');
		$fck = new FCKeditor($fname);
		$fck->BasePath		= $GLOBALS['cfg_cmspath'].'/include/FCKeditor/' ;
		$fck->Width		= '100%' ;
		$fck->Height		= $nheight ;
		$fck->ToolbarSet	= $etype ;
		$fck->Config['FullPage'] = $isfullpage;
		if($GLOBALS['cfg_fck_xhtml']=='Y')
		{
			$fck->Config['EnableXHTML'] = 'true';
			$fck->Config['EnableSourceXHTML'] = 'true';
		}
		$fck->Value = $fvalue ;
		if($gtype=="print")
		{
			$fck->Create();
		}
		else
		{
			return $fck->CreateHtml();
		}
	}
	else
	{
		require_once(DEDEINC.'/htmledit/dede_editor.php');
		$ded = new DedeEditor($fname);
		$ded->BasePath		= $GLOBALS['cfg_cmspath'].'/include/htmledit/' ;
		$ded->Width		= '100%' ;
		$ded->Height		= $nheight ;
		$ded->ToolbarSet = strtolower($etype);
		$ded->Value = $fvalue ;
		if($gtype=="print")
		{
			$ded->Create();
		}
		else
		{
			return $ded->CreateHtml();
		}
	}
}

function SpGetNewInfo()
{
	global $cfg_version;
	$nurl = $_SERVER['HTTP_HOST'];
	if( eregi("[a-z\-]{1,}\.[a-z]{2,}",$nurl) ) {
		$nurl = urlencode($nurl);
	}
	else {
		$nurl = "test";
	}
	$offUrl = "http://www.de"."decms.com/newinfov5x.php?version={$cfg_version}&formurl={$nurl}";
	return $offUrl;
}

?>
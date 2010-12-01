<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

function ch_img($fvalue,&$arcTag,&$refObj,$fname='')
{
	global $cfg_album_width,$cfg_album_row,$cfg_album_col,$cfg_album_pagesize,$cfg_album_style,$cfg_album_ddwidth,$cfg_basehost,$cfg_multi_site;
	$dtp = new DedeTagParse();
	$dtp->LoadSource($fvalue);
	if(!is_array($dtp->CTags))
	{
		$dtp->Clear();
		return "��ͼƬ��Ϣ��";
	}
	$pagestyle = $cfg_album_style;
	$maxwidth = $cfg_album_width;
	$ddmaxwidth = $cfg_album_ddwidth;
	$pagepicnum = $cfg_album_pagesize;
	$row = $cfg_album_row;
	$icol = $cfg_album_col;
	$ptag = $dtp->GetTag('pagestyle');
	if(is_object($ptag))
	{
		$pagestyle = $ptag->GetAtt('value');
		$maxwidth = $ptag->GetAtt('maxwidth');
		$ddmaxwidth = $ptag->GetAtt('ddmaxwidth');
		$pagepicnum = $ptag->GetAtt('pagepicnum');
		$irow = $ptag->GetAtt('row');
		$icol = $ptag->GetAtt('col');
		if(empty($maxwidth))
		{
			$maxwidth = $cfg_album_width;
		}
	}

	//����ͼƬ��Ϣ
	$mrow = 0;
	$mcol = 0;
	$images = array();
	$innerTmp = $arcTag->GetInnerText();
	if(trim($innerTmp)=='')
	{
		$innerTmp = GetSysTemplets("channel_article_image.htm");
	}

	if($pagestyle==1)
	{
		$pagesize = $pagepicnum;
	}
	else if($pagestyle==2)
	{
		$pagesize = 1;
	}
	else
	{
		$pagesize = $irow * $icol;
	}

	if(is_object($arcTag) && $arcTag->GetAtt('pagesize') > 0)
	{
		$pagesize = $arcTag->GetAtt('pagesize');
	}
	if(empty($pagesize))
	{
		$pagesize = 12;
	}

	$revalue = '';
	$GLOBAL['photoid'] = 0;
	foreach($dtp->CTags as $ctag)
	{
		if($ctag->GetName()=="img")
		{
			$fields = $ctag->CAttribute->Items;
			$fields['text'] = str_replace("'","",$ctag->GetAtt('text'));
			$fields['imgsrc'] = trim($ctag->GetInnerText());
			$fields['imgsrctrue'] = $fields['imgsrc'];
			if(empty($fields['ddimg']))
			{
				$fields['ddimg'] = $fields['imgsrc'];
			}
			if($cfg_multi_site=='Y')
			{
				//$cfg_basehost)
				if( !eregi('^http:', $fields['imgsrc']) ) {
					$fields['imgsrc'] = $cfg_basehost.$fields['imgsrc'];
				}
				if( !eregi('^http:', $fields['ddimg']) ) {
					$fields['ddimg'] = $cfg_basehost.$fields['ddimg'];
				}
			}
			if(empty($fields['width']))
			{
				$fields['width'] = $maxwidth;
			}
			//if($fields['text']=='')
			//{
				//$fields['text'] = 'ͼƬ'.($GLOBAL['photoid']+1);
			//}
			$fields['alttext'] = str_replace("'",'',$fields['text']);
			$fields['pagestyle'] = $pagestyle;
			$dtp2 = new DedeTagParse();
			$dtp2->SetNameSpace("field","[","]");
			$dtp2->LoadSource($innerTmp);
			if($GLOBAL['photoid']>0 && ($GLOBAL['photoid'] % $pagesize)==0)
			{
				$revalue .= "#p#��ҳ����#e#";
			}
			if($pagestyle==1)
			{
				$fields['imgwidth'] = '';
				$fields['linkurl'] = $fields['imgsrc'];
				$fields['textlink'] = "<br /><a href='{$fields['linkurl']}' target='_blank'>{$fields['text']}</a>";
			}
			else if($pagestyle==2)
			{
				if($fields['width'] > $maxwidth) {
					$fields['imgwidth'] = " width='$maxwidth' ";
				}
				else {
					$fields['imgwidth'] = " width='{$fields['width']}' ";
				}
				$fields['linkurl'] = $fields['imgsrc'];
				if($fields['text']!='') {
					$fields['textlink'] = "<br /><a href='{$fields['linkurl']}' target='_blank'>{$fields['text']}</a>\r\n";
				}
				else {
					$fields['textlink'] = '';
				}
			}
			else if($pagestyle==3)
			{
				$fields['text'] = $fields['textlink'] = '';
				$fields['imgsrc'] = $fields['ddimg'];
				$fields['imgwidth'] = " width='$ddmaxwidth' ";
				$fields['linkurl'] = "{$GLOBALS['cfg_phpurl']}/showphoto.php?aid={$refObj->ArcID}&src=".urlencode($fields['imgsrctrue'])."&npos={$GLOBAL['photoid']}";
			}
			if(is_array($dtp2->CTags))
			{
				foreach($dtp2->CTags as $tagid=>$ctag)
				{
					if(isset($fields[$ctag->GetName()]))
					{
						$dtp2->Assign($tagid,$fields[$ctag->GetName()]);
					}
				}
				$revalue .= $dtp2->GetResult();
			}
			$GLOBAL['photoid']++;
		}
	}
	return $revalue;
}


?>
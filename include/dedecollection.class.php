<?php
if(!defined('DEDEINC'))
{
	exit('dedecms');
}

require_once(DEDEINC."/dedecollection.func.php"); //�ɼ���չ����
require_once(DEDEINC."/image.func.php");
require_once(DEDEINC."/dedehtml2.class.php");
@set_time_limit(0);
class DedeCollection
{
	var $artNotes = array(); //���²ɼ����ֶ���Ϣ
	var $lists = array(); //�ɼ��ڵ����Դ�б�����Ϣ
	var $noteInfos = array(); //�ɼ��ڵ�Ļ���������Ϣ
	var $dsql = '';
	var $noteId = '';
	var $cDedeHtml = '';
	var $cHttpDown = '';
	var $mediaCount = 0;
	var $tmpUnitValue = '';
	var $tmpLinks = array();
	var $tmpHtml = '';
	var $breImage = '';
	var $errString = '';

	//����php5���캯��
	function __construct()
	{
		$this->dsql = $GLOBALS['dsql'];
		$this->cHttpDown = new DedeHttpDown();
		$this->cDedeHtml = new DedeHtml2();
	}

	function DedeCollection()
	{
		$this->__construct();
	}

	//������Դ
	function Close()
	{
	}

	//�����ݿ�������ĳ���ڵ�
	function LoadNote($nid)
	{
		$this->noteId = $nid;
		$row = $this->dsql->GetOne("Select * from `#@__co_note` where nid='$nid'");
		$this->LoadListConfig($row['listconfig']);
		$this->LoadItemConfig($row['itemconfig']);
	}

	//���������ڵ�ļ�����������Ϣ
	function LoadListConfig($configString)
	{
		$dtp = new DedeTagParse();
		$dtp2 = new DedeTagParse();
		$dtp->LoadString($configString);
		for($i=0;$i<=$dtp->Count;$i++)
		{
			$ctag = $dtp->CTags[$i];

			//item ����
			//�ڵ������Ϣ
			if($ctag->GetName()=="noteinfo")
			{
				$this->noteInfos['notename'] = $ctag->GetAtt('notename');
				$this->noteInfos['matchtype'] = $ctag->GetAtt('matchtype');
				$this->noteInfos['channelid'] = $ctag->GetAtt('channelid');
				$this->noteInfos['refurl'] = $ctag->GetAtt('refurl');
				$this->noteInfos['sourcelang'] = $ctag->GetAtt('sourcelang');
				$this->noteInfos['cosort'] = $ctag->GetAtt('cosort');
				$this->noteInfos['isref'] = $ctag->GetAtt('isref');
				$this->noteInfos['exptime'] = $ctag->GetAtt('exptime');
			}

			//list ����
			//Ҫ�ɼ����б�ҳ����Ϣ
			else if($ctag->GetName()=="listrule")
			{
				$this->lists['sourcetype'] = $ctag->GetAtt('sourcetype');
				$this->lists['rssurl'] = $ctag->GetAtt('rssurl');
				$this->lists['regxurl'] = $ctag->GetAtt('regxurl');
				$this->lists['startid'] = $ctag->GetAtt('startid');
				$this->lists['endid'] = $ctag->GetAtt('endid');
				$this->lists['addv'] = $ctag->GetAtt('addv');
				$this->lists['urlrule'] = $ctag->GetAtt('urlrule');
				$this->lists['musthas'] = $ctag->GetAtt('musthas');
				$this->lists['nothas'] = $ctag->GetAtt('nothas');
				$this->lists['listpic'] = $ctag->GetAtt('listpic');
				$this->lists['usemore'] =  $ctag->GetAtt('usemore');
				$dtp2->LoadString($ctag->GetInnerText());
				for($j=0;$j<=$dtp2->Count;$j++)
				{
					$ctag2 = $dtp2->CTags[$j];
					$tname = $ctag2->GetName();
					if($tname=='addurls')
					{
						$this->lists['addurls'] = trim($ctag2->GetInnerText());
					}
					else if($tname=='regxrule')
					{
						$this->lists['regxrule'] = trim($ctag2->GetInnerText());
					}
					else if($tname=='areastart')
					{
						$this->lists['areastart'] = trim($ctag2->GetInnerText());
					}
					else if($tname=='areaend')
					{
						$this->lists['areaend'] = trim($ctag2->GetInnerText());
					}
					else if($tname=='batchrule')
					{
						$this->lists['batchrule'] = trim($ctag2->GetInnerText());
					}
				}

				//�����б���ַ
				if($this->lists['sourcetype'] != 'rss')
				{
					$this->lists['url'] = GetUrlFromListRule($this->lists['regxurl'],$this->lists['addurls'],
					$this->lists['startid'],$this->lists['endid'],$this->lists['addv'],$this->lists['usemore'],$this->lists['batchrule']);
				}
				else
				{
					$this->lists['url'] = $this->lists['rssurl'];
				}
			}
		}//End Loop

		$dtp->Clear();
		$dtp2->Clear();
	}

	//�����ɼ�����ҳ���ֶε�����
	function LoadItemConfig($configString)
	{
		$dtp = new DedeTagParse();
		$dtp2 = new DedeTagParse();
		$dtp->LoadString($configString);
		for($i=0;$i<=$dtp->Count;$i++)
		{
			$ctag = $dtp->CTags[$i];
			if($ctag->GetName()=='sppage')
			{
				$this->artNotes['sppage'] = $ctag->GetInnerText();
				$this->artNotes['sptype'] = $ctag->GetAtt('sptype');
			}
			else if($ctag->GetName()=='previewurl')
			{
				$this->artNotes['previewurl'] = $ctag->GetInnerText();
			}
			else if($ctag->GetName()=='keywordtrim')
			{
				$this->artNotes['keywordtrim'] = $ctag->GetInnerText();
			}
			else if($ctag->GetName()=='descriptiontrim')
			{
				$this->artNotes['descriptiontrim'] = $ctag->GetInnerText();
			}
			else if($ctag->GetName()=='item')
			{
				$field = $ctag->GetAtt('field');
				if($field == '')
				{
					continue;
				}
				$this->artNotes[$field]['value'] = $ctag->GetAtt('value');
				$this->artNotes[$field]['isunit'] = $ctag->GetAtt('isunit');
				$this->artNotes[$field]['isdown'] = $ctag->GetAtt('isdown');
				$this->artNotes[$field]['trim'] = array();
				$this->artNotes[$field]['match'] = '';
				$this->artNotes[$field]['function'] = '';
				$t = 0;
				$dtp2->LoadString($ctag->GetInnerText());
				for($k=0;$k<=$dtp2->Count;$k++)
				{
					$ctag2 = $dtp2->CTags[$k];
					if($ctag2->GetName()=='trim')
					{
						$this->artNotes[$field]['trim'][$t][0] = str_replace('#n#','&nbsp;',$ctag2->GetInnerText());
						$this->artNotes[$field]['trim'][$t][1] = $ctag2->GetAtt('replace');
						$t++;
					}
					else if($ctag2->GetName()=='match')
					{
						$this->artNotes[$field]['match'] = str_replace('#n#','&nbsp;',$ctag2->GetInnerText());
					}
					else if($ctag2->GetName()=='function')
					{
						$this->artNotes[$field]['function'] = $ctag2->GetInnerText();
					}
				}
			}
		}//End Loop

		$dtp->Clear();
		$dtp2->Clear();
	}

	//��������һ����ַ��������
	function DownUrl($aid,$dourl,$litpic='',$issave=true)
	{
		$this->tmpLinks = array();
		$this->tmpUnitValue = '';
		$this->breImage = '';
		$this->tmpHtml = $this->DownOnePage($dourl);

		//����Ƿ��з�ҳ�ֶΣ���Ԥ�ȴ���
		if(!empty($this->artNotes['sppage']))
		{
			$noteid = '';
			foreach($this->artNotes as $k=>$sarr)
			{
				if(isset($sarr['isunit']) && $sarr['isunit']==1)
				{
					$noteid = $k;
					break;
				}
			}
			$this->GetSpPage($dourl,$noteid,$this->tmpHtml);

			if(eregi('#p#',$this->tmpUnitValue))
			{
				$this->tmpUnitValue = '������#e#'.$this->tmpUnitValue;
			}

		}

		//�����ֶ�
		$body = $this->GetPageFields($dourl,$issave,$litpic);

		//�������ϵ����ݿ�
		if($issave)
		{
			$query = " Update `#@__co_htmls` set dtime='".time()."',result='".addslashes($body)."',isdown='1' where aid='$aid' ";
			if(!$this->dsql->ExecuteNoneQuery($query))
			{
				echo $this->dsql->GetError();
			}
			return $body;
		}
		return $body;
	}

	//��ȡ��ҳ���������
	function GetSpPage($dourl,$noteid,$html,$step=0)
	{
		$sarr = $this->artNotes[$noteid];
		$linkareaHtml = $this->GetHtmlArea('[����]',$this->artNotes['sppage'],$html);
		if($linkareaHtml=='')
		{
			if($this->tmpUnitValue=='')
			{
				$this->tmpUnitValue .= $this->GetHtmlArea('[����]',$sarr['match'],$html);
			}
			else
			{
				$this->tmpUnitValue .= "#p#������#e#".$this->GetHtmlArea('[����]',$sarr['match'],$html);
			}
			return;
		}

		//�����ķ�ҳ�б�
		if($this->artNotes["sptype"]=='full' || $this->artNotes["sptype"]=='')
		{
			$this->tmpUnitValue .= $this->GetHtmlArea('[����]',$sarr['match'],$html);
			$this->cDedeHtml->GetLinkType = "link";
			$this->cDedeHtml->SetSource($linkareaHtml,$dourl,'link');
			foreach($this->cDedeHtml->Links as $k=>$t)
			{
				$k = $this->cDedeHtml->FillUrl($k);
				if($k==$dourl)
				{
					continue;
				}
				$nhtml = $this->DownOnePage($k);
				if($nhtml!='')
				{
					$ct = trim($this->GetHtmlArea('[����]',$sarr['match'],$nhtml));
					if($ct!='')
					{
						$this->tmpUnitValue .= "#p#������#e#".$ct;
					}
				}
			}
		}

		//����ҳ��ʽ�������ķ�ҳ�б�
		else
		{
			if($step>50)
			{
				return;
			}
			if($step==0)
			{
				$this->tmpUnitValue .= $this->GetHtmlArea('[����]',$sarr['match'],$html);
			}
			$this->cDedeHtml->GetLinkType = "link";
			$this->cDedeHtml->SetSource($linkareaHtml,$dourl,'link');
			$hasLink = false;
			foreach($this->cDedeHtml->Links as $k=>$t)
			{
				$k = $this->cDedeHtml->FillUrl($k);
				if(in_array($k,$this->tmpLinks))
				{
					continue;
				}
				else{
					$nhtml = $this->DownOnePage($k);
					if($nhtml!='')
					{
						$ct = trim($this->GetHtmlArea('[����]',$sarr['match'],$nhtml));
						if($ct!='')
						{
							$this->tmpUnitValue .= "#p#������#e#".$ct;
						}
					}
					$hasLink = true;
					$this->tmpLinks[] = $k;
					$dourl = $k;
					$step++;
				}
			}
			if($hasLink)
			{
				$this->GetSpPage($dourl,$noteid,$nhtml,$step);
			}
		}
	}

	//��ȡ�ض������HTML
	function GetHtmlArea($sptag,&$areaRule,&$html)
	{
		//��������ʽ��ģʽƥ��
		if($this->noteInfos['matchtype']=='regex')
		{
			$areaRule = str_replace("/","\\/",$areaRule);
			$areaRules = explode($sptag,$areaRule);
			$arr = array();
			if($html==''||$areaRules[0]=='')
			{
				return '';
			}
			preg_match('/'.$areaRules[0]."(.*)".$areaRules[1]."/isU",$html,$arr);
			return empty($arr[1]) ? '' : trim($arr[1]);
		}

		//���ַ���ģʽƥ��
		else
		{
			$areaRules = explode($sptag,$areaRule);
			if($html=='' || $areaRules[0]=='')
			{
				return '';
			}
			$posstart = @strpos($html,$areaRules[0]);
			if($posstart===false)
			{
				return '';
			}
			$posstart = $posstart + strlen($areaRules[0]);
			$posend = @strpos($html,$areaRules[1],$posstart);
			if($posend > $posstart && $posend!==false)
			{
				//return substr($html,$posstart+strlen($areaRules[0]),$posend-$posstart-strlen($areaRules[0]));
				return substr($html,$posstart,$posend-$posstart);
			}
			else
			{
				return '';
			}
		}
	}

	//����ָ����ַ
	function DownOnePage($dourl)
	{
		$this->cHttpDown->OpenUrl($dourl);
		$html = $this->cHttpDown->GetHtml();
		$this->cHttpDown->Close();
		$this->ChangeCode($html);
		return $html;
	}

	//�����ض���Դ��������Ϊָ���ļ�
	function DownMedia($dourl,$mtype='img',$islitpic=false)
	{
		global $notckpic;
		if(empty($notckpic))
		{
			$notckpic = 0;
		}

		//����Ƿ��Ѿ����ش��ļ�
		$wi = false;
		$tofile = $filename = '';
		if($notckpic==0)
		{
			$row = $this->dsql->GetOne("Select hash,tofile from `#@__co_mediaurls` where nid='{$this->noteId}' And hash='".md5($dourl)."' ");
			if(isset($row['tofile']))
			{
				$tofile = $filename = $row['tofile'];
			}
		}

		//��������ڣ������ļ�
		if($tofile=='' || !file_exists($GLOBALS['cfg_basedir'].$filename))
		{
			$filename = $this->GetRndName($dourl,$mtype);
			if(!ereg("^/",$filename))
			{
				$filename = "/".$filename;
			}

			//������ģʽ
			if($this->noteInfos['isref']=='yes' && $this->noteInfos['refurl']!='')
			{
				if($this->noteInfos['exptime']=='')
				{
					$this->noteInfos['exptime'] = 10;
				}
				DownImageKeep($dourl,$this->noteInfos['refurl'],$GLOBALS['cfg_basedir'].$filename,'',0,$this->Item['exptime']);
			}

			//��ͨģʽ
			else
			{
				$this->cHttpDown->OpenUrl($dourl);
				$this->cHttpDown->SaveToBin($GLOBALS['cfg_basedir'].$filename);
				$this->cHttpDown->Close();
			}

			//�����ļ��ɹ��������¼
			if(file_exists($GLOBALS['cfg_basedir'].$filename))
			{
				if($tofile=='')
				{
					$query = "INSERT INTO `#@__co_mediaurls`(nid,hash,tofile) VALUES ('".$this->noteId."', '".md5($dourl)."', '".addslashes($filename)."');";
				}
				else
				{
					$query = "Update `#@__co_mediaurls` set tofile='".addslashes($filename)."' where hash='".md5($dourl)."' ";
				}
				$this->dsql->ExecuteNoneQuery($query);
			}
		}

		//�������ͼƬʧ�ܻ�ͼƬ�����ڣ�������ַ
		if(!file_exists($GLOBALS['cfg_basedir'].$filename))
		{
			return $dourl;
		}

		//��������ͼ
		if($mtype=='img' && !$islitpic && $this->breImage=='')
		{
			$this->breImage = $filename;
			if(!eregi("^http://",$this->breImage) && file_exists($GLOBALS['cfg_basedir'].$filename))
			{
				$filenames = explode('/',$filename);
				$filenamed = $filenames[count($filenames)-1];
				$nfilename = str_replace('.','_lit.',$filenamed);
				$nfilename = str_replace($filenamed,$nfilename,$filename);
				if(@copy($GLOBALS['cfg_basedir'].$filename,$GLOBALS['cfg_basedir'].$nfilename))
				{
					ImageResize($GLOBALS['cfg_basedir'].$nfilename,$GLOBALS['cfg_ddimg_width'],$GLOBALS['cfg_ddimg_height']);
					$this->breImage = $nfilename;
				}
			}
		}
		if($mtype=='img' && !$islitpic)
		{
			@WaterImg($GLOBALS['cfg_basedir'].$filename,'collect');
		}
		return $filename;
	}

	//�������ý����������
	function GetRndName($url,$v)
	{
		global $cfg_image_dir,$cfg_dir_purview;
		$this->mediaCount++;
		$mnum = $this->mediaCount;
		$timedir = "c".MyDate("ymd",time());
		//���·��
		$fullurl = preg_replace("/\/{1,}/","/",$cfg_image_dir."/");
		if(!is_dir($GLOBALS['cfg_basedir']."/$fullurl"))
		{
			MkdirAll($GLOBALS['cfg_basedir']."/$fullurl",$cfg_dir_purview);
		}

		$fullurl = $fullurl.$timedir."/";
		if(!is_dir($GLOBALS['cfg_basedir']."/$fullurl"))
		{
			MkdirAll($GLOBALS['cfg_basedir']."/$fullurl",$cfg_dir_purview);
		}

		//�ļ�����
		$timename = str_replace('.','',ExecTime());
		$threadnum = 0;
		if(isset($_GET['threadnum']))
		{
			$threadnum = intval($_GET['threadnum']);
		}
		$filename = dd2char($timename.$threadnum.'-'.$mnum.mt_rand(1000,9999));

		//������չ��
		$urls = explode('.',$url);
		if($v=='img')
		{
			$shortname = '.jpg';
			if(eregi("\.gif$",$url))
			{
				$shortname = '.gif';
			}
			else if(eregi("\.png$",$url))
			{
				$shortname = '.png';
			}
		}
		else if($v=='embed')
		{
			$shortname = '.swf';
		}
		else
		{
			$shortname = '';
		}
		$fullname = $fullurl.$filename.$shortname;
		return preg_replace("/\/{1,}/","/",$fullname);
	}

	//���������ҳ���ݻ�ȡ���򣬴�һ��HTML�ļ��л�ȡ����
	function GetPageFields($dourl,$needDown,$litpic='')
	{
		global $cfg_auot_description;
		if($this->tmpHtml == '')
		{
			return '';
		}
		$artitem = '';
		$isPutUnit = false;
		$tmpLtKeys = array();
		$inarr = array();

		//�Զ������ؼ��ֺ�ժҪ
		preg_match("/<meta[\s]+name=['\"]keywords['\"] content=['\"](.*)['\"]/isU",$this->tmpHtml,$inarr);
		preg_match("/<meta[\s]+content=['\"](.*)['\"] name=['\"]keywords['\"]/isU",$this->tmpHtml,$inarr2);
		if(!isset($inarr[1]) && isset($inarr2[1]))
		{
			$inarr[1] = $inarr2[1];
		}
		if(isset($inarr[1]))
		{
			$keywords = trim(cn_substr(html2text($inarr[1]),30));
			$keywords = preg_replace("/".$this->artNotes['keywordtrim']."/isU",'',$keywords);
			if(!ereg(',',$keywords))
			{
				$keywords = str_replace(' ',',',$keywords);
			}
			$artitem .= "{dede:field name='keywords'}".$keywords."{/dede:field}\r\n";
		}
		else
		{
			$artitem .= "{dede:field name='keywords'}{/dede:field}\r\n";
		}
		preg_match("/<meta[\s]+name=['\"]description['\"] content=['\"](.*)['\"]/isU",$this->tmpHtml,$inarr);
		preg_match("/<meta[\s]+content=['\"](.*)['\"] name=['\"]description['\"]/isU",$this->tmpHtml,$inarr2);
		if(!isset($inarr[1]) && isset($inarr2[1]))
		{
			$inarr[1] = $inarr2[1];
		}
		if(isset($inarr[1]))
		{
			$description = trim(cn_substr(html2text($inarr[1]),$cfg_auot_description));
			$description = preg_replace("/".$this->artNotes['descriptiontrim']."/isU",'',$description);
			$artitem .= "{dede:field name='description'}".$description."{/dede:field}\r\n";
		}
		else
		{
			$artitem .= "{dede:field name='description'}{/dede:field}\r\n";
		}

		foreach($this->artNotes as $k=>$sarr)
		{
			//���ܳ�����������
			if($k=='sppage' || $k=='sptype')
			{
				continue;
			}
			if(!is_array($sarr))
			{
				continue;
			}

			//����Ĺ����ûƥ��ѡ��
			if($sarr['match']=='' || trim($sarr['match'])=='[����]')
			{
				if($sarr['value']!='[����]')
				{
					$v = trim($sarr['value']);
				}
				else
				{
					$v = '';
				}
			}
			else
			{
				//�ֶ�ҳ������
				if($this->tmpUnitValue!='' && !$isPutUnit && $sarr['isunit']==1)
				{
					$v = $this->tmpUnitValue;
					$isPutUnit = true;
				}
				else
				{
					$v = $this->GetHtmlArea('[����]',$sarr['match'],$this->tmpHtml);
				}

				//�������ݹ���
				if(isset($sarr['trim']) && $v!='')
				{
					foreach($sarr['trim'] as $nv)
					{
						if($nv[0]=='')
						{
							continue;
						}
						$nvs = str_replace("/","\\/",$nv[0]);
						$v = preg_replace("/".$nvs."/isU",$nv[1],$v);
					}
				}

				//�Ƿ�����Զ����Դ
				if($needDown)
				{
					if($sarr['isdown'] == '1')
					{
						$v = $this->DownMedias($v,$dourl);
					}
				}
				else
				{
					if($sarr['isdown'] == '1')
					{
						$v = $this->MediasReplace($v,$dourl);
					}
				}
			}
			$v = trim($v);

			//�û����ж����ݽ��д���Ľӿ�
			if($sarr['function'] != '')
			{
				$tmpLtKeys[$k]['v'] = $v;
				$tmpLtKeys[$k]['f'] = $sarr['function'];
			}
			else
			{
				$v = ereg_replace("(��)$",'',$v);
				$v = ereg_replace("[\r\n\t ]{1,}$",'',$v);
				$artitem .= "{dede:field name='$k'}$v{/dede:field}\r\n";
			}
		}//End Foreach

		//�������������Ŀ
		foreach($tmpLtKeys as $k=>$sarr)
		{
			$v = $this->RunPHP($sarr['v'],$sarr['f']);
			$v = ereg_replace("(��)$",'',$v);
			$v = ereg_replace("[\r\n\t ]{1,}$",'',$v);
			$artitem .= "{dede:field name='$k'}$v{/dede:field}\r\n";
		}
		if($litpic!='' && $this->lists['listpic']==1)
		{
			$artitem .= "{dede:field name='litpic'}".$this->DownMedia($litpic,'img',true)."{/dede:field}\r\n";
		}
		else
		{
			$artitem .= "{dede:field name='litpic'}".$this->breImage."{/dede:field}\r\n";
		}
		return $artitem;
	}

	//�������������Դ
	function DownMedias(&$html,$url)
	{
		$this->cDedeHtml->SetSource($html,$url,'media');

		//���ر�����ͼƬ��flash
		foreach($this->cDedeHtml->Medias as $k=>$v)
		{
			$furl = $this->cDedeHtml->FillUrl($k);
			if($v=='embed' && !eregi("\.(swf)\?(.*)$",$k)&& !eregi("\.(swf)$",$k))
			{
				continue;
			}
			$okurl = $this->DownMedia($furl,$v);
			$html = str_replace($k,$okurl,$html);
		}

		//���س��������ͼƬ
		foreach($this->cDedeHtml->Links as $v=>$k)
		{
			if(eregi("\.(jpg|gif|png)\?(.*)$",$v) || eregi("\.(jpg|gif|png)$",$v))
			{
				$m = "img";
			}
			else if(eregi("\.(swf)\?(.*)$",$v) || eregi("\.(swf)$",$v))
			{
				$m = "embed";
			}
			else
			{
				continue;
			}
			$furl = $this->cDedeHtml->FillUrl($v);
			$okurl = $this->DownMedia($furl,$m);
			$html = str_replace($v,$okurl,$html);
		}
		return $html;
	}

	//���滻���������ԴΪ������ַ
	function MediasReplace(&$html,$dourl)
	{
		$this->cDedeHtml->SetSource($html,$dourl,'media');
		foreach($this->cDedeHtml->Medias as $k=>$v)
		{
			$k = trim($k);
			$okurl = $this->cDedeHtml->FillUrl($k);
			$html = str_replace($k,$okurl,$html);
		}
		return $html;
	}

	//�����б�
	function Testlists(&$dourl)
	{
		$links = array();

		//��RSS�л�ȡ��ַ
		if($this->lists['sourcetype']=='rss')
		{
			$dourl = $this->lists['rssurl'];
			$links = GetRssLinks($dourl);
			return $links;
		}

		//�������
		if(isset($this->lists['url'][0][0]))
		{
			$dourl = $this->lists['url'][0][0];
		}
		else
		{
			$dourl = '';
			$this->errString = "������ָ���б����ַ����!\r\n";
			return $links;
		}
		$dhtml = new DedeHtml2();
		$html = $this->DownOnePage($dourl);
		if($html=='')
		{
			$this->errString = "��ȡ��ַ�� $dourl ʱʧ�ܣ�\r\n";
			return $links;
		}
		if( trim($this->lists['areastart']) !='' && trim($this->lists['areaend']) != '' )
		{
			$areabody = $this->lists['areastart'].'[var:����]'.$this->lists['areaend'];
			$html = $this->GetHtmlArea('[var:����]',$areabody,$html);
		}
		$t1 = ExecTime();
		$dhtml->SetSource($html,$dourl,'link');
		foreach($dhtml->Links as $s)
		{
			if($this->lists['nothas']!='')
			{
				if( eregi($this->lists['nothas'],$s['link']) )
				{
					continue;
				}
			}
			if($this->lists['musthas']!='')
			{
				if( !eregi($this->lists['musthas'],$s['link']) )
				{
					continue;
				}
			}
			$links[] = $s;
		}
		return $links;
	}

	//�������¹���
	function TestArt($dourl)
	{
		return $this->DownUrl(0,$dourl,'',false);
	}

	//�ɼ�������ַ
	function GetSourceUrl($islisten=0,$glstart=0,$pagesize=10)
	{
		//�ڵ�һҳ�н���Ԥ����
		//������������ַ��δ�������ݡ���ģʽ����Ҫ�����ɼ�������ַ�Ĳ���
		if($glstart==0)
		{
			//���²ɼ���������ģʽ
			if($islisten == -1)
			{
				$this->dsql->ExecuteNoneQuery("Delete From `#@__co_urls` where nid='".$this->noteId."'");
				$this->dsql->ExecuteNoneQuery("Delete From `#@__co_htmls` where nid='".$this->noteId."' ");
			}
			//����ģʽ(����δ���������ݡ������ڵ����ʷ��ַ��¼)
			else
			{
				$this->dsql->ExecuteNoneQuery("Delete From `#@__co_htmls` where nid='".$this->noteId."' And isexport=1 ");
			}
		}

		//��RSS�л�ȡ����
		if($this->lists['sourcetype']=='rss')
		{
			$links = GetRssLinks($this->lists['rssurl']);
			//if($this->noteInfos['cosort']!='asc')
			$tmplink = krsort($links);
			foreach($links as $v)
			{
				if($islisten==1)
				{
					$lrow = $this->dsql->GetOne("Select * From `#@__co_urls` where nid='{$this->noteId}' And hash='".md5($v['link'])."' ");
					if(is_array($lrow))
					{
						continue;
					}
				}

				$inquery = "INSERT INTO `#@__co_htmls` (`nid` ,`typeid`, `title` , `litpic` , `url` , `dtime` , `isdown` , `isexport` , `result`)
                    VALUES ('{$this->noteId}' , '0', '".addslashes($v['title'])."' , '".addslashes($v['image'])."' , '".addslashes($v['link'])."' , 'dtime' , '0' , '0' , ''); ";
				$this->dsql->ExecuteNoneQuery($inquery);

				$inquery = "INSERT INTO `#@__co_urls`(hash,nid) VALUES ('".md5($v['link'])."','{$this->noteId}');";
				$this->dsql->ExecuteNoneQuery($inquery);
			}
			return 0;
		}
		else
		{
			$tmplink = array();
			$arrStart = 0;
			$moviePostion = 0;
			$endpos = $glstart + $pagesize;
			$totallen = count($this->lists['url']);
			foreach($this->lists['url'] as $k=>$cururls)
			{
				$cururl = $cururls[0];
				$typeid = (empty($cururls[1]) ? 0 : $cururls[1]);
				$moviePostion++;
				if($moviePostion > $endpos)
				{
					break;
				}
				if($moviePostion > $glstart)
				{
					$html = $this->DownOnePage($cururl);
					if( trim($this->lists['areastart']) !='' && trim($this->lists['areaend']) != '' )
					{
						$areabody = $this->lists['areastart'].'[var:����]'.$this->lists['areaend'];
						$html = $this->GetHtmlArea('[var:����]',$areabody,$html);
					}
					$this->cDedeHtml->SetSource($html,$cururl,'link');
					$lk = 0;
					foreach($this->cDedeHtml->Links as $k=>$v)
					{
						if($this->lists['nothas']!='')
						{
							if( eregi($this->lists['nothas'],$v['link']) )
							{
								continue;
							}
						}
						if($this->lists['musthas']!='')
						{
							if( !eregi($this->lists['musthas'],$v['link']) )
							{
								continue;
							}
						}
						$tmplink[$arrStart][0] = $v;
						$tmplink[$arrStart][1] = $typeid;
						$arrStart++;
						$lk++;
					}
					$this->cDedeHtml->Clear();
				}
			}//foreach
			//if($this->noteInfos['cosort']!='asc')

			krsort($tmplink);
			$unum = count($tmplink);
			if($unum>0)
			{
				//echo "��ɱ���������ַץȡ�����ҵ���{$unum} ����¼!<br/>\r\n";
				foreach($tmplink as $vs)
				{
					$v = $vs[0];
					$typeid = $vs[1];
					if($islisten==1)
					{
						$lrow = $this->dsql->GetOne("Select * From `#@__co_urls` where nid='{$this->noteId}' And hash='".md5($v['link'])."' ");
						if(is_array($lrow))
						{
							continue;
						}
					}
					$inquery = "INSERT INTO `#@__co_htmls` (`nid` ,`typeid`, `title` , `litpic` , `url` , `dtime` , `isdown` , `isexport` , `result`)
                    VALUES ('{$this->noteId}' ,'$typeid', '".addslashes($v['title'])."' , '".addslashes($v['image'])."' , '".addslashes($v['link'])."' , '".time()."' , '0' , '0' , ''); ";
					$this->dsql->ExecuteNoneQuery($inquery);

					$inquery = "INSERT INTO `#@__co_urls`(hash,nid) VALUES ('".md5($v['link'])."','{$this->noteId}');";
					$this->dsql->ExecuteNoneQuery($inquery);
				}
				if($endpos >= $totallen)
				{
					return 0;
				}
				else
				{
					return ($totallen-$endpos);
				}
			}
			else
			{
				//���ڵ�һ���ɼ�ʱ����ŷ���
				if($glstart==0)
				{
					return -1;
				}

				//������ҳ�����ճ��ɼ���������
				if($endpos >= $totallen)
				{
					return 0;
				}
				else
				{
					return ($totallen-$endpos);
				}
			}
		}
	}

	//����չ��������ɼ�����ԭʼ����
	function RunPHP($fvalue,$phpcode)
	{
		$DedeMeValue = $fvalue;
		$phpcode = preg_replace("/'@me'|\"@me\"|@me/isU",'$DedeMeValue',$phpcode);
		if(eregi('@body',$phpcode))
		{
			$DedeBodyValue = $this->tmpHtml;
			$phpcode = preg_replace("/'@body'|\"@body\"|@body/isU",'$DedeBodyValue',$phpcode);
		}
		if(eregi('@litpic',$phpcode))
		{
			$DedeLitPicValue = $this->breImage;
			$phpcode = preg_replace("/'@litpic'|\"@litpic\"|@litpic/isU",'$DedeLitPicValue',$phpcode);
		}
		eval($phpcode.";");
		return $DedeMeValue;
	}

	//����ת��
	function ChangeCode(&$str)
	{
		global $cfg_soft_lang;
		if($cfg_soft_lang=='utf-8')
		{
			if($this->noteInfos["sourcelang"]=="gb2312")
			{
				$str = gb2utf8($str);
			}
			if($this->noteInfos["sourcelang"]=="big5")
			{
				$str = gb2utf8(big52gb($str));
			}
		}
		else
		{
			if($this->noteInfos["sourcelang"]=="utf-8")
			{
				$str = utf82gb($str);
			}
			if($this->noteInfos["sourcelang"]=="big5")
			{
				$str = big52gb($str);
			}
		}
	}
}
?>
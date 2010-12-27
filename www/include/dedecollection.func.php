<?php
if(!defined('DEDEINC'))
{
	exit('dedecms');
}
require_once(DEDEINC."/dedehttpdown.class.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/charset.func.php");

function DownImageKeep($gurl,$rfurl,$filename,$gcookie="",$JumpCount=0,$maxtime=30)
{
	$urlinfos = GetHostInfo($gurl);
	$ghost = trim($urlinfos['host']);
	if($ghost=='')
	{
		return false;
	}
	$gquery = $urlinfos['query'];
	if($gcookie=="" && !empty($rfurl))
	{
		$gcookie = RefurlCookie($rfurl);
	}
	$sessionQuery = "GET $gquery HTTP/1.1\r\n";
	$sessionQuery .= "Host: $ghost\r\n";
	$sessionQuery .= "Referer: $rfurl\r\n";
	$sessionQuery .= "Accept: */*\r\n";
	$sessionQuery .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";
	if($gcookie!=""&&!ereg("[\r\n]",$gcookie))
	{
		$sessionQuery .= $gcookie."\r\n";
	}
	$sessionQuery .= "Connection: Keep-Alive\r\n\r\n";
	$errno = "";
	$errstr = "";
	$m_fp = fsockopen($ghost, 80, $errno, $errstr,10);
	fwrite($m_fp,$sessionQuery);
	$lnum = 0;

	//��ȡ��ϸӦ��ͷ
	$m_httphead = Array();
	$httpstas = explode(" ",fgets($m_fp,256));
	$m_httphead["http-edition"] = trim($httpstas[0]);
	$m_httphead["http-state"] = trim($httpstas[1]);
	while(!feof($m_fp))
	{
		$line = trim(fgets($m_fp,256));
		if($line == "" || $lnum>100)
		{
			break;
		}
		$hkey = "";
		$hvalue = "";
		$v = 0;
		for($i=0;$i<strlen($line);$i++)
		{
			if($v==1)
			{
				$hvalue .= $line[$i];
			}
			if($line[$i]==":")
			{
				$v = 1;
			}
			if($v==0)
			{
				$hkey .= $line[$i];
			}
		}
		$hkey = trim($hkey);
		if($hkey!="")
		{
			$m_httphead[strtolower($hkey)] = trim($hvalue);
		}
	}

	//�������ؼ�¼
	if(ereg("^3",$m_httphead["http-state"]))
	{
		if(isset($m_httphead["location"]) && $JumpCount<3)
		{
			$JumpCount++;
			DownImageKeep($gurl,$rfurl,$filename,$gcookie,$JumpCount);
		}
		else
		{
			return false;
		}
	}
	if(!ereg("^2",$m_httphead["http-state"]))
	{
		return false;
	}
	if(!isset($m_httphead))
	{
		return false;
	}
	$contentLength = $m_httphead['content-length'];

	//�����ļ�
	$fp = fopen($filename,"w") or die("д���ļ���{$filename} ʧ�ܣ�");
	$i=0;
	$okdata = "";
	$starttime = time();
	while(!feof($m_fp))
	{
		$okdata .= fgetc($m_fp);
		$i++;

		//��ʱ����
		if(time()-$starttime>$maxtime)
		{
			break;
		}

		//����ָ����С����
		if($i >= $contentLength)
		{
			break;
		}
	}
	if($okdata!="")
	{
		fwrite($fp,$okdata);
	}
	fclose($fp);
	if($okdata=="")
	{
		@unlink($filename);
		fclose($m_fp);
		return false;
	}
	fclose($m_fp);
	return true;
}

//���ĳҳ�淵�ص�Cookie��Ϣ
function RefurlCookie($gurl)
{
	global $gcookie,$lastRfurl;
	$gurl = trim($gurl);
	if(!empty($gcookie) && $lastRfurl==$gurl)
	{
		return $gcookie;
	}
	else
	{
		$lastRfurl=$gurl;
	}
	if(trim($gurl)=='')
	{
		return '';
	}
	$urlinfos = GetHostInfo($gurl);
	$ghost = $urlinfos['host'];
	$gquery = $urlinfos['query'];
	$sessionQuery = "GET $gquery HTTP/1.1\r\n";
	$sessionQuery .= "Host: $ghost\r\n";
	$sessionQuery .= "Accept: */*\r\n";
	$sessionQuery .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";
	$sessionQuery .= "Connection: Close\r\n\r\n";
	$errno = "";
	$errstr = "";
	$m_fp = fsockopen($ghost, 80, $errno, $errstr,10) or die($ghost.'<br />');
	fwrite($m_fp,$sessionQuery);
	$lnum = 0;

	//��ȡ��ϸӦ��ͷ
	$gcookie = "";
	while(!feof($m_fp))
	{
		$line = trim(fgets($m_fp,256));
		if($line == "" || $lnum>100)
		{
			break;
		}
		else
		{
			if(eregi("^cookie",$line))
			{
				$gcookie = $line;
				break;
			}
		}
	}
	fclose($m_fp);
	return $gcookie;
}

//�����ַ��host��query����
function GetHostInfo($gurl)
{
	$gurl = eregi_replace("^http://","",trim($gurl));
	$garr['host'] = eregi_replace("/(.*)$","",$gurl);
	$garr['query'] = "/".eregi_replace("^([^/]*)/","",$gurl);
	return $garr;
}

//HTML���ͼƬתDEDE��ʽ
function TurnImageTag(&$body)
{
	global $cfg_album_width,$cfg_ddimg_width;
	if(empty($cfg_album_width))
	{
		$cfg_album_width = 800;
	}
	if(empty($cfg_ddimg_width))
	{
		$cfg_ddimg_width = 150;
	}
	$patten = "/<\\s*img\\s.*?src\\s*=\\s*([\"\\'])?(?(1)(.*?)\\1|([^\\s\\>\"\\']+))/isx";
  preg_match_all($patten,$body,$images);
  $returnArray1 = $images[2];
  $returnArray2 = $images[3];
  foreach ( $returnArray1 as $key => $value )
  {
      if ($value)
      {
        $ttx .= "{dede:img ddimg='$litpicname' text='ͼ ".($key+1)."'}".$value."{/dede:img}"."\r\n";
      }
      else
      {
        $ttx .= "{dede:img ddimg='$litpicname' text='ͼ ".($key+1)."'}".$returnArray2[$key]."{/dede:img}"."\r\n";
      }
  }
	$ttx = "\r\n{dede:pagestyle maxwidth='{$cfg_album_width}' ddmaxwidth='{$cfg_ddimg_width}' row='3' col='3' value='2'/}\r\n{dede:comments}ͼ�����ͻ�ɼ�ʱ���ɴ������������ģ������������û�и���img������ʾ������Ч{/dede:comments}\r\n".$ttx;
	return $ttx;
}

//HTML�����ַ��ʽת��
function TurnLinkTag(&$body)
{
	$ttx = '';
	$handid = '������';
	preg_match_all("/<a href=['\"](.+?)['\"]([^>]+?)>(.+?)<\/a>/is",$body,$match);
	if(is_array($match[1]) && count($match[1])>0)
	{
		for($i=0;isset($match[1][$i]);$i++)
		{
			$servername = (isset($match[3][$i]) ? str_replace("'","`",$match[3][$i]) : $handid.($i+1));
			if(ereg("[<>]",$servername) || strlen($servername)>40)
			{
				$servername = $handid.($i+1);
			}
			$ttx .= "{dede:link text='$servername'} {$match[1][$i]} {/dede:link}\r\n";
		}
	}
	return $ttx;
}

//�滻XML��CDATA
function RpCdata($str)
{
	$str = str_replace('<![CDATA[','',$str);
	$str = str_replace(']]>','',$str);
	return  $str;
}

//����RSS�������
function GetRssLinks($rssurl)
{
	global $cfg_soft_lang;
	$dhd = new DedeHttpDown();
	$dhd->OpenUrl($rssurl);
	$rsshtml = $dhd->GetHtml();

	//��������
	preg_match("/encoding=[\"']([^\"']*)[\"']/is",$rsshtml,$infos);
	if(isset($infos[1]))
	{
		$pcode = strtolower(trim($infos[1]));
	}
	else
	{
		$pcode = strtolower($cfg_soft_lang);
	}
	if($cfg_soft_lang=='gb2312')
	{
		if($pcode=='utf-8')
		{
			$rsshtml = utf82gb($rsshtml);
		}
		else if($pcode=='big5')
		{
			$rsshtml = big52gb($rsshtml);
		}
	}
	else if($cfg_soft_lang=='utf-8')
	{
		if($pcode=='gbk'||$pcode=='gb2312')
		{
			$rsshtml = gb2utf8($rsshtml);
		}
		else if($pcode=='big5')
		{
			$rsshtml = gb2utf8(big52gb($rsshtml));
		}
	}
	$rsarr = array();
	preg_match_all("/<item(.*)<title>(.*)<\/title>/isU",$rsshtml,$titles);
	preg_match_all("/<item(.*)<link>(.*)<\/link>/isU",$rsshtml,$links);
	preg_match_all("/<item(.*)<description>(.*)<\/description>/isU",$rsshtml,$descriptions);
	if(!isset($links[2]))
	{
		return '';
	}
	foreach($links[2] as $k=>$v)
	{
		$rsarr[$k]['link'] = RpCdata($v);

		if(isset($titles[2][$k]))
		{
			$rsarr[$k]['title'] = RpCdata($titles[2][$k]);
		}
		else
		{
			$rsarr[$k]['title'] = ereg_replace("^(.*)/","",RpCdata($titles[2][$k]));
		}
		if(isset($descriptions[2][$k]))
		{
			$rsarr[$k]['image'] = GetddImgFromRss($descriptions[2][$k],$rssurl);
		}
		else
		{
			$rsarr[$k]['image'] = '';
		}
	}
	return $rsarr;
}

//��RSSժҪ��ȡͼƬ��Ϣ
function GetddImgFromRss($descriptions,$refurl)
{
	if($descriptions=='')
	{
		return '';
	}
	preg_match_all("/<img(.*)src=[\"']{0,1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU",$descriptions,$imgs);
	if(isset($imgs[2][0]))
	{
		$imgs[2][0] = ereg_replace("[\"']",'',$imgs[2][0]);
		$imgs[2][0] = ereg_replace("/{1,}",'/',$imgs[2][0]);
		return FillUrl($refurl,$imgs[2][0]);
	}
	else
	{
		return '';
	}
}

//��ȫ��ַ
function FillUrl($refurl,$surl)
{
	$i = $pathStep = 0;
	$dstr = $pstr = $okurl = '';
	$refurl = trim($refurl);
	$surl = trim($surl);
	$urls = @parse_url($refurl);
	$basehost = ( (!isset($urls['port']) || $urls['port']=='80') ? $urls['host'] : $urls['host'].':'.$urls['port']);

	//$basepath = $basehost.(!isset($urls['path']) ? '' : '/'.$urls['path']);
	//����ֱ�ӻ�õ�path�ڴ��� http://xxxx/nnn/aaa?fdsafd �������ʱ���д��������������ʽ����
	$basepath = $basehost;
	$paths = explode('/',eregi_replace("^http://","",$refurl));
	$n = count($paths);
	for($i=1;$i < ($n-1);$i++)
	{
		if(!ereg("[\?]",$paths[$i])) $basepath .= '/'.$paths[$i];
	}
	if(!ereg("[\?\.]",$paths[$n-1]))
	{
		$basepath .= '/'.$paths[$n-1];
	}
	if($surl=='')
	{
		return $basepath;
	}
	$pos = strpos($surl,"#");
	if($pos>0)
	{
		$surl = substr($surl,0,$pos);
	}

	//�� '/' ��ʾ��վ������ַ
	if($surl[0]=='/')
	{
		$okurl = $basehost.$surl;
	}
	else if($surl[0]=='.')
	{
		if(strlen($surl)<=2)
		{
			return '';
		}
		else if($surl[1]=='/')
		{
			$okurl = $basepath.ereg_replace('^.','',$surl);
		}
		else
		{
			$okurl = $basepath.'/'.$surl;
		}
	}
	else
	{
		if( strlen($surl) < 7 )
		{
			$okurl = $basepath.'/'.$surl;
		}
		else if( eregi('^http://',$surl) )
		{
			$okurl = $surl;
		}
		else
		{
			$okurl = $basepath.'/'.$surl;
		}
	}
	$okurl = eregi_replace('^http://','',$okurl);
	$okurl = 'http://'.eregi_replace('/{1,}','/',$okurl);
	return $okurl;
}

//��ƥ������л�ȡ�б���ַ
function GetUrlFromListRule($regxurl='',$handurl='',$startid=0,$endid=0,$addv=1,$usemore=0,$batchrule='')
{
	global $dsql,$islisten;

	$lists = array();
	$n = 0;
	$islisten = (empty($islisten) ? 0 : $islisten);
	if($handurl!='')
	{
		$handurls = explode("\n",$handurl);
		foreach($handurls as $handurl)
		{
			$handurl = trim($handurl);
			if(eregi("^http://",$handurl))
			{
				$lists[$n][0] = $handurl;
				$lists[$n][1] = 0;
				$n++;
				if($islisten==1)
				{
					break;
				}
			}
		}
	}
	if($regxurl!='')
	{
		//ûָ��(#)��(*)
		if(!ereg("\(\*\)",$regxurl) && !ereg("\(#\)",$regxurl))
		{
			$lists[$n][0] = $regxurl;
			$lists[$n][1] = 0;
			$n++;
		}
		else
		{
			if($addv <= 0)
			{
				$addv = 1;
			}

			//ûָ������Ŀƥ�����
			if($usemore==0)
			{
				while($startid <= $endid)
				{
					$lists[$n][0] = str_replace("(*)",sprintf('%0'.strlen($startid).'d',$startid),$regxurl);
					$lists[$n][1] = 0;
					$startid = sprintf('%0'.strlen($startid).'d',$startid + $addv);
					$n++;
					if($n>2000 || $islisten==1)
					{
						break;
					}
				}
			}

			//ƥ������Ŀ
			//������ʽ [(#)=>(#)ƥ�����ַ; (*)=>(*)�ķ�Χ���磺1-20; typeid=>��Ŀid; addurl=>���ӵ���ַ(��|�ֿ����)]
			else
			{
				$nrules = explode(']',trim($batchrule));
				foreach($nrules as $nrule)
				{
					$nrule = trim($nrule);
					$nrule = ereg_replace("^\[|\]$",'',$nrule);
					$nrules  = explode(';',$nrule);
					if(count($nrules)<3)
					{
						continue;
					}
					$brtag = '';
					$startid = 0;
					$endid = 0;
					$typeid = 0;
					$addurls = array();
					foreach($nrules as $nrule)
					{
						$nrule = trim($nrule);
						list($k,$v) = explode('=>',$nrule);
						if(trim($k)=='(#)')
						{
							$brtag = trim($v);
						}
						else if(trim($k)=='typeid')
						{
							$typeid = trim($v);
						}
						else if(trim($k)=='addurl')
						{
							$addurl = trim($v);
							$addurls = explode('|',$addurl);
						}
						else if(trim($k)=='(*)')
						{
							$v = ereg_replace("[ \r\n\t]",'',trim($v));
							list($startid,$endid) = explode('-',$v);
						}
					}

					//�����Ŀ����Ŀ����
					if(ereg('[^0-9]',$typeid))
					{
						$arr = $dsql->GetOne("Select id From `#@__arctype` where typename like '$typeid' ");
						if(is_array($arr))
						{
							$typeid = $arr['id'];
						}
						else
						{
							$typeid = 0;
						}
					}

					//������ַ����
					$mjj = 0;
					if(isset($addurls[0]))
					{
						foreach($addurls as $addurl)
						{
							$addurl = trim($addurl);
							if($addurl=='')
							{
								continue;
							}
							$lists[$n][0] = $addurl;
							$lists[$n][1] = $typeid;
							$n++;
							$mjj++;
							if($islisten==1)
							{
								break;
							}
						}
					}

					//���Ϊ�Ǽ���ģʽ�����ģʽû�ֹ�ָ���ĸ�����ַ
					if($islisten!=1 || $mjj==0 )
					{
						//ƥ����������ַ��ע��(#)����ַ��������ʹ��(*)��
						while($startid <= $endid)
						{
							$lists[$n][0] = str_replace("(#)",$brtag,$regxurl);
							$lists[$n][0] = str_replace("(*)",sprintf('%0'.strlen($startid).'d',$startid),$lists[$n][0]);
							$lists[$n][1] = $typeid;
							$startid = sprintf('%0'.strlen($startid).'d',$startid + $addv);
							$n++;
							if($islisten==1)
							{
								break;
							}
							if($n>20000)
							{
								break;
							}
						}
					}
				}
			} //End ƥ�����Ŀ

		} //Endʹ�ù���ƥ������

	}

	return $lists;
}
?>
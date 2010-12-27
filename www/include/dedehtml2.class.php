<?php
if(!defined('DEDEINC'))
{
	exit('dedecms');
}
/*******************************
* ֯��HTML������V1.6 PHP�� www.dedecms.com
* function c____DedeHtml2();
* ���������ڲɼ�������Ҫ�ǻ�ȡĳ�����ڵ�ͼƬ�������ӵ���Ϣ
* ����޸� 2008-7-8 ���޸�links��ȡ��ʽ����ʶ��link���Ƿ���ͼƬ��ȥ����ʹ�õ�ʶ��HTMLͷ�Ĺ���
********************************/
class DedeHtml2
{
	var $CAtt;
	var $SourceHtml;
	var $Title;
	var $Medias;
	var $MediaInfos;
	var $Links;
	var $CharSet;
	var $BaseUrl;
	var $BaseUrlPath;
	var $HomeUrl;
	var $IsHead;
	var $ImgHeight;
	var $ImgWidth;
	var $GetLinkType;

	//���캯��
	function __construct()
	{
		$this->CAtt = '';
		$this->SourceHtml = '';
		$this->Title = '';
		$this->Medias = Array();
		$this->MediaInfos = Array();
		$this->Links = Array();
		$this->BaseUrl = '';
		$this->BaseUrlPath = '';
		$this->HomeUrl = '';
		$this->IsHead = false;
		$this->ImgHeight = 30;
		$this->ImgWidth = 50;
		$this->GetLinkType = 'link';
	}

	function DedeHtml2()
	{
		$this->__construct();
	}

	//����HTML�����ݺ���Դ��ַ
	function SetSource(&$html,$url = '',$linktype='')
	{
		$this->__construct();
		$this->CAtt = new DedeAttribute2();
		$url = trim($url);
		$this->SourceHtml = $html;
		$this->BaseUrl = $url;
		//�ж��ĵ�����ڵ�ǰ��·��
		$urls = @parse_url($url);
		$this->HomeUrl = $urls['host'];
		$this->BaseUrlPath = $this->HomeUrl.$urls['path'];
		$this->BaseUrlPath = preg_replace("/\/([^\/]*)\.(.*)$/","/",$this->BaseUrlPath);
		$this->BaseUrlPath = preg_replace("/\/$/",'',$this->BaseUrlPath);
		if($linktype!='')
		{
			$this->GetLinkType = $linktype;
		}
		if($html != '')
		{
			$this->Analyser();
		}
	}

	//����HTML
	function Analyser()
	{
		$cAtt = new DedeAttribute2();
		$cAtt->IsTagName = false;
		$c = '';
		$i = 0;
		$startPos = 0;
		$endPos = 0;
		$wt = 0;
		$ht = 0;
		$scriptdd = 0;
		$attStr = '';
		$tmpValue = '';
		$tmpValue2 = '';
		$tagName = '';
		$hashead = 0;
		$slen = strlen($this->SourceHtml);
		if($this->GetLinkType=='link' || $this->GetLinkType=='')
		{
			$needTags = array('a');
		}
		if($this->GetLinkType=='media')
		{
			$needTags = array('img','embed','a');
			$this->IsHead = true;
		}
		$tagbreaks = array(' ','<','>',"\r","\n","\t");
		for(;isset($this->SourceHtml[$i]);$i++)
		{
			if($this->SourceHtml[$i]=='<')
			{
				$tagName = '';
				$j = 0;
				for($i=$i+1; isset($this->SourceHtml[$i]); $i++)
				{
					if($j>10)
					{
						break;
					}
					$j++;
					if( in_array($this->SourceHtml[$i],$tagbreaks) )
					{
						break;
					}
					else
					{
						$tagName .= $this->SourceHtml[$i];
					}
				}
				$tagName = strtolower($tagName);

				//���Ϊע��
				if($tagName=='!--')
				{
					$endPos = strpos($this->SourceHtml,'-->',$i);
					if($endPos !== false)
					{
						$i=$endPos+3;
					}
					continue;
				}

				//�����ָ��������
				else if( in_array($tagName,$needTags) )
				{
					$startPos = $i;
					$endPos = strpos($this->SourceHtml,'>',$i+1);
					if($endPos===false)
					{
						break;
					}
					$attStr = substr($this->SourceHtml,$i+1,$endPos-$startPos-1);
					$cAtt->SetSource($attStr);
					if($tagName=='img')
					{
						$this->InsertMedia($cAtt->GetAtt('src'),'img');
					}
					else if($tagName=='embed')
					{
						$rurl = $this->InsertMedia($cAtt->GetAtt('src'),'embed');
						if($rurl != '')
						{
							$this->MediaInfos[$rurl][0] = $cAtt->GetAtt('width');
							$this->MediaInfos[$rurl][1] = $cAtt->GetAtt('height');
						}
					}
					else if($tagName=='a')
					{
						$this->InsertLink($this->FillUrl($cAtt->GetAtt('href')),$this->GetInnerText($i,'a'));
					}
				}
				else
				{
					continue;
				}
			}//End if char

		}//End for

		if($this->Title == '')
		{
			$this->Title = $this->BaseUrl;
		}
	}

	//������Դ
	function Clear()
	{
		$this->CAtt = '';
		$this->SourceHtml = '';
		$this->Title = '';
		$this->Links = '';
		$this->Medias = '';
		$this->BaseUrl = '';
		$this->BaseUrlPath = '';
	}

	//����ý������
	function InsertMedia($url,$mtype)
	{
		if( ereg("^(javascript:|#|'|\")",$url) )
		{
			return '';
		}
		if($url == '')
		{
			return '';
		}
		$this->Medias[$url]=$mtype;
		return $url;
	}

	function InsertLink($url,$atitle)
	{
		if( ereg("^(javascript:|#|'|\")",$url) )
		{
			return '';
		}
		if($url == '')
		{
			return '';
		}
		if(ereg('^img:',$atitle))
		{
			list($aimg,$atitle) = explode(':txt:',$atitle);
			if(!isset($this->Links[$url]))
			{
				if($atitle != '')
				{
					$this->Links[$url]['title'] = cn_substr($atitle,50);
				}
				else
				{
					$this->Links[$url]['title'] = ereg_replace('img:','',$aimg);
				}
				$this->Links[$url]['link']  = $url;
			}
			$this->Links[$url]['image'] = ereg_replace('img:','',$aimg);
			$this->InsertMedia($this->Links[$url]['image'],'img');
		}
		else
		{
			if(!isset($this->Links[$url]))
			{
				$this->Links[$url]['image'] = '';
				$this->Links[$url]['title'] = $atitle;
				$this->Links[$url]['link']  = $url;
			}
			else
			{
				if(strlen($this->Links[$url]['title']) < strlen($atitle)) $this->Links[$url]['title'] = $atitle;
			}
		}
		return $url;
	}

	//����content-type�е��ַ�����
	function ParCharSet($att)
	{
		$startdd=0;
		$taglen=0;
		$startdd = strpos($att,'=');
		if($startdd===false)
		{
			return '';
		}
		else
		{
			$taglen = strlen($att)-$startdd-1;
			if($taglen<=0)
			{
				return '';
			}
			return trim(substr($att,$startdd+1,$taglen));
		}
	}

	//��ȫ�����ַ
	function FillUrl($surl)
	{
		$i = $pathStep = 0;
		$dstr = $pstr = $okurl = '';

		$surl = trim($surl);
		if($surl == '')
		{
			return '';
		}
		$pos = strpos($surl,'#');
		if($pos>0)
		{
			$surl = substr($surl,0,$pos);
		}
		if($surl[0]=='/')
		{
			$okurl = $this->HomeUrl.'/'.$surl;
		}
		else if($surl[0]=='.')
		{
			if(!isset($surl[2]))
			{
				return '';
			}
			else if($surl[0]=='/')
			{
				$okurl = $this->BaseUrlPath."/".substr($surl,2,strlen($surl)-2);
			}
			else
			{
				$urls = explode('/',$surl);
				foreach($urls as $u)
				{
					if($u=='..')
					{
						$pathStep++;
					}
					else if($i<count($urls)-1)
					{
						$dstr .= $urls[$i].'/';
					}
					else
					{
						$dstr .= $urls[$i];
					}
					$i++;
				}
				$urls = explode('/',$this->BaseUrlPath);
				if(count($urls) <= $pathStep)
				{
					return '';
				}
				else
				{
					$pstr = '';
					for($i=0;$i<count($urls)-$pathStep;$i++){ $pstr .= $urls[$i].'/'; }
					$okurl = $pstr.$dstr;
				}
			}
		}
		else
		{
			if( strlen($surl) < 7 )
			{
				$okurl = $this->BaseUrlPath.'/'.$surl;
			}
			else if( strtolower(substr($surl,0,7))=='http://' )
			{
				$okurl = eregi_replace('^http://','',$surl);
			}
			else
			{
				$okurl = $this->BaseUrlPath.'/'.$surl;
			}
		}
		$okurl = eregi_replace('/{1,}','/',$okurl);
		return 'http://'.$okurl;
	}

	//��ú���һ�����֮����ı�����
	function GetInnerText(&$pos,$tagname)
	{
		$startPos=0;
		$endPos=0;
		$textLen=0;
		$str = '';
		$startPos = strpos($this->SourceHtml,'>',$pos);

		if($tagname=='title')
		{
			$endPos = strpos($this->SourceHtml,'<',$startPos);
		}
		else
		{
			$endPos1 = strpos($this->SourceHtml,'</a',$startPos);
			$endPos2 = strpos($this->SourceHtml,'</A',$startPos);
			if($endPos1===false)
			{
				$endPos = $endPos2;
			}
			else if($endPos2===false)
			{
				$endPos = $endPos1;
			}
			else
			{
				$endPos = ($endPos1 < $endPos2 ? $endPos1 : $endPos2 );
			}
		}
		if($endPos > $startPos)
		{
			$textLen = $endPos-$startPos;
			$str = substr($this->SourceHtml,$startPos+1,$textLen-1);
		}
		$pos = $startPos + $textLen + strlen("</".$tagname) + 1;
		if($tagname=='title')
		{
			return trim($str);
		}
		else
		{
			preg_match_all("/<img(.*)src=[\"']{0,1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU",$str,$imgs);
			if(isset($imgs[2][0]))
			{
				$txt = trim(Html2Text($str));
				$imgs[2][0] = ereg_replace("[\"']",'',$imgs[2][0]);
				return "img:".$this->FillUrl($imgs[2][0]).':txt:'.$txt;
			}
			else
			{
				$str = eregi_replace('</(.*)$','',$str);
				$str = trim(eregi_replace('^(.*)>','',$str));
				return $str;
			}
		}
	}
}//End class

/*******************************
//���Խ�����
function c____DedeAttribute2();
********************************/
class DedeAttribute2
{
	var $SourceString = '';
	var $SourceMaxSize = 1024;
	var $CharToLow = FALSE;  //����ֵ�Ƿ񲻷ִ�Сд(������ͳһΪСд)
	var $IsTagName = TRUE; //�Ƿ�����������
	var $Count = -1;
	var $Items = ''; //����Ԫ�صļ���

	//�������Խ�����Դ�ַ���
	function SetSource($str = '')
	{
		$this->Count = -1;
		$this->Items = '';
		$strLen = 0;
		$this->SourceString = trim(preg_replace("/[ \t\r\n]{1,}/"," ",$str));
		$strLen = strlen($this->SourceString);
		$this->SourceString .= " "; //����һ���ո��β,�Է��㴦��û�����Եı��
		if($strLen>0&&$strLen<=$this->SourceMaxSize)
		{
			$this->PrivateAttParse();
		}
	}

	//���ĳ������
	function GetAtt($str)
	{
		if($str == '')
		{
			return '';
		}
		$str = strtolower($str);
		if(isset($this->Items[$str]))
		{
			return $this->Items[$str];
		}
		else
		{
			return '';
		}
	}

	//�ж������Ƿ����
	function IsAtt($str)
	{
		if($str == '')
		{
			return false;
		}
		$str = strtolower($str);
		if(isset($this->Items[$str]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//��ñ������
	function GetTagName()
	{
		return $this->GetAtt("tagname");
	}

	// ������Ը���
	function GetCount()
	{
		return $this->Count+1;
	}

	//��������(����SetSource����)
	function PrivateAttParse()
	{
		$d = '';
		$tmpatt = '';
		$tmpvalue = '';
		$startdd = -1;
		$ddtag = '';
		$strLen = strlen($this->SourceString);
		$j = 0;

		//�����ǻ�ñ�ǵ�����
		if($this->IsTagName)
		{
			//���������ע�⣬���ٽ�����������ݣ�ֱ�ӷ���
			if(isset($this->SourceString[2]))
			{
				if($this->SourceString[0].$this->SourceString[1].$this->SourceString[2]=='!--')
				{
					$this->Items['tagname'] = '!--';
					return ;
				}
			}
			for($i=0;$i<$strLen;$i++)
			{
				$d = $this->SourceString[$i];
				$j++;
				if(ereg("[ '\"\r\n\t]",$d))
				{
					$this->Count++;
					$this->Items["tagname"]=strtolower(trim($tmpvalue));
					$tmpvalue = ''; break;
				}
				else
				{
					$tmpvalue .= $d;
				}
			}
			if($j>0)
			{
				$j = $j-1;
			}
		}

		//����Դ�ַ�������ø�����
		for($i=$j;$i<$strLen;$i++)
		{
			$d = $this->SourceString[$i];
			//������Եļ�
			if($startdd==-1)
			{
				if($d!='=')
				{
					$tmpatt .= $d;
				}
				else
				{
					$tmpatt = strtolower(trim($tmpatt));
					$startdd=0;
				}
			}

			//�������ֵ����ʲô��Χ�ģ�����ʹ�� '' '' ��հ�
			else if($startdd==0)
			{
				switch($d)
				{
					case ' ':
						continue;
						break;
					case '\'':
						$ddtag='\'';
						$startdd=1;
						break;
					case '"':
						$ddtag='"';
						$startdd=1;
						break;
					default:
						$tmpvalue.=$d;
						$ddtag=' ';
						$startdd=1;
						break;
				}
			}

			//������Ե�ֵ
			else if($startdd==1)
			{
				if($d==$ddtag)
				{
					$this->Count++;
					if($this->CharToLow)
					{
						$this->Items[$tmpatt] = strtolower(trim($tmpvalue));
					}
					else
					{
						$this->Items[$tmpatt] = trim($tmpvalue);
					}
					$tmpatt = '';
					$tmpvalue = '';
					$startdd=-1;
				}
				else
				{
					$tmpvalue.=$d;
				}
			}
		}//End for

		//����û��ֵ������(������ڽ�β����Ч)�磺"input type=radio name=t1 value=aaa checked"
		if($tmpatt != '')
		{
			$this->Items[$tmpatt] = '';
		}
	}//End Function PrivateAttParse

}//End Class DedeAttribute2

?>
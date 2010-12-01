<?php
if(!defined('DEDEINC'))
{
	//exit('dedecms');
}

/****************************************
class DedeTag ��ǵ����ݽṹ����
function c____DedeTag();
**************************************/
class DedeTag
{
	var $IsReplace=FALSE; //����Ƿ��ѱ��������������ʹ��
	var $TagName=""; //�������
	var $InnerText=""; //���֮����ı�
	var $StartPos=0; //�����ʼλ��
	var $EndPos=0; //��ǽ���λ��
	var $CAttribute=""; //�����������,����class DedeAttribute
	var $TagValue=""; //��ǵ�ֵ
	var $TagID = 0;

	//��ȡ��ǵ����ƺ�ֵ
	function GetName()
	{
		return strtolower($this->TagName);
	}

	function GetValue()
	{
		return $this->TagValue;
	}

	//����������Ա��������Ϊ�˼��ݾɰ�
	function GetTagName()
	{
		return strtolower($this->TagName);
	}

	function GetTagValue()
	{
		return $this->TagValue;
	}

	//��ȡ��ǵ�ָ������
	function IsAttribute($str)
	{
		return $this->CAttribute->IsAttribute($str);
	}

	function GetAttribute($str)
	{
		return $this->CAttribute->GetAtt($str);
	}

	function GetAtt($str)
	{
		return $this->CAttribute->GetAtt($str);
	}

	function GetInnerText()
	{
		return $this->InnerText;
	}
}

/**********************************************
//DedeTagParse Dede֯��ģ����
function c____DedeTagParse();
***********************************************/
class DedeTagParse
{
	var $NameSpace = 'dede'; //��ǵ����ֿռ�
	var $TagStartWord = '{'; //�����ʼ
	var $TagEndWord = '}'; //��ǽ���
	var $TagMaxLen = 64; //������Ƶ����ֵ
	var $CharToLow = true; // true��ʾ�����Ժͱ�����Ʋ����ִ�Сд
	var $IsCache = FALSE; //�Ƿ�ʹ�û���
	var $TempMkTime = 0;
	var $CacheFile = '';
	var $SourceString = '';//ģ���ַ���
	var $CTags = '';		 //��Ǽ���
	var $Count = -1;		 //$Tags��Ǹ���
	var $refObj = '';   //���õ�ǰģ����Ķ���

	function __construct()
	{
		if(!isset($GLOBALS['cfg_tplcache']))
		{
			$GLOBALS['cfg_tplcache'] = 'N';
		}
		if($GLOBALS['cfg_tplcache']=='Y')
		{
			$this->IsCache = true;
		}
		else
		{
			$this->IsCache = FALSE;
		}
		$this->NameSpace = 'dede';
		$this->TagStartWord = '{';
		$this->TagEndWord = '}';
		$this->TagMaxLen = 64;
		$this->CharToLow = true;
		$this->SourceString = '';
		$this->CTags = Array();
		$this->Count = -1;
		$this->TempMkTime = 0;
		$this->CacheFile = '';
	}

	function DedeTagParse()
	{
		$this->__construct();
	}

	//���ñ�ǵ������ռ䣬Ĭ��Ϊdede
	function SetNameSpace($str,$s="{",$e="}")
	{
		$this->NameSpace = strtolower($str);
		$this->TagStartWord = $s;
		$this->TagEndWord = $e;
	}

	//���ó�Ա������Clear
	function SetDefault()
	{
		$this->SourceString = '';
		$this->CTags = '';
		$this->Count=-1;
	}
	
	//ǿ������
	function SetRefObj(&$refObj)
	{
		$this->refObj = $refObj;
	}

	function GetCount()
	{
		return $this->Count+1;
	}

	function Clear()
	{
		$this->SetDefault();
	}

	//���ģ�建��
	function LoadCache($filename)
	{
		global $cfg_tplcache,$cfg_tplcache_dir;
		if(!$this->IsCache)
		{
			return false;
		}
		$cdir = dirname($filename);
		$cachedir = DEDEROOT.$cfg_tplcache_dir;
		$ckfile = str_replace($cdir,'',$filename).substr(md5($filename),0,16).'.inc';
		$ckfullfile = $cachedir.'/'.$ckfile;
		$ckfullfile_t = $cachedir.'/'.$ckfile.'.txt';
		$this->CacheFile = $ckfullfile;
		$this->TempMkTime = filemtime($filename);
		if(!file_exists($ckfullfile)||!file_exists($ckfullfile_t))
		{
			return false;
		}

		//���ģ��������ʱ��
		$fp = fopen($ckfullfile_t,'r');
		$time_info = trim(fgets($fp,64));
		fclose($fp);
		if($time_info != $this->TempMkTime)
		{
			return false;
		}

		//���뻺������
		include($this->CacheFile);

		//�ѻ����������ݶ�����
		if(isset($z) && is_array($z))
		{
			foreach($z as $k=>$v)
			{
				$this->Count++;
				$ctag = new DedeTAg();
				$ctag->CAttribute = new DedeAttribute();
				$ctag->IsReplace = FALSE;
				$ctag->TagName = $v[0];
				$ctag->InnerText = $v[1];
				$ctag->StartPos = $v[2];
				$ctag->EndPos = $v[3];
				$ctag->TagValue = '';
				$ctag->TagID = $k;
				if(isset($v[4]) && is_array($v[4]))
				{
					$i = 0;
					foreach($v[4] as $k=>$v)
					{
						$ctag->CAttribute->Count++;
						$ctag->CAttribute->Items[$k]=$v;
					}
				}
				$this->CTags[$this->Count] = $ctag;
			}
		}
		else
		{
			//ģ��û�л�������
			$this->CTags = '';
			$this->Count = -1;
		}
		return true;
	}

	//д�뻺��
	function SaveCache()
	{
		$fp = fopen($this->CacheFile.'.txt',"w");
		fwrite($fp,$this->TempMkTime."\n");
		fclose($fp);
		$fp = fopen($this->CacheFile,"w");
		flock($fp,3);
		fwrite($fp,'<'.'?php'."\r\n");
		if(is_array($this->CTags))
		{
			foreach($this->CTags as $tid=>$ctag)
			{
				$arrayValue = 'Array("'.$ctag->TagName.'",';
				$arrayValue .= '"'.str_replace('$','\$',str_replace("\r","\\r",str_replace("\n","\\n",str_replace('"','\"',str_replace("\\","\\\\",$ctag->InnerText))))).'"';
				$arrayValue .= ",{$ctag->StartPos},{$ctag->EndPos});";
				fwrite($fp,"\$z[$tid]={$arrayValue}\n");
				if(is_array($ctag->CAttribute->Items))
				{
					foreach($ctag->CAttribute->Items as $k=>$v)
					{
						$v = str_replace("\\","\\\\",$v);
						$v = str_replace('"',"\\".'"',$v);
						$v = str_replace('$','\$',$v);
						$k = trim(str_replace("'","",$k));
						if($k=="")
						{
							continue;
						}
						if($k!='tagname')
						{
							fwrite($fp,"\$z[$tid][4]['$k']=\"$v\";\n");
						}
					}
				}
			}
		}
		fwrite($fp,"\n".'?'.'>');
		fclose($fp);
	}

	//����ģ���ļ�
	function LoadTemplate($filename)
	{
		$this->SetDefault();
		if(!file_exists($filename))
		{
			$this->SourceString = " $filename Not Found! ";
			$this->ParseTemplet();
		}
		else
		{
			$fp = @fopen($filename, "r");
			while($line = fgets($fp,1024))
			{
				$this->SourceString .= $line;
			}
			fclose($fp);
			if($this->LoadCache($filename))
			{
				return '';
			}
			else
			{
				$this->ParseTemplet();
			}
		}
	}

	function LoadTemplet($filename)
	{
		$this->LoadTemplate($filename);
	}

	function LoadFile($filename)
	{
		$this->LoadTemplate($filename);
	}

	//����ģ���ַ���
	function LoadSource($str)
	{
		/*
		$this->SetDefault();
		$this->SourceString = $str;
		$this->IsCache = FALSE;
		$this->ParseTemplet();
		*/
		//�Ż�ģ���ַ�����ȡ��ȡ��ʽ
		$filename = DEDEDATA.'/tplcache/'.md5($str).'.inc';
		if( !is_file($filename) )
		{
		    file_put_contents($filename, $str);
		}
		$this->LoadTemplate($filename);
	}

	function LoadString($str)
	{
		$this->LoadSource($str);
	}

	//���ָ�����Ƶ�Tag��ID(����ж��ͬ����Tag,��ȡû�б�ȡ��Ϊ���ݵĵ�һ��Tag)
	function GetTagID($str)
	{
		if($this->Count==-1)
		{
			return -1;
		}
		if($this->CharToLow)
		{
			$str=strtolower($str);
		}
		foreach($this->CTags as $id=>$CTag)
		{
			if($CTag->TagName==$str && !$CTag->IsReplace)
			{
				return $id;
				break;
			}
		}
		return -1;
	}

	//���ָ�����Ƶ�CTag������(����ж��ͬ����Tag,��ȡû�б��������ݵĵ�һ��Tag)
	function GetTag($str)
	{
		if($this->Count==-1)
		{
			return '';
		}
		if($this->CharToLow)
		{
			$str=strtolower($str);
		}
		foreach($this->CTags as $id=>$CTag)
		{
			if($CTag->TagName==$str && !$CTag->IsReplace)
			{
				return $CTag;
				break;
			}
		}
		return '';
	}

	function GetTagByName($str)
	{
		return $this->GetTag($str);
	}

	//���ָ��ID��CTag������
	function GetTagByID($id)
	{
		if(isset($this->CTags[$id]))
		{
			return $this->CTags[$id];
		}
		else
		{
			return '';
		}
	}

	//��_vars���鴫��һ��Ԫ��
	function AssignVar($vname,$vvalue)
	{
		if(!isset($_sys_globals['define']))
		{
			$_sys_globals['define'] = 'yes';
		}
		$_sys_globals[$vname] = $vvalue;
	}

	//����ָ��ID�ı�ǵ�ֵ
	function Assign($i,$str,$runfunc = true)
	{
		if(isset($this->CTags[$i]))
		{
			$this->CTags[$i]->IsReplace = true;
			$this->CTags[$i]->TagValue = $str;

			if( $this->CTags[$i]->GetAtt('function')!='' && $runfunc )
			{
				$this->CTags[$i]->TagValue = $this->EvalFunc( $str, $this->CTags[$i]->GetAtt('function'),$this->CTags[$i] );
			}
		}
	}

	//����ָ�����Ƶı�ǵ�ֵ�������ǰ������ԣ��벻Ҫ�ô˺���
	function AssignName($tagname,$str)
	{
		foreach($this->CTags as $id=>$CTag)
		{
			if($CTag->TagName==$tagname)
			{
				$this->Assign($id,$str);
			}
		}
	}

	//����������
	function AssignSysTag()
	{
		global $_sys_globals;
		for($i=0;$i<=$this->Count;$i++)
		{
			$CTag = $this->CTags[$i];
			$str = '';

			//��ȡһ���ⲿ����
			if( $CTag->TagName == 'global' )
			{
				$str = $this->GetGlobals($CTag->GetAtt('name'));
				if( $this->CTags[$i]->GetAtt('function')!='' )
				{
					//$str = $this->EvalFunc( $this->CTags[$i]->TagValue, $this->CTags[$i]->GetAtt('function'),$this->CTags[$i] );
					$str = $this->EvalFunc( $str, $this->CTags[$i]->GetAtt('function'),$this->CTags[$i] );
				}
				$this->CTags[$i]->IsReplace = true;
				$this->CTags[$i]->TagValue = $str;
			}

			//���뾲̬�ļ�
			else if( $CTag->TagName == 'include' )
			{
				$filename = ($CTag->GetAtt('file')=='' ? $CTag->GetAtt('filename') : $CTag->GetAtt('file') );
				$str = $this->IncludeFile($filename,$CTag->GetAtt('ismake'));
				$this->CTags[$i]->IsReplace = true;
				$this->CTags[$i]->TagValue = $str;
			}

			//ѭ��һ����ͨ����
			else if( $CTag->TagName == 'foreach' )
			{
				$arr = $this->CTags[$i]->GetAtt('array');
				if(isset($GLOBALS[$arr]))
				{
					foreach($GLOBALS[$arr] as $k=>$v)
					{
						$istr = '';
						$istr .= preg_replace("/\[field:key([\r\n\t\f ]+)\/\]/is",$k,$this->CTags[$i]->InnerText);
						$str .= preg_replace("/\[field:value([\r\n\t\f ]+)\/\]/is",$v,$istr);
					}
				}
				$this->CTags[$i]->IsReplace = true;
				$this->CTags[$i]->TagValue = $str;
			}

			//����/��ȡ����ֵ
			else if( $CTag->TagName == 'var' )
			{
				$vname = $this->CTags[$i]->GetAtt('name');
				if($vname=='')
				{
					$str = '';
				}
				else if($this->CTags[$i]->GetAtt('value')!='')
				{
					$_vars[$vname] = $this->CTags[$i]->GetAtt('value');
				}
				else
				{
					$str = (isset($_vars[$vname]) ? $_vars[$vname] : '');
				}
				$this->CTags[$i]->IsReplace = true;
				$this->CTags[$i]->TagValue = $str;
			}

			//����PHP�ӿ�
			if( $CTag->GetAtt('runphp') == 'yes' )
			{
				$this->RunPHP($CTag,$i);
			}
			if(is_array($this->CTags[$i]->TagValue))
			{
				$this->CTags[$i]->TagValue = 'array';
			}
		}
	}

	//����PHP����
	function RunPHP(&$refObj,$i)
	{
		$DedeMeValue = $phpcode = '';
		if($refObj->GetAtt('source')=='value')
		{
			$phpcode = $this->CTags[$i]->TagValue;
		}
		else
		{
			$DedeMeValue = $this->CTags[$i]->TagValue;
			$phpcode = $refObj->GetInnerText();
		}
		$phpcode = eregi_replace("'@me'|\"@me\"|@me",'$DedeMeValue',$phpcode);
		@eval($phpcode); //or die("<xmp>$phpcode</xmp>");

		$this->CTags[$i]->TagValue = $DedeMeValue;
		$this->CTags[$i]->IsReplace = true;
	}

	//�ѷ���ģ�������һ���ַ�����
	//���滻û�������ֵ
	function GetResultNP()
	{
		$ResultString = '';
		if($this->Count==-1)
		{
			return $this->SourceString;
		}
		$this->AssignSysTag();
		$nextTagEnd = 0;
		$strok = "";
		for($i=0;$i<=$this->Count;$i++)
		{
			if($this->CTags[$i]->GetValue()!="")
			{
				if($this->CTags[$i]->GetValue()=='#@Delete@#')
				{
					$this->CTags[$i]->TagValue = "";
				}
				$ResultString .= substr($this->SourceString,$nextTagEnd,$this->CTags[$i]->StartPos-$nextTagEnd);
				$ResultString .= $this->CTags[$i]->GetValue();
				$nextTagEnd = $this->CTags[$i]->EndPos;
			}
		}
		$slen = strlen($this->SourceString);
		if($slen>$nextTagEnd)
		{
			$ResultString .= substr($this->SourceString,$nextTagEnd,$slen-$nextTagEnd);
		}
		return $ResultString;
	}

	//�ѷ���ģ�������һ���ַ�����,������
	function GetResult()
	{
		$ResultString = '';
		if($this->Count==-1)
		{
			return $this->SourceString;
		}
		$this->AssignSysTag();
		$nextTagEnd = 0;
		$strok = "";
		for($i=0;$i<=$this->Count;$i++)
		{
			$ResultString .= substr($this->SourceString,$nextTagEnd,$this->CTags[$i]->StartPos-$nextTagEnd);
			$ResultString .= $this->CTags[$i]->GetValue();
			$nextTagEnd = $this->CTags[$i]->EndPos;
		}
		$slen = strlen($this->SourceString);
		if($slen>$nextTagEnd)
		{
			$ResultString .= substr($this->SourceString,$nextTagEnd,$slen-$nextTagEnd);
		}
		return $ResultString;
	}

	//ֱ���������ģ��
	function Display()
	{
		echo $this->GetResult();
	}

	//�ѷ���ģ�����Ϊ�ļ�
	function SaveTo($filename)
	{
		$fp = @fopen($filename,"w") or die("DedeTag Engine Create File False");
		fwrite($fp,$this->GetResult());
		fclose($fp);
	}

	//����ģ��
	function ParseTemplet()
	{
		$TagStartWord = $this->TagStartWord;
		$TagEndWord = $this->TagEndWord;
		$sPos = 0; $ePos = 0;
		$FullTagStartWord =  $TagStartWord.$this->NameSpace.":";
		$sTagEndWord =  $TagStartWord."/".$this->NameSpace.":";
		$eTagEndWord = "/".$TagEndWord;
		$tsLen = strlen($FullTagStartWord);
		$sourceLen=strlen($this->SourceString);
		
		if( $sourceLen <= ($tsLen + 3) )
		{
			return;
		}
		$cAtt = new DedeAttributeParse();
		$cAtt->charToLow = $this->CharToLow;

		//����ģ���ַ�������ȡ��Ǽ���������Ϣ
		for($i=0; $i < $sourceLen; $i++)
		{
			$tTagName = '';

			//��������д��жϣ����޷�ʶ���������������
			if($i-1 >= 0)
			{
				$ss = $i-1;
			}
			else
			{
				$ss = 0;
			}
			$sPos = strpos($this->SourceString,$FullTagStartWord,$ss);
			$isTag = $sPos;
			if($i==0)
			{
				$headerTag = substr($this->SourceString,0,strlen($FullTagStartWord));
				if($headerTag==$FullTagStartWord)
				{
					$isTag=true; $sPos=0;
				}
			}
			if($isTag===FALSE)
			{
				break;
			}
			//�ж��Ƿ��Ѿ��������������ַ�(�����Լ��ʼ�С��ȡ�����߼�)
			/*
			if($sPos > ($sourceLen-$tsLen-3) )
			{
				break;
			}
			*/
			for($j=($sPos+$tsLen);$j<($sPos+$tsLen+$this->TagMaxLen);$j++)
			{
				if($j>($sourceLen-1))
				{
					break;
				}
				else if( ereg("[/ \t\r\n]",$this->SourceString[$j]) || $this->SourceString[$j] == $this->TagEndWord )
				{
					break;
				}
				else
				{
					$tTagName .= $this->SourceString[$j];
				}
			}
			if($tTagName!='')
			{
				$i = $sPos+$tsLen;
				$endPos = -1;
				$fullTagEndWordThis = $sTagEndWord.$tTagName.$TagEndWord;
				
				$e1 = strpos($this->SourceString,$eTagEndWord, $i);
				$e2 = strpos($this->SourceString,$FullTagStartWord, $i);
				$e3 = strpos($this->SourceString,$fullTagEndWordThis,$i);
				
				//$eTagEndWord = /} $FullTagStartWord = {tag: $fullTagEndWordThis = {/tag:xxx]
				
				$e1 = trim($e1); $e2 = trim($e2); $e3 = trim($e3);
				$e1 = ($e1=='' ? '-1' : $e1);
				$e2 = ($e2=='' ? '-1' : $e2);
				$e3 = ($e3=='' ? '-1' : $e3);
				//not found '{/tag:'
				if($e3==-1) 
				{
					$endPos = $e1;
					$elen = $endPos + strlen($eTagEndWord);
				}
				//not found '/}'
				else if($e1==-1) 
				{
					$endPos = $e3;
					$elen = $endPos + strlen($fullTagEndWordThis);
				}
				//found '/}' and found '{/dede:'
				else
				{
					//if '/}' more near '{dede:'��'{/dede:' , end tag is '/}', else is '{/dede:'
					if($e1 < $e2 &&  $e1 < $e3 )
					{
						$endPos = $e1;
						$elen = $endPos + strlen($eTagEndWord);
					}
					else
					{
						$endPos = $e3;
						$elen = $endPos + strlen($fullTagEndWordThis);
					}
				}

				//not found end tag , error
				if($endPos==-1)
				{
					echo "Character postion $sPos, '$tTagName' Error��<br />\r\n";
					break;
				}
				$i = $elen;
				$ePos = $endPos;

				//�������ҵ��ı��λ�õ���Ϣ
				$attStr = '';
				$innerText = '';
				$startInner = 0;
				for($j=($sPos+$tsLen);$j < $ePos;$j++)
				{
					if($startInner==0 && ($this->SourceString[$j]==$TagEndWord && $this->SourceString[$j-1]!="\\") )
					{
						$startInner=1;
						continue;
					}
					if($startInner==0)
					{
						$attStr .= $this->SourceString[$j];
					}
					else
					{
						$innerText .= $this->SourceString[$j];
					}
				}
				//echo "<xmp>$attStr</xmp>\r\n";
				$cAtt->SetSource($attStr);
				if($cAtt->cAttributes->GetTagName()!='')
				{
					$this->Count++;
					$CDTag = new DedeTag();
					$CDTag->TagName = $cAtt->cAttributes->GetTagName();
					$CDTag->StartPos = $sPos;
					$CDTag->EndPos = $i;
					$CDTag->CAttribute = $cAtt->cAttributes;
					$CDTag->IsReplace = FALSE;
					$CDTag->TagID = $this->Count;
					$CDTag->InnerText = $innerText;
					$this->CTags[$this->Count] = $CDTag;
				}
			}
			else
			{
				$i = $sPos+$tsLen;
				break;
			}
		}//��������ģ���ַ���

		if($this->IsCache)
		{
			$this->SaveCache();
		}
	}

	//����ĳ�ֶεĺ���
	function EvalFunc($fieldvalue,$functionname,&$refObj)
	{
		$DedeFieldValue = $fieldvalue;
		$functionname = str_replace("{\"","[\"",$functionname);
		$functionname = str_replace("\"}","\"]",$functionname);
		$functionname = eregi_replace("'@me'|\"@me\"|@me",'$DedeFieldValue',$functionname);
		$functionname = "\$DedeFieldValue = ".$functionname;
		@eval($functionname.";"); //or die("<xmp>$functionname</xmp>");
		if(empty($DedeFieldValue))
		{
			return '';
		}
		else
		{
			return $DedeFieldValue;
		}
	}

	//���һ���ⲿ����
	function GetGlobals($varname)
	{
		$varname = trim($varname);

		//��ֹ��ģ���ļ���ȡ���ݿ�����
		if($varname=="dbuserpwd"||$varname=="cfg_dbpwd")
		{
			return "";
		}

		//�������
		if(isset($GLOBALS[$varname]))
		{
			return $GLOBALS[$varname];
		}
		else
		{
			return "";
		}
	}

	//�����ļ�
	function IncludeFile($filename,$ismake='no')
	{
		global $cfg_df_style;
		$restr = '';
		if($filename=='')
		{
			return '';
		}
		if( file_exists(DEDEROOT."/templets/".$filename) )
		{
			$okfile = DEDEROOT."/templets/".$filename;
		}
		else if(file_exists(DEDEROOT.'/templets/'.$cfg_df_style.'/'.$filename) )
		{
			$okfile = DEDEROOT.'/templets/'.$cfg_df_style.'/'.$filename;
		}
		else
		{
			return "�޷������λ���ҵ��� $filename";
		}

		//����
		if($ismake!="no")
		{
			require_once(DEDEINC."/channelunit.func.php");
			$dtp = new DedeTagParse();
			$dtp->LoadTemplet($okfile);
			MakeOneTag($dtp,$this->refObj);
			$restr = $dtp->GetResult();
		}
		else
		{
			$fp = @fopen($okfile,"r");
			while($line=fgets($fp,1024)) $restr.=$line;
			fclose($fp);
		}
		return $restr;
	}
}

/**********************************************
//class DedeAttribute Dedeģ�������Լ���
function c____DedeAttribute();
**********************************************/
//���Ե���������
class DedeAttribute
{
	var $Count = -1;
	var $Items = ""; //����Ԫ�صļ���
	//���ĳ������
	function GetAtt($str)
	{
		if($str=="")
		{
			return "";
		}
		if(isset($this->Items[$str]))
		{
			return $this->Items[$str];
		}
		else
		{
			return "";
		}
	}

	//ͬ��
	function GetAttribute($str)
	{
		return $this->GetAtt($str);
	}

	//�ж������Ƿ����
	function IsAttribute($str)
	{
		if(isset($this->Items[$str])) return true;
		else return false;
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
}

/*******************************
//���Խ�����(���汾���Ѿ�֧��ʹ��\'�����﷨,����.�����ʾname����,�� field.body)
function c____DedeAttributeParse();
********************************/
class DedeAttributeParse
{
	var $sourceString = "";
	var $sourceMaxSize = 1024;
	var $cAttributes = "";
	var $charToLow = true;
	function SetSource($str='')
	{
		$this->cAttributes = new DedeAttribute();
		$strLen = 0;
		$this->sourceString = trim(preg_replace("/[ \r\n\t]{1,}/"," ",$str));
		
		//Ϊ����function����ʹ�����飬���������[ ]����ת��ʹ��
		$this->sourceString = str_replace('\]',']',$this->sourceString);
		$this->sourceString = str_replace('[','[',$this->sourceString);
		/*
		$this->sourceString = str_replace('\>','>',$this->sourceString);
		$this->sourceString = str_replace('<','>',$this->sourceString);
		$this->sourceString = str_replace('{','{',$this->sourceString);
		$this->sourceString = str_replace('\}','}',$this->sourceString);
		*/
		
		$strLen = strlen($this->sourceString);
		if($strLen>0 && $strLen <= $this->sourceMaxSize)
		{
			$this->ParseAttribute();
		}
	}

	//��������
	function ParseAttribute()
	{
		$d = '';
		$tmpatt = '';
		$tmpvalue = '';
		$startdd = -1;
		$ddtag = '';
		$hasAttribute=false;
		$strLen = strlen($this->sourceString);

		// ���Tag�����ƣ������� cAtt->GetAtt('tagname') ��
		for($i=0; $i<$strLen; $i++)
		{
			if($this->sourceString[$i]==' ')
			{
				$this->cAttributes->Count++;
				$tmpvalues = explode('.', $tmpvalue);
				$this->cAttributes->Items['tagname'] = ($this->charToLow ? strtolower($tmpvalues[0]) : $tmpvalues[0]);
				if(isset($tmpvalues[1]) && $tmpvalues[1]!='')
				{
					$this->cAttributes->Items['name'] = $tmpvalues[1];
				}
				$tmpvalue = '';
				$hasAttribute = true;
				break;
			}
			else
			{
				$tmpvalue .= $this->sourceString[$i];
			}
		}

		//�����������б�����
		if(!$hasAttribute)
		{
			$this->cAttributes->Count++;
			$tmpvalues = explode('.', $tmpvalue);
			$this->cAttributes->Items['tagname'] = ($this->charToLow ? strtolower($tmpvalues[0]) : $tmpvalues[0]);
			if(isset($tmpvalues[1]) && $tmpvalues[1]!='')
			{
				$this->cAttributes->Items['name'] = $tmpvalues[1];
			}
			return ;
		}
		$tmpvalue = '';

		//����ַ�����������ֵ������Դ�ַ���,����ø�����
		for($i; $i<$strLen; $i++)
		{
			$d = $this->sourceString[$i];
			//������������
			if($startdd==-1)
			{
				if($d != '=')
				{
					$tmpatt .= $d;
				}
				else
				{
					if($this->charToLow)
					{
						$tmpatt = strtolower(trim($tmpatt));
					}
					else
					{
						$tmpatt = trim($tmpatt);
					}
					$startdd=0;
				}
			}

			//�������Ե��޶���־
			else if($startdd==0)
			{
				switch($d)
				{
					case ' ':
						break;
					case '"':
						$ddtag = '"';
						$startdd = 1;
						break;
					case '\'':
						$ddtag = '\'';
						$startdd = 1;
						break;
					default:
						$tmpvalue .= $d;
						$ddtag = ' ';
						$startdd = 1;
						break;
				}
			}
			else if($startdd==1)
			{
				if($d==$ddtag && ( isset($this->sourceString[$i-1]) && $this->sourceString[$i-1]!="\\") )
				{
					$this->cAttributes->Count++;
					$this->cAttributes->Items[$tmpatt] = trim($tmpvalue);
					$tmpatt = '';
					$tmpvalue = '';
					$startdd = -1;
				}
				else
				{
					$tmpvalue .= $d;
				}
			}
		}//for

		//���һ�����Եĸ�ֵ
		if($tmpatt != '')
		{
			$this->cAttributes->Count++;
			$this->cAttributes->Items[$tmpatt] = trim($tmpvalue);
		}
		//print_r($this->cAttributes->Items);
	}// end func
}
?>
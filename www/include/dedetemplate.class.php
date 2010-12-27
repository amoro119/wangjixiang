<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

//����������ڶ����������ƵĿ�ʹ�õĽӿ�
//����ֵӦ��һ����ά����
function MakePublicTag($atts=array(),$refObj='',$fields=array())
{
	//����ö�Ӧ���ļ�Ϊ include/taglib/plus_blockname.php
	/***************************************
	���ڱ��һ�����Ĭ�����ԣ��ڱ�д�麯��ʱ��Ӧ���ڿ麯���н��и����Ը�ʡȱֵ�����磺
	$attlist = "titlelen=30,catalogid=0,modelid=0,flag=,addon=,row=8,ids=,orderby=id,orderway=desc,limit=,subday=0";
	//�����Ը�ʡȱֵ
	FillAtts($atts,$attlist);
	//����������ʹ�õ�ϵͳ���� var��global��field ����(��֧�ֶ�ά����)
	FillFields($atts,$fields,$refObj);
	****************************************/
	$atts['tagname'] = ereg_replace("[0-9]{1,}$","",$atts['tagname']);
	$plusfile = DEDEINC.'/tpllib/plus_'.$atts['tagname'].'.php';
	if(!file_exists($plusfile))
	{
		if(isset($atts['rstype']) && $atts['rstype']=='string')
		{
			return '';
		}
		else
		{
			return array();
		}
	}
	else
	{
		include_once($plusfile);
		$func = 'plus_'.$atts['tagname'];
		return $func($atts,$refObj,$fields);
	}
}

//�趨���Ե�Ĭ��ֵ
function FillAtts(&$atts,$attlist)
{
	$attlists = explode(',',$attlist);
	foreach($attlists as $att)
	{
		list($k,$v)=explode('=',$att);
		if(!isset($atts[$k]))
		{
			$atts[$k] = $v;
		}
	}
}

//���ϼ���fields���ݸ�atts
function FillFields(&$atts,&$refObj,&$fields)
{
	global $_vars;
	foreach($atts as $k=>$v)
	{
		if(eregi('^field\.',$v))
		{
			$key = eregi_replace('^field\.','',$v);
			if( isset($fields[$key]) )
			{
				$atts[$k] = $fields[$key];
			}
		}
		else if(eregi('^var\.',$v))
		{
			$key = eregi_replace('^var\.','',$v);
			if( isset($_vars[$key]) )
			{
				$atts[$k] = $_vars[$key];
			}
		}
		else if(eregi('^global\.',$v))
		{
			$key = eregi_replace('^global\.','',$v);
			if( isset($GLOBALS[$key]) )
			{
				$atts[$k] = $GLOBALS[$key];
			}
		}
	}
}

/*------------------------
class Tag ��ǵ����ݽṹ����
function C__Tag();
-------------------------*/
class Tag
{
	var $isCompiler=false; //����Ƿ��ѱ��������������ʹ��
	var $tagName=""; //�������
	var $innerText=""; //���֮����ı�
	var $startPos=0; //�����ʼλ��
	var $endPos=0; //��ǽ���λ��
	var $cAtt=""; //�����������,����class TagAttribute
	var $tagValue=""; //��ǵ�ֵ
	var $tagID = 0;

	//��ȡ��ǵ����ƺ�ֵ
	function GetName()
	{
		return strtolower($this->tagName);
	}

	function GetValue()
	{
		return $this->tagValue;
	}

	function IsAtt($str)
	{
		return $this->cAtt->IsAttribute($str);
	}

	function GetAtt($str)
	{
		return $this->cAtt->GetAtt($str);
	}

	function GetinnerText()
	{
		return $this->innerText;
	}
}

/*----------------------------
ģ�������
function C__DedeTemplate
-----------------------------*/
class DedeTemplate
{
	var $tagMaxLen = 64;
	var $charToLow = true;
	var $isCache = true;
	var $isParse = false;
	var $isCompiler = true;
	var $templateDir = '';
	var $tempMkTime = 0;
	var $cacheFile = '';
	var $configFile = '';
	var $buildFile = '';
	var $refDir = '';
	var $cacheDir = '';
	var $templateFile = '';
	var $sourceString = '';
	var $cTags = '';

	//var $definedVars = array();
	var $count = -1;
	var $loopNum = 0;
	var $refObj = '';
	var $makeLoop = 0;
	var $tagStartWord =  '{dede:';
	var $fullTagEndWord =  '{/dede:';
	var $sTagEndWord = '/}';
	var $tagEndWord = '}';
	var $tpCfgs = array();

	//���캯��
	function __construct($templatedir='',$refDir='')
	{
		//$definedVars[] = 'var';
		//����Ŀ¼
		if($templatedir=='')
		{
			$this->templateDir = DEDEROOT.'/templates';
		}
		else
		{
			$this->templateDir = $templatedir;
		}

		//ģ��includeĿ¼
		if($refDir=='')
		{
			if(isset($GLOBALS['cfg_df_style']))
			{
				$this->refDir = $this->templateDir.'/'.$GLOBALS['cfg_df_style'].'/';
			}
			else
			{
				$this->refDir = $this->templateDir;
			}
		}
		$this->cacheDir = DEDEROOT.$GLOBALS['cfg_tplcache_dir'];
	}

	function DedeTemplate($templatedir='',$refDir='')
	{
		$this->__construct($templatedir,$refDir);
	}

	//�趨��������ʵ���������ú�ʹ�ñ������ʵ��(���������ʹ�ñ�ģ�����棬��һ����һ��Ϊ$this)
	function SetObject(&$refObj)
	{
		$this->refObj = $refObj;
	}

	//�趨Var�ļ�ֵ��
	function SetVar($k,$v)
	{
		$GLOBALS['_vars'][$k] = $v;
	}

	function Assign($k,$v)
	{
		$GLOBALS['_vars'][$k] = $v;
	}

	//���ñ�Ƿ��
	function SetTagStyle($ts='{dede:',$ftend='{/dede:',$stend='/}',$tend='}')
	{
		$this->tagStartWord =  $ts;
		$this->fullTagEndWord =  $ftend;
		$this->sTagEndWord = $stend;
		$this->tagEndWord = $tend;
	}

	//���ģ���趨��configֵ
	function GetConfig($k)
	{
		return (isset($this->tpCfgs[$k]) ? $this->tpCfgs[$k] : '');
	}

	//�趨ģ���ļ�
	function LoadTemplate($tmpfile)
	{
		if(!file_exists($tmpfile))
		{
			echo " Template Not Found! ";
			exit();
		}
		$tmpfile = ereg_replace("[\\/]{1,}","/",$tmpfile);
		$tmpfiles = explode('/',$tmpfile);
		$tmpfileOnlyName = ereg_replace("(.*)/","",$tmpfile);
		$this->templateFile = $tmpfile;
		$this->refDir = '';
		for($i=0; $i < count($tmpfiles)-1; $i++)
		{
			$this->refDir .= $tmpfiles[$i].'/';
		}
		if(!is_dir($this->cacheDir))
		{
			$this->cacheDir = $this->refDir;
		}
		if($this->cacheDir!='')
		{
			$this->cacheDir = $this->cacheDir.'/';
		}
		if(isset($GLOBALS['_DEBUG_CACHE']))
		{
			$this->cacheDir = $this->refDir;
		}
		$this->cacheFile = $this->cacheDir.ereg_replace("\.(wml|html|htm|php)$","_".$this->GetEncodeStr($tmpfile).'.inc',$tmpfileOnlyName);
		$this->configFile = $this->cacheDir.ereg_replace("\.(wml|html|htm|php)$","_".$this->GetEncodeStr($tmpfile).'_config.inc',$tmpfileOnlyName);

		//���������桢�������ļ������ڡ���ģ��Ϊ���µ��ļ���ʱ�������ģ�岢���н���
		if($this->isCache==false || !file_exists($this->cacheFile)
		|| filemtime($this->templateFile) > filemtime($this->cacheFile))
		{
			$t1 = ExecTime(); //debug
			$fp = fopen($this->templateFile,'r');
			$this->sourceString = fread($fp,filesize($this->templateFile));
			fclose($fp);
			$this->ParseTemplate();
			//ģ�����ʱ��
			//echo ExecTime() - $t1;
		}
		else
		{
			//�������config�ļ�����������ļ������ļ����ڱ��� $this->tpCfgs�����ݣ��Թ���չ��;
			//ģ������{tag:config name='' value=''/}���趨��ֵ
			if(file_exists($this->configFile))
			{
				include($this->configFile);
			}
		}
	}

	//����ģ���ַ���
	function LoadString($str='')
	{
		$this->sourceString = $str;
		$hashcode = md5($this->sourceString);
		$this->cacheFile = $this->cacheDir."/string_".$hashcode.".inc";
		$this->configFile = $this->cacheDir."/string_".$hashcode."_config.inc";
		$this->ParseTemplate();
	}

	//���ô˺���includeһ��������PHP�ļ���ͨ���������һ������ŵ��ñ��ļ�
	function CacheFile()
	{
		global $gtmpfile;
		$this->WriteCache();
		return $this->cacheFile;
	}

	//��ʾ���ݣ����ں����л����½�ѹһ��$GLOBALS�����������ڶ�̬ҳ�У�Ӧ�þ������ñ�������
	//ȡ��֮��ֱ���ڳ����� include $tpl->CacheFile()������include $tpl->CacheFile()���ַ�ʽ�������������ʹ��
	function Display()
	{
		global $gtmpfile;
		extract($GLOBALS, EXTR_SKIP);
		$this->WriteCache();
		include $this->cacheFile;
	}

	//�������к�ĳ���Ϊ�ļ�
	function SaveTo($savefile)
	{
		extract($GLOBALS, EXTR_SKIP);
		$this->WriteCache();
		ob_start();
		include $this->cacheFile;
		$okstr = ob_get_contents();
		ob_end_clean();
		$fp = @fopen($savefile,"w") or die(" Tag Engine Create File false! ");
		fwrite($fp,$okstr);
		fclose($fp);
	}

	//����ģ�岢д�����ļ�
	function WriteCache($ctype='all')
	{
		if(!file_exists($this->cacheFile) || $this->isCache==false
		|| ( file_exists($this->templateFile) && (filemtime($this->templateFile) > filemtime($this->cacheFile)) ) )
		{
				if(!$this->isParse)
				{
					$this->ParseTemplate();
				}
				$fp = fopen($this->cacheFile,'w') or dir("Write Cache File Error! ");
				flock($fp,3);
				fwrite($fp,trim($this->GetResult()));
				fclose($fp);
				if(count($this->tpCfgs) > 0)
				{
					$fp = fopen($this->configFile,'w') or dir("Write Config File Error! ");
					flock($fp,3);
					fwrite($fp,'<'.'?php'."\r\n");
					foreach($this->tpCfgs as $k=>$v)
					{
						$v = str_replace("\"","\\\"",$v);
						$v = str_replace("\$","\\\$",$v);
						fwrite($fp,"\$this->tpCfgs['$k']=\"$v\";\r\n");
					}
					fwrite($fp,'?'.'>');
					fclose($fp);
				}
		}
		/*
		if(!file_exists($this->cacheFile) || $this->isCache==false
		|| ( file_exists($this->templateFile) && (filemtime($this->templateFile) > filemtime($this->cacheFile)) ) )
		{
			if($ctype!='config')
			{
				if(!$this->isParse)
				{
					$this->ParseTemplate();
				}
				$fp = fopen($this->cacheFile,'w') or dir("Write Cache File Error! ");
				flock($fp,3);
				fwrite($fp,trim($this->GetResult()));
				fclose($fp);

			}
			else
			{
				if(count($this->tpCfgs) > 0)
				{
					$fp = fopen($this->configFile,'w') or dir("Write Config File Error! ");
					flock($fp,3);
					fwrite($fp,'<'.'?php'."\r\n");
					foreach($this->tpCfgs as $k=>$v)
					{
						$v = str_replace("\"","\\\"",$v);
						$v = str_replace("\$","\\\$",$v);
						fwrite($fp,"\$this->tpCfgs['$k']=\"$v\";\r\n");
					}
					fwrite($fp,'?'.'>');
					fclose($fp);
				}
			}
		}
		else
		{
			if($ctype=='config' && count($this->tpCfgs) > 0 )
			{
				$fp = fopen($this->configFile,'w') or dir("Write Config File Error! ");
				flock($fp,3);
				fwrite($fp,'<'.'?php'."\r\n");
				foreach($this->tpCfgs as $k=>$v)
				{
					$v = str_replace("\"","\\\"",$v);
					$v = str_replace("\$","\\\$",$v);
					fwrite($fp,"\$this->tpCfgs['$k']=\"$v\";\r\n");
				}
				fwrite($fp,'?'.'>');
				fclose($fp);
			}
		}
		*/
	}

	//���ģ���ļ�����md5�ַ���
	function GetEncodeStr($tmpfile)
	{
		//$tmpfiles = explode('/',$tmpfile);
		$encodeStr = substr(md5($tmpfile),0,24);
		return $encodeStr;
	}

	//����ģ��
	function ParseTemplate()
	{
		if($this->makeLoop > 5)
		{
			return ;
		}
		$this->count = -1;
		$this->cTags = array();
		$this->isParse = true;
		$sPos = 0;
		$ePos = 0;
		$tagStartWord =  $this->tagStartWord;
		$fullTagEndWord =  $this->fullTagEndWord;
		$sTagEndWord = $this->sTagEndWord;
		$tagEndWord = $this->tagEndWord;
		$startWordLen = strlen($tagStartWord);
		$sourceLen = strlen($this->sourceString);
		if( $sourceLen <= ($startWordLen + 3) )
		{
			return;
		}
		$cAtt = new TagAttributeParse();
		$cAtt->CharToLow = true;

		//����ģ���ַ�������ȡ��Ǽ���������Ϣ
		$t = 0;
		$preTag = '';
		$tswLen = strlen($tagStartWord);
		for($i=0; $i<$sourceLen; $i++)
		{
			$ttagName = '';

			//��������д��жϣ����޷�ʶ���������������
			if($i-1>=0)
			{
				$ss = $i-1;
			}
			else
			{
				$ss = 0;
			}
			$tagPos = strpos($this->sourceString,$tagStartWord,$ss);

			//�жϺ����Ƿ���ģ����
			if($tagPos==0 && ($sourceLen-$i < $tswLen
			|| substr($this->sourceString,$i,$tswLen)!=$tagStartWord ))
			{
				$tagPos = -1;
				break;
			}

			//��ȡTAG������Ϣ
			for($j = $tagPos+$startWordLen; $j < $tagPos+$startWordLen+$this->tagMaxLen; $j++)
			{
				if(ereg("[ >/\r\n\t\}\.]",$this->sourceString[$j]))
				{
					break;
				}
				else
				{
					$ttagName .= $this->sourceString[$j];
				}
			}
			if($ttagName!='')
			{
				$i = $tagPos + $startWordLen;
				$endPos = -1;

				//�ж�  '/}' '{tag:��һ��ǿ�ʼ' '{/tag:��ǽ���' ˭���
				$fullTagEndWordThis = $fullTagEndWord.$ttagName.$tagEndWord;
				$e1 = strpos($this->sourceString,$sTagEndWord, $i);
				$e2 = strpos($this->sourceString,$tagStartWord, $i);
				$e3 = strpos($this->sourceString,$fullTagEndWordThis,$i);
				$e1 = trim($e1); $e2 = trim($e2); $e3 = trim($e3);
				$e1 = ($e1=='' ? '-1' : $e1);
				$e2 = ($e2=='' ? '-1' : $e2);
				$e3 = ($e3=='' ? '-1' : $e3);
				if($e3==-1)
				{
					//������'{/tag:���'
					$endPos = $e1;
					$elen = $endPos + strlen($sTagEndWord);
				}
				else if($e1==-1)
				{
					//������ '/}'
					$endPos = $e3;
					$elen = $endPos + strlen($fullTagEndWordThis);
				}

				//ͬʱ���� '/}' �� '{/tag:���'
				else
				{
					//��� '/}' �� '{tag:'��'{/tag:���' ��Ҫ����������Ϊ������־�� '/}'�����������־Ϊ '{/tag:���'
					if($e1 < $e2 &&  $e1 < $e3 )
					{
						$endPos = $e1;
						$elen = $endPos + strlen($sTagEndWord);
					}
					else
					{
						$endPos = $e3;
						$elen = $endPos + strlen($fullTagEndWordThis);
					}
				}

				//����Ҳ���������ǣ�����Ϊ�����Ǵ��ڴ���
				if($endPos==-1)
				{
					echo "Character postion $tagPos, '$ttagName' Error��<br />\r\n";
					break;
				}
				$i = $elen;

				//�������ҵ��ı��λ�õ���Ϣ
				$attStr = '';
				$innerText = '';
				$startInner = 0;
				for($j = $tagPos+$startWordLen; $j < $endPos; $j++)
				{
					if($startInner==0)
					{
						if($this->sourceString[$j]==$tagEndWord)
						{
							$startInner=1; continue;
						 }
						else
						{
							$attStr .= $this->sourceString[$j];
						}
					}
					else
					{
						$innerText .= $this->sourceString[$j];
					}
				}
				$ttagName = strtolower($ttagName);

				//if��php��ǣ����������Դ���Ϊ����
				if(ereg("^if[0-9]{0,}$",$ttagName))
				{
					$cAtt->cAttributes = new TagAttribute();
					$cAtt->cAttributes->count = 2;
					$cAtt->cAttributes->items['tagname'] = $ttagName;
					$cAtt->cAttributes->items['condition'] = ereg_replace("^if[0-9]{0,}[\r\n\t ]","",$attStr);
					$innerText = eregi_replace("\{else\}",'<'."?php\r\n}\r\nelse{\r\n".'?'.'>',$innerText);
				}
				else if($ttagName=='php')
				{
					$cAtt->cAttributes = new TagAttribute();
					$cAtt->cAttributes->count = 2;
					$cAtt->cAttributes->items['tagname'] = $ttagName;
					$cAtt->cAttributes->items['code'] = '<'."?php\r\n".trim(ereg_replace("^php[0-9]{0,}[\r\n\t ]","",$attStr))."\r\n?".'>';
				}
				else
				{
					//��ͨ��ǣ���������
					$cAtt->SetSource($attStr);
				}
				$this->count++;
				$cTag = new Tag();
				$cTag->tagName = $ttagName;
				$cTag->startPos = $tagPos;
				$cTag->endPos = $i;
				$cTag->cAtt = $cAtt->cAttributes;
				$cTag->isCompiler = false;
				$cTag->tagID = $this->count;
				$cTag->innerText = $innerText;
				$this->cTags[$this->count] = $cTag;
			}
			else
			{
				$i = $tagPos+$startWordLen;
				break;
			}
		}//��������ģ���ַ���
		if( $this->count > -1 && $this->isCompiler )
		{
			$this->CompilerAll();
		}
	}


	//��ģ����ת��ΪPHP����
	function CompilerAll()
	{
		$this->loopNum++;
		if($this->loopNum > 10)
		{
			return; //�������ݹ����Ϊ 10 �Է�ֹ���ǳ���ȿ����Ե�����ѭ��
		}
		$ResultString = '';
		$nextTagEnd = 0;
		for($i=0; isset($this->cTags[$i]); $i++)
		{
			$ResultString .= substr($this->sourceString, $nextTagEnd, $this->cTags[$i]->startPos - $nextTagEnd);
			$ResultString .= $this->CompilerOneTag($this->cTags[$i]);
			$nextTagEnd = $this->cTags[$i]->endPos;
		}
		$slen = strlen($this->sourceString);
		if($slen > $nextTagEnd)
		{
			$ResultString .= substr($this->sourceString,$nextTagEnd,$slen-$nextTagEnd);
		}
		$this->sourceString = $ResultString;
		$this->ParseTemplate();
	}


	//������ս��
	function GetResult()
	{
		if(!$this->isParse)
		{
			$this->ParseTemplate();
		}
		$addset = '';
		$addset .= '<'.'?php'."\r\n".'if(!isset($GLOBALS[\'_vars\'])) $GLOBALS[\'_vars\'] = array(); '."\r\n".'$fields = array();'."\r\n".'?'.'>';
		return ereg_replace("\?".">[ \r\n\t]{0,}<"."\?php","",$addset.$this->sourceString);
	}

	//���뵥�����
	function CompilerOneTag(&$cTag)
	{
		$cTag->isCompiler = true;
		$tagname = $cTag->tagName;
		$varname = $cTag->GetAtt('name');
		$rsvalue = "";

		//������ģ��������һ���������ṩ����չ��;
		//�˱���ֱ���ύ�� this->tpCfgs �У�����������ģ���Ӧ�Ļ����ļ� ***_config.php �ļ�
		if( $tagname == 'config' )
		{
			$this->tpCfgs[$varname] = $cTag->GetAtt('value');
		}
		else if( $tagname == 'global' )
		{
			$cTag->tagValue = $this->CompilerArrayVar('global',$varname);
			if( $cTag->GetAtt('function') != '' )
			{
				$cTag->tagValue = $this->CompilerFunction($cTag->GetAtt('function'), $cTag->tagValue);
			}
			$cTag->tagValue = '<'.'?php echo '.$cTag->tagValue.'; ?'.'>';
		}
		else if( $tagname == 'cfg' )
		{
			$cTag->tagValue = '$GLOBALS[\'cfg_'.$varname.'\']'; //������
			if( $cTag->GetAtt('function')!='' )
			{
				$cTag->tagValue = $this->CompilerFunction($cTag->GetAtt('function'), $cTag->tagValue);
			}
			$cTag->tagValue = '<'.'?php echo '.$cTag->tagValue.'; ?'.'>';
		}
		else if( $tagname == 'object' )
		{
			list($_obs,$_em) = explode('->',$varname);
			$cTag->tagValue = "\$GLOBALS['{$_obs}']->{$_em}"; //������
			if( $cTag->GetAtt('function')!='' )
			{
				$cTag->tagValue = $this->CompilerFunction($cTag->GetAtt('function'), $cTag->tagValue);
			}
			$cTag->tagValue = '<'.'?php echo '.$cTag->tagValue.'; ?'.'>';
		}
		else if($tagname == 'var')
		{
			$cTag->tagValue = $this->CompilerArrayVar('var', $varname);

			if( $cTag->GetAtt('function')!='' )
			{
				$cTag->tagValue = $this->CompilerFunction($cTag->GetAtt('function'), $cTag->tagValue);
			}
			$cTag->tagValue = '<'.'?php echo '.$cTag->tagValue.'; ?'.'>';
		}
		else if($tagname == 'field')
		{
			$cTag->tagValue = '$fields[\''.$varname.'\']';
			if( $cTag->GetAtt('function')!='' )
			{
				$cTag->tagValue = $this->CompilerFunction($cTag->GetAtt('function'), $cTag->tagValue);
			}
			$cTag->tagValue = '<'.'?php echo '.$cTag->tagValue.'; ?'.'>';
		}
		else if( ereg("^key[0-9]{0,}",$tagname) || ereg("^value[0-9]{0,}",$tagname))
		{
			if( ereg("^value[0-9]{0,}",$tagname) && $varname!='' )
			{
				$cTag->tagValue = '<'.'?php echo '.$this->CompilerArrayVar($tagname,$varname).'; ?'.'>';
			}
			else
			{
				$cTag->tagValue = '<'.'?php echo $'.$tagname.'; ?'.'>';
			}
		}
		else if( ereg("^if[0-9]{0,}$",$tagname) )
		{
			$cTag->tagValue = $this->CompilerIf($cTag);
		}
		else if( $tagname=='php' )
		{
			if(trim($cTag->GetInnerText())=='') $cTag->tagValue = $cTag->GetAtt('code');
			else
			{
				$cTag->tagValue =  '<'."?php\r\n".trim($cTag->GetInnerText())."\r\n?".'>';
			}
		}

		//��������
		else if( ereg("^array[0-9]{0,}",$tagname) )
		{
			$kk = '$key';
			$vv = '$value';
			if($cTag->GetAtt('key')!='')
			{
				$kk = '$key'.$cTag->GetAtt('key');
			}
			if($cTag->GetAtt('value')!='')
			{
				$vv = '$value'.$cTag->GetAtt('value');
			}
			$addvar = '';
			if(!ereg("\(",$varname))
			{
				$varname = '$GLOBALS[\''.$varname.'\']';
			}
			else
			{
				$addvar = "\r\n".'$myarrs = $pageClass->'.$varname.";\r\n";
				$varname = ' $myarrs ';
			}
			$rsvalue = '<'.'?php '.$addvar.' foreach('.$varname.' as '.$kk.'=>'.$vv.'){ ?'.">";
			$rsvalue .= $cTag->GetInnerText();
			$rsvalue .= '<'.'?php  }    ?'.">\r\n";
			$cTag->tagValue = $rsvalue;
		}

		//include �ļ�
		else if($tagname == 'include')
		{
			$filename = $cTag->GetAtt('file');
			if($filename=='')
			{
				$filename = $cTag->GetAtt('filename');
			}
			$cTag->tagValue = $this->CompilerInclude($filename, false);
			if($cTag->tagValue==0) $cTag->tagValue = '';
			$cTag->tagValue = '<'.'?php include $this->CompilerInclude("'.$filename.'");'."\r\n".' ?'.'>';
		}
		else if( $tagname=='label' )
		{
			$bindFunc = $cTag->GetAtt('bind');
			$rsvalue = 'echo '.$bindFunc.";\r\n";
			$rsvalue = '<'.'?php  '.$rsvalue.'  ?'.">\r\n";
			$cTag->tagValue = $rsvalue;
		}
		else if( $tagname=='datalist' )
		{
			//������������
			foreach($cTag->cAtt->items as $k=>$v)
			{
				$v = $this->TrimAtts($v);
				$rsvalue .= '$atts[\''.$k.'\'] = \''.str_replace("'","\\'",$v)."';\r\n";
			}
			$rsvalue = '<'.'?php'."\r\n".'$atts = array();'."\r\n".$rsvalue;
			$rsvalue .= '$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); '."\r\n";
			$rsvalue .= 'foreach( $blockValue as $key=>$fields )'."\r\n{\r\n".'?'.">";
			$rsvalue .= $cTag->GetInnerText();
			$rsvalue .= '<'.'?php'."\r\n}\r\n".'?'.'>';
			$cTag->tagValue = $rsvalue;
		}
		else if( $tagname=='pagelist' )
		{
			//������������
			foreach($cTag->cAtt->items as $k=>$v)
			{
				$v = $this->TrimAtts($v);
				$rsvalue .= '$atts[\''.$k.'\'] = \''.str_replace("'","\\'",$v)."';\r\n";
			}
			$rsvalue = '<'.'?php'."\r\n".'$atts = array();'."\r\n".$rsvalue;
			$rsvalue .= ' echo $this->refObj->GetPageList($atts,$this->refObj,$fields); '."\r\n".'?'.">\r\n";
			$cTag->tagValue = $rsvalue;
		}
		else
		{
			$bindFunc = $cTag->GetAtt('bind');
			$bindType = $cTag->GetAtt('bindtype');
			$rstype =  ($cTag->GetAtt('resulttype')=='' ? $cTag->GetAtt('rstype') : $cTag->GetAtt('resulttype') );
			$rstype = strtolower($rstype);

			//������������
			foreach($cTag->cAtt->items as $k=>$v)
			{
				if(eregi("(bind|bindtype)",$k))
				{
					continue;
				}
				$v = $this->TrimAtts($v);
				$rsvalue .= '$atts[\''.$k.'\'] = \''.str_replace("'","\\'",$v)."';\r\n";
			}
			$rsvalue = '<'.'?php'."\r\n".'$atts = array();'."\r\n".$rsvalue;

			//�󶨵�Ĭ�Ϻ�������ָ������(datasource����ָ��)
			if($bindFunc=='')
			{
				$rsvalue .= '$blockValue = MakePublicTag($atts,$this->refObj,$fields); '."\r\n";
			}
			else
			{
				//�Զ���󶨺��������ָ�� bindtype����ָ��$this->refObj->�󶨺�����������Ĭ��ָ�����õ������
				if($bindType=='') $rsvalue .= '$blockValue = $this->refObj->'.$bindFunc.'($atts,$this->refObj,$fields); '."\r\n";
				else $rsvalue .= '$blockValue = '.$bindFunc.'($atts,$this->refObj,$fields); '."\r\n";
			}

			//���ؽ�����ͣ�Ĭ��Ϊ array ��һ����ά���飬string ���ַ���
			if($rstype=='string')
			{
				$rsvalue .= 'echo $blockValue;'."\r\n".'?'.">";
			}
			else
			{
				$rsvalue .= 'if(is_array($blockValue) && count($blockValue) > 0){'."\r\n";
				$rsvalue .= 'foreach( $blockValue as $key=>$fields )'."\r\n{\r\n".'?'.">";
				$rsvalue .= $cTag->GetInnerText();
				$rsvalue .= '<'.'?php'."\r\n}\r\n}\r\n".'?'.'>';
			}
			$cTag->tagValue = $rsvalue;
		}
		return $cTag->tagValue;
	}


	//�������Ϊ����ı���
	function CompilerArrayVar($vartype,$varname)
	{
		$okvalue = '';

		if(!ereg("\[",$varname))
		{
			if(ereg("^value",$vartype))
			{
				$varname = $vartype.'.'.$varname;
			}
			$varnames = explode('.',$varname);
			if(isset($varnames[1]))
			{
				$varname = $varnames[0];
				for($i=1; isset($varnames[$i]); $i++)
				{
					$varname .= "['".$varnames[$i]."']";
				}
			}
		}

		if(ereg("\[",$varname))
		{
			$varnames = explode('[',$varname);
			$arrend = '';
			for($i=1;isset($varnames[$i]);$i++)
			{
				$arrend .= '['.$varnames[$i];
			}
			if(!ereg("[\"']", $arrend)) {
				$arrend = str_replace('[', '', $arrend);
				$arrend = str_replace(']', '', $arrend);
				$arrend = "['{$arrend}']";
			}
			if($vartype=='var')
			{
				$okvalue = '$GLOBALS[\'_vars\'][\''.$varnames[0].'\']'.$arrend;
			}
			else if( ereg("^value",$vartype) )
			{
				$okvalue = '$'.$varnames[0].$arrend;
			}
			else if($vartype=='field')
			{
				$okvalue = '$fields[\''.$varnames[0].'\']'.$arrend;
			}
			else
			{
				$okvalue = '$GLOBALS[\''.$varnames[0].'\']'.$arrend;
			}
		}
		else
		{
			if($vartype=='var')
			{
				$okvalue = '$GLOBALS[\'_vars\'][\''.$varname.'\']';
			}
			else if( ereg("^value",$vartype) )
			{
				$okvalue = '$'.$vartype;
			}
			else if($vartype=='field')
			{
				$okvalue = '$'.str_replace($varname);
			}
			else
			{
				$okvalue = '$GLOBALS[\''.$varname.'\']';
			}
		}
		return $okvalue;
	}

	//����if���
	function CompilerIf($cTag)
	{
		$condition = trim($cTag->GetAtt('condition'));
		if($condition =='')
		{
			$cTag->tagValue=''; return '';
		}
		$condition = preg_replace("/((var\.|field\.|cfg\.|global\.|key[0-9]{0,}\.|value[0-9]{0,}\.)[\._a-z0-9]+)/ies", "private_rt('\\1')", $condition);
		$rsvalue = '<'.'?php if('.$condition.'){ ?'.'>';
		$rsvalue .= $cTag->GetInnerText();
		$rsvalue .= '<'.'?php } ?'.'>';
		return $rsvalue;
	}

	//����block���鴫�ݵ�atts���Ե�ֵ
	function TrimAtts($v)
	{
		$v = str_replace('<'.'?','&lt;?',$v);
		$v = str_replace('?'.'>','?&gt;',$v);
		return  $v;
	}

	//���� function �﷨����
	function CompilerFunction($funcstr,$nvalue)
	{
		$funcstr = str_replace('@quote','"',$funcstr);
		$funcstr = str_replace('@me',$nvalue,$funcstr);
		return $funcstr;
	}

	//�����ļ� include �﷨����
	function CompilerInclude($filename, $isload=true)
	{
		$okfile = '';
		if( @file_exists($filename) )
		{
			$okfile = $filename;
		}
		else if( @file_exists($this->refDir.$filename) )
		{
			$okfile = $this->refDir.$filename;
		}
		else if( @file_exists($this->refDir."../".$filename) )
		{
			$okfile = $this->refDir."../".$filename;
		}
		if($okfile=='') return 0;
		if( !$isload ) return 1;
		$itpl = new DedeTemplate($this->templateDir);
		$itpl->isCache = $this->isCache;
		$itpl->SetObject($this->refObj);
		$itpl->LoadTemplate($okfile);
		return $itpl->CacheFile();
	}
}

/*------------------------
//class TagAttribute Tag���Լ���
function C__TagAttribute();
-------------------------*/
//���Ե���������
class TagAttribute
{
	var $count = -1;
	var $items = ""; //����Ԫ�صļ���

	//���ĳ������
	function GetAtt($str)
	{
		if($str=="")
		{
			return "";
		}
		if(isset($this->items[$str]))
		{
			return $this->items[$str];
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
		if(isset($this->items[$str])) return true;
		else return false;
	}

	//��ñ������
	function GettagName()
	{
		return $this->GetAtt("tagname");
	}

	// ������Ը���
	function Getcount()
	{
		return $this->count+1;
	}
}

/*-------------------------------
//���Խ�����
function C__TagAttributeParse();
-------------------------------*/
class TagAttributeParse
{
	var $sourceString = "";
	var $sourceMaxSize = 1024;
	var $cAttributes = "";
	var $charToLow = true;
	function SetSource($str="")
	{
		$this->cAttributes = new TagAttribute();
		$strLen = 0;
		$this->sourceString = trim(preg_replace("/[ \r\n\t\f]{1,}/"," ",$str));
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
				$this->cAttributes->count++;
				$tmpvalues = explode('.', $tmpvalue);
				$this->cAttributes->items['tagname'] = ($this->charToLow ? strtolower($tmpvalues[0]) : $tmpvalues[0]);
				if( isset($tmpvalues[2]) )
				{
					$okname = $tmpvalues[1];
					for($j=2;isset($tmpvalues[$j]);$j++)
					{
						$okname .= "['".$tmpvalues[$j]."']";
					}
					$this->cAttributes->items['name'] = $okname;
				}
				else if(isset($tmpvalues[1]) && $tmpvalues[1]!='')
				{
					$this->cAttributes->items['name'] = $tmpvalues[1];
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
			$this->cAttributes->count++;
			$tmpvalues = explode('.', $tmpvalue);
			$this->cAttributes->items['tagname'] = ($this->charToLow ? strtolower($tmpvalues[0]) : $tmpvalues[0]);
			if( isset($tmpvalues[2]) )
			{
				$okname = $tmpvalues[1];
				for($i=2;isset($tmpvalues[$i]);$i++)
				{
					$okname .= "['".$tmpvalues[$i]."']";
				 }
				$this->cAttributes->items['name'] = $okname;
			}
			else if(isset($tmpvalues[1]) && $tmpvalues[1]!='')
			{
				$this->cAttributes->items['name'] = $tmpvalues[1];
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
					case '\'':
						$ddtag = '\'';
						$startdd = 1;
						break;
					case '"':
						$ddtag = '"';
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
					$this->cAttributes->count++;
					$this->cAttributes->items[$tmpatt] = trim($tmpvalue);
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
			$this->cAttributes->count++;
			$this->cAttributes->items[$tmpatt] = trim($tmpvalue);
		}//print_r($this->cAttributes->items);

	}// end func

}

function private_rt($str)
{
	$arr = explode('.', $str);

	$rs = '$GLOBALS[\'';
	if($arr[0] == 'cfg')
	{
		return $rs.'cfg_'.$arr[1]."']";
	}
	elseif($arr[0] == 'var')
	{
		$arr[0] = '_vars';
		$rs .= implode('\'][\'', $arr);
		$rs .= "']";
		return $rs;
	}
	elseif($arr[0] == 'global')
	{
		unset($arr[0]);
		$rs .= implode('\'][\'', $arr);
		$rs .= "']";
		return $rs;
	}
	else
	{
		if($arr[0] == 'field') $arr[0] = 'fields';
		$rs = '$'.$arr[0]."['";
		unset($arr[0]);
		$rs .= implode('\'][\'', $arr);
		$rs .= "']";
		return $rs;
	}
}
?>

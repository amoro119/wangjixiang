<?php
/**********************************************
function c____DedeAtt();
**********************************************/
//���Ե���������
class DedeAtt
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
		return isset($this->Items[$str]) ? true : false;
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
//���Խ�����
function c____DedeAttParse();
********************************/
class DedeAttParse
{
	var $SourceString = "";
	var $SourceMaxSize = 1024;
	var $CAtt = ""; //���Ե�����������
	var $CharToLow = TRUE;

	//////�������Խ�����Դ�ַ���////////////////////////
	function SetSource($str="")
	{
		$this->CAtt = new DedeAtt();
		$strLen = 0;
		$this->SourceString = trim(preg_replace("/[ \t\r\n]{1,}/"," ",$str));
		$strLen = strlen($this->SourceString);
		if($strLen>0&&$strLen<=$this->SourceMaxSize)
		{
			$this->ParseAtt();
		}
	}

	//////��������(˽�г�Ա������SetSource����)/////////////////
	function ParseAtt()
	{
		$d = "";
		$tmpatt="";
		$tmpvalue="";
		$startdd=-1;
		$ddtag="";
		$notAttribute=true;
		$strLen = strlen($this->SourceString);

		// �����ǻ��Tag������,��������Ƿ���Ҫ
		// ���������������,���ڽ�������Tagʱ����
		// �����в�Ӧ�ô���tagname�������
		for($i=0;$i<$strLen;$i++)
		{
			$d = substr($this->SourceString,$i,1);
			if($d==' ')
			{
				$this->CAtt->Count++;
				if($this->CharToLow)
				{
					$this->CAtt->Items["tagname"]=strtolower(trim($tmpvalue));
				}
				else
				{
					$this->CAtt->Items["tagname"]=trim($tmpvalue);
				}
				$tmpvalue = "";
				$notAttribute = false;
				break;
			}
			else
			{
				$tmpvalue .= $d;
			}
		}

		//�����������б�����
		if($notAttribute)
		{
			$this->CAtt->Count++;
			$this->CAtt->Items["tagname"]= ($this->CharToLow ? strtolower(trim($tmpvalue)) : trim($tmpvalue));
		}

		//����ַ�����������ֵ������Դ�ַ���,����ø�����
		if(!$notAttribute)
		{
			for($i;$i<$strLen;$i++)
			{
				$d = substr($this->SourceString,$i,1);
				if($startdd==-1)
				{
					if($d!="=")
					{
						$tmpatt .= $d;
					}
					else
					{
						if($this->CharToLow)
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
				else if($startdd==1)
				{
					if($d==$ddtag)
					{
						$this->CAtt->Count++;
						$this->CAtt->Items[$tmpatt] = trim($tmpvalue);//strtolower(trim($tmpvalue));
						$tmpatt = "";
						$tmpvalue = "";
						$startdd=-1;
					}
					else
					{
						$tmpvalue.=$d;
					}
				}
			}
			if($tmpatt!="")
			{
				$this->CAtt->Count++;
				$this->CAtt->Items[$tmpatt]=trim($tmpvalue);//strtolower(trim($tmpvalue));
			}//������Խ���

		}//for

	}//has Attribute
}
?>
<?php
if(!defined('DEDEMEMBER'))
{
	exit("dedecms");
}

//����Ƿ��Ƽ��ı���
function IsCommendArchives($iscommend)
{
  $s = '';
  if(ereg('c',$iscommend))
  {
  	$s .= '�Ƽ�';
  }
  else if(ereg('h',$iscommend))
  {
  	$s .= ' ͷ��';
  }
  else if(ereg('p',$iscommend))
  {
  	$s .= ' ͼƬ';
  }
  else if(ereg('j',$iscommend))
  {
  	$s .= ' ��ת';
  }
  return $s;
}

//����Ƽ��ı���
function GetCommendTitle($title,$iscommend)
{
	if(ereg('c',$iscommend))
	{
		$title = "$title<font color='red'>(�Ƽ�)</font>";
	}
	return "$title";
}

//������ɫ
$GLOBALS['RndTrunID'] = 1;
function GetColor($color1,$color2)
{
	$GLOBALS['RndTrunID']++;
	if($GLOBALS['RndTrunID']%2==0)
	{
		return $color1;
	}
	else
	{
		return $color2;
	}
}

//���ͼƬ�Ƿ����
function CheckPic($picname)
{
	if($picname!="")
	{
		return $picname;
	}
	else
	{
		return "img/dfpic.gif";
	}
}

//�ж������Ƿ�����HTML
function IsHtmlArchives($ismake)
{
	if($ismake==1)
	{
		return "������";
	}
	else if($ismake==-1)
	{
		return "����̬";
	}
	else
	{
		return "<font color='red'>δ����</font>";
	}
}

//������ݵ��޶���������
function GetRankName($arcrank)
{
	global $arcArray;
	if(!is_array($arcArray))
	{
		$dsql->SetQuery("Select * from #@__arcrank");
		$dsql->Execute();
		while($row = $dsql->GetObject())
		{
			$arcArray[$row->rank]=$row->membername;
		 }
	}
	if(isset($arcArray[$arcrank]))
	{
		return $arcArray[$arcrank];
	}
	else
	{
		return "����";
	}
}

//�ж������Ƿ�ΪͼƬ����
function IsPicArchives($picname)
{
	if($picname!="")
	{
		return "<font color='red'>(ͼ)</font>";
	}
	else
	{
		return "";
	}
}
?>
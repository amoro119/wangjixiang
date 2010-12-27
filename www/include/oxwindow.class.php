<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/dedetag.class.php");

class OxWindow
{
	var $myWin = "";
	var $myWinItem = "";
	var $checkCode = "";
	var $formName = "";
	var $tmpCode = "//checkcode";
	var $hasStart = false;

	//��ʼ��Ϊ������ҳ��
	function Init($formaction="",$checkScript="js/blank.js",$formmethod="POST",$formname="myform")
	{
		$this->myWin .= "<script language='javascript'>\r\n";
		if($checkScript!="" && file_exists($checkScript))
		{
			$fp = fopen($checkScript,"r");
			$this->myWin .= fread($fp,filesize($checkScript));
			fclose($fp);
		}
		else
		{
			$this->myWin .= "<!-- function CheckSubmit()\r\n{ return true; } -->";
		}
		$this->myWin .= "</script>\r\n";
		$this->formName = $formname;
		$this->myWin .= "<form name='$formname' method='$formmethod' onSubmit='return CheckSubmit();' action='$formaction'>\r\n";
	}

	//����������
	function AddHidden($iname,$ivalue)
	{
		$this->myWin .= "<input type='hidden' name='$iname' value='$ivalue'>\r\n";
	}

	function StartWin()
	{
		$this->myWin .= "<table width='100%'  border='0' cellpadding='3' cellspacing='1' bgcolor='#CBD8AC'>\r\n";
	}

	//����һ�����е���
	function AddItem($iname,$ivalue)
	{
		$this->myWinItem .= "<tr bgcolor='#FFFFFF'>\r\n";
		$this->myWinItem .= "<td width='25%'>$iname</td>\r\n";
		$this->myWinItem .= "<td width='75%'>$ivalue</td>\r\n";
		$this->myWinItem .= "</tr>\r\n";
	}

	//����һ�����е���Ϣ��
	function AddMsgItem($ivalue,$height="100",$col="2")
	{
		if($height!=""&&$height!="0")
		{
			$height = " height='$height'";
		}
		else
		{
			$height="";
		}
		if($col!=""&&$col!=0)
		{
			$colspan="colspan='$col'";
		}
		else
		{
			$colspan="";
		}
		$this->myWinItem .= "<tr bgcolor='#FFFFFF'>\r\n";
		$this->myWinItem .= "<td $colspan $height> $ivalue </td>\r\n";
		$this->myWinItem .= "</tr>\r\n";
	}

	//���ӵ��еı�����
	function AddTitle($title,$col="2")
	{
		global $cfg_plus_dir;
		if($col!=""&&$col!="0")
		{
			$colspan="colspan='$col'";
		}
		else
		{
			$colspan="";
		}
		$this->myWinItem .= "<tr bgcolor='#EEF4EA'>\r\n";
		$this->myWinItem .= "<td $colspan background='{$cfg_plus_dir}/img/wbg.gif' height='26'><font color='#666600'><b>$title</b></font></td>\r\n";
		$this->myWinItem .= "</tr>\r\n";
	}

	//����Window
	function CloseWin($isform=true)
	{
		if(!$isform)
		{
			$this->myWin .= "</table>\r\n";
		}
		else
		{
			$this->myWin .= "</table></form>\r\n";
		}
	}


	//�����Զ���JS�ű�-
	function SetCheckScript($scripts)
	{
		$pos = strpos($this->myWin,$this->tmpCode);
		if($pos>0)
		{
			$this->myWin = substr_replace($this->myWin,$scripts,$pos,strlen($this->tmpCode));
		}
	}


	//��ȡ����
	function GetWindow($wintype="save",$msg="",$isform=true)
	{
		global $cfg_plus_dir;
		$this->StartWin();
		$this->myWin .= $this->myWinItem;
		if($wintype!="")
		{
			if($wintype!="hand")
			{
				$this->myWin .= "
<tr>
<td colspan='2' bgcolor='#f7ffdb'>
<table width='270' border='0' cellpadding='0' cellspacing='0'>
<tr align='center' height='28'>
<td width='90'><input name='imageField1' type='image' class='np' src='{$cfg_plus_dir}/img/button_".$wintype.".gif' width='60' height='22' border='0' /></td>
<td width='90'><a href='#'><img class='np' src='{$cfg_plus_dir}/img/button_reset.gif' width='60' height='22' border='0' onClick='this.form.reset();return false;' /></a></td>
<td><a href='#'><img src='{$cfg_plus_dir}/img/button_back.gif' width='60' height='22' border='0' onClick='history.go(-1);' /></a></td>
</tr>
</table>
</td>
</tr>";
			}
			else
			{
				if($msg!='')
				{
					$this->myWin .= "<tr><td bgcolor='#f7ffdb'>$msg</td></tr>";
				}
				else
				{
					$this->myWin .= '';
				}
			}
		}
		$this->CloseWin($isform);
		return $this->myWin;
	}

	//��ʾҳ��
	function Display($modfile="")
	{
		global $cfg_templets_dir,$wecome_info,$cfg_basedir;
		if(empty($wecome_info))
		{
			$wecome_info = "DedeCms OX ͨ�öԻ���";
		}
		$ctp = new DedeTagParse();
		if($modfile=='')
		{
			$ctp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/win_templet.htm');
		}
		else
		{
			$ctp->LoadTemplate($modfile);
		}
		$emnum = $ctp->Count;
		for($i=0;$i<=$emnum;$i++)
		{
			if(isset($GLOBALS[$ctp->CTags[$i]->GetTagName()]))
			{
				$ctp->Assign($i,$GLOBALS[$ctp->CTags[$i]->GetTagName()]);
			}
		}
		$ctp->Display();
		$ctp->Clear();
	}
}

/*------
��ʾһ������������ͨ��ʾ
-------*/
function ShowMsgWin($msg,$title)
{
	$win = new OxWindow();
	$win->Init();
	$win->mainTitle = "DeDeCmsϵͳ��ʾ��";
	$win->AddTitle($title);
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow("hand");
	$win->Display();
}
?>
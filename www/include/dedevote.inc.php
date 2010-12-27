<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/dedetag.class.php");

class DedeVote
{
	var $VoteInfos;
	var $VoteNotes;
	var $VoteCount;
	var $VoteID;
	var $dsql;

	//php5���캯��
	function __construct($aid)
	{
		$this->dsql = $GLOBALS['dsql'];
		$this->VoteInfos = $this->dsql->GetOne("Select * From `#@__vote` where aid='$aid'");
		$this->VoteNotes = Array();
		$this->VoteCount = 0;
		$this->VoteID = $aid;
		if(!is_array($this->VoteInfos))
		{
			return;
		}
		$dtp = new DedeTagParse();
		$dtp->SetNameSpace("v","<",">");
		$dtp->LoadSource($this->VoteInfos['votenote']);
		if(is_array($dtp->CTags))
		{
			foreach($dtp->CTags as $ctag)
			{
				$this->VoteNotes[$ctag->GetAtt('id')]['count'] = $ctag->GetAtt('count');
				$this->VoteNotes[$ctag->GetAtt('id')]['name'] = trim($ctag->GetInnerText());
				$this->VoteCount++;
			}
		}
		$dtp->Clear();
	}

	function DedeVote($aid)
	{
		$this->__construct($aid);
	}

	function Close()
	{
	}

	//���ͶƱ��Ŀ��ͶƱ����
	function GetTotalCount()
	{
		if(!empty($this->VoteInfos["totalcount"]))
		{
			return $this->VoteInfos["totalcount"];
		}
		else
		{
			return 0;
		}
	}

	//����ָ����ͶƱ�ڵ��Ʊ��
	function AddVoteCount($aid)
	{
		if(isset($this->VoteNotes[$aid]))
		{
			$this->VoteNotes[$aid]['count']++;
		}
	}

	//�����Ŀ��ͶƱ��
	function GetVoteForm($lineheight=24,$tablewidth="100%",$titlebgcolor="#EDEDE2",$titlebackgroup="",$tablebg="#FFFFFF",$itembgcolor="#FFFFFF")
	{
		//ʡ�Բ���
		if($lineheight=="")
		{
			$lineheight=24;
		}
		if($tablewidth=="")
		{
			$tablewidth="100%";
		}
		if($titlebgcolor=="")
		{
			$titlebgcolor="#EDEDE2";
		}
		if($titlebackgroup!="")
		{
			$titlebackgroup="background='$titlebackgroup'";
		}
		if($tablebg=="")
		{
			$tablebg="#FFFFFF";
		}
		if($itembgcolor=="")
		{
			$itembgcolor="#FFFFFF";
		}
		$items = "<table width='$tablewidth' border='0' cellspacing='1' cellpadding='1' bgcolor='$tablebg'>\r\n";
		$items .= "<form name='voteform' method='post' action='".$GLOBALS['cfg_phpurl']."/vote.php' target='_blank'>\r\n";
		$items .= "<input type='hidden' name='dopost' value='send'>\r\n";
		$items .= "<input type='hidden' name='aid' value='".$this->VoteID."'>\r\n";
		$items .= "<input type='hidden' name='ismore' value='".$this->VoteInfos['ismore']."'>\r\n";
		$items.="<tr align='center'><td height='$lineheight' bgcolor='$titlebgcolor' $titlebackgroup>".$this->VoteInfos['votename']."</td></tr>\r\n";
		if($this->VoteCount > 0)
		{
			foreach($this->VoteNotes as $k=>$arr)
			{
				if($this->VoteInfos['ismore']==0)
				{
					$items.="<tr><td height=$lineheight bgcolor=$itembgcolor><input type='radio' name='voteitem' value='$k'>".$arr['name']."</td></tr>\r\n";
				}
				else
				{
					$items.="<tr><td height=$lineheight bgcolor=$itembgcolor><input type=checkbox name='voteitem[]' value='$k'>".$arr['name']."</td></tr>\r\n";
				}
			}
			$items .= "<tr><td height='$lineheight' bgcolor='#FFFFFF'>\r\n";
			$items .= "<input type='submit' style='width:40;background-color:$titlebgcolor;border:1px soild #818279' name='vbt1' value='ͶƱ'>\r\n";
			$items .= "<input type='button' style='width:80;background-color:$titlebgcolor;border:1px soild #818279' name='vbt2' ";
			$items .= "value='�鿴���' onClick=\"window.open('".$GLOBALS['cfg_phpurl']."/vote.php?dopost=view&aid=".$this->VoteID."');\"></td></tr>\r\n";
		}

		$items.="</form>\r\n</table>\r\n";
		return $items;
	}

	//����ͶƱ����
	//�벻Ҫ������κ�����֮ǰʹ��SaveVote()����!
	function SaveVote($voteitem)
	{
		if(empty($voteitem))
		{
			return '��ûѡ���κ���Ŀ��';
		}
		$items = '';

		//���ͶƱ�Ƿ��ѹ���
		$nowtime = time();
		if($nowtime > $this->VoteInfos['endtime'])
		{
			return 'ͶƱ�Ѿ����ڣ�';
		}
		if($nowtime < $this->VoteInfos['starttime'])
		{
			return 'ͶƱ��û�п�ʼ��';
		}

		//����û��Ƿ���Ͷ��Ʊ��cookie��Լ����Լʮ��
		if(isset($_COOKIE['DEDE_VOTENAME_AAA']))
		{
			if($_COOKIE['DEDE_VOTENAME_AAA']==$this->VoteInfos['aid'])
			{
				return '���Ѿ�Ͷ��Ʊ��';
			}
			else
			{
				setcookie('DEDE_VOTENAME_AAA',$this->VoteInfos['aid'],time()+360000,'/');
			}
		}
		else
		{
			setcookie('DEDE_VOTENAME_AAA',$this->VoteInfos['aid'],time()+360000,'/');
		}

		//�������ͶƱ��Ŀ
		if($this->VoteCount > 0)
		{
			foreach($this->VoteNotes as $k=>$v)
			{
				if($this->VoteInfos['ismore']==0)
				{
					//��ѡ��
					if($voteitem == $k)
					{
						$this->VoteNotes[$k]['count']++; break;
					}
				}
				else
				{
					//��ѡ��
					if(is_array($voteitem) && in_array($k,$voteitem))
					{
						$this->VoteNotes[$k]['count']++;
					}
				}
			}
			foreach($this->VoteNotes as $k=>$arr)
			{
				$items .= "<v:note id='$k' count='".$arr['count']."'>".$arr['name']."</v:note>\r\n";
			}
		}
		$this->dsql->ExecuteNoneQuery("Update `#@__vote` set totalcount='".($this->VoteInfos['totalcount']+1)."',votenote='".addslashes($items)."' where aid='".$this->VoteID."'");
		return "ͶƱ�ɹ���";
	}

	//�����Ŀ��ͶƱ���
	function GetVoteResult($tablewidth="600",$lineheight="24",$tablesplit="40%")
	{
		$totalcount = $this->VoteInfos['totalcount'];
		if($totalcount==0)
		{
			$totalcount=1;
		}
		$res = "<table width='$tablewidth' border='0' cellspacing='1' cellpadding='1'>\r\n";
		$res .= "<tr height='8'><td width='$tablesplit'></td><td></td></tr>\r\n";
		$i=1;
		foreach($this->VoteNotes as $k=>$arr)
		{
			$res .= "<tr height='$lineheight'><td style='border-bottom:1px solid'>".$i."��".$arr['name']."</td>";
			$c = $arr['count'];
			$res .= "<td style='border-bottom:1px solid'>
			<table border='0' cellspacing='0' cellpadding='2' width='".(($c/$totalcount)*100)."%'><tr><td height='16' background='img/votebg.gif' style='border:1px solid #666666;font-size:9pt;line-height:110%'>".$arr['count']."</td></tr></table>
			</td></tr>\r\n";
		}
		$res .= "<tr><td></td><td></td></tr>\r\n";
		$res .= "</table>\r\n";
		return $res;
	}
}
?>
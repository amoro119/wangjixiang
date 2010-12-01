<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/channelunit.class.php");
require_once(DEDEINC.'/ftp.class.php');

@set_time_limit(0);
class SpecView
{
	var $dsql;
	var $dtp;
	var $dtp2;
	var $TypeID;
	var $TypeLink;
	var $PageNo;
	var $TotalPage;
	var $TotalResult;
	var $PageSize;
	var $ChannelUnit;
	var $ListType;
	var $TempInfos;
	var $Fields;
	var $StartTime;
	var $ftp;
	var $remoteDir;

	//php5���캯��
	function __construct($starttime=0)
	{
		global $ftp;
		$this->TypeID = 0;
		$this->dsql = $GLOBALS['dsql'];
		$this->dtp = new DedeTagParse();
		$this->dtp->SetRefObj($this);
		$this->dtp->SetNameSpace("dede","{","}");
		$this->dtp2 = new DedeTagParse();
		$this->dtp2->SetNameSpace("field","[","]");
		$this->TypeLink = new TypeLink(0);
		$this->ChannelUnit = new ChannelUnit(-1);
		$this->ftp = &$ftp;
		$this->remoteDir = '';

		//����һЩȫ�ֲ�����ֵ
		foreach($GLOBALS['PubFields'] as $k=>$v)
		{
			$this->Fields[$k] = $v;
		}
		if($starttime==0)
		{
			$this->StartTime = 0;
		}
		else
		{
			$this->StartTime = GetMkTime($starttime);
		}
		$this->CountRecord();
		$tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/list_spec.htm";
		if(!file_exists($tempfile)||!is_file($tempfile))
		{
			echo "ģ���ļ������ڣ��޷������ĵ���";
			exit();
		}
		$this->dtp->LoadTemplate($tempfile);
		$this->TempInfos['tags'] = $this->dtp->CTags;
		$this->TempInfos['source'] = $this->dtp->SourceString;
		$ctag = $this->dtp->GetTag("page");
		if(!is_object($ctag))
		{
			$this->PageSize = 20;
		}
		else
		{
			if($ctag->GetAtt("pagesize")!="")
			{
				$this->PageSize = $ctag->GetAtt("pagesize");
			}
			else
			{
				$this->PageSize = 20;
			}
		}
		$this->TotalPage = ceil($this->TotalResult/$this->PageSize);
	}

	//php4���캯��
	function SpecView($starttime=0)
	{
		$this->__construct($starttime);
	}

	//�ر������Դ
	function Close()
	{
	}

	//ͳ���б���ļ�¼
	function CountRecord()
	{
		$this->TotalResult = -1;
		if(isset($GLOBALS['TotalResult']))
		{
			$this->TotalResult = $GLOBALS['TotalResult'];
		}
		if(isset($GLOBALS['PageNo']))
		{
			$this->PageNo = $GLOBALS['PageNo'];
		}
		else
		{
			$this->PageNo = 1;
		}
		if($this->TotalResult==-1)
		{
			if($this->StartTime>0)
			{
				$timesql = " And #@__archives.senddate>'".$this->StartTime."'";
			}
			else
			{
				$timesql = "";
			}
			$row = $this->dsql->GetOne("Select count(*) as dd From #@__archives where #@__archives.arcrank > -1 And channel=-1 $timesql");
			if(is_array($row))
			{
				$this->TotalResult = $row['dd'];
			}
			else
			{
				$this->TotalResult = 0;
			}
		}
	}

	//��ʾ�б�
	function Display()
	{
		if($this->TypeLink->TypeInfos['ispart']==1
		||$this->TypeLink->TypeInfos['ispart']==2)
		{
			$this->DisplayPartTemplets();
		}
		$this->ParseTempletsFirst();
		foreach($this->dtp->CTags as $tagid=>$ctag)
		{
			if($ctag->GetName()=="list")
			{
				$limitstart = ($this->PageNo-1) * $this->PageSize;
				$row = $this->PageSize;
				if(trim($ctag->GetInnerText())=="")
				{
					$InnerText = GetSysTemplets("list_fulllist.htm");
				}
				else
				{
					$InnerText = trim($ctag->GetInnerText());
				}
				$this->dtp->Assign($tagid,
				$this->GetArcList($limitstart,$row,
				$ctag->GetAtt("col"),
				$ctag->GetAtt("titlelen"),
				$ctag->GetAtt("infolen"),
				$ctag->GetAtt("imgwidth"),
				$ctag->GetAtt("imgheight"),
				$ctag->GetAtt("listtype"),
				$ctag->GetAtt("orderby"),
				$InnerText,
				$ctag->GetAtt("tablewidth"))
				);
			}
			else if($ctag->GetName()=="pagelist")
			{
				$list_len = trim($ctag->GetAtt("listsize"));
				if($list_len=="")
				{
					$list_len = 3;
				}
				$this->dtp->Assign($tagid,$this->GetPageListDM($list_len));
			}
		}
		$this->dtp->Display();
	}

	//��ʼ�����б�
	function MakeHtml($isremote=0)
	{
		global $cfg_remote_site;
		//�������̶�ֵ�ı�Ǹ�ֵ
		$this->ParseTempletsFirst();
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage==0)
		{
			$totalpage = 1;
		}
		CreateDir($GLOBALS['cfg_special']);
		$murl = "";
		for($this->PageNo=1;$this->PageNo<=$totalpage;$this->PageNo++)
		{
			foreach($this->dtp->CTags as $tagid=>$ctag)
			{
				if($ctag->GetName()=="list")
				{
					$limitstart = ($this->PageNo-1) * $this->PageSize;
					$row = $this->PageSize;
					if(trim($ctag->GetInnerText())=="")
					{
						$InnerText = GetSysTemplets("spec_list.htm");
					}
					else
					{
						$InnerText = trim($ctag->GetInnerText());
					}
					$this->dtp->Assign($tagid,
					$this->GetArcList($limitstart,$row,
					$ctag->GetAtt("col"),
					$ctag->GetAtt("titlelen"),
					$ctag->GetAtt("infolen"),
					$ctag->GetAtt("imgwidth"),
					$ctag->GetAtt("imgheight"),
					"spec",
					$ctag->GetAtt("orderby"),
					$InnerText,
					$ctag->GetAtt("tablewidth"))
					);
				}
				else if($ctag->GetName()=="pagelist")
				{
					$list_len = trim($ctag->GetAtt("listsize"));
					if($list_len=="")
					{
						$list_len = 3;
					}
					$this->dtp->Assign($tagid,$this->GetPageListST($list_len));
				}
			}//End foreach

			$makeFile = $GLOBALS['cfg_special']."/spec_".$this->PageNo.$GLOBALS['art_shortname'];
			$murl = $makeFile;
			$makeFile = $GLOBALS['cfg_basedir'].$makeFile;
			$this->dtp->SaveTo($makeFile);
			//�������Զ��վ�����ϴ�
      if($cfg_remote_site=='Y'&& $isremote == 1)
      {
  			//����Զ���ļ�·��
  			$remotefile = str_replace(DEDEROOT, '', $makeFile);
  			$localfile = '..'.$remotefile;
  			$remotedir = preg_replace('/[^\/]*\.html/', '',$remotefile);
  			//�������˵���Ѿ��л�Ŀ¼����Դ�������
  			$this->ftp->rmkdir($remotedir);
  			$this->ftp->upload($localfile, $remotefile, 'acii');
      }
			echo "�ɹ�������$murl<br/>";
		}
		copy($GLOBALS['cfg_basedir'].$GLOBALS['cfg_special']."/spec_1".$GLOBALS['art_shortname'],$GLOBALS['cfg_basedir'].$GLOBALS['cfg_special']."/index.html");
		$murl = $GLOBALS['cfg_special']."/index.html";
		return $murl;
	}

	//����ģ�壬�Թ̶��ı�ǽ��г�ʼ��ֵ
	function ParseTempletsFirst()
	{
		MakeOneTag($this->dtp,$this);
	}

	//��ȡ�����б�
	function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$infolen=250,
	$imgwidth=120,$imgheight=90,$listtype="all",$orderby="default",$innertext="",$tablewidth="100")
	{
		$typeid=$this->TypeID;
		if($row=="")
		{
			$row = 10;
		}
		if($limitstart=="")
		{
			$limitstart = 0;
		}
		if($titlelen=="")
		{
			$titlelen = 30;
		}
		if($infolen=="")
		{
			$infolen = 250;
		}
		if($imgwidth=="")
		{
			$imgwidth = 120;
		}
		if($imgheight=="")
		{
			$imgheight = 120;
		}
		if($listtype=="")
		{
			$listtype = "all";
		}
		if($orderby=="")
		{
			$orderby="default";
		}
		else
		{
			$orderby=strtolower($orderby);
		}
		$tablewidth = str_replace("%","",$tablewidth);
		if($tablewidth=="")
		{
			$tablewidth=100;
		}
		if($col=="")
		{
			$col=1;
		}
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth."%";
		$colWidth = $colWidth."%";
		$innertext = trim($innertext);
		if($innertext=="")
		{
			$innertext = GetSysTemplets("spec_list.htm");
		}

		//����ͬ����趨SQL����
		$orwhere = " arc.arcrank > -1 And arc.channel = -1 ";
		if($this->StartTime>0)
		{
			$orwhere .= " And arc.senddate>'".$this->StartTime."'";
		}

		//����ʽ
		$ordersql = '';
		if($orderby=='senddate')
		{
			$ordersql=" order by arc.senddate desc";
		}
		else if($orderby=='pubdate')
		{
			$ordersql=" order by arc.pubdate desc";
		}
		else if($orderby=='id')
		{
			$ordersql="  order by arc.id desc";
		}
		else
		{
			$ordersql=" order by arc.sortrank desc";
		}
		$query = "Select arc.*,tp.typedir,tp.typename,tp.isdefault,arc.money,
		tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
		from `#@__archives` arc left join `#@__arctype` tp on arc.typeid=tp.id
		where $orwhere $ordersql limit $limitstart,$row ";
		$this->dsql->SetQuery($query);
		$this->dsql->Execute('al');
		$artlist = '';
		if($col>1)
		{
			$artlist = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
		}
		$this->dtp2->LoadSource($innertext);
		for($i=0;$i<$row;$i++)
		{
			if($col>1)
			{
				$artlist .= "<tr>\r\n";
			}
			for($j=0;$j<$col;$j++)
			{
				if($col>1)
				{
					$artlist .= "<td width='$colWidth'>\r\n";
				}
				if($row = $this->dsql->GetArray("al"))
				{
					//����һЩ�����ֶ�
					$row["description"] = cn_substr($row["description"],$infolen);
					$row["title"] = cn_substr($row["title"],$titlelen);
					$row["id"] =  $row["id"];
					if($row['litpic'] == '-' || $row['litpic'] == '')
					{
						$row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
					}
					if(!eregi("^http://",$row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
					{
						$row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
					}
					$row['picname'] = $row['litpic'];
					$row["arcurl"] = GetFileUrl($row["id"],$row["typeid"],$row["senddate"],$row["title"],
					$row["ismake"],$row["arcrank"],$row["namerule"],$row["typedir"],$row["money"],$row['filename'],$row["moresite"],$row["siteurl"],$row["sitepath"]);
					$row["typeurl"] = GetTypeUrl($row["typeid"],$row["typedir"],$row["isdefault"],$row["defaultname"],$row["ispart"],$row["namerule2"],$row["moresite"],$row["siteurl"],$row["sitepath"]);
					$row["info"] = $row["description"];
					$row["filename"] = $row["arcurl"];
					$row["stime"] = GetDateMK($row["pubdate"]);
					$row["textlink"] = "<a href='".$row["filename"]."'>".$row["title"]."</a>";
					$row["typelink"] = "[<a href='".$row["typeurl"]."'>".$row["typename"]."</a>]";
					$row["imglink"] = "<a href='".$row["filename"]."'><img src='".$row["picname"]."' border='0' width='$imgwidth' height='$imgheight'></a>";
					$row["image"] = "<img src='".$row["picname"]."' border='0' width='$imgwidth' height='$imgheight'>";
					$row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
					$row['memberurl'] = $GLOBALS['cfg_memberurl'];
					$row['templeturl'] = $GLOBALS['cfg_templeturl'];

					//���븽�ӱ��������
					foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
					{
						if(isset($row[$k]))
						{
							$row[$k] = $this->ChannelUnit->MakeField($k,$row[$k]);
						}
					}
					if(is_array($this->dtp2->CTags))
					{
						foreach($this->dtp2->CTags as $k=>$ctag)
						{
							if($ctag->GetName()=='array')
							{
								//�����������飬��runphpģʽ������������
								$this->dtp2->Assign($k,$row);
							}
							else
							{
								if(isset($row[$ctag->GetName()]))
								{
									$this->dtp2->Assign($k,$row[$ctag->GetName()]);
								}
								else
								{
									$this->dtp2->Assign($k,'');
								}
							}
						}
					}
					$artlist .= $this->dtp2->GetResult();
				}//if hasRow

				else
				{
					$artlist .= "";
				}
				if($col>1)
				{
					$artlist .= "</td>\r\n";
				}
			}//Loop Col

			if($col>1)
			{
				$artlist .= "</tr>\r\n";
			}
		}//Loop Line

		if($col>1)
		{
			$artlist .= "</table>\r\n";
		}
		$this->dsql->FreeResult("al");
		return $artlist;
	}

	//��ȡ��̬�ķ�ҳ�б�
	function GetPageListST($list_len)
	{
		$prepage="";
		$nextpage="";
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		if($list_len==""||ereg("[^0-9]",$list_len))
		{
			$list_len=3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{

			return "<span class=\"pageinfo\">�� <strong>1</strong>ҳ<strong>".$this->TotalResult."</strong>����¼</span>";
		}
		if($this->TotalResult == 0)
		{
			return "<span class=\"pageinfo\">�� <strong>0</strong>ҳ<strong>".$this->TotalResult."</strong>����¼</span>";
		}
		$purl = $this->GetCurUrl();
		$tnamerule = "spec_";

		//�����һҳ����һҳ������
		if($this->PageNo != 1)
		{
			$prepage.="<li><a href='".$tnamerule."$prepagenum".$GLOBALS['art_shortname']."'>��һҳ</a></li>\r\n";
			$indexpage="<li><a href='".$tnamerule."1".$GLOBALS['art_shortname']."'>��ҳ</a></li>\r\n";
		}
		else
		{
			$indexpage="<li><a>��ҳ</a></li>\r\n";
		}
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.="<li><a href='".$tnamerule."$nextpagenum".$GLOBALS['art_shortname']."'>��һҳ</a></li>\r\n";
			$endpage="<li><a href='".$tnamerule."$totalpage".$GLOBALS['art_shortname']."'>ĩҳ</a></li>\r\n";
		}
		else
		{
			$endpage="<li><a>ĩҳ</a></li>\r\n";
		}

		//�����������
		$listdd="";
		$total_list = $list_len * 2 + 1;
		if($this->PageNo >= $total_list)
		{
			$j = $this->PageNo-$list_len;
			$total_list = $this->PageNo+$list_len;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		else
		{
			$j=1;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		for($j;$j<=$total_list;$j++)
		{
			if($j==$this->PageNo)
			{
				$listdd.= "<li class=\"thisclass\"><a>$j</a></li>\r\n";
			}
			else
			{
				$listdd.="<li><a href='".$tnamerule."$j".$GLOBALS['art_shortname']."'>".$j."</a></li>\r\n";
			}
		}
		$plist = $indexpage.$prepage.$listdd.$nextpage.$endpage;
		return $plist;
	}

	//��ȡ��̬�ķ�ҳ�б�
	function GetPageListDM($list_len)
	{
		$prepage="";
		$nextpage="";
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		if($list_len==""||ereg("[^0-9]",$list_len))
		{
			$list_len=3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{
			return "<span class=\"pageinfo\">��1ҳ/".$this->TotalResult."����¼</span>";
		}
		if($this->TotalResult == 0)
		{
			return "<span class=\"pageinfo\">��0ҳ/".$this->TotalResult."����¼</span>";
		}

		$purl = $this->GetCurUrl();
		$geturl = "typeid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
		$hidenform = "<input type='hidden' name='typeid' value='".$this->TypeID."'>\r\n";
		$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
		$purl .= "?".$geturl;

		//�����һҳ����һҳ������
		if($this->PageNo != 1)
		{
			$prepage.="<li><a href='".$purl."PageNo=$prepagenum'>��һҳ</a></li>\r\n";
			$indexpage="<li><a href='".$purl."PageNo=1'>��ҳ</a></li>\r\n";
		}
		else
		{
			$indexpage="<li><a>��ҳ</a></li>\r\n";
		}
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.="<li><a href='".$purl."PageNo=$nextpagenum'>��һҳ</a></li>\r\n";
			$endpage="<li><a href='".$purl."PageNo=$totalpage'>ĩҳ</a></li>\r\n";
		}
		else
		{
			$endpage="<li><a>ĩҳ</a></li>";
		}

		//�����������
		$listdd="";
		$total_list = $list_len * 2 + 1;
		if($this->PageNo >= $total_list)
		{
			$j = $this->PageNo-$list_len;
			$total_list = $this->PageNo+$list_len;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		else
		{
			$j=1;
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		for($j;$j<=$total_list;$j++)
		{
			if($j==$this->PageNo)
			{
				$listdd.= "<li class=\"thisclass\"><a>$j</a></li>\r\n";
			}
			else
			{
				$listdd.="<li><a href='".$purl."PageNo=$j'>".$j."</a></li>\r\n";
			}
		}

		$plist = $indexpage.$prepage.$listdd.$nextpage.$endpage;
		return $plist;
	}

	//��õ�ǰ��ҳ���ļ���url
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"]))
		{
			$nowurl = $_SERVER["REQUEST_URI"];
			$nowurls = explode("?",$nowurl);
			$nowurl = $nowurls[0];
		}
		else
		{
			$nowurl = $_SERVER["PHP_SELF"];
		}
		return $nowurl;
	}
}//End Class
?>
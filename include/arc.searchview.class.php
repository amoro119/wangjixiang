<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/splitword.class.php");
require_once(DEDEINC."/taglib/hotwords.lib.php");
require_once(DEDEINC."/taglib/channel.lib.php");

//Copyright 2004-2006 by DedeCms.com itprato
//�������;�������ĵ�����
@set_time_limit(0);

//������
class SearchView
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
	var $ChannelType;
	var $TempInfos;
	var $Fields;
	var $PartView;
	var $StartTime;
	var $Keywords;
	var $OrderBy;
	var $SearchType;
	var $mid;
	var $KType;
	var $Keyword;
	var $SearchMax;
	var $SearchMaxRc;
	var $SearchTime;
	var $AddSql;
	var $RsFields;

	//php5���캯��
	function __construct($typeid,$keyword,$orderby,$achanneltype="all",
	$searchtype='',$starttime=0,$upagesize=20,$kwtype=1,$mid=0)
	{
		global $cfg_search_max,$cfg_search_maxrc,$cfg_search_time;
		if(empty($upagesize))
		{
			$upagesize = 10;
		}
		$this->TypeID = $typeid;
		$this->Keyword = $keyword;
		$this->OrderBy = $orderby;
		$this->KType = $kwtype;
		$this->PageSize = $upagesize;
		$this->StartTime = $starttime;
		$this->ChannelType = $achanneltype;
		$this->SearchMax = $cfg_search_max;
		$this->SearchMaxRc = $cfg_search_maxrc;
		$this->SearchTime = $cfg_search_time;
		$this->mid = $mid;
		$this->RsFields = '';
		$this->SearchType = $searchtype=='' ? 'titlekeyword' : $searchtype;
		$this->dsql = $GLOBALS['dsql'];
		$this->dtp = new DedeTagParse();
		$this->dtp->SetRefObj($this);
		$this->dtp->SetNameSpace("dede","{","}");
		$this->dtp2 = new DedeTagParse();
		$this->dtp2->SetNameSpace("field","[","]");
		$this->TypeLink = new TypeLink($typeid);
		$this->Keywords = $this->GetKeywords($keyword);

		//����һЩȫ�ֲ�����ֵ
		if($this->TypeID=="0"){
			$this->ChannelTypeid=1;
		}else{
			$row =$this->dsql->GetOne("Select channeltype From `#@__arctype` where id={$this->TypeID}");
			$this->ChannelTypeid=$row['channeltype'];
		}
		foreach($GLOBALS['PubFields'] as $k=>$v)
		{
			$this->Fields[$k] = $v;
		}
		$this->CountRecord();
		$tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/search.htm";
		if(!file_exists($tempfile)||!is_file($tempfile))
		{
			echo "ģ���ļ������ڣ��޷�������";
			exit();
		}
		$this->dtp->LoadTemplate($tempfile);
		$this->TempInfos['tags'] = $this->dtp->CTags;
		$this->TempInfos['source'] = $this->dtp->SourceString;
		if($this->PageSize=="")
		{
			$this->PageSize = 20;
		}
		$this->TotalPage = ceil($this->TotalResult/$this->PageSize);
		if($this->PageNo==1)
		{
			$this->dsql->ExecuteNoneQuery("Update `#@__search_keywords` set result='".$this->TotalResult."' where keyword='".addslashes($keyword)."'; ");
		}
	
	}

	//php4���캯��
	function SearchView($typeid,$keyword,$orderby,$achanneltype="all",
	$searchtype="",$starttime=0,$upagesize=20,$kwtype=1,$mid=0)
	{
		$this->__construct($typeid,$keyword,$orderby,$achanneltype,$searchtype,$starttime,$upagesize,$kwtype,$mid);
	}

	//�ر������Դ
	function Close()
	{
	}

	//��ùؼ��ֵķִʽ���������浽���ݿ�
	function GetKeywords($keyword)
	{
		global $cfg_soft_lang;
		$keyword = cn_substr($keyword,50);
		$row = $this->dsql->GetOne("Select spwords From `#@__search_keywords` where keyword='".addslashes($keyword)."'; ");
		if(!is_array($row))
		{
			if(strlen($keyword)>7)
			{
				//echo $keyword;
				$sp = new SplitWord($cfg_soft_lang, $cfg_soft_lang);
				$sp->SetSource($keyword, $cfg_soft_lang, $cfg_soft_lang);
				$sp->StartAnalysis();
				$keywords = $sp->GetFinallyResult(' ');
				$keywords = ereg_replace("[ ]{1,}"," ",trim($keywords));
			}
			else
			{
				$keywords = $keyword;
			}
			$inquery = "INSERT INTO `#@__search_keywords`(`keyword`,`spwords`,`count`,`result`,`lasttime`)
          VALUES ('".addslashes($keyword)."', '".addslashes($keywords)."', '1', '0', '".time()."'); ";
			$this->dsql->ExecuteNoneQuery($inquery);
		}
		else
		{
			$this->dsql->ExecuteNoneQuery("Update `#@__search_keywords` set count=count+1,lasttime='".time()."' where keyword='".addslashes($keyword)."'; ");
			$keywords = $row['spwords'];
		}
		return $keywords;
	}

	//��ùؼ���SQL
	function GetKeywordSql()
	{
		$ks = explode(' ',$this->Keywords);
		$kwsql = '';
		$kwsqls = array();
		foreach($ks as $k)
		{
			$k = trim($k);
			if(strlen($k)<1)
			{
				continue;
			}
			if(ord($k[0])>0x80 && strlen($k)<2)
			{
				continue;
			}
			$k = addslashes($k);
			if($this->ChannelType < 0 || $this->ChannelTypeid < 0){
				$kwsqls[] = " arc.title like '%$k%' ";
			}else{
				if($this->SearchType=="title"){
					$kwsqls[] = " arc.title like '%$k%' ";
				}else{
					$kwsqls[] = " CONCAT(arc.title,' ',arc.writer,' ',arc.keywords) like '%$k%' ";
				}
			}
		}
		if(!isset($kwsqls[0]))
		{
			return '';
		}
		else
		{
			if($this->KType==1)
			{
				$kwsql = join(' OR ',$kwsqls);
			}
			else
			{
				$kwsql = join(' And ',$kwsqls);
			}
			return $kwsql;
		}
	}

	//�����صĹؼ���
	function GetLikeWords($num=8)
	{
		$ks = explode(' ',$this->Keywords);
		$lsql = '';
		foreach($ks as $k)
		{
			$k = trim($k);
			if(strlen($k)<2)
			{
				continue;
			}
			if(ord($k[0])>0x80 && strlen($k)<2)
			{
				continue;
			}
			$k = addslashes($k);
			if($lsql=='')
			{
				$lsql = $lsql." CONCAT(spwords,' ') like '%$k %' ";	
			}else{
				$lsql = $lsql." Or CONCAT(spwords,' ') like '%$k %' ";
			}
		}
		if($lsql=='')
		{
			return '';
		}
		else
		{
			$likeword = '';
			$lsql = "(".$lsql.") And Not(keyword like '".addslashes($this->Keyword)."') ";
			$this->dsql->SetQuery("Select keyword,count From `#@__search_keywords` where $lsql order by lasttime desc limit 0,$num; ");
			$this->dsql->Execute('l');
			while($row=$this->dsql->GetArray('l'))
			{
				if($row['count']>1000)
				{
					$fstyle=" style='font-size:11pt;color:red'";
				}
				else if($row['count']>300)
				{
					$fstyle=" style='font-size:10pt;color:green'";
				}
				else
				{
					$style = "";
				}
				$likeword .= "��<a href='search.php?keyword=".urlencode($row['keyword'])."&searchtype=titlekeyword'".$style."><u>".$row['keyword']."</u></a> ";
			}
			return $likeword;
		}
	}

	//�Ӵֹؼ���
	function GetRedKeyWord($fstr)
	{
		//echo $fstr;
		$ks = explode(' ',$this->Keywords);
		foreach($ks as $k)
		{
			$k = trim($k);
			if($k=='')
			{
				continue;
			}
			if(ord($k[0])>0x80 && strlen($k)<2)
			{
				continue;
			}
			$fstr = str_replace($k,"<font color='red'>$k</font>",$fstr);
		}
		return $fstr;
	}

	//ͳ���б���ļ�¼
	function CountRecord()
	{
		$this->TotalResult = -1;
		if(isset($GLOBALS['TotalResult']))
		{
			$this->TotalResult = $GLOBALS['TotalResult'];
			$this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
		}
		if(isset($GLOBALS['PageNo']))
		{
			$this->PageNo = $GLOBALS['PageNo'];
		}
		else
		{
			$this->PageNo = 1;
		}
		$ksql = $this->GetKeywordSql();
		$ksqls = array();
		if($this->StartTime > 0)
		{
			$ksqls[] = " arc.senddate>'".$this->StartTime."' ";
		}
		if($this->TypeID > 0)
		{
			$ksqls[] = " typeid in (".GetSonIds($this->TypeID).") ";
		}
		if($this->ChannelType > 0)
		{
			$ksqls[] = " arc.channel='".$this->ChannelType."'";
		}
		if($this->mid > 0)
		{
			$ksqls[] = " arc.mid = '".$this->mid."'";
		}
		$ksqls[] = " arc.arcrank > -1 ";
		$this->AddSql = ($ksql=='' ? join(' And ',$ksqls) : join(' And ',$ksqls)." And ($ksql)" );
		if($this->ChannelType < 0 || $this->ChannelTypeid< 0){
			if($this->ChannelType=="0") $id=$this->ChannelTypeid;
			else $id=$this->ChannelType;
			$row =$this->dsql->GetOne("Select addtable From `#@__channeltype` Where id=$id");
			$addtable = trim($row['addtable']);
			$this->AddTable=$addtable;
		}else{
			$this->AddTable="#@__archives";
		}
    $cquery = "Select * From `{$this->AddTable}` arc where ".$this->AddSql;
		$hascode = md5($cquery);
		$row = $this->dsql->GetOne("Select * From `#@__arccache` where `md5hash`='".$hascode."' ");
		$uptime = time();
		if(is_array($row) && time()-$row['uptime'] < 3600 * 24)
		{
			$aids = explode(',', $row['cachedata']);
			$this->TotalResult = count($aids)-1;
			$this->RsFields = $row['cachedata'];
		}
		else
		{
			if($this->TotalResult==-1)
			{
				$this->dsql->SetQuery($cquery);
				$this->dsql->execute();
				$aidarr = array();
				$aidarr[] = 0;
				while($row = $this->dsql->getarray())
				{
					if($this->ChannelType< 0 ||$this->ChannelTypeid< 0) $aidarr[] = $row['aid'];
					else $aidarr[] = $row['id'];
				}
				$nums = count($aidarr)-1;
				$aids = implode(',', $aidarr);
				$delete = "Delete From `#@__arccache` where uptime<".(time() - 3600 * 24);
				$this->dsql->SetQuery($delete);
				$this->dsql->executenonequery();
				$insert = "insert into `#@__arccache` (`md5hash`, `uptime`, `cachedata`)
				 values('$hascode', '$uptime', '$aids')";
				$this->dsql->SetQuery($insert);
				$this->dsql->executenonequery();
				$this->TotalResult = $nums;
			}
		}
	}

	//��ʾ�б�
	function Display()
	{
		foreach($this->dtp->CTags as $tagid=>$ctag)
		{
			$tagname = $ctag->GetName();
			if($tagname=="list")
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
				$this->GetArcList($limitstart,
				$row,
				$ctag->GetAtt("col"),
				$ctag->GetAtt("titlelen"),
				$ctag->GetAtt("infolen"),
				$ctag->GetAtt("imgwidth"),
				$ctag->GetAtt("imgheight"),
				$this->ChannelType,
				$this->OrderBy,
				$InnerText,
				$ctag->GetAtt("tablewidth"))
				);
			}
			else if($tagname=="pagelist")
			{
				$list_len = trim($ctag->GetAtt("listsize"));
				if($list_len=="")
				{
					$list_len = 3;
				}
				$this->dtp->Assign($tagid,$this->GetPageListDM($list_len));
			}
			else if($tagname=="likewords")
			{
				$this->dtp->Assign($tagid,$this->GetLikeWords($ctag->GetAtt('num')));
			}
			else if($tagname=="hotwords")
			{
				$this->dtp->Assign($tagid,lib_hotwords($ctag,$this));
			}
			else if($tagname=="field")
			{
				//����ָ���ֶ�
				if(isset($this->Fields[$ctag->GetAtt('name')]))
				{
					$this->dtp->Assign($tagid,$this->Fields[$ctag->GetAtt('name')]);
				}
				else
				{
					$this->dtp->Assign($tagid,"");
				}
			}
			else if($tagname=="channel")
			{
				//�¼�Ƶ���б�
				if($this->TypeID>0)
				{
					$typeid = $this->TypeID; $reid = $this->TypeLink->TypeInfos['reid'];
				}
				else
				{
					$typeid = 0; $reid=0;
				}
				$GLOBALS['envs']['typeid'] = $typeid;
				$GLOBALS['envs']['reid'] = $typeid;
				$this->dtp->Assign($tagid,lib_channel($ctag,$this));
			}//End if

		}
		global $keyword,  $oldkeyword;
		if(!empty($oldkeyword)) $keyword = $oldkeyword;
		$this->dtp->Display();
	}

	//����ĵ��б�
	function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$infolen=250,
	$imgwidth=120,$imgheight=90,$achanneltype="all",$orderby="default",$innertext="",$tablewidth="100")
	{
		$typeid=$this->TypeID;
		if($row=='') $row = 10;
		if($limitstart=='') $limitstart = 0;
		if($titlelen=='') $titlelen = 30;
		if($infolen=='') $infolen = 250;
		if($imgwidth=='') $imgwidth = 120;
		if($imgheight='') $imgheight = 120;
		if($achanneltype=='') $achanneltype = '0';
		$orderby = $orderby=='' ? 'default' : strtolower($orderby);
		$tablewidth = str_replace("%","",$tablewidth);
		if($tablewidth=='') $tablewidth=100;
		if($col=='') $col=1;
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth."%";
		$colWidth = $colWidth."%";
		$innertext = trim($innertext);
		if($innertext=='')
		{
			$innertext = GetSysTemplets("search_list.htm");
		}

		//����ʽ
		$ordersql = '';
		if($this->ChannelType< 0 ||$this->ChannelTypeid< 0){
			if($orderby=="id"){
				$ordersql="order by arc.aid desc";
			}else{
				$ordersql="order by arc.senddate desc";
			}
		}else{
			if($orderby=="senddate")
			{
				$ordersql=" order by arc.senddate desc";
			}
			else if($orderby=="pubdate")
			{
				$ordersql=" order by arc.pubdate desc";
			}
			else if($orderby=="id")
			{
				$ordersql="  order by arc.id desc";
			}
			else
			{
				$ordersql=" order by arc.sortrank desc";
			}
		}

		//����
		$query = "Select arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
		act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
		from `{$this->AddTable}` arc left join `#@__arctype` act on arc.typeid=act.id
		where {$this->AddSql} $ordersql limit $limitstart,$row";
		$this->dsql->SetQuery($query);
		$this->dsql->Execute("al");
		$artlist = "";
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
					if($this->ChannelType< 0 || $this->ChannelTypeid< 0) {
							$row["id"]=$row["aid"];
							$row["ismake"]=empty($row["ismake"])? "" : $row["ismake"];
							$row["filename"]=empty($row["filename"])? "" : $row["filename"];
							$row["money"]=empty($row["money"])? "" : $row["money"];
							$row["description"]=empty($row["description "])? "" : $row["description"];
							$row["pubdate"]=empty($row["pubdate  "])? $row["senddate"] : $row["pubdate"];
					}
					//����һЩ�����ֶ�
					$row["arcurl"] = GetFileUrl($row["id"],$row["typeid"],$row["senddate"],$row["title"],
					$row["ismake"],$row["arcrank"],$row["namerule"],$row["typedir"],$row["money"],$row['filename'],$row["moresite"],$row["siteurl"],$row["sitepath"]);
					$row["description"] = $this->GetRedKeyWord(cn_substr($row["description"],$infolen));
					$row["title"] = $this->GetRedKeyWord(cn_substr($row["title"],$titlelen));
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
				if($col>1) $artlist .= "</td>\r\n";
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
	function GetPageListDM($list_len)
	{
		global $oldkeyword;
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
			return "��1ҳ/".$this->TotalResult."����¼";
		}
		if($this->TotalResult == 0)
		{
			return "��0ҳ/".$this->TotalResult."����¼";
		}
		$purl = $this->GetCurUrl();
		
		$oldkeyword = (empty($oldkeyword) ? $this->Keyword : $oldkeyword);

		//�������������ʱ��������ҳ��
		if($this->TotalResult > $this->SearchMaxRc)
		{
			$totalpage = ceil($this->SearchMaxRc/$this->PageSize);
		}
		$infos = "<td>���ҵ�<b>".$this->TotalResult."</b>����¼/�����ʾ<b>{$totalpage}</b>ҳ </td>\r\n";
		$geturl = "keyword=".urlencode($oldkeyword)."&searchtype=".$this->SearchType;
		$hidenform = "<input type='hidden' name='keyword' value='".rawurldecode($oldkeyword)."'>\r\n";
		$geturl .= "&channeltype=".$this->ChannelType."&orderby=".$this->OrderBy;
		$hidenform .= "<input type='hidden' name='channeltype' value='".$this->ChannelType."'>\r\n";
		$hidenform .= "<input type='hidden' name='orderby' value='".$this->OrderBy."'>\r\n";
		$geturl .= "&kwtype=".$this->KType."&pagesize=".$this->PageSize;
		$hidenform .= "<input type='hidden' name='kwtype' value='".$this->KType."'>\r\n";
		$hidenform .= "<input type='hidden' name='pagesize' value='".$this->PageSize."'>\r\n";
		$geturl .= "&typeid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
		$hidenform .= "<input type='hidden' name='typeid' value='".$this->TypeID."'>\r\n";
		$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
		$purl .= "?".$geturl;

		//�����һҳ����һҳ������
		if($this->PageNo != 1)
		{
			$prepage.="<td width='50'><a href='".$purl."PageNo=$prepagenum'>��һҳ</a></td>\r\n";
			$indexpage="<td width='30'><a href='".$purl."PageNo=1'>��ҳ</a></td>\r\n";
		}
		else
		{
			$indexpage="<td width='30'>��ҳ</td>\r\n";
		}
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.="<td width='50'><a href='".$purl."PageNo=$nextpagenum'>��һҳ</a></td>\r\n";
			$endpage="<td width='30'><a href='".$purl."PageNo=$totalpage'>ĩҳ</a></td>\r\n";
		}
		else
		{
			$endpage="<td width='30'>ĩҳ</td>\r\n";
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
				$listdd.= "<td>$j&nbsp;</td>\r\n";
			}
			else
			{
				$listdd.="<td><a href='".$purl."PageNo=$j'>[".$j."]</a>&nbsp;</td>\r\n";
			}
		}
		$plist  =  "<table border='0' cellpadding='0' cellspacing='0'>\r\n";
		$plist .= "<tr align='center' style='font-size:10pt'>\r\n";
		$plist .= "<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
		$plist .= $infos;
		$plist .= $indexpage;
		$plist .= $prepage;
		$plist .= $listdd;
		$plist .= $nextpage;
		$plist .= $endpage;
		if($totalpage>$total_list)
		{
			$plist.="<td width='38'><input type='text' name='PageNo' style='width:28px;height:14px' value='".$this->PageNo."' /></td>\r\n";
			$plist.="<td width='30'><input type='submit' name='plistgo' value='GO' style='width:30px;height:22px;font-size:9pt' /></td>\r\n";
		}
		$plist .= "</form>\r\n</tr>\r\n</table>\r\n";
		return $plist;
	}

	//---------------
	//��õ�ǰ��ҳ���ļ���url
	//----------------
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
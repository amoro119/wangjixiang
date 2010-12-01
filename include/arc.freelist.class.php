<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once DEDEINC.'/arc.partview.class.php';

@set_time_limit(0);
class FreeList
{
	var $dsql;
	var $dtp;
	var $TypeID;
	var $TypeLink;
	var $PageNo;
	var $TotalPage;
	var $TotalResult;
	var $PageSize;
	var $ChannelUnit;
	var $Fields;
	var $PartView;
	var $FLInfos;
	var $ListObj;
	var $TempletsFile;
	var $maintable;

	//php5���캯��
	function __construct($fid)
	{
		global $dsql;
		$this->FreeID = $fid;
		$this->TypeLink = new TypeLink(0);
		$this->dsql = $dsql;
		$this->maintable = '#@__archives';
		$this->TempletsFile = '';
		$this->FLInfos = $this->dsql->GetOne("Select * From `#@__freelist` where aid='$fid' ");
		$liststr = $this->FLInfos['listtag'];
		$this->FLInfos['maxpage'] = (empty($this->FLInfos['maxpage']) ? 100 : $this->FLInfos['maxpage']);

		//���������ﱣ����б�������Ϣ
		$ndtp = new DedeTagParse();
		$ndtp->SetNameSpace("dede","{","}");
		$ndtp->LoadString($liststr);
		$this->ListObj = $ndtp->GetTag('list');
		$this->PageSize = $this->ListObj->GetAtt('pagesize');
		if(empty($this->PageSize))
		{
			$this->PageSize = 30;
		}
		$channelid = $this->ListObj->GetAtt('channel');
		
		/*
		if(empty($channelid))
		{
			showmsg('����ָ��Ƶ��','-1');exit();
		}
		else
		{
			$channelid = intval($channelid);
			$channelinfo = $this->dsql->getone("select maintable from #@__channeltype where id='$channelid'");
			$this->maintable = $channelinfo['maintable'];
		}
		*/
		$channelid = intval($channelid);
		$this->maintable = '#@__archives';
		
		//ȫ��ģ�������
		$this->dtp = new DedeTagParse();
		$this->dtp->SetNameSpace("dede","{","}");
		$this->dtp->SetRefObj($this);

		//����һЩȫ�ֲ�����ֵ
		$this->Fields['aid'] = $this->FLInfos['aid'];
		$this->Fields['title'] = $this->FLInfos['title'];
		$this->Fields['position'] = $this->FLInfos['title'];
		$this->Fields['keywords'] = $this->FLInfos['keywords'];
		$this->Fields['description'] = $this->FLInfos['description'];
		$channelid = $this->ListObj->GetAtt('channel');
		if(!empty($channelid))
		{
			$this->Fields['channeltype'] = $channelid;
			$this->ChannelUnit = new ChannelUnit($channelid);
		}
		else
		{
			$this->Fields['channeltype'] = 0;
		}
		foreach($GLOBALS['PubFields'] as $k=>$v)
		{
			$this->Fields[$k] = $v;
		}
		$this->PartView = new PartView();
		$this->CountRecord();
	}

	//php4���캯��
	function FreeList($fid)
	{
		$this->__construct($fid);
	}

	//�ر������Դ
	function Close()
	{
	}

	//ͳ���б���ļ�¼
	function CountRecord()
	{
		global $cfg_list_son,$cfg_needsontype;

		//ͳ�����ݿ��¼
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

		//�Ѿ����ܼ�¼��ֵ
		if($this->TotalResult==-1)
		{
			$addSql  = " arcrank > -1 And channel>-1 ";
			$typeid = $this->ListObj->GetAtt('typeid');
			$subday = $this->ListObj->GetAtt('subday');
			$listtype = $this->ListObj->GetAtt('type');
			$att = $this->ListObj->GetAtt('att');
			$channelid = $this->ListObj->GetAtt('channel');
			if(empty($channelid))
			{
				$channelid = 0;
			}

			//�Ƿ�ָ����Ŀ����
			if(!empty($typeid))
			{
				if($cfg_list_son=='N')
				{
					$addSql .= " And (typeid='$typeid') ";
				}
				else
				{
					$addSql .= " And typeid in (".GetSonIds($typeid,0,true).") ";
				}
			}

			//�Զ�����������
			if($att!='') {
				$flags = explode(',',$att);
				for($i=0;isset($flags[$i]);$i++) $addSql .= " AND FIND_IN_SET('{$flags[$i]}',flag)>0 ";
			}

			//�ĵ���Ƶ��ģ��
			if($channelid>0 && !eregi("spec",$listtype))
			{
				$addSql .= " And channel = '$channelid' ";
			}

			//�Ƽ��ĵ� ������ͼ  ר���ĵ�
			if(eregi("commend",$listtype))
			{
				$addSql .= " And FIND_IN_SET('c',flag) > 0  ";
			}
			if(eregi("image",$listtype))
			{
				$addSql .= " And litpic <> ''  ";
			}
			if(eregi("spec",$listtype) || $channelid==-1)
			{
				$addSql .= " And channel = -1  ";
			}
			if(!empty($subday))
			{
				$starttime = time() - $subday * 86400;
				$addSql .= " And senddate > $starttime  ";
			}
			$keyword = $this->ListObj->GetAtt('keyword');
			if(!empty($keyword))
			{
				$addSql .= " And CONCAT(title,keywords) REGEXP '$keyword' ";
			}
			$cquery = "Select count(*) as dd From `{$this->maintable}` where $addSql";
			$row = $this->dsql->GetOne($cquery);
			if(is_array($row))
			{
				$this->TotalResult = $row['dd'];
			}
			else
			{
				$this->TotalResult = 0;
			}
		}
		$this->TotalPage = ceil($this->TotalResult/$this->PageSize);
		if($this->TotalPage > $this->FLInfos['maxpage'])
		{
			$this->TotalPage = $this->FLInfos['maxpage'];
			$this->TotalResult = $this->TotalPage * $this->PageSize;
		}
	}

	//����ģ��
	function LoadTemplet()
	{
		$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
		$tempfile = str_replace("{style}",$GLOBALS['cfg_df_style'],$this->FLInfos['templet']);
		$tempfile = $tmpdir."/".$tempfile;
		if(!file_exists($tempfile))
		{
			$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/list_free.htm";
		}
		$this->dtp->LoadTemplate($tempfile);
		$this->TempletsFile = ereg_replace("^".$GLOBALS['cfg_basedir'],'',$tempfile);
	}

	//�б���HTML
	function MakeHtml($startpage=1,$makepagesize=0)
	{
		$this->LoadTemplet();
		$murl = "";
		if(empty($startpage))
		{
			$startpage = 1;
		}
		$this->ParseTempletsFirst();
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage==0)
		{
			$totalpage = 1;
		}
		if($makepagesize>0)
		{
			$endpage = $startpage+$makepagesize;
		}
		else
		{
			$endpage = ($totalpage+1);
		}
		if($endpage>($totalpage+1))
		{
			$endpage = $totalpage;
		}
		$firstFile = '';
		for($this->PageNo=$startpage;$this->PageNo<$endpage;$this->PageNo++)
		{
			$this->ParseDMFields($this->PageNo,1);

			//�ļ���
			$makeFile = $this->GetMakeFileRule();
			if(!ereg("^/",$makeFile))
			{
				$makeFile = "/".$makeFile;
			}
			$makeFile = str_replace('{page}',$this->PageNo,$makeFile);
			$murl = $makeFile;
			$makeFile = $GLOBALS['cfg_basedir'].$makeFile;
			$makeFile = ereg_replace("/{1,}","/",$makeFile);
			if($this->PageNo==1)
			{
				$firstFile = $makeFile;
			}

			//�����ļ�
			$this->dtp->SaveTo($makeFile);
			echo "�ɹ�������<a href='$murl' target='_blank'>".ereg_replace("/{1,}","/",$murl)."</a><br/>";
		}
		if($this->FLInfos['nodefault']==0)
		{
			$murl = '/'.str_replace('{cmspath}',$GLOBALS['cfg_cmspath'],$this->FLInfos['listdir']);
			$murl .= '/'.$this->FLInfos['defaultpage'];
			$indexfile = $GLOBALS['cfg_basedir'].$murl;
			$murl = ereg_replace("/{1,}","/",$murl);
			echo "���ƣ�$firstFile Ϊ ".$this->FLInfos['defaultpage']." <br/>";
			copy($firstFile,$indexfile);
		}
		$this->Close();
		return $murl;
	}

	//��ʾ�б�
	function Display()
	{
		$this->LoadTemplet();
		$this->ParseTempletsFirst();
		$this->ParseDMFields($this->PageNo,0);
		$this->dtp->Display();
	}

	//��ʾ����ģ��ҳ��
	function DisplayPartTemplets()
	{
		$nmfa = 0;
		$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
		if($this->Fields['ispart']==1)
		{
			$tempfile = str_replace("{tid}",$this->FreeID,$this->Fields['tempindex']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile))
			{
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default.htm";
			}
			$this->PartView->SetTemplet($tempfile);
		}
		else if($this->Fields['ispart']==2)
		{
			$tempfile = str_replace("{tid}",$this->FreeID,$this->Fields['tempone']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			if(is_file($tmpdir."/".$tempfile))
			{
				$this->PartView->SetTemplet($tmpdir."/".$tempfile);
			}
			else
			{
				$this->PartView->SetTemplet("����û��ʹ��ģ��ĵ���ҳ��","string"); $nmfa = 1;
			}
		}
		CreateDir($this->Fields['typedir']);
		$makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",$this->Fields['typedir'],$this->Fields['defaultname'],$this->Fields['namerule2']);
		$makeFile = $this->GetTruePath().$makeUrl;
		if($nmfa==0)
		{
			$this->PartView->Display();
		}
		else{
			if(!file_exists($makeFile))
			{
				$this->PartView->Display();
			}
			else
			{
				include($makeFile);
			}
		}
	}

	//����ģ�壬�Թ̶��ı�ǽ��г�ʼ��ֵ
	function ParseTempletsFirst()
	{
		MakeOneTag($this->dtp,$this);
	}

	//����ģ�壬��������ı䶯���и�ֵ
	function ParseDMFields($PageNo,$ismake=1)
	{
		foreach($this->dtp->CTags as $tagid=>$ctag)
		{
			if($ctag->GetName()=="freelist")
			{
				$limitstart = ($this->PageNo-1) * $this->PageSize;
				if($this->PageNo > $this->FLInfos['maxpage']) $this->dtp->Assign($tagid, '�Ѿ���������������г���ҳ�棡');
				else $this->dtp->Assign($tagid,$this->GetList($limitstart,$ismake));
			}
			else if($ctag->GetName()=="pagelist")
			{
				$list_len = trim($ctag->GetAtt("listsize"));
				$ctag->GetAtt("listitem")=="" ? $listitem="info,index,pre,pageno,next,end,option" : $listitem=$ctag->GetAtt("listitem");
				if($list_len=="")
				{
					$list_len = 3;
				}
				if($ismake==0)
				{
					$this->dtp->Assign($tagid,$this->GetPageListDM($list_len,$listitem));
				}
				else
				{
					$this->dtp->Assign($tagid,$this->GetPageListST($list_len,$listitem));
				}
			}
			else if($ctag->GetName()=="pageno")
			{
				$this->dtp->Assign($tagid,$PageNo);
			}
		}
	}

	//���Ҫ�������ļ����ƹ���
	function GetMakeFileRule()
	{
		$okfile = '';
		$namerule = $this->FLInfos['namerule'];
		$listdir = $this->FLInfos['listdir'];
		$listdir = str_replace('{cmspath}',$GLOBALS['cfg_cmspath'],$listdir);
		$okfile = str_replace('{listid}',$this->FLInfos['aid'],$namerule);
		$okfile = str_replace('{listdir}',$listdir,$okfile);
		$okfile = str_replace("\\","/",$okfile);
		$mdir = ereg_replace("/([^/]*)$","",$okfile);
		if(!ereg("/",$mdir) && ereg("\.",$mdir))
		{
			return $okfile;
		}
		else
		{
			CreateDir($mdir,'','');
			return $okfile;
		}
	}

	//���һ�����е��ĵ��б�
	function GetList($limitstart,$ismake=1)
	{
		global $cfg_list_son,$cfg_needsontype;
		$col = $this->ListObj->GetAtt('col');
		if(empty($col))
		{
			$col = 1;
		}
		$titlelen = $this->ListObj->GetAtt('titlelen');
		$infolen = $this->ListObj->GetAtt('infolen');
		$imgwidth = $this->ListObj->GetAtt('imgwidth');
		$imgheight = $this->ListObj->GetAtt('imgheight');
		$titlelen = AttDef($titlelen,60);
		$infolen = AttDef($infolen,250);
		$imgwidth = AttDef($imgwidth,80);
		$imgheight = AttDef($imgheight,80);
		$innertext = trim($this->ListObj->GetInnerText());
		if(empty($innertext))
		{
			$innertext = GetSysTemplets("list_fulllist.htm");
		}
		$tablewidth=100;
		if($col=="")
		{
			$col=1;
		}
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth."%";
		$colWidth = $colWidth."%";

		//����ͬ����趨SQL����
		$orwhere = " arc.arcrank > -1 And channel>-1 ";
		$typeid = $this->ListObj->GetAtt('typeid');
		$subday = $this->ListObj->GetAtt('subday');
		$listtype = $this->ListObj->GetAtt('type');
		$att = $this->ListObj->GetAtt('att');
		$channelid = $this->ListObj->GetAtt('channel');
		if(empty($channelid))
		{
			$channelid = 0;
		}

		//�Ƿ�ָ����Ŀ����
		if(!empty($typeid))
		{
			if($cfg_list_son=='N')
			{
				$orwhere .= " And (arc.typeid='$typeid') ";
			}
			else
			{
				$orwhere .= " And arc.typeid in (".GetSonIds($typeid,0,true).") ";
			}
		}

		//�Զ�����������
		if($att!='') {
			$flags = explode(',',$att);
			for($i=0;isset($flags[$i]);$i++) $orwhere .= " AND FIND_IN_SET('{$flags[$i]}',flag)>0 ";
		}
		//�ĵ���Ƶ��ģ��
		if($channelid>0 && !eregi("spec",$listtype))
		{
			$orwhere .= " And arc.channel = '$channelid' ";
		}

		//�Ƽ��ĵ� ������ͼ  ר���ĵ�
		if(eregi("commend",$listtype))
		{
			$orwhere .= " And FIND_IN_SET('c',flag) > 0  ";
		}
		if(eregi("image",$listtype))
		{
			$orwhere .= " And arc.litpic <> ''  ";
		}
		if(eregi("spec",$listtype) || $channelid==-1)
		{
			$orwhere .= " And arc.channel = -1  ";
		}
		if(!empty($subday))
		{
			$starttime = time() - $subday*86400;
			$orwhere .= " And arc.senddate > $starttime  ";
		}
		$keyword = $this->ListObj->GetAtt('keyword');
		if(!empty($keyword))
		{
			$orwhere .= " And CONCAT(arc.title,arc.keywords) REGEXP '$keyword' ";
		}
		$orderby = $this->ListObj->GetAtt('orderby');
		$orderWay = $this->ListObj->GetAtt('orderway');

		//����ʽ
		$ordersql = "";
		if($orderby=="senddate")
		{
			$ordersql=" order by arc.senddate $orderWay";
		}
		else if($orderby=="pubdate")
		{
			$ordersql=" order by arc.pubdate $orderWay";
		}
		else if($orderby=="id")
		{
			$ordersql="  order by arc.id $orderWay";
		}
		else if($orderby=="hot"||$orderby=="click")
		{
			$ordersql = " order by arc.click $orderWay";
		}
		else if($orderby=="lastpost")
		{
			$ordersql = "  order by arc.lastpost $orderWay";
		}
		else if($orderby=="scores")
		{
			$ordersql = "  order by arc.scores $orderWay";
		}
		else if($orderby=="rand")
		{
			$ordersql = "  order by rand()";
		}
		else
		{
			$ordersql=" order by arc.sortrank $orderWay";
		}

		//��ø��ӱ�������Ϣ
		$addField = "";
		$addJoin = "";
		if(is_object($this->ChannelUnit))
		{
			$addtable  = $this->ChannelUnit->ChannelInfos['addtable'];
			if($addtable!="")
			{
				$addJoin = " left join $addtable on arc.id = ".$addtable.".aid ";
				$addField = "";
				$fields = explode(",",$this->ChannelUnit->ChannelInfos['listfields']);
				foreach($fields as $k=>$v)
				{
					$nfields[$v] = $k;
				}
				foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
				{
					if(isset($nfields[$k]))
					{
						if(!empty($arr['rename']))
						{
							$addField .= ",".$addtable.".".$k." as ".$arr['rename'];
						}
						else
						{
							$addField .= ",".$addtable.".".$k;
						}
					}
				}
			}
		}

		$query = "Select arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,
		tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
		$addField
		from {$this->maintable} arc
		left join #@__arctype tp on arc.typeid=tp.id
		$addJoin
		where $orwhere $ordersql limit $limitstart,".$this->PageSize;
		$this->dsql->SetQuery($query);
		$this->dsql->Execute("al");
		$artlist = "";
		if($col>1)
		{
			$artlist = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
		}
		$indtp = new DedeTagParse();
		$indtp->SetNameSpace("field","[","]");
		$indtp->LoadSource($innertext);
		$GLOBALS['autoindex'] = 0;
		for($i=0;$i<$this->PageSize;$i++)
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
					$GLOBALS['autoindex']++;

					//����һЩ�����ֶ�
					$row['id'] =  $row['id'];
					$row['arcurl'] = $this->GetArcUrl($row['id'],$row['typeid'],$row['senddate'],
					$row['title'],$row['ismake'],$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],
					$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);
					$row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],
					$row['ispart'],$row['namerule2'],$row['siteurl'],$row['sitepath']);
					if($ismake==0 && $GLOBALS['cfg_multi_site']=='Y')
					{
						if($row["siteurl"]=="")
						{
							$row["siteurl"] = $GLOBALS['cfg_mainsite'];
						}
					}

					$row['description'] = cn_substr($row['description'],$infolen);

					if($row['litpic'] == '-' || $row['litpic'] == '')
					{
						$row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
					}
					if(!eregi("^http://",$row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
					{
						$row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
					}
					$row['picname'] = $row['litpic'];
					$row['info'] = $row['description'];
					$row['filename'] = $row['arcurl'];
					$row['stime'] = GetDateMK($row['pubdate']);
					$row['textlink'] = "<a href='".$row['filename']."' title='".str_replace("'","",$row['title'])."'>".$row['title']."</a>";
					$row['typelink'] = "<a href='".$row['typeurl']."'>[".$row['typename']."]</a>";
					$row['imglink'] = "<a href='".$row['filename']."'><img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".str_replace("'","",$row['title'])."'></a>";
					$row['image'] = "<img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".str_replace("'","",$row['title'])."'>";
					$row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
					$row['memberurl'] = $GLOBALS['cfg_memberurl'];
					$row['templeturl'] = $GLOBALS['cfg_templeturl'];
					$row['title'] = cn_substr($row['title'],$titlelen);
					if($row['color']!="")
					{
						$row['title'] = "<font color='".$row['color']."'>".$row['title']."</font>";
					}
					if(ereg('c',$row['flag']))
					{
						$row['title'] = "<b>".$row['title']."</b>";
					}

					//���븽�ӱ��������
					if(is_object($this->ChannelUnit))
					{
						foreach($row as $k=>$v)
						{
							if(ereg("[A-Z]",$k))
							{
								$row[strtolower($k)] = $v;
							}
						}
						foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
						{
							if(isset($row[$k]))
							{
								$row[$k] = $this->ChannelUnit->MakeField($k,$row[$k]);
							}
						}
					}

					//����������¼
					if(is_array($indtp->CTags))
					{
						foreach($indtp->CTags as $k=>$ctag)
						{
							$_f = $ctag->GetName();
							if($_f=='array')
							{
								//�����������飬��runphpģʽ������������
								$indtp->Assign($k,$row);
							}
							else
							{
								if(isset($row[$_f]))
								{
									$indtp->Assign($k,$row[$_f]);
								}
								else
								{
									$indtp->Assign($k,"");
								}
							}
						}
					}
					$artlist .= $indtp->GetResult();
				}//if hasRow

				else
				{
					$artlist .= "";
				}
				if($col>1)
				{
					$artlist .= "	</td>\r\n";
				}
			}//Loop Col

			if($col>1){
				$i += $col - 1;
			}
			if($col>1)
			{
				$artlist .= "	</tr>\r\n";
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
	function GetPageListST($list_len,$listitem="info,index,end,pre,next,pageno")
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
			return "��1ҳ/".$this->TotalResult."����¼";
		}
		if($this->TotalResult == 0)
		{
			return "��0ҳ/".$this->TotalResult."����¼";
		}
		$maininfo = " ��{$totalpage}ҳ/".$this->TotalResult."����¼ ";
		$purl = $this->GetCurUrl();
		$tnamerule = $this->GetMakeFileRule();
		$tnamerule = ereg_replace('^(.*)/','',$tnamerule);

		//�����һҳ����ҳ������
		if($this->PageNo != 1)
		{
			$prepage.="<a href='".str_replace("{page}",$prepagenum,$tnamerule)."'>��һҳ</a>\r\n";
			$indexpage="<a href='".str_replace("{page}",1,$tnamerule)."'>��ҳ</a>\r\n";
		}
		else
		{
			$indexpage="<a href='#'>��ҳ</a>\r\n";
		}

		//��һҳ,δҳ������
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.="<a href='".str_replace("{page}",$nextpagenum,$tnamerule)."'>��һҳ</a>\r\n";
			$endpage="<a href='".str_replace("{page}",$totalpage,$tnamerule)."'>ĩҳ</a>\r\n";
		}
		else
		{
			$endpage="<a href='#'>ĩҳ</a>\r\n";
		}

		//option����
		$optionlen = strlen($totalpage);
		$optionlen = $optionlen*12 + 18;
		if($optionlen < 36) $optionlen = 36;
		if($optionlen > 100) $optionlen = 100;
		$optionlist = "<select name='sldd' style='width:$optionlen' onchange='location.href=this.options[this.selectedIndex].value;'>\r\n";
		for($mjj=1;$mjj<=$totalpage;$mjj++)
		{
			if($mjj==$this->PageNo)
			{
				$optionlist .= "<option value='".str_replace("{page}",$mjj,$tnamerule)."' selected>$mjj</option>\r\n";
			}
			else
			{
				$optionlist .= "<option value='".str_replace("{page}",$mjj,$tnamerule)."'>$mjj</option>\r\n";
			}
		}
		$optionlist .= "</select>";

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
				$listdd.= "<strong>{$j}</strong>\r\n";
			}
			else
			{
				$listdd.="<a href='".str_replace("{page}",$j,$tnamerule)."'>".$j."</a>\r\n";
			}
		}
		$plist = "";
		if(eregi('info',$listitem))
		{
			$plist .= $maininfo.' ';
		}
		if(eregi('index',$listitem))
		{
			$plist .= $indexpage.' ';
		}
		if(eregi('pre',$listitem))
		{
			$plist .= $prepage.' ';
		}
		if(eregi('pageno',$listitem))
		{
			$plist .= $listdd.' ';
		}
		if(eregi('next',$listitem))
		{
			$plist .= $nextpage.' ';
		}
		if(eregi('end',$listitem))
		{
			$plist .= $endpage.' ';
		}
		if(eregi('option',$listitem))
		{
			$plist .= $optionlist;
		}
		return $plist;
	}

	//��ȡ��̬�ķ�ҳ�б�
	function GetPageListDM($list_len,$listitem="index,end,pre,next,pageno")
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
			return "��1ҳ/".$this->TotalResult."����¼";
		}
		if($this->TotalResult == 0)
		{
			return "��0ҳ/".$this->TotalResult."����¼";
		}
		$maininfo = "��{$totalpage}ҳ/".$this->TotalResult."����¼";
		$purl = $this->GetCurUrl();
		$geturl = "lid=".$this->FreeID."&TotalResult=".$this->TotalResult."&";
		$hidenform = "<input type='hidden' name='lid' value='".$this->FreeID."' />\r\n";
		$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."' />\r\n";
		$purl .= "?".$geturl;

		//�����һҳ����һҳ������
		if($this->PageNo != 1)
		{
			$prepage.="<a href='".$purl."PageNo=$prepagenum'>��һҳ</a>\r\n";
			$indexpage="<a href='".$purl."PageNo=1'>��ҳ</a>\r\n";
		}
		else
		{
			$indexpage="<a href='#'>��ҳ</a>\r\n";
		}
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.="<a href='".$purl."PageNo=$nextpagenum'>��һҳ</a>\r\n";
			$endpage="<a href='".$purl."PageNo=$totalpage'>ĩҳ</a>\r\n";
		}
		else
		{
			$endpage="<a href='#'>ĩҳ</a>\r\n";
		}

		//�����������
		$listdd="";
		$total_list = $list_len * 2 + 1;
		if($this->PageNo >= $total_list)
		{
			$j = $this->PageNo-$list_len;
			$total_list = $this->PageNo+$list_len;
			if($total_list>$totalpage) $total_list=$totalpage;
		}
		else
		{
			$j=1;
			if($total_list>$totalpage) $total_list=$totalpage;
		}
		for($j;$j<=$total_list;$j++)
		{
			if($j==$this->PageNo)
			{
				$listdd.= "<a href='#'>.$j.</a>\r\n";
			}
			else
			{
				$listdd.="<a href='".$purl."PageNo=$j'>".$j."</a>\r\n";
			}
		}
		$plist  = "<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
		$plist .= $maininfo.$indexpage.$prepage.$listdd.$nextpage.$endpage;
		if($totalpage>$total_list)
		{
			$plist.="<input type='text' name='PageNo'  value='".$this->PageNo."' style='width:30px' />\r\n";
			$plist.="<input type='submit' name='plistgo' value='GO' />\r\n";
		}
		$plist .= "</form>\r\n";
		return $plist;
	}

	//���һ��ָ������������
	function GetArcUrl($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$artdir='',
	$money=0,$filename='',$moresite='',$siteurl='',$sitepath='')
	{
		return GetFileUrl($aid,$typeid,$timetag,$title,$ismake,$rank,$namerule,$artdir,
		$money,$filename,$moresite,$siteurl,$sitepath);
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
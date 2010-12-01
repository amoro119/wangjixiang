<?php

if(!defined('DEDEINC')) exit('Request Error!');
require_once(DEDEINC.'/arc.partview.class.php');
require_once(DEDEINC.'/ftp.class.php');

@set_time_limit(0);
class ListView
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
	var $Fields;
	var $PartView;
	var $upPageType;
	var $addSql;
	var $IsError;
	var $CrossID;
	var $IsReplace;
	var $ftp;
	var $remoteDir;
	
	//php5���캯��
	function __construct($typeid,$uppage=1)
	{
		global $dsql,$ftp;
		$this->TypeID = $typeid;
		$this->dsql = &$dsql;
		$this->CrossID = '';
		$this->IsReplace = false;
		$this->IsError = false;
		$this->dtp = new DedeTagParse();
		$this->dtp->SetRefObj($this);
		$this->dtp->SetNameSpace("dede", "{", "}");
		$this->dtp2 = new DedeTagParse();
		$this->dtp2->SetNameSpace("field","[","]");
		$this->TypeLink = new TypeLink($typeid);
		$this->upPageType = $uppage;
		$this->ftp = &$ftp;;
		$this->remoteDir = '';
		$this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
		
		if(!is_array($this->TypeLink->TypeInfos))
		{
			$this->IsError = true;
		}
		if(!$this->IsError)
		{
			$this->ChannelUnit = new ChannelUnit($this->TypeLink->TypeInfos['channeltype']);
			$this->Fields = $this->TypeLink->TypeInfos;
			$this->Fields['id'] = $typeid;
			$this->Fields['position'] = $this->TypeLink->GetPositionLink(true);
			$this->Fields['title'] = ereg_replace("[<>]"," / ",$this->TypeLink->GetPositionLink(false));

			//����һЩȫ�ֲ�����ֵ
			foreach($GLOBALS['PubFields'] as $k=>$v) $this->Fields[$k] = $v;
			$this->Fields['rsslink'] = $GLOBALS['cfg_cmsurl']."/data/rss/".$this->TypeID.".xml";

			//���û�������
			SetSysEnv($this->TypeID,$this->Fields['typename'],0,'','list');
			$this->Fields['typeid'] = $this->TypeID;

			//��ý�����ĿID
			if($this->TypeLink->TypeInfos['cross']>0 && $this->TypeLink->TypeInfos['ispart']==0)
			{
				$selquery = '';
				if($this->TypeLink->TypeInfos['cross']==1)
				{
					$selquery = "Select id,topid From `#@__arctype` where typename like '{$this->Fields['typename']}' And id<>'{$this->TypeID}' And topid<>'{$this->TypeID}'  ";
				}
				else
				{
					$this->Fields['crossid'] = ereg_replace('[^0-9,]','',trim($this->Fields['crossid']));
					if($this->Fields['crossid']!='')
					{
						$selquery = "Select id,topid From `#@__arctype` where id in({$this->Fields['crossid']}) And id<>{$this->TypeID} And topid<>{$this->TypeID}  ";
					}
				}
				if($selquery!='')
				{
					$this->dsql->SetQuery($selquery);
					$this->dsql->Execute();
					while($arr = $this->dsql->GetArray())
					{
						$this->CrossID .= ($this->CrossID=='' ? $arr['id'] : ','.$arr['id']);
					}
				}
			}

		}//!error

	}

	//php4���캯��
	function ListView($typeid,$uppage=0){
		$this->__construct($typeid,$uppage);
	}
	//�ر������Դ
	function Close()
	{

	}

	//ͳ���б���ļ�¼
	function CountRecord()
	{
		global $cfg_list_son,$cfg_need_typeid2;
		if(empty($cfg_need_typeid2)) $cfg_need_typeid2 = 'N';
		
		//ͳ�����ݿ��¼
		$this->TotalResult = -1;
		if(isset($GLOBALS['TotalResult'])) $this->TotalResult = $GLOBALS['TotalResult'];
		if(isset($GLOBALS['PageNo'])) $this->PageNo = $GLOBALS['PageNo'];
		else $this->PageNo = 1;
		$this->addSql  = " arc.arcrank > -1 ";
		
		$typeid2like = " '%,{$this->TypeID},%' ";
		if($cfg_list_son=='N')
		{
			
			if($cfg_need_typeid2=='N')
			{
				if($this->CrossID=='') $this->addSql .= " And (arc.typeid='".$this->TypeID."') ";
				else $this->addSql .= " And (arc.typeid in({$this->CrossID},{$this->TypeID})) ";
			}
			else
			{
				if($this->CrossID=='') $this->addSql .= " And ( (arc.typeid='".$this->TypeID."') OR CONCAT(',', arc.typeid2, ',') like $typeid2like) ";
				else $this->addSql .= " And ( arc.typeid in({$this->CrossID},{$this->TypeID}) OR CONCAT(',', arc.typeid2, ',') like $typeid2like) ";
			}
		}
		else
		{
			$sonids = GetSonIds($this->TypeID,$this->Fields['channeltype']);
			if(!preg_match("/,/", $sonids)) {
				$sonidsCon = " arc.typeid = '$sonids' ";
			}
			else {
				$sonidsCon = " arc.typeid in($sonids) ";
			}
			if($cfg_need_typeid2=='N')
			{
				if($this->CrossID=='') $this->addSql .= " And ( $sonidsCon ) ";
				else $this->addSql .= " And ( arc.typeid in ({$sonids},{$this->CrossID}) ) ";
			}
			else
			{
				if($this->CrossID=='') $this->addSql .= " And ( $sonidsCon OR CONCAT(',', arc.typeid2, ',') like $typeid2like  ) ";
				else $this->addSql .= " And ( arc.typeid in ({$sonids},{$this->CrossID}) OR CONCAT(',', arc.typeid2, ',') like $typeid2like ) ";
			}
		}
		if($this->TotalResult==-1)
		{
			$cquery = "Select count(*) as dd From `#@__arctiny` arc where ".$this->addSql;
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

		//��ʼ���б�ģ�壬��ͳ��ҳ������
		$tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$this->TypeLink->TypeInfos['templist'];
		$tempfile = str_replace("{tid}",$this->TypeID,$tempfile);
		$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
		if(!file_exists($tempfile))
		{
			$tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/list_default.htm";
		}
		if(!file_exists($tempfile)||!is_file($tempfile))
		{
			echo "ģ���ļ������ڣ��޷������ĵ���";
			exit();
		}
		$this->dtp->LoadTemplate($tempfile);
		$ctag = $this->dtp->GetTag("page");
		if(!is_object($ctag))
		{
			$ctag = $this->dtp->GetTag("list");
		}
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

	//�б���HTML
	function MakeHtml($startpage=1,$makepagesize=0,$isremote=0)
	{
		global $cfg_remote_site;
		if(empty($startpage))
		{
			$startpage = 1;
		}

		//��������ģ���ļ�
		if($this->TypeLink->TypeInfos['isdefault']==-1)
		{
			echo '�����Ŀ�Ƕ�̬��Ŀ��';
			return '../plus/list.php?tid='.$this->TypeLink->TypeInfos['id'];
		}

		//����ҳ��
		else if($this->TypeLink->TypeInfos['ispart']>0)
		{
			$reurl = $this->MakePartTemplets();
			return $reurl;
		}

		$this->CountRecord();
		//�������̶�ֵ�ı�Ǹ�ֵ
		$this->ParseTempletsFirst();
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage==0)
		{
			$totalpage = 1;
		}
		CreateDir(MfTypedir($this->Fields['typedir']));
		$murl = '';
		if($makepagesize > 0)
		{
			$endpage = $startpage+$makepagesize;
		}
		else
		{
			$endpage = ($totalpage+1);
		}
		if( $endpage >= $totalpage+1 )
		{
			$endpage = $totalpage+1;
		}
		if($endpage==1)
		{
			$endpage = 2;
		}
		for($this->PageNo=$startpage; $this->PageNo < $endpage; $this->PageNo++)
		{
			$this->ParseDMFields($this->PageNo,1);
			$makeFile = $this->GetMakeFileRule($this->Fields['id'],'list',$this->Fields['typedir'],'',$this->Fields['namerule2']);
			$makeFile = str_replace("{page}",$this->PageNo,$makeFile);
			$murl = $makeFile;
			if(!ereg("^/",$makeFile))
			{
				$makeFile = "/".$makeFile;
			}
			$makeFile = $this->GetTruePath().$makeFile;
			$makeFile = ereg_replace("/{1,}","/",$makeFile);
			$murl = $this->GetTrueUrl($murl);
			$this->dtp->SaveTo($makeFile);
			//�������Զ�̷�������Ҫ�����ж�
			if($cfg_remote_site=='Y'&& $isremote == 1)
			{
	  		//����Զ���ļ�·��
	  		$remotefile = str_replace(DEDEROOT, '',$makeFile);
        $localfile = '..'.$remotefile;
        $remotedir = preg_replace('/[^\/]*\.html/', '',$remotefile);
    		//�������˵���Ѿ��л�Ŀ¼����Դ�������
        $this->ftp->rmkdir($remotedir);
	   	  $this->ftp->upload($localfile, $remotefile, 'acii');
		  }
		}
		if($startpage==1)
		{
			//����б����÷����ļ�����������ļ���һҳ
			if($this->TypeLink->TypeInfos['isdefault']==1
			&& $this->TypeLink->TypeInfos['ispart']==0)
			{
				$onlyrule = $this->GetMakeFileRule($this->Fields['id'],"list",$this->Fields['typedir'],'',$this->Fields['namerule2']);
				$onlyrule = str_replace("{page}","1",$onlyrule);
				$list_1 = $this->GetTruePath().$onlyrule;
				$murl = MfTypedir($this->Fields['typedir']).'/'.$this->Fields['defaultname'];
				//�������Զ�̷�������Ҫ�����ж�
				if($cfg_remote_site=='Y'&& $isremote == 1)
				{
				  //����Զ���ļ�·��
					$remotefile = $murl;
					$localfile = '..'.$remotefile;
					$remotedir = preg_replace('/[^\/]*\.html/', '',$remotefile);
					//�������˵���Ѿ��л�Ŀ¼����Դ�������
          $this->ftp->rmkdir($remotedir);
			   	$this->ftp->upload($localfile, $remotefile, 'acii');
			  }
				$indexname = $this->GetTruePath().$murl;
				copy($list_1,$indexname);
			}
		}
		return $murl;
	}

	//��ʾ�б�
	function Display()
	{
		if($this->TypeLink->TypeInfos['ispart']>0)
		{
			$this->DisplayPartTemplets();
			return ;
		}
		$this->CountRecord();
		if((empty($this->PageNo) || $this->PageNo==1)
		&& $this->TypeLink->TypeInfos['ispart']==1)
		{
			$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
			$tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
			$tempfile = $tmpdir."/".$tempfile;
			if(!file_exists($tempfile))
			{
				$tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default.htm";
			}
			$this->dtp->LoadTemplate($tempfile);
		}
		$this->ParseTempletsFirst();
		$this->ParseDMFields($this->PageNo,0);
		$this->dtp->Display();
	}

	//��������ģ��ҳ��
	function MakePartTemplets()
	{
		$this->PartView = new PartView($this->TypeID,false);
		$this->PartView->SetTypeLink($this->TypeLink);
		$nmfa = 0;
		$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
		if($this->Fields['ispart']==1)
		{
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
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
			//��ת��ַ
			return $this->Fields['typedir'];
		}
		CreateDir(MfTypedir($this->Fields['typedir']));
		$makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",MfTypedir($this->Fields['typedir']),$this->Fields['defaultname'],$this->Fields['namerule2']);
		$makeUrl = ereg_replace("/{1,}","/",$makeUrl);
		$makeFile = $this->GetTruePath().$makeUrl;
		if($nmfa==0)
		{
			$this->PartView->SaveToHtml($makeFile);
			//�������Զ�̷�������Ҫ�����ж�
			if($GLOBALS['cfg_remote_site']=='Y'&& $isremote == 1)
			{
			  		//����Զ���ļ�·��
			  		$remotefile = str_replace(DEDEROOT, '',$makeFile);
            $localfile = '..'.$remotefile;
            $remotedir = preg_replace('/[^\/]*\.html/', '',$remotefile);
        		//�������˵���Ѿ��л�Ŀ¼����Դ�������
            $this->ftp->rmkdir($remotedir);
			   	  $this->ftp->upload($localfile, $remotefile, 'acii');
		  }
		}
		else
		{
			if(!file_exists($makeFile))
			{
				$this->PartView->SaveToHtml($makeFile);
				//�������Զ�̷�������Ҫ�����ж�
				if($cfg_remote_site=='Y'&& $isremote == 1)
				{
				  		//����Զ���ļ�·��
				  		$remotefile = str_replace(DEDEROOT, '',$makeFile);
	            $localfile = '..'.$remotefile;
	            $remotedir = preg_replace('/[^\/]*\.html/', '',$remotefile);
	        		//�������˵���Ѿ��л�Ŀ¼����Դ�������
	            $this->ftp->rmkdir($remotedir);
				   	  $this->ftp->upload($localfile, $remotefile, 'acii');
			  }
			}
		}
		return $this->GetTrueUrl($makeUrl);
	}

	//��ʾ����ģ��ҳ��
	function DisplayPartTemplets()
	{
		$this->PartView = new PartView($this->TypeID,false);
		$this->PartView->SetTypeLink($this->TypeLink);
		$nmfa = 0;
		$tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
		if($this->Fields['ispart']==1)
		{
			//����ģ��
			$tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
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
			//��ת��ַ
			$gotourl = $this->Fields['typedir'];
			header("Location:$gotourl");
			exit();
		}
		CreateDir(MfTypedir($this->Fields['typedir']));
		$makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",MfTypedir($this->Fields['typedir']),$this->Fields['defaultname'],$this->Fields['namerule2']);
		$makeFile = $this->GetTruePath().$makeUrl;
		if($nmfa==0)
		{
			$this->PartView->Display();
		}
		else
		{
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

	//���վ�����ʵ��·��
	function GetTruePath()
	{
		$truepath = $GLOBALS["cfg_basedir"];
		return $truepath;
	}

	//�����ʵ����·��
	function GetTrueUrl($nurl)
	{
		if($this->Fields['moresite']==1)
		{
			if($this->Fields['sitepath']!='')
			{
				$nurl = ereg_replace("^".$this->Fields['sitepath'],'',$nurl);
			}
			$nurl = $this->Fields['siteurl'].$nurl;
		}
		return $nurl;
	}

	//����ģ�壬�Թ̶��ı�ǽ��г�ʼ��ֵ
	function ParseTempletsFirst()
	{
		if(isset($this->TypeLink->TypeInfos['reid']))
		{
			$GLOBALS['envs']['reid'] = $this->TypeLink->TypeInfos['reid'];
		}
		$GLOBALS['envs']['typeid'] = $this->TypeID;
		$GLOBALS['envs']['topid'] = GetTopid($this->Fields['typeid']);
		$GLOBALS['envs']['cross'] = 1;
		MakeOneTag($this->dtp,$this);
	}

	//����ģ�壬��������ı䶯���и�ֵ
	function ParseDMFields($PageNo,$ismake=1)
	{
		//�滻�ڶ�ҳ�������
		if(($PageNo>1 || strlen($this->Fields['content'])<10 ) && !$this->IsReplace)
		{
			$this->dtp->SourceString = str_replace('[cmsreplace]','display:none',$this->dtp->SourceString);
			$this->IsReplace = true;
		}
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
				$this->GetArcList(
				$limitstart,
				$row,
				$ctag->GetAtt("col"),
				$ctag->GetAtt("titlelen"),
				$ctag->GetAtt("infolen"),
				$ctag->GetAtt("imgwidth"),
				$ctag->GetAtt("imgheight"),
				$ctag->GetAtt("listtype"),
				$ctag->GetAtt("orderby"),
				$InnerText,
				$ctag->GetAtt("tablewidth"),
				$ismake,
				$ctag->GetAtt("orderway")
				)
				);
			}
			else if($ctag->GetName()=="pagelist")
			{
				$list_len = trim($ctag->GetAtt("listsize"));
				$ctag->GetAtt("listitem")=="" ? $listitem="index,pre,pageno,next,end,option" : $listitem=$ctag->GetAtt("listitem");
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
			else if($PageNo!=1 && $ctag->GetName()=='field' && $ctag->GetAtt('display')!='')
			{
				$this->dtp->Assign($tagid,'');
			}
		}
	}

	//���Ҫ�������ļ����ƹ���
	function GetMakeFileRule($typeid,$wname,$typedir,$defaultname,$namerule2)
	{
		$typedir = MfTypedir($typedir);
		if($wname=='index')
		{
			return $typedir.'/'.$defaultname;
		}
		else
		{
			$namerule2 = str_replace('{tid}',$typeid,$namerule2);
			$namerule2 = str_replace('{typedir}',$typedir,$namerule2);
			return $namerule2;
		}
	}

	//���һ�����е��ĵ��б�
	function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$infolen=250,
	$imgwidth=120,$imgheight=90,$listtype="all",$orderby="default",$innertext="",$tablewidth="100",$ismake=1,$orderWay='desc')
	{
		global $cfg_list_son;
		
		$typeid=$this->TypeID;
		
		if($row=='') $row = 10;
		if($limitstart=='') $limitstart = 0;
		if($titlelen=='') $titlelen = 100;
		if($infolen=='') $infolen = 250;
		if($imgwidth=='') $imgwidth = 120;
		if($imgheight=='') $imgheight = 120;
		if($listtype=='') $listtype = 'all';
		if($orderWay=='') $orderWay = 'desc';
		
		if($orderby=='') {
			$orderby='default';
		}
		else {
			$orderby=strtolower($orderby);
		}
		
		$tablewidth = str_replace('%','',$tablewidth);
		if($tablewidth=='') $tablewidth=100;
		if($col=='') $col=1;
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth.'%';
		$colWidth = $colWidth.'%';
		
		$innertext = trim($innertext);
		if($innertext=='') {
			$innertext = GetSysTemplets('list_fulllist.htm');
		}

		//����ʽ
		$ordersql = '';
		if($orderby=="senddate" || $orderby=="id") {
			$ordersql=" order by arc.id $orderWay";
		}
		else if($orderby=="hot" || $orderby=="click") {
			$ordersql = " order by arc.click $orderWay";
		}
		else if($orderby=="lastpost") {
			$ordersql = "  order by arc.lastpost $orderWay";
		}
		else {
			$ordersql=" order by arc.sortrank $orderWay";
		}

		//��ø��ӱ�������Ϣ
		$addtable  = $this->ChannelUnit->ChannelInfos['addtable'];
		if($addtable!="")
		{
			$addJoin = " left join `$addtable` on arc.id = ".$addtable.'.aid ';
			$addField = '';
			$fields = explode(',',$this->ChannelUnit->ChannelInfos['listfields']);
			foreach($fields as $k=>$v)
			{
				$nfields[$v] = $k;
			}
			if(is_array($this->ChannelUnit->ChannelFields) && !empty($this->ChannelUnit->ChannelFields))
			{
				foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
				{
					if(isset($nfields[$k]))
					{
						if(!empty($arr['rename'])) {
							$addField .= ','.$addtable.'.'.$k.' as '.$arr['rename'];
						}
						else {
							$addField .= ','.$addtable.'.'.$k;
						}
					}
				}
			}
		}
		else
		{
			$addField = '';
			$addJoin = '';
		}

		//�������Ĭ�ϵ�sortrank��id����ʹ�����ϲ�ѯ����������ʱ�ǳ�������
		if(ereg('hot|click|lastpost',$orderby))
		{
			$query = "Select arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,
		   tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
		   $addField
		   from `#@__archives` arc
		   left join `#@__arctype` tp on arc.typeid=tp.id
		   $addJoin
		   where {$this->addSql} $ordersql limit $limitstart,$row";
		}
		//��ͨ����ȴ�arctiny����ID��Ȼ��ID��ѯ���ٶȷǳ��죩
		else
		{
			$t1 = ExecTime();
			$ids = array();
			$query = "Select id From `#@__arctiny` arc where {$this->addSql} $ordersql limit $limitstart,$row ";
			$this->dsql->SetQuery($query);
			$this->dsql->Execute();
			while($arr=$this->dsql->GetArray())
			{
				$ids[] = $arr['id'];
			}
			$idstr = join(',',$ids);
			if($idstr=='')
			{
				return '';
			}
			else
			{
				$query = "Select arc.*,tp.typedir,tp.typename,tp.corank,tp.isdefault,tp.defaultname,
		   			tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
		   			$addField
		   			from `#@__archives` arc left join `#@__arctype` tp on arc.typeid=tp.id
		   			$addJoin
		   			where arc.id in($idstr) $ordersql ";
			}
			$t2 = ExecTime();
			//echo $t2-$t1;

		}
		$this->dsql->SetQuery($query);
		$this->dsql->Execute('al');
		$t2 = ExecTime();

		//echo $t2-$t1;
		$artlist = '';
		$this->dtp2->LoadSource($innertext);
		$GLOBALS['autoindex'] = 0;
		for($i=0;$i<$row;$i++)
		{
			if($col>1)
			{
				$artlist .= "<div>\r\n";
			}
			for($j=0;$j<$col;$j++)
			{
				if($row = $this->dsql->GetArray("al"))
				{
					$GLOBALS['autoindex']++;
					$ids[$row['id']] = $row['id'];

					//����һЩ�����ֶ�
					$row['infos'] = cn_substr($row['description'],$infolen);
					$row['id'] =  $row['id'];

					if($row['corank'] > 0 && $row['arcrank']==0)
					{
						$row['arcrank'] = $row['corank'];
					}

					$row['filename'] = $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
					$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);
					$row['typeurl'] = GetTypeUrl($row['typeid'],MfTypedir($row['typedir']),$row['isdefault'],$row['defaultname'],
					$row['ispart'],$row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);
					if($row['litpic'] == '-' || $row['litpic'] == '')
					{
						$row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
					}
					if(!eregi("^http://",$row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
					{
						$row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
					}
					$row['picname'] = $row['litpic'];
					$row['stime'] = GetDateMK($row['pubdate']);
					$row['typelink'] = "<a href='".$row['typeurl']."'>".$row['typename']."</a>";
					$row['image'] = "<img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".ereg_replace("['><]","",$row['title'])."'>";
					$row['imglink'] = "<a href='".$row['filename']."'>".$row['image']."</a>";
					$row['fulltitle'] = $row['title'];
					$row['title'] = cn_substr($row['title'],$titlelen);
					if($row['color']!='')
					{
						$row['title'] = "<font color='".$row['color']."'>".$row['title']."</font>";
					}
					if(ereg('c',$row['flag']))
					{
						$row['title'] = "<b>".$row['title']."</b>";
					}
					$row['textlink'] = "<a href='".$row['filename']."'>".$row['title']."</a>";
					$row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
					$row['memberurl'] = $GLOBALS['cfg_memberurl'];
					$row['templeturl'] = $GLOBALS['cfg_templeturl'];

					//���븽�ӱ��������
					foreach($row as $k=>$v)
					{
						$row[strtolower($k)] = $v;
					}
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

			}//Loop Col

			if($col>1)
			{
				$i += $col - 1;
				$artlist .= "	</div>\r\n";
			}
		}//Loop Line

		$t3 = ExecTime();

		//echo ($t3-$t2);
		$this->dsql->FreeResult('al');
		return $artlist;
	}

	//��ȡ��̬�ķ�ҳ�б�
	function GetPageListST($list_len,$listitem="index,end,pre,next,pageno")
	{
		$prepage = $nextpage = '';
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		if($list_len=='' || ereg("[^0-9]",$list_len))
		{
			$list_len=3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{

			return "<li><span class=\"pageinfo\">�� <strong>1</strong>ҳ<strong>".$this->TotalResult."</strong>����¼</span></li>\r\n";
		}
		if($this->TotalResult == 0)
		{
			return "<li><span class=\"pageinfo\">�� <strong>0</strong>ҳ<strong>".$this->TotalResult."</strong>����¼</span></li>\r\n";
		}
		$purl = $this->GetCurUrl();
		$maininfo = "<li><span class=\"pageinfo\">�� <strong>{$totalpage}</strong>ҳ<strong>".$this->TotalResult."</strong>��</span></li>\r\n";
		$tnamerule = $this->GetMakeFileRule($this->Fields['id'],"list",$this->Fields['typedir'],$this->Fields['defaultname'],$this->Fields['namerule2']);
		$tnamerule = ereg_replace('^(.*)/','',$tnamerule);

		//�����һҳ����ҳ������
		if($this->PageNo != 1)
		{
			$prepage.="<li><a href='".str_replace("{page}",$prepagenum,$tnamerule)."'>��һҳ</a></li>\r\n";
			$indexpage="<li><a href='".str_replace("{page}",1,$tnamerule)."'>��ҳ</a></li>\r\n";
		}
		else
		{
			$indexpage="<li>��ҳ</li>\r\n";
		}

		//��һҳ,δҳ������
		if($this->PageNo!=$totalpage && $totalpage>1)
		{
			$nextpage.="<li><a href='".str_replace("{page}",$nextpagenum,$tnamerule)."'>��һҳ</a></li>\r\n";
			$endpage="<li><a href='".str_replace("{page}",$totalpage,$tnamerule)."'>ĩҳ</a></li>\r\n";
		}
		else
		{
			$endpage="<li>ĩҳ</li>\r\n";
		}

		//option����
		$optionlist = '';

		$optionlen = strlen($totalpage);
		$optionlen = $optionlen*12 + 18;
		if($optionlen < 36) $optionlen = 36;
		if($optionlen > 100) $optionlen = 100;
		$optionlist = "<li><select name='sldd' style='width:{$optionlen}px' onchange='location.href=this.options[this.selectedIndex].value;'>\r\n";
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
		$optionlist .= "</select></li>\r\n";

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
				$listdd.= "<li class=\"thisclass\">$j</li>\r\n";
			}
			else
			{
				$listdd.="<li><a href='".str_replace("{page}",$j,$tnamerule)."'>".$j."</a></li>\r\n";
			}
		}
		$plist = '';
		if(eregi('index',$listitem)) $plist .= $indexpage;
		if(eregi('pre',$listitem)) $plist .= $prepage;
		if(eregi('pageno',$listitem)) $plist .= $listdd;
		if(eregi('next',$listitem)) $plist .= $nextpage;
		if(eregi('end',$listitem)) $plist .= $endpage;
		if(eregi('option',$listitem)) $plist .= $optionlist;
		if(eregi('info',$listitem)) $plist .= $maininfo;
		
		return $plist;
	}

	//��ȡ��̬�ķ�ҳ�б�
	function GetPageListDM($list_len,$listitem="index,end,pre,next,pageno")
	{
		global $cfg_rewrite;
		$prepage = $nextpage = '';
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		if($list_len=='' || ereg("[^0-9]",$list_len))
		{
			$list_len=3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{
			return "<li><span class=\"pageinfo\">�� 1 ҳ/".$this->TotalResult." ����¼</span></li>\r\n";
		}
		if($this->TotalResult == 0)
		{
			return "<li><span class=\"pageinfo\">�� 0 ҳ/".$this->TotalResult." ����¼</span></li>\r\n";
		}
		$maininfo = "<li><span class=\"pageinfo\">�� <strong>{$totalpage}</strong>ҳ<strong>".$this->TotalResult."</strong>��</span></li>\r\n";
		
		$purl = $this->GetCurUrl();
		if($cfg_rewrite == 'Y')
		{
			$nowurls = ereg_replace("\-", ".php?", $purl);
			$nowurls = explode("?", $nowurls);
			$purl = $nowurls[0];
		}

		$geturl = "tid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
		$purl .= '?'.$geturl;
		
		$optionlist = '';
		//$hidenform = "<input type='hidden' name='tid' value='".$this->TypeID."'>\r\n";
		//$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";

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
				$listdd.="<li><a href='".$purl."PageNo=$j'>".$j."</a></li>\r\n";
			}
		}

		$plist = '';
		if(eregi('index',$listitem)) $plist .= $indexpage;
		if(eregi('pre',$listitem)) $plist .= $prepage;
		if(eregi('pageno',$listitem)) $plist .= $listdd;
		if(eregi('next',$listitem)) $plist .= $nextpage;
		if(eregi('end',$listitem)) $plist .= $endpage;
		if(eregi('option',$listitem)) $plist .= $optionlist;
		if(eregi('info',$listitem)) $plist .= $maininfo;
		
		if($cfg_rewrite == 'Y')
		{
			$plist = str_replace('.php?tid=', '-', $plist);
			$plist = str_replace('&TotalResult=', '-', $plist);
			$plist = preg_replace("/&PageNo=(\d+)/i",'-\\1.html',$plist);
		}
		return $plist;
	}

	//��õ�ǰ��ҳ���ļ���url
	function GetCurUrl()
	{
		if(!empty($_SERVER['REQUEST_URI']))
		{
			$nowurl = $_SERVER['REQUEST_URI'];
			$nowurls = explode('?', $nowurl);
			$nowurl = $nowurls[0];
		}
		else
		{
			$nowurl = $_SERVER['PHP_SELF'];
		}
		return $nowurl;
	}
}//End Class
?>
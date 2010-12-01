<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/channelunit.func.php");

class TypeLink
{
	var $typeDir;
	var $dsql;
	var $TypeID;
	var $baseDir;
	var $modDir;
	var $indexUrl;
	var $indexName;
	var $TypeInfos;
	var $SplitSymbol;
	var $valuePosition;
	var $valuePositionName;
	var $OptionArrayList;

	//���캯��///////
	//php5���캯��
	function __construct($typeid)
	{
		$this->indexUrl = $GLOBALS['cfg_basehost'].$GLOBALS['cfg_indexurl'];
		$this->indexName = $GLOBALS['cfg_indexname'];
		$this->baseDir = $GLOBALS['cfg_basedir'];
		$this->modDir = $GLOBALS['cfg_templets_dir'];
		$this->SplitSymbol = $GLOBALS['cfg_list_symbol'];
		$this->dsql = $GLOBALS['dsql'];
		$this->TypeID = $typeid;
		$this->valuePosition = '';
		$this->valuePositionName = '';
		$this->typeDir = '';
		$this->OptionArrayList = '';

		//������Ŀ��Ϣ
		$query = "Select tp.*,ch.typename as ctypename,ch.addtable,ch.issystem From `#@__arctype` tp left join `#@__channeltype` ch
		on ch.id=tp.channeltype  where tp.id='$typeid' ";
		if($typeid > 0)
		{
			$this->TypeInfos = $this->dsql->GetOne($query);
			if(is_array($this->TypeInfos))
			{
				$this->TypeInfos['tempindex'] = MfTemplet($this->TypeInfos['tempindex']);
				$this->TypeInfos['templist'] = MfTemplet($this->TypeInfos['templist']);
				$this->TypeInfos['temparticle'] = MfTemplet($this->TypeInfos['temparticle']);
			}
		}
	}

	//����ʹ��Ĭ�Ϲ��캯�������
	//GetPositionLink()��������
	function TypeLink($typeid)
	{
		$this->__construct($typeid);
	}

	//�ر����ݿ����ӣ�������Դ
	function Close()
	{
	}

	//������ĿID
	function SetTypeID($typeid)
	{
		$this->TypeID = $typeid;
		$this->valuePosition = "";
		$this->valuePositionName = "";
		$this->typeDir = "";
		$this->OptionArrayList = "";

		//������Ŀ��Ϣ
		$query = "
		Select #@__arctype.*,#@__channeltype.typename as ctypename
		From #@__arctype left join #@__channeltype
		on #@__channeltype.id=#@__arctype.channeltype where #@__arctype.id='$typeid' ";
		$this->dsql->SetQuery($query);
		$this->TypeInfos = $this->dsql->GetOne();
	}

	//��������Ŀ��·��
	function GetTypeDir()
	{
		if(empty($this->TypeInfos['typedir']))
		{
			return $GLOBALS['cfg_cmspath'].$GLOBALS['cfg_arcdir'];
		}
		else
		{
			return $this->TypeInfos['typedir'];
		}
	}

	//���ĳ��Ŀ�������б� �磺��Ŀһ>>��Ŀ��>> ��������ʽ
	//islink ��ʾ���ص��б��Ƿ������
	function GetPositionLink($islink=true)
	{
		$indexpage = "<a href='".$this->indexUrl."'>".$this->indexName."</a>";
		if($this->valuePosition!="" && $islink)
		{
			return $this->valuePosition;
		}
		else if($this->valuePositionName!="" && !$islink)
		{
			return $this->valuePositionName;
		}
		else if($this->TypeID==0)
		{
			if($islink)
			{
				return $indexpage;
			}
			else
			{
				return "ûָ�����࣡";
			}
		}
		else
		{
			if($islink)
			{
				$this->valuePosition = $this->GetOneTypeLink($this->TypeInfos);
				if($this->TypeInfos['reid']!=0)
				{
					//���õݹ��߼�
					$this->LogicGetPosition($this->TypeInfos['reid'],true);
				}
				$this->valuePosition = $indexpage.$this->SplitSymbol.$this->valuePosition;
				return $this->valuePosition.$this->SplitSymbol;
			}
			else
			{
				$this->valuePositionName = $this->TypeInfos['typename'];
				if($this->TypeInfos['reid']!=0)
				{
					//���õݹ��߼�
					$this->LogicGetPosition($this->TypeInfos['reid'],false);
				}
				return $this->valuePositionName;
			}
		}
	}

	//��������б�
	function GetPositionName()
	{
		return $this->GetPositionLink(false);
	}

	//���ĳ��Ŀ�������б��ݹ��߼�����
	function LogicGetPosition($id,$islink)
	{
		$this->dsql->SetQuery("Select id,reid,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath From #@__arctype where id='".$id."'");
		$tinfos = $this->dsql->GetOne();
		if($islink)
		{
			$this->valuePosition = $this->GetOneTypeLink($tinfos).$this->SplitSymbol.$this->valuePosition;
		}
		else
		{
			$this->valuePositionName = $tinfos['typename'].$this->SplitSymbol.$this->valuePositionName;
		}
		if($tinfos['reid']>0)
		{
			$this->LogicGetPosition($tinfos['reid'],$islink);
		}
		else
		{
			return 0;
		}

	}

	//���ĳ����Ŀ�ĳ�������Ϣ
	function GetOneTypeLink($typeinfos)
	{
		$typepage = $this->GetOneTypeUrl($typeinfos);
		$typelink = "<a href='".$typepage."'>".$typeinfos['typename']."</a>";
		return $typelink;
	}

	//���ĳ�������ӵ�URL
	function GetOneTypeUrl($typeinfos)
	{
		return GetTypeUrl($typeinfos['id'],MfTypedir($typeinfos['typedir']),$typeinfos['isdefault'],$typeinfos['defaultname'],
		$typeinfos['ispart'],$typeinfos['namerule2'],$typeinfos['moresite'],$typeinfos['siteurl'],$typeinfos['sitepath']);
	}

	//�������б�
	//hid ��ָĬ��ѡ����Ŀ��0 ��ʾ����ѡ����Ŀ���򡰲�����Ŀ��
	//oper ���û�����������Ŀ��0 ��ʾ������Ŀ
	//channeltype ��ָ��Ŀ���������ͣ�0 ��ʾ����Ƶ��
	function GetOptionArray($hid=0,$oper=0,$channeltype=0,$usersg=0)
	{
		return $this->GetOptionList($hid,$oper,$channeltype,$usersg);
	}

	function GetOptionList($hid=0,$oper=0,$channeltype=0,$usersg=0)
	{
		global $cfg_admin_channel;
		if(empty($cfg_admin_channel)) $cfg_admin_channel = 'all';
		
		if(!$this->dsql) $this->dsql = $GLOBALS['dsql'];
		$this->OptionArrayList = '';
		
		if($hid>0)
		{
			$row = $this->dsql->GetOne("Select id,typename,ispart,channeltype From #@__arctype where id='$hid'");
			$channeltype = $row['channeltype'];
			if($row['ispart']==1) {
				$this->OptionArrayList .= "<option value='".$row['id']."' style='background-color:#DFDFDB;color:#888888' selected>".$row['typename']."</option>\r\n";
			}
			else {
				$this->OptionArrayList .= "<option value='".$row['id']."' selected>".$row['typename']."</option>\r\n";
			}
		}
		
		if($channeltype==0) $ctsql = '';
		else $ctsql=" And channeltype='$channeltype' ";
		
		
		if(is_array($oper) && $cfg_admin_channel != 'all')
		{
			if( count($oper) == 0 )
			{
				$query = "Select id,typename,ispart From `#@__arctype` where 1=2 ";
			}
			else
			{
				$admin_catalog_tmp = $admin_catalog = join(',', $oper);
				$this->dsql->SetQuery("Select reid From `#@__arctype` where id in($admin_catalog) group by reid ");
				$this->dsql->Execute();
				$topidstr = '';
				while($row = $this->dsql->GetObject())
				{
					if($row->reid==0) continue;
					$topidstr .= ($topidstr=='' ? $row->reid : ','.$row->reid);
				}
				$admin_catalog .= ','.$topidstr;
				$admin_catalogs = explode(',', $admin_catalog);
				$admin_catalogs = array_unique($admin_catalogs);
				$admin_catalog = join(',', $admin_catalogs);
				$admin_catalog = preg_replace("/,$/", '', $admin_catalog);
				$query = "Select id,typename,ispart From `#@__arctype` where ispart<>2 And id in({$admin_catalog}) And reid=0 $ctsql";
			}
		}
		else
		{
			$query = "Select id,typename,ispart From `#@__arctype` where ispart<>2 And reid=0 $ctsql order by sortrank asc";
		}

		$this->dsql->SetQuery($query);
		$this->dsql->Execute();
		while($row=$this->dsql->GetObject())
		{
			if($row->id!=$hid)
			{
				if($row->ispart==1) {
					$this->OptionArrayList .= "<option value='".$row->id."' style='background-color:#EFEFEF;color:#666666'>".$row->typename."</option>\r\n";
				}
				else {
					$this->OptionArrayList .= "<option value='".$row->id."'>".$row->typename."</option>\r\n";
				}
			}
			$this->LogicGetOptionArray($row->id, "��", $oper);
		}
		return $this->OptionArrayList;
	}

	function LogicGetOptionArray($id, $step, $oper=0)
	{
		global $cfg_admin_channel;
		if(empty($cfg_admin_channel)) $cfg_admin_channel = 'all';
		
		$this->dsql->SetQuery("Select id,typename,ispart From #@__arctype where reid='".$id."' And ispart<>2 order by sortrank asc");
		$this->dsql->Execute($id);
		while($row=$this->dsql->GetObject($id))
		{
			if(is_array($oper) && $cfg_admin_channel != 'all')
			{
				if(!in_array($row->id, $oper)) continue;
			}
			if($row->ispart==1) {
				$this->OptionArrayList .= "<option value='".$row->id."' style='background-color:#EFEFEF;color:#666666'>$step".$row->typename."</option>\r\n";
			}
			else {
				$this->OptionArrayList .= "<option value='".$row->id."'>$step".$row->typename."</option>\r\n";
			}
			$this->LogicGetOptionArray($row->id, $step."��", $oper);
		}
	}

	//����������ص���Ŀ��������Ӧ����ģ����{dede:channel}{/dede:channel}��
	//$typetype ��ֵΪ�� sun �¼����� self ͬ������ top ��������
	function GetChannelList($typeid=0,$reid=0,$row=8,$typetype='sun',$innertext='',
	$col=1,$tablewidth=100,$myinnertext='')
	{
		if($typeid==0)
		{
			$typeid = $this->TypeID;
		}
		if($row=="")
		{
			$row = 8;
		}
		if($reid=="")
		{
			$reid = 0;
		}
		if($col=="")
		{
			$col = 1;
		}
		$tablewidth = str_replace("%","",$tablewidth);
		if($tablewidth=="")
		{
			$tablewidth=100;
		}
		if($col=="")
		{
			$col = 1;
		}
		$colWidth = ceil(100/$col);
		$tablewidth = $tablewidth."%";
		$colWidth = $colWidth."%";
		if($typetype=="")
		{
			$typetype="sun";
		}
		if($innertext=="")
		{
			$innertext = GetSysTemplets("channel_list.htm");
		}
		if($reid==0 && $typeid>0)
		{
			$dbrow = $this->dsql->GetOne("Select reid From #@__arctype where id='$typeid' ");
			if(is_array($dbrow))
			{
				$reid = $dbrow['reid'];
			}
		}
		$likeType = "";
		if($typetype=="top")
		{
			$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl
		  From #@__arctype where reid=0 And ishidden<>1 order by sortrank asc limit 0,$row";
		}
		else if($typetype=="sun"||$typetype=="son")
		{
			$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl
		  From #@__arctype where reid='$typeid' And ishidden<>1 order by sortrank asc limit 0,$row";
		}
		else if($typetype=="self")
		{
			$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl
			From #@__arctype where reid='$reid' And ishidden<>1 order by sortrank asc limit 0,$row";
		}

		//And ID<>'$typeid'
		$dtp2 = new DedeTagParse();
		$dtp2->SetNameSpace("field","[","]");
		$dtp2->LoadSource($innertext);
		$this->dsql->SetQuery($sql);
		$this->dsql->Execute();
		$line = $row;
		$GLOBALS['autoindex'] = 0;
		if($col>1)
		{
			$likeType = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
		}
		for($i=0;$i<$line;$i++)
		{
			if($col>1)
			{
				$likeType .= "<tr>\r\n";
			}
			for($j=0;$j<$col;$j++)
			{
				if($col>1) $likeType .= "	<td width='$colWidth'>\r\n";
				if($row=$this->dsql->GetArray())
				{
					//����ǰ��Ŀ����ʽ
					if($row['id']=="$typeid" && $myinnertext != '')
					{
						$linkOkstr = $myinnertext;
						$row['typelink'] = $this->GetOneTypeUrl($row);
						$linkOkstr = str_replace("~typelink~",$row['typelink'],$linkOkstr);
						$linkOkstr = str_replace("~typename~",$row['typename'],$linkOkstr);
						$likeType .= $linkOkstr;
					}
					else
					{
						//�ǵ�ǰ��Ŀ
						$row['typelink'] = $this->GetOneTypeUrl($row);
						if(is_array($dtp2->CTags))
						{
							foreach($dtp2->CTags as $tagid=>$ctag)
							{
								if(isset($row[$ctag->GetName()]))
								{
									$dtp2->Assign($tagid,$row[$ctag->GetName()]);
								}
							}
						}
						$likeType .= $dtp2->GetResult();
					}
				}
				if($col>1)
				{
					$likeType .= "	</td>\r\n";
				}
				$GLOBALS['autoindex']++;
			}//Loop Col

			if($col>1)
			{
				$i += $col - 1;
			}
			if($col>1)
			{
				$likeType .= "	</tr>\r\n";
			}
		}//Loop for $i

		if($col>1)
		{
			$likeType .= "	</table>\r\n";
		}
		$this->dsql->FreeResult();
		return $likeType;
	}//GetChannel

}//End Class
?>
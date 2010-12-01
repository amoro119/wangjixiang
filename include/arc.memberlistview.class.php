<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/dedetemplate.class.php");
$lang_pre_page = '��ҳ';
$lang_next_page = '��ҳ';
$lang_index_page = '��ҳ';
$lang_end_page = 'ĩҳ';
$lang_record_number = '����¼';
$lang_page = 'ҳ';
$lang_total = '��';

// ����չʾ��
class MemberListview
{
	var $dsql = '';
	var $tpl = '';
	var $pageNO = 1;
	var $totalPage = 0;
	var $totalResult = 0;
	var $pageSize = 25;
	var $getValues = array();
	var $sourceSql = '';
	var $isQuery = false;
	var $randts = 0;

	//��ָ�����ĵ�ID���г�ʼ��
	function __construct($tplfile='')
	{
		$this->sourceSql = '';
		$this->pageSize = 25;
		$this->queryTime = 0;
		$this->getValues = Array();
		$this->randts = time();
		$this->dsql = $GLOBALS['dsql'];
		$this->SetVar('ParseEnv','datalist');
		$this->tpl = new DedeTemplate();
		if($GLOBALS['cfg_tplcache']=='N')
		{
			$this->tpl->isCache = false;
		}
		if($tplfile!='')
		{
			$this->tpl->LoadTemplate($tplfile);
		}
	}

	function MemberListview($tplfile='')
	{
		$this->__construct($tplfile);
	}

	//����SQL���
	function SetSource($sql)
	{
		$this->sourceSql = $sql;
	}

	//����ģ��
	//�����Ҫʹ��ģ����ָ����pagesize�������ڵ���ģ���ŵ��� SetSource($sql)
	function SetTemplate($tplfile)
	{
		$this->tpl->LoadTemplate($tplfile);
	}

	function SetTemplet($tplfile)
	{
		$this->tpl->LoadTemplate($tplfile);
	}

	//��config������get�����Ƚ���Ԥ����
	function PreLoad()
	{
		global $totalresult,$pageno;
		if(empty($pageno) || ereg("[^0-9]",$pageno))
		{
			$pageno = 1;
		}
		if(empty($totalresult) || ereg("[^0-9]",$totalresult))
		{
			$totalresult = 0;
		}
		$this->pageNO = $pageno;
		$this->totalResult = $totalresult;

		if(isset($this->tpl->tpCfgs['pagesize']))
		{
			$this->pageSize = $this->tpl->tpCfgs['pagesize'];
		}
		$this->totalPage = ceil($this->totalResult/$this->pageSize);
		if($this->totalResult==0)
		{
			//$this->isQuery = true;
			//$this->dsql->Execute('mbdl',$this->sourceSql);
			//$this->totalResult = $this->dsql->GetTotalRow('mbdl');
			$countQuery = eregi_replace("select[ \r\n\t](.*)[ \r\n\t]from","Select count(*) as dd From",$this->sourceSql);
			$row = $this->dsql->GetOne($countQuery);
			$this->totalResult = $row['dd'];
			$this->sourceSql .= " limit 0,".$this->pageSize;
		}
		else
		{
			$this->sourceSql .= " limit ".(($this->pageNO-1) * $this->pageSize).",".$this->pageSize;
		}
	}

	//������ַ��Get������ֵ
	function SetParameter($key,$value)
	{
		$this->getValues[$key] = $value;
	}

	//����/��ȡ�ĵ���صĸ��ֱ���
	function SetVar($k,$v)
	{
		global $_vars;
		if(!isset($_vars[$k])) $_vars[$k] = $v;
	}

	function GetVar($k)
	{
		global $_vars;
		if(isset($_vars[$k])) return $_vars[$k];
		else return '';
	}

	//��ȡ��ǰҳ�����б�
	function GetArcList($atts,$refObj='',$fields=array())
	{
		$attlist = "titlelen=30,infolen=200,imgwidth=120,imgheight=90";
		FillAtts($atts,$attlist);
		FillFields($atts,$fields,$refObj);
		extract($atts, EXTR_OVERWRITE);
		$rsArray = array();

		//global $_vars;
		//$t1 = Exectime();
		if(!$this->isQuery)
		{
			$this->dsql->Execute('mbdl',$this->sourceSql);
		}
		$i = 0;
		while($row = $this->dsql->GetArray('mbdl'))
		{
			$i++;

			if(!isset($row['description'])) $row['description'] = '';
			if(!isset($row['color'])) $row['color'] = '';
			if(!isset($row['pubdate'])) $row['pubdate'] = $row['senddate'];
			//����һЩ�����ֶ�
			$row['infos'] = cn_substr($row['description'],$infolen);
			$row['id'] =  $row['id'];
			$row['filename'] = $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
			$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);
			$row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],$row['ispart'],
			$row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);
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
			if(ereg('b',$row['flag']))
			{
				$row['title'] = "<strong>".$row['title']."</strong>";
			}

			//$row['title'] = "<b>".$row['title']."</b>";
			$row['textlink'] = "<a href='".$row['filename']."'>".$row['title']."</a>";
			$row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
			$row['memberurl'] = $GLOBALS['cfg_memberurl'];
			$row['templeturl'] = $GLOBALS['cfg_templeturl'];
			$rsArray[$i] = $row;
			if($i >= $this->pageSize)
			{
				break;
			}
		}
		$this->dsql->FreeResult();

		//echo "ִ��ʱ�䣺".(Exectime() - $t1);
		return $rsArray;
	}

	//��ȡ��ҳ�����б�
	function GetPageList($atts,$refObj='',$fields=array())
	{
		global $lang_pre_page,$lang_next_page,$lang_index_page,$lang_end_page,$lang_record_number,$lang_page,$lang_total;
		$prepage = $nextpage = $geturl= $hidenform = '';
		$purl = $this->GetCurUrl();
		$prepagenum = $this->pageNO-1;
		$nextpagenum = $this->pageNO+1;
		if(!isset($atts['listsize']) || ereg("[^0-9]",$atts['listsize']))
		{
			$atts['listsize'] = 5;
		}
		if(!isset($atts['listitem']))
		{
			$atts['listitem'] = "info,index,end,pre,next,pageno";
		}
		$totalpage = ceil($this->totalResult/$this->pageSize);

		//echo " {$totalpage}=={$this->totalResult}=={$this->pageSize}";

		//�޽����ֻ��һҳ�����
		if($totalpage<=1 && $this->totalResult > 0)
		{
			return "{$lang_total} 1 {$lang_page}/".$this->totalResult.$lang_record_number;
		}
		if($this->totalResult == 0)
		{
			return "{$lang_total} 0 {$lang_page}/".$this->totalResult.$lang_record_number;
		}
		$infos = "<span>{$lang_total} {$totalpage} {$lang_page}/{$this->totalResult}{$lang_record_number}</span> ";
		if($this->totalResult!=0)
		{
			$this->getValues['totalresult'] = $this->totalResult;
		}
		if(count($this->getValues)>0)
		{
			foreach($this->getValues as $key=>$value)
			{
				$value = urlencode($value);
				$geturl.="$key=$value"."&";
				$hidenform.="<input type='hidden' name='$key' value='$value'>\r\n";
			}
		}
		$purl .= "?".$geturl;

		//�����һҳ����һҳ������
		if($this->pageNO!=1)
		{
			$prepage.="<a href='".$purl."pageno=$prepagenum'>$lang_pre_page</a> \r\n";
			$indexpage="<a href='".$purl."pageno=1'>$lang_index_page</a> \r\n";
		}
		else
		{
			$indexpage="$lang_index_page \r\n";
		}
		if($this->pageNO!=$totalpage&&$totalpage>1)
		{
			$nextpage.="<a href='".$purl."pageno=$nextpagenum'>$lang_next_page</a> \r\n";
			$endpage="<a href='".$purl."pageno=$totalpage'>$lang_end_page</a> \r\n";
		}
		else
		{
			$endpage=" $lang_end_page \r\n";
		}

		//�����������
		$listdd="";
		$total_list = $atts['listsize'] * 2 + 1;
		if($this->pageNO>=$total_list)
		{
			$j=$this->pageNO-$atts['listsize'];
			$total_list=$this->pageNO+$atts['listsize'];
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		else
		{
			$j=1;
			if($total_list>$totalpage) $total_list=$totalpage;
		}
		for($j;$j<=$total_list;$j++)
		{
			if($j==$this->pageNO)
			{
				$listdd.= "<strong>$j</strong> \r\n";
			}
			else
			{
				$listdd.="<a href='".$purl."pageno=$j'>".$j."</a> \r\n";
			}
		}
		$plist = "<div class=\"pagelistbox\">\r\n";

		//info,index,end,pre,next,pageno,form
		if(eregi("info",$atts['listitem']))
		{
			$plist .= $infos;
		}
		if(eregi("index",$atts['listitem']))
		{
			$plist .= $indexpage;
		}
		if(eregi("pre",$atts['listitem']))
		{
			$plist .= $prepage;
		}
		if(eregi("pageno",$atts['listitem']))
		{
			$plist .= $listdd;
		}
		if(eregi("next",$atts['listitem']))
		{
			$plist .= $nextpage;
		}
		if(eregi("end",$atts['listitem']))
		{
			$plist .= $endpage;
		}
		if(eregi("form",$atts['listitem']))
		{
			$plist .=" <form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
			if($totalpage>$total_list)
			{
				$plist.="<input type='text' name='pageno' style='padding:0px;width:30px;height:18px'>\r\n";
				$plist.="<input type='submit' name='plistgo' value='GO' style='padding:0px;width:30px;height:18px;font-size:11px'>\r\n";
			}
			$plist .= "</form>\r\n";
		}
		$plist .= "</div>\r\n";
		return $plist;
	}

	//��õ�ǰ��ַ
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

	//�ر�
	function Close()
	{
	}

	//��ʾ����
	function Display()
	{
		
		if($this->sourceSql != '') $this->PreLoad();

		//��PHP4�У��������ñ������display֮ǰ����������λ������Ч
		$this->tpl->SetObject($this);
		$this->tpl->Display();
	}

	//����ΪHTML
	function SaveTo($filename)
	{
		$this->tpl->SaveTo($filename);
	}
}
?>
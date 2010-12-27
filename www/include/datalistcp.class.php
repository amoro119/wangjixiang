<?php
if(!defined('DEDEINC'))
{
	exit('Request Error!');
}
require_once(DEDEINC.'/dedetemplate.class.php');
$codefile = (isset($needCode) ? $needCode : $cfg_soft_lang);
$codefile = preg_replace("/[\w-]/", '', $codefile);
if(file_exists(DEDEINC.'/code/datalist.'.$codefile.'.inc'))
{
	require_once(DEDEINC.'/code/datalist.'.$codefile.'.inc');
}
else
{
	$lang_pre_page = '��ҳ';
	$lang_next_page = '��ҳ';
	$lang_index_page = '��ҳ';
	$lang_end_page = 'ĩҳ';
	$lang_record_number = '����¼';
	$lang_page = 'ҳ';
	$lang_total = '��';
}

// ����չʾ��
class DataListCP
{
	var $dsql;
	var $tpl;
	var $pageNO;
	var $totalPage;
	var $totalResult;
	var $pageSize;
	var $getValues;
	var $sourceSql;
	var $isQuery;
	var $queryTime;

	//��ָ�����ĵ�ID���г�ʼ��
	function __construct($tplfile='')
	{
		$this->sourceSql='';
		$this->pageSize=25;
		$this->queryTime=0;
		$this->getValues=Array();
		$this->isQuery = false;
		$this->totalResult = 0;
		$this->totalPage = 0;
		$this->pageNO = 0;
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

	function DataListCP($tplfile='')
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
			$countQuery = eregi_replace("select[ \r\n\t](.*)[ \r\n\t]from","Select count(*) as dd From",$this->sourceSql);
			$countQuery = eregi_replace('order[ \r\n\t]{1,}by(.*)', '', $countQuery);
			$row = $this->dsql->GetOne($countQuery);
			if(!is_array($row)) $row['dd'] = 0;
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
		if(!isset($_vars[$k]))
		{
			$_vars[$k] = $v;
		}
	}

	function GetVar($k)
	{
		global $_vars;
		return isset($_vars[$k]) ? $_vars[$k] : '';
	}

	//��ȡ��ǰҳ�����б�
	function GetArcList($atts,$refObj='',$fields=array())
	{
		$rsArray = array();
		$t1 = Exectime();
		if(!$this->isQuery)
		{
			$this->dsql->Execute('dlist',$this->sourceSql);
		}
		$i = 0;
		while($arr=$this->dsql->GetArray('dlist'))
		{
			$i++;
			$rsArray[$i]  =  $arr;
			if($i >= $this->pageSize)
			{
				break;
			}
		}
		$this->dsql->FreeResult('dlist');
		$this->queryTime = (Exectime() - $t1);
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
			return "<span>{$lang_total} 1 {$lang_page}/".$this->totalResult.$lang_record_number."</span>";
		}
		if($this->totalResult == 0)
		{
			return "<span>{$lang_total} 0 {$lang_page}/".$this->totalResult.$lang_record_number."</span>";
		}
		$infos = "<span>{$lang_total} {$totalpage} {$lang_page}/{$this->totalResult}{$lang_record_number} </span>";
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
				$hidenform.="<input type='hidden' name='$key' value='$value' />\n";
			}
		}
		$purl .= "?".$geturl;

		//�����һҳ����һҳ������
		if($this->pageNO!=1)
		{
			$prepage.="<a class='prePage' href='".$purl."pageno=$prepagenum'>$lang_pre_page</a> \n";
			$indexpage="<a class='indexPage' href='".$purl."pageno=1'>$lang_index_page</a> \n";
		}
		else
		{
			$indexpage="<span class='indexPage'>"."$lang_index_page \n"."</span>";
		}
		if($this->pageNO!=$totalpage&&$totalpage>1)
		{
			$nextpage.="<a class='nextPage' href='".$purl."pageno=$nextpagenum'>$lang_next_page</a> \n";
			$endpage="<a class='endPage' href='".$purl."pageno=$totalpage'>$lang_end_page</a> \n";
		}
		else
		{
			$endpage=" <strong>$lang_end_page</strong> \n";
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
			if($total_list>$totalpage)
			{
				$total_list=$totalpage;
			}
		}
		for($j;$j<=$total_list;$j++)
		{
			$listdd .= $j==$this->pageNO ? "<strong>$j</strong>\n" : "<a href='".$purl."pageno=$j'>".$j."</a>\n";
		}

		$plist = "<div class=\"pagelistbox\">\n";

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
			$plist .=" <form name='pagelist' action='".$this->GetCurUrl()."' style='float:left;' class='pagelistform'>$hidenform";
			if($totalpage>$total_list)
			{
				$plist.="<input type='text' name='pageno' style='padding:0px;width:30px;height:18px;font-size:11px' />\r\n";
				$plist.="<input type='submit' name='plistgo' value='GO' style='padding:0px;width:30px;height:22px;font-size:11px' />\r\n";
			}
			$plist .= "</form>\n";
		}
		$plist .= "</div>\n";
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
		$this->PreLoad();

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
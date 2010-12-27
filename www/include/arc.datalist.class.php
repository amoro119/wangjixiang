<?php
if(!defined('DEDEINC')) exit('Request Error!');
@set_time_limit(0);
require_once(DEDEINC.'/channelunit.class.php');
require_once(DEDEINC.'/typelink.class.php');

$codefile = (isset($needCode) ? $needCode : $cfg_soft_lang);
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

class DataList
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
	var $SourceSql;
	var $Template;
	var $TemplateString;
	var $QueryTime;
	var $GetValues;
	//php5���캯��
	function __construct($typeid=0, $sql='', $template='')
	{
		$this->TypeID = $typeid;
		$this->dsql = $GLOBALS['dsql'];
		$this->Template = $template;
		$this->SourceSql = $sql;
		$this->GetValues = array();
		$this->dtp = new DedeTagParse();
		$this->dtp->refObj = $this;
		$this->dtp->SetNameSpace('dede', '{', '}');
		$this->dtp2 = new DedeTagParse();
		$this->dtp2->SetNameSpace('field', '[', ']');
		$this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
		
		//�����Ҫ�� �����Ŀ��Ϣ
		if( !empty($typeid) )
		{
			$this->TypeLink = new TypeLink($typeid);
			$this->Fields = $this->TypeLink->TypeInfos;
			$this->Fields['id'] = $typeid;
			$this->Fields['position'] = $this->TypeLink->GetPositionLink(true);
			$this->Fields['title'] = ereg_replace("[<>]"," / ",$this->TypeLink->GetPositionLink(false));
			$this->Fields['rsslink'] = $GLOBALS['cfg_cmsurl']."/data/rss/".$this->TypeID.".xml";
			//���û�������
			SetSysEnv($this->TypeID, $this->Fields['typename'], 0, '', 'datalist');
			$this->Fields['typeid'] = $this->TypeID;
		}
		//����һЩȫ�ֲ�����ֵ
		foreach($GLOBALS['PubFields'] as $k=>$v)
		{
			$this->Fields[$k] = $v;
		}
	}

	//php4���캯��
	function DataList($typeid=0, $sql='', $template='')
	{
		$this->__construct($typeid, $sql, $template);
	}
	
	//���ò�ѯ���
	function SetQuery($sql)
	{
		$this->SourceSql = $sql;
	}
	
	//����ģ��
	function SetTemplate($filename)
	{
		$this->Template = $filename;
	}
	
	//����ģ���ַ���
	function SetTemplateString($str)
	{
		$this->TemplateString = $str;
	}
	
	//�ر������Դ
	function Close()
	{

	}

	//ͳ���б���ļ�¼
	function CountRecord()
	{
		//ͳ�����ݿ��¼
		$this->TotalResult = -1;
		if(isset($GLOBALS['TotalResult'])) $this->TotalResult = $GLOBALS['TotalResult'];
		if(isset($GLOBALS['PageNo'])) $this->PageNo = $GLOBALS['PageNo'];
		else $this->PageNo = 1;
		
		if($this->TotalResult==-1)
		{
			$countQuery = eregi_replace("select[ \r\n\t](.*)[ \r\n\t]from","Select count(*) as dd From", $this->SourceSql);
			$countQuery = eregi_replace('order[ \r\n\t]{1,}by(.*)', '', $countQuery);
			$row = $this->dsql->GetOne($countQuery);
			if(is_array($row)) {
				$this->TotalResult = $row['dd'];
			}
			else {
				$this->TotalResult = 0;
			}
		}

		//��ʼ���б�ģ�壬��ͳ��ҳ������
		if($this->Template != '')
		{
			$tempfile = $this->Template;
			if(!file_exists($tempfile) || !is_file($tempfile))
			{
				$tempfile = ereg_replace("^[^/\\]*/", "/", $tempfile);
				echo "ģ���ļ� {$tempfile} �����ڣ��޷������ĵ���";
				exit();
			}
			$this->dtp->LoadTemplate($tempfile);
		}
		else if($this->TemplateString != '')
		{
			$this->dtp->LoadString($this->TemplateString);
		}
		else
		{
			echo "ûָ��ģ���ļ����ַ��������������࣡";
			exit();
		}
		
		$ctag = $this->dtp->GetTag('page');
		if(!is_object($ctag))
		{
			$ctag = $this->dtp->GetTag('list');
		}
		if(!is_object($ctag))
		{
			$this->PageSize = 20;
		}
		else
		{
			if($ctag->GetAtt('pagesize')!='') $this->PageSize = $ctag->GetAtt('pagesize');
			else $this->PageSize = 20;
		}
		$this->TotalPage = ceil($this->TotalResult/$this->PageSize);
	}

	//��ʾ�б�
	function Display()
	{
		$this->CountRecord();
		$this->ParseTempletsFirst();
		$this->ParseDMFields($this->PageNo, 0);
		$this->dtp->Display();
	}

	//����ģ�壬�Թ̶��ı�ǽ��г�ʼ��ֵ
	function ParseTempletsFirst()
	{
		if( isset($this->TypeLink->TypeInfos) )
		{
			$GLOBALS['envs']['reid'] = $this->TypeLink->TypeInfos['reid'];
			$GLOBALS['envs']['typeid'] = $this->TypeID;
			$GLOBALS['envs']['topid'] = GetTopid($this->TypeID);
			$GLOBALS['envs']['cross'] = 1;
		}
		MakeOneTag($this->dtp, $this);
	}

	//����ģ�壬��������ı䶯���и�ֵ
	function ParseDMFields($PageNo, $ismake=0)
	{
		foreach($this->dtp->CTags as $tagid=>$ctag)
		{
			if($ctag->GetName() == 'list')
			{
				$this->dtp->Assign($tagid, $this->GetList($ctag->CAttribute->Items,  $ctag->GetInnerText()));
			}
			else if($ctag->GetName() == 'pagelist')
			{
				$list_len = (trim($ctag->GetAtt('listsize'))=='' ? 3 : trim($ctag->GetAtt('listsize')));
				$listitem = ($ctag->GetAtt('listitem')=='' ? 'index,pre,pageno,next,end,option' : $ctag->GetAtt('listitem'));
				if($ismake==0)
				{
					$this->dtp->Assign($tagid, $this->GetPageListDM($list_len, $listitem));
				}
				else
				{
					$this->dtp->Assign($tagid, $this->GetPageListST($list_len, $listitem));
				}
			}
			//���ĳField����ָ����ֻ�ڵ�һҳ��ʾ����ô�ǵ�һҳǿ�ư�����Ϊ��
			else if($PageNo!=1 && $ctag->GetName()=='field' && $ctag->GetAtt('display')=='first')
			{
				$this->dtp->Assign($tagid,'');
			}
		}
	}

	//����б�
	function GetList($catts, $innertext)
	{
		$rsvalue = '';
		$t1 = Exectime();
		$limitstart = ($this->PageNo-1) * $this->PageSize;
		$oksql = $this->SourceSql." limit $limitstart, ".$this->PageSize;
		$this->dsql->Execute('dlist', $oksql);
		$this->dtp2->LoadSource($innertext);
		$GLOBALS['autoindex'] = 0;
		while($arr=$this->dsql->GetArray('dlist'))
		{
			if(is_array($this->dtp2->CTags))
			{
					foreach($this->dtp2->CTags as $k=>$ctag)
					{
							if($ctag->GetName()=='array')
							{
								$this->dtp2->Assign($k,$arr);
							}
							else
							{
								if(isset($arr[$ctag->GetName()])) $this->dtp2->Assign($k, $arr[$ctag->GetName()]);
								else $this->dtp2->Assign($k, $ctag->GetName().' Not Exists');
							}
					}
			}
			$GLOBALS['autoindex']++;
			$rsvalue .= $this->dtp2->GetResult();
		}
		$this->dsql->FreeResult('dlist');
		$this->QueryTime = (Exectime() - $t1);
		return $rsvalue;
	}
	
	//������ַ��Get������ֵ
	function SetParameter($key, $value)
	{
		$this->GetValues[$key] = $value;
	}

	//��ȡ��̬�ķ�ҳ�б�
	//Ϊ����ģ����Ʋ��ù��࿼�ǣ� ���ﻹ�ǲ��ñ��Ƕ����ʽ��������Ӧ��ʽΪ
	//.pageinfo ͳ����Ϣ�� .dlistEmpty û�����ӵ����ֵ�td(��ǰҳ)�� .dlistLink �����ӵ�����
	//.dlistPage ��ת�� .dlistSubmit ��Submit��ť
	function GetPageListDM($list_len, $listitem='index,end,pre,next,pageno')
	{
		global $lang_pre_page,$lang_next_page,$lang_index_page,$lang_end_page,$lang_record_number,$lang_page,$lang_total;
		$prepage = $nextpage = $geturl= $hidenform = '';
		$prepagenum = $this->PageNo-1;
		$nextpagenum = $this->PageNo+1;
		
		$revalue = "<table align='center' class='pagelist'>\r\n<tr>\r\n";
		$endvalue = "</tr>\r\n</table>\r\n";
		if($list_len=='' || ereg("[^0-9]",$list_len))
		{
			$list_len = 3;
		}
		$totalpage = ceil($this->TotalResult/$this->PageSize);
		if($totalpage<=1 && $this->TotalResult>0)
		{
			return $revalue." <td class='pageinfo'>".$lang_total.' 1 '.$lang_page.'/'.$this->TotalResult.' '.$lang_record_number."</td>\r\n".$endvalue;
		}
		if($this->TotalResult == 0)
		{
			return $revalue." <td class='pageinfo'>".$lang_total.' 0 '.$lang_page.'/'.$this->TotalResult.' '.$lang_record_number."</td>\r\n".$endvalue;
		}
		
		$pageinfo = "<td class='pageinfo'>{$lang_total} {$totalpage} {$lang_page}/{$this->TotalResult} {$lang_record_number}</td>\r\n";

		$purl = $this->GetCurUrl();

		//��ʼ��ǰ׺URL
		$geturl = "TotalResult={$this->TotalResult}&";
		$hidenform = "<td><form action='$purl' name='dlistPage' class='dlistPage' style='padding:0px;margin:0px'>\r\n";
		$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
		if(count($this->GetValues)>0)
		{
			foreach($this->GetValues as $key=>$value)
			{
				$value = urlencode($value);
				$geturl .= "{$key}={$value}&";
				$hidenform .= "<input type='hidden' name='$key' value='$value' />\n";
			}
		}
		$hidenform .= "<input type='text' name='PageNO' value='".$this->TotalResult."' style='width:30px;height:22px' class='dlistPageno' />\r\n";
		$hidenform .= "<input type='submit' name='sbgo' value='GO' style='width:30px;height:22px' class='dlistSubmit' />\r\n";
		$hidenform .= "</form></td>\r\n";
		$purl .= '?'.$geturl;

		//�����һҳ����һҳ������
		if($this->PageNo != 1)
		{
			$prepage .= "<td class='dlistLink'><a href='{$purl}PageNo=$prepagenum'>{$lang_pre_page}</a></td>\r\n";
			$indexpage = "<td class='dlistLink'><a href='{$purl}PageNo=1'>{$lang_index_page}</a></td>\r\n";
		}
		else
		{
			$indexpage = "<td class='dlistEmpty'>{$lang_index_page}</td>\r\n";
		}
		if($this->PageNo != $totalpage && $totalpage>1)
		{
			$nextpage .= "<td class='dlistLink'><a href='{$purl}PageNo=$nextpagenum'>{$lang_next_page}</a></td>\r\n";
			$endpage = "<td class='dlistLink'><a href='{$purl}PageNo=$totalpage'>{$lang_end_page}</a></td>\r\n";
		}
		else
		{
			$endpage = "<td class='dlistEmpty'>{$lang_end_page}</td>\r\n";
		}

		//�����������
		$listdd = '';
		$total_list = $list_len * 2 + 1;
		if($this->PageNo >= $total_list)
		{
			$j = $this->PageNo-$list_len;
			$total_list = $this->PageNo+$list_len;
			$total_list = ($total_list>$totalpage ? $totalpage : $total_list);
		}
		else
		{
			$j=1;
			$total_list = ($total_list>$totalpage ? $totalpage : $total_list);
		}
		for($j;$j<=$total_list;$j++)
		{
			$listdd.= ($j==$this->PageNo ? "<td class='dlistEmpty'>$j</td>\r\n" : "<td class='dlistLink'><a href='{$purl}PageNo=$j'>[{$j}]</a></td>\r\n");
		}

		//index,end,pre,next,pageno
		$plist = $revalue;
		if( eregi('index', $listitem) ) $plist .= $indexpage;
		if( eregi('pre', $listitem) ) $plist .= $prepage;
		if( eregi('pageno', $listitem) ) $plist .= $listdd;
		if( eregi('next', $listitem) ) $plist .= $nextpage;
		if( eregi('end', $listitem) ) $plist .= $endpage;
		if( eregi('form', $listitem) ) $plist .= $hidenform;
		$plist .= $endpage;

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
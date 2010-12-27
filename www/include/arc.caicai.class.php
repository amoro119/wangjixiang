<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/channelunit.func.php");

class Caicai extends DataListCP
{
	var $maxPageSize = 100;
	var $arcCacheTime = 3600;

	/**
	 * ��config������get�����Ƚ���Ԥ����
	 *
	 */
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

		if(isset($this->tpl->tpCfgs['pagesize'])){
			$this->pageSize = $this->tpl->tpCfgs['pagesize'];
		}
		$this->totalPage = ceil($this->totalResult/$this->pageSize);
		if($this->totalPage > $this->maxPageSize)
		{
			$this->totalPage = $this->maxPageSize;
		}
		//�������ҳ��
		if($this->pageNO > $this->totalPage)
		{
			$this->pageNO = $this->totalPage;
			$this->totalResult = $this->totalPage * $this->pageSize;
		}
		$this->sourceSql = ereg_replace("limit [0-9,]{1,}",'',$this->sourceSql);
		if($this->totalResult==0)
		{
			//$this->isQuery = true;
			//$this->dsql->Execute('dlist',$this->sourceSql);
			//$this->totalResult = $this->dsql->GetTotalRow('dlist');
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

	/**
	 * ��ȡ��ǰҳ�����б�
	 *
	 * @param array $atts
	 * @param object $refObj
	 * @param array $fields
	 * @return array
	 */
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
			$arr['filename'] = $arr['arcurl'] = GetFileUrl($arr['id'],$arr['typeid'],$arr['senddate'],$arr['title'],$arr['ismake'],
			$arr['arcrank'],$arr['namerule'],$arr['typedir'],$arr['money'],$arr['filename'],$arr['moresite'],$arr['siteurl'],$arr['sitepath']);
			$arr['typeurl'] = GetTypeUrl($arr['typeid'],MfTypedir($arr['typedir']),$arr['isdefault'],$arr['defaultname'],
			$arr['ispart'],$arr['namerule2'],$arr['moresite'],$arr['siteurl'],$arr['sitepath']);
			if($arr['litpic'] == '-' || $arr['litpic'] == '')
			{
				$arr['litpic'] = 'templets/images/dfpic.gif';
			}
			if(!eregi("^http://",$arr['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
			{
				$arr['litpic'] = $GLOBALS['cfg_mainsite'].$arr['litpic'];
			}
			$arr['picname'] = $arr['litpic'];
			$arr['alttitle'] = $arr['userid']." �Ŀռ�";
			$arr['face'] = ($arr['face']!='' ? $arr['face'] : 'images/nopic.gif');
			if($arr['userid']!='')
			{
				$arr['spaceurl'] = $GLOBALS['cfg_basehost'].'/member/index.php?uid='.$arr['userid'];
			}
			else
			{
				$arr['alttitle'] = $arr['title'];
				$arr['spaceurl'] = $arr['arcurl'];
				$arr['face'] = $arr['litpic'];
				$arr['face'] = str_replace('defaultpic','dfcaicai',$arr['face']);
			}
			if(!empty($arr['lastpost']))
			{
				$arr['lastpost'] = MyDate('m-d h:i',$arr['lastpost']);
			}
			else
			{
				$arr['lastpost'] = "<a href='../plus/feedback.php?aid={$arr['id']}'>˵����&gt;&gt;</a>";
			}
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

	/**
	 * ���������õĲȲ�����
	 *
	 * @param array $atts
	 * @param object $refObj
	 * @param array $fields
	 * @return array
	 */
	function GetSortArc($atts,$refObj='',$fields=array())
	{
		$arcrow = (empty($atts['row']) ?  12 : $atts['row']);
		$order = (empty($atts['order']) ? 'scores' : $atts['order'] );
		$orderway = (empty($atts['orderway']) ? 'desc' : $atts['orderway'] );
		if(empty($arcrow))
		{
			$arcrow = 12;
		}

		$query = "Select arc.*,tp.typedir,tp.typename,
		      tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
          From `#@__archives` arc left join `#@__arctype` tp on tp.id=arc.typeid
          where arc.arcrank>-1 order by arc.{$order} $orderway limit 0,$arcrow ";

		$rsArray = array();
		
		$cacheFile = DEDEDATA.'/cache/caicai_'.md5($query).'.inc';
		$needCache = false;
		if(file_exists($cacheFile) && filemtime($cacheFile)-time() < $this->arcCacheTime)
		{
			$fp = fopen($cacheFile, 'r');
			$ids = fread($fp, filesize($cacheFile));
			fclose($fp);
			$ids = trim($ids);
			if( !empty($ids) )
			{
				$query = "Select arc.*,tp.typedir,tp.typename,
		      tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
          From `#@__archives` arc left join `#@__arctype` tp on tp.id=arc.typeid
          where arc.id in($ids) order by arc.{$order} $orderway ";
      }
		}
		else
		{
			$needCache = true;
		}
		$ids = array();
		$i = 0;
		$this->dsql->Execute('cai',$query);
		while($arr=$this->dsql->GetArray('cai'))
		{
			$i++;
			$ids[] = $arr['id'];
			$arr['filename'] = $arr['arcurl'] = GetFileUrl($arr['id'],$arr['typeid'],$arr['senddate'],$arr['title'],$arr['ismake'],
			$arr['arcrank'],$arr['namerule'],$arr['typedir'],$arr['money'],$arr['filename'],$arr['moresite'],$arr['siteurl'],$arr['sitepath']);

			$arr['typeurl'] = GetTypeUrl($arr['typeid'],MfTypedir($arr['typedir']),$arr['isdefault'],$arr['defaultname'],
			$arr['ispart'],$arr['namerule2'],$arr['moresite'],$arr['siteurl'],$arr['sitepath']);

			if($arr['litpic']=='')
			{
				$arr['litpic'] = '/images/defaultpic.gif';
			}

			if(!eregi("^http://",$arr['litpic']))
			{
				$arr['picname'] = $arr['litpic'] = $GLOBALS['cfg_cmsurl'].$arr['litpic'];
			}
			else
			{
				$arr['picname'] = $arr['litpic'] = $arr['litpic'];
			}

			$rsArray[$i]  =  $arr;
		}
		$this->dsql->FreeResult('cai');
		
		//д�뻺��
		if($needCache && count($ids) > 0)
		{
			$idsstr = join(',', $ids);
			$fp = fopen($cacheFile, 'w');
			fwrite($fp, $idsstr);
			fclose($fp);
		}
		
		return $rsArray;

	}

	/**
	 * ��ȡ������Ŀ�б�
	 *
	 * @param array $atts
	 * @param object $refObj
	 * @param array $fields
	 * @return array
	 */
	function GetCatalog($atts,$refObj='',$fields=array())
	{
		$maxrow = (empty($atts['row']) ?  12 : $atts['row']);
		$query = "Select id,typename From `#@__arctype` where reid=0 and ispart<2 And channeltype>0 order by sortrank asc limit 0,$maxrow ";
		$rsArray = array();
		$this->dsql->Execute('co',$query);
		$i = 0;
		while($arr=$this->dsql->GetArray('co'))
		{
			$i++;
			$rsArray[$i]  =  $arr;
		}
		$this->dsql->FreeResult('co');
		return $rsArray;
	}

}
?>
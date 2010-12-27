<?php
if(!defined('DEDEINC')) exit('Request Error!');
require_once(DEDEINC.'/channelunit.func.php');

function plus_channel(&$atts, &$refObj, &$fields)
{
	global $dsql,$_vars;

	$attlist = "typeid=0,reid=0,row=100,type=son,currentstyle=";
  FillAtts($atts,$attlist);
  FillFields($atts,$fields,$refObj);
	extract($atts, EXTR_OVERWRITE);

	$line = empty($row) ? 100 : $row;
	$reArray = array();
	$reid = 0;
	$topid = 0;
	
	//���������ûָ����Ŀid�������������ȡ��Ŀ��Ϣ
	if(empty($typeid))
	{
		if( isset($refObj->TypeLink->TypeInfos['id']) )
		{
			$typeid = $refObj->TypeLink->TypeInfos['id'];
			$reid = $refObj->TypeLink->TypeInfos['reid'];
			$topid = $refObj->TypeLink->TypeInfos['topid'];
		}
		else {
	  	$typeid = 0;
	  }
	}
	//���ָ������Ŀid�������ݿ��ȡ��Ŀ��Ϣ
	else
	{
		$row2 = $dsql->GetOne("Select * From `#@__arctype` where id='$typeid' ");
		$typeid = $row2['id'];
		$reid = $row2['reid'];
		$topid = $row2['topid'];
		$issetInfos = true;
	}
	
	if($type=='' || $type=='sun') $type='son';

	if($type=='top')
	{
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
		  From `#@__arctype` where reid=0 And ishidden<>1 order by sortrank asc limit 0, $line ";
	}
	else if($type=='son')
	{
		if($typeid==0) return $reArray;
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
		  From `#@__arctype` where reid='$typeid' And ishidden<>1 order by sortrank asc limit 0, $line ";
	}
	else if($type=='self')
	{
		if($reid==0) return $reArray;
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
			From `#@__arctype` where reid='$reid' And ishidden<>1 order by sortrank asc limit 0, $line ";
	}

	//����Ƿ�������Ŀ��������rel��ʾ�����ڶ����˵���
	$needRel = true;
	
	if(empty($sql)) return $reArray;

	$dsql->Execute('me',$sql);
	$totalRow = $dsql->GetTotalRow('me');
	
	//���������Ŀģʽ����û������Ŀʱ��ʾͬ����Ŀ
	if($type=='son' && $reid!=0 && $totalRow==0)
	{
		$sql = "Select id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
			From `#@__arctype` where reid='$reid' And ishidden<>1 order by sortrank asc limit 0, $line ";
	  $dsql->Execute('me', $sql);
	}
	$GLOBALS['autoindex'] = 0;
	while($row=$dsql->GetArray())
	{
				$row['currentstyle'] = $row['sonids'] = $row['rel'] = '';
				if($needRel)
				{
					$row['sonids'] = GetSonIds($row['id'], 0, false);
					if($row['sonids']=='') $row['rel'] = '';
					else $row['rel'] = " rel='dropmenu{$row['id']}'";
				}
				//����ͬ����Ŀ�У���ǰ��Ŀ����ʽ
				if( ($row['id']==$typeid || ($topid==$row['id'] && $type=='top') ) && $currentstyle!='' )
				{
					$row['currentstyle'] = $currentstyle;
				}
				$row['typelink'] = $row['typeurl'] = GetOneTypeUrlA($row);
				$reArray[] = $row;
			  $GLOBALS['autoindex']++;
	}
	//Loop for $i
	$dsql->FreeResult();
	return $reArray;
}
?>
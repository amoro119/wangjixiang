<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('co_Export');
if(empty($dopost))
{
	$dopost = '';
}
if($dopost!='done')
{
	require_once(DEDEADMIN."/inc/inc_catalog_options.php");
	$totalcc = $channelid = $usemore = 0;
	if(!empty($nid))
	{
		$mrow = $dsql->GetOne("Select count(*) as dd From `#@__co_htmls` where nid='$nid' And isdown='1' ");
		$totalcc = $mrow['dd'];
		$rrow = $dsql->GetOne("Select channelid,usemore From `#@__co_note` where nid='$nid' ");
		$channelid = $rrow['channelid'];
		$usemore = $rrow['usemore'];
	}
	else
	{
		$mrow = $dsql->GetOne("Select count(*) as dd From `#@__co_htmls` where isdown='1' ");
		$totalcc = $mrow['dd'];
	}
	include DedeInclude("templets/co_export.htm");
	exit();
}
else
{
	require_once(DEDEINC.'/dedecollection.class.php');
	$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 0;
	$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
	$pageno = isset($pageno) && is_numeric($pageno) ? $pageno : 1;
	$startid = isset($startid) && is_numeric($startid) ? $startid : 0;
	$endid = isset($endid) && is_numeric($endid) ? $endid : 0;
	if(!isset($makehtml))
	{
		$makehtml = 0;
	}
	if(!isset($onlytitle))
	{
		$onlytitle = 0;
	}
	if(!isset($usetitle))
	{
		$usetitle = 0;
	}
	if(!isset($autotype))
	{
		$autotype = 0;
	}
	$co = new DedeCollection();
	$co->LoadNote($nid);
	$orderway = (($co->noteInfos['cosort']=='desc' || $co->noteInfos['cosort']=='asc') ? $co->noteInfos['cosort'] : 'desc');
	if($channelid==0 && $typeid==0)
	{
		ShowMsg('��ָ��Ĭ�ϵ�����Ŀ��Ƶ��ID��','javascript:;');
		exit();
	}
	if($channelid==0)
	{
		$row = $dsql->GetOne("Select ch.* From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id='$typeid'; ");
	}
	else
	{
		$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$channelid'; ");
	}
	if(!is_array($row))
	{
		echo "�Ҳ���Ƶ������ģ����Ϣ���޷���ɲ�����";
		exit();
	}

	//�������򣬲�������ʱ��SQL���
	$channelid = $row['id'];
	$maintable = $row['maintable'];
	$addtable = $row['addtable'];
	if(empty($maintable))
	{
		$maintable = '#@__archives';
	}
	if(empty($addtable))
	{
		echo "�Ҳ�����������Ϣ���޷���ɲ�����";
		exit();
	}
	$adminid = $cuserLogin->getUserID();

	//΢������
	$indexSqlTemplate = "INSERT INTO `#@__arctiny`(`arcrank`,`typeid`,`channel`,`senddate`,`sortrank`) VALUES ('$arcrank','@typeid@' ,'$channelid','@senddate@', '@sortrank@'); ";

	//������Ϣ����
	$mainSqlTemplate  = "INSERT INTO `$maintable`(id,typeid,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,color,writer,source,litpic,pubdate,senddate,mid,description,keywords)
               VALUES ('@aid@','@typeid@','@sortrank@','@flag@','0','$channelid','$arcrank','0','0','@title@','','','@writer@','@source@','@litpic@','@pubdate@','@senddate@','$adminid','@description@','@keywords@'); ";

	//���ɸ��ӱ�����SQL���
	$inadd_f = $inadd_v = '';
	$dtp = new DedeTagParse();
	$dtp->SetNameSpace('field','<','>');
	$dtp->LoadString($row['fieldset']);
	foreach($dtp->CTags as $ctag)
	{
		$tname = $ctag->GetTagName();
		$inadd_f .= ",`$tname`";
		$notsend = $ctag->GetAtt('notsend');
		$fieldtype = $ctag->GetAtt('type');
		if($notsend==1)
		{
			//�Բ�ͬ��������Ĭ��ֵ
			if($ctag->GetAtt('default')!='')
			{
				$dfvalue = $ctag->GetAtt('default');
			}
			else if($fieldtype=='int'||$fieldtype=='float'||$fieldtype=='number')
			{
				$dfvalue = '0';
			}
			else if($fieldtype=='dtime')
			{
				$dfvalue = time();
			}
			else
			{
				$dfvalue = '';
			}
			$inadd_v .= ",'$dfvalue'";
		}
		else
		{
			$inadd_v .= ",'@$tname@'";
		}
	}
	$addSqlTemplate = "INSERT INTO `{$addtable}`(`aid`,`typeid`{$inadd_f}) Values('@aid@','@typeid@'{$inadd_v})";

	//�������ݵ�SQL����
	$dtp = new DedeTagParse();
	$totalpage = $totalcc/$pagesize;
	$startdd = ($pageno-1) * $pagesize;
	if(!empty($nid))
	{
		$dsql->SetQuery("Select * From `#@__co_htmls` where nid='$nid' And isdown='1' order by aid $orderway limit $startdd,$pagesize");
	}
	else
	{
		$dsql->SetQuery("Select * From `#@__co_htmls` where isdown='1' order by aid $orderway limit $startdd,$pagesize");
	}
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		if(trim($row->result==''))
		{
			continue;
		}
		//$addSqlTemplate,$mainSqlTemplate,$indexSqlTemplate
		$ntypeid = ($autotype==1 && $row->typeid != 0) ? $row->typeid : $typeid;
		$indexSql = str_replace('@typeid@',$ntypeid,$indexSqlTemplate);
		$mainSql = str_replace('@typeid@',$ntypeid,$mainSqlTemplate);
		$addSql = str_replace('@typeid@',$ntypeid,$addSqlTemplate);
		$dtp->LoadString($row->result);
		$exid = $row->aid;
		if(!is_array($dtp->CTags))
		{
			continue;
		}

		//��ȡʱ��ͱ���
		$pubdate = $sortrank = time();
		$title = $row->title;
		$litpic = '';
		foreach ($dtp->CTags as $ctag)
		{
			$itemName = $ctag->GetAtt('name');
			if($itemName == 'title' && $usetitle==0)
			{
				$title = trim($ctag->GetInnerText());
				if($title=='')
				{
					$title = $row->title;
				}
			}
			else if($itemName == 'pubdate')
			{
				$pubdate = trim($ctag->GetInnerText());
				if(ereg("[^0-9]",$pubdate))
				{
					$pubdate = $sortrank = GetMkTime($pubdate);
				}
				else
				{
					$pubdate = $sortrank = time();
				}
			}
			else if($itemName == 'litpic')
			{
				$litpic = trim($ctag->GetInnerText());
			}
		}

		//����ظ�����
		$title = addslashes($title);
		if($onlytitle)
		{
			$testrow = $dsql->GetOne("Select count(ID) as dd From `$maintable` where title like '$title'");
			if($testrow['dd']>0)
			{
				echo "���ݿ��Ѵ��ڱ���Ϊ: {$title} ���ĵ���������ֹ�˴˱������ݵ���<br />\r\n";
				continue;
			}
		}

		//�滻�̶�����Ŀ
		$senddate = time();
		$flag = '';
		if($litpic!='')
		{
			$flag = 'p';
		}

		//����Ƽ�
		if($randcc>0)
		{
			$rflag = mt_rand(1,$randcc);
			if($rflag==$randcc)
			{
				$flag = ($flag=='' ? 'c' : $flag.',c');
			}
		}
		$indexSql = str_replace('@senddate@',$senddate,$indexSql);
		$indexSql = str_replace('@sortrank@',$sortrank,$indexSql);
		$mainSql = str_replace('@flag@',$flag,$mainSql);
		$mainSql = str_replace('@sortrank@',$sortrank,$mainSql);
		$mainSql = str_replace('@pubdate@',$pubdate,$mainSql);
		$mainSql = str_replace('@senddate@',$senddate,$mainSql);
		$mainSql = str_replace('@title@',cn_substr($title, 60),$mainSql);
		$addSql = str_replace('@sortrank@',$sortrank,$addSql);
		$addSql = str_replace('@senddate@',$senddate,$addSql);

		//�滻ģ����������ֶ�
		foreach($dtp->CTags as $ctag)
		{
			if($ctag->GetName()!='field')
			{
				continue;
			}
			$itemname = $ctag->GetAtt('name');
			$itemvalue = addslashes(trim($ctag->GetInnerText()));
			$mainSql = str_replace("@$itemname@",$itemvalue,$mainSql);
			$addSql = str_replace("@$itemname@",$itemvalue,$addSql);
		}

		//�������ݿ�
		$rs = $dsql->ExecuteNoneQuery($indexSql);
		if($rs)
		{
			$aid = $dsql->GetLastID();
			$mainSql = str_replace('@aid@',$aid,$mainSql);
			$addSql = str_replace('@aid@',$aid,$addSql);
			$mainSql = ereg_replace('@([a-z0-9]{1,})@','',$mainSql);
			$addSql = ereg_replace('@([a-z0-9]{1,})@','',$addSql);
			$rs = $dsql->ExecuteNoneQuery($mainSql);
			if(!$rs)
			{
				echo "���� '$title' ʱ����".$dsql->GetError()."<br />";
				$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$aid' ");
			}
			else
			{
				$rs = $dsql->ExecuteNoneQuery($addSql);
				if(!$rs)
				{
					echo "���� '$title' ʱ����".$dsql->GetError()."<br />";
					$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$aid' ");
					$dsql->ExecuteNoneQuery("Delete From `$maintable` where id='$aid' ");
				}
			}
		}
		$dsql->ExecuteNoneQuery("update `#@__co_htmls` set isexport=1 where aid='$exid' ");
	}

	//����Ƿ���ɻ��������
	if($totalpage <= $pageno)
	{
		if($channelid>0 && $makehtml==1)
		{
			if( $autotype==0 && !empty($nid) )
			{
				$mhtml = "makehtml_archives_action.php?typeid=$typeid&startid=$startid&endid=$endid&pagesize=20";
				ShowMsg("������ݵ��룬׼�������ĵ�HTML...",$mhtml);
				exit();
			}
			else
			{
				ShowMsg("����������ݵ��룬���ֹ�����HTML��","javascript:;");
				exit();
			}
		}
		else
		{
			ShowMsg("����������ݵ��룡","javascript:;");
			exit();
		}
	}
	else
	{
		if($totalpage>0)
		{
			$rs = substr(($pageno/$totalpage * 100),0,2);
		}
		else
		{
			$rs = 100;
		}
		$pageno++;
		$gourl = "co_export.php?dopost=done&nid=$nid&totalcc=$totalcc&channelid=$channelid&pageno=$pageno";
		$gourl .= "&nid=$nid&typeid=$typeid&autotype=$autotype&arcrank=$arcrank&pagesize=$pagesize&randcc=$randcc";
		$gourl .= "&startid=$startid&endid=$endid&onlytitle=$onlytitle&usetitle=$usetitle&makehtml=$makehtml";
		ShowMsg("��� {$rs}% ���룬����ִ�в���...",$gourl,'',500);
		exit();
	}
}

?>
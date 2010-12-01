<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/channelunit.func.php");
$action = (empty($action) ? '' : $action);


if($action=='')
{
	require_once(DEDEADMIN."/templets/makehtml_all.htm");
	exit();
}
else if($action=='make')
{
	//step = 1 ������ҳ��step = 2 �������ݡ�step = 3 ������Ŀ
	if(empty($step))
	{
		$step = 1;
	}

	//�����ĵ�ǰ�Ż�����
	/*-------------------
	function _1_OptimizeData1()
	---------------------*/
	if($step==1)
	{
		$starttime = GetMkTime($starttime);
		$mkvalue = ($uptype=='time' ? $starttime : $startid);
		OptimizeData($dsql);
		ShowMsg("��������Ż������ڿ�ʼ�����ĵ���","makehtml_all.php?action=make&step=2&uptype=$uptype&mkvalue=$mkvalue");
		exit();
	}

	//�����ĵ�
	/*-------------------
	function _2_MakeArchives()
	---------------------*/
	else if($step==2)
	{
		include_once(DEDEADMIN."/makehtml_archives_action.php");
		exit();
	}

	//������ҳ
	/*-------------------------
	function _3_MakeHomePage()
	-------------------*/
	if($step==3)
	{
		include_once(DEDEINC."/arc.partview.class.php");
		$pv = new PartView();
		$row = $pv->dsql->GetOne("Select * From `#@__homepageset` ");
		$templet = str_replace("{style}",$cfg_df_style,$row['templet']);
		$homeFile = DEDEADMIN.'/'.$row['position'];
		$homeFile = str_replace("\\",'/',$homeFile);
		$homeFile = ereg_replace('/{1,}','/',$homeFile);
		$pv->SetTemplet($cfg_basedir.$cfg_templets_dir.'/'.$templet);
		$pv->SaveToHtml($homeFile);
		$pv->Close();
		ShowMsg("��ɸ��������ĵ������ڿ�ʼ������Ŀҳ��","makehtml_all.php?action=make&step=4&uptype=$uptype&mkvalue=$mkvalue");
		exit();
	}

	//������Ŀ
	/*-------------------
	function _4_MakeCatalog()
	--------------------*/
	else if($step==4)
	{
		$mkvalue = intval($mkvalue);
		$typeidsok = $typeids = array();
		$adminID = $cuserLogin->getUserID();
		$mkcachefile = DEDEROOT."/data/mkall_cache_{$adminID}.php";
		if($uptype=='all' || empty($mkvalue))
		{
			ShowMsg("����Ҫ���г������ָ���������Ŀ��","makehtml_list_action.php?gotype=mkallct");
			exit();
		}
		else
		{
			if($uptype=='time')
			{
				$query = "Select  DISTINCT typeid From `#@__arctiny` where senddate >=".GetMkTime($mkvalue)." And arcrank>-1";
			}
			else
			{
				$query = "Select DISTINCT typeid From `#@__arctiny` where id>=$mkvalue And arcrank>-1";
			}
			$dsql->SetQuery($query);
			$dsql->Execute();
			while($row = $dsql->GetArray())
			{
				$typeids[$row['typeid']] = 1;
			}

			foreach($typeids as $k=>$v)
			{
				$vs = array();
				$vs = GetParentIds($k);
				if( !isset($typeidsok[$k]) )
				{
					$typeidsok[$k] = 1;
				}
				foreach($vs as $k=>$v)
				{
					if(!isset($typeidsok[$v]))
					{
						$typeidsok[$v] = 1;
					}
				}
			}
		}
		$fp = fopen($mkcachefile,'w') or die("�޷�д�뻺���ļ���{$mkcachefile} �����޷�������Ŀ��");
		if(count($typeidsok)>0)
		{
			fwrite($fp,"<"."?php\r\n");
			$i = -1;
			foreach($typeidsok as $k=>$t)
			{
				if($k!='')
				{
					$i++;
					fwrite($fp,"\$idArray[$i]={$k};\r\n");
				}
			}
			fwrite($fp,"?".">");
			fclose($fp);
			ShowMsg("�����Ŀ���洦����ת�������Ŀ��","makehtml_list_action.php?gotype=mkall");
			exit();
		}
		else
		{
			fclose($fp);
			ShowMsg("û�пɸ��µ���Ŀ����������������Ż���","makehtml_all.php?action=make&step=10");
			exit();
		}
	}

	//�ɹ�״̬
	/*-------------------
	function _10_MakeAllOK()
	--------------------*/
	else if($step==10)
	{
		$adminID = $cuserLogin->getUserID();
		$mkcachefile = DEDEDATA."/mkall_cache_{$adminID}.php";
		@unlink($mkcachefile);
		OptimizeData($dsql);
		ShowMsg("��������ļ��ĸ��£�","javascript:;");
		exit();
	}//make step

} //action=='make'

//�Ż�����
function OptimizeData($dsql)
{
	global $cfg_dbprefix;
	$tptables = array("{$cfg_dbprefix}archives","{$cfg_dbprefix}arctiny");
	$dsql->SetQuery("Select maintable,addtable From `#@__channeltype` ");
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		$addtable = str_replace('#@__',$cfg_dbprefix,$row->addtable);
		if($addtable!='' && !in_array($addtable,$tptables)) $tptables[] = $addtable;
	}
	$tptable = '';
	foreach($tptables as $t) $tptable .= ($tptable=='' ? "`{$t}`" : ",`{$t}`" );
	$dsql->ExecuteNoneQuery(" OPTIMIZE TABLE $tptable; ");
}

?>
<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 3;
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
$menutype = 'content';

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
	//��ȡ�鵵��Ϣ
	$arcQuery = "Select
    #@__channeltype.typename as channelname,
    #@__arcrank.membername as rankname,
    #@__channeltype.arcsta,
    #@__archives.*
    From #@__archives
    left join #@__channeltype on #@__channeltype.id=#@__archives.channel
    left join #@__arcrank on #@__arcrank.rank=#@__archives.arcrank
    where #@__archives.id='$aid'";
	$dsql->SetQuery($arcQuery);
	$row = $dsql->GetOne($arcQuery);
	if(!is_array($row))
	{
		ShowMsg("��ȡ����������Ϣ����!","-1");
		exit();
	}
	else if($row['arcrank']>=0)
	{
		$dtime = time();
		$maxtime = $cfg_mb_editday * 24 *3600;
		if($dtime - $row['senddate'] > $maxtime)
		{
			ShowMsg("��ƪ�ĵ��Ѿ��������㲻�����޸�����","-1");
			exit();
		}
	}
	$query = "Select * From `#@__channeltype` where id='".$row['channel']."'";
	$cInfos = $dsql->GetOne($query);
	if(!is_array($cInfos))
	{
		ShowMsg("��ȡƵ��������Ϣ����!","javascript:;");
		exit();
	}
	$addtable = $cInfos['addtable'];
	$addQuery = "Select * From `$addtable` where aid='$aid'";
	$addRow = $dsql->GetOne($addQuery);
	$newRowStart = 1;
	$nForm = '';
	if($addRow['softlinks']!='')
	{
		$dtp = new DedeTagParse();
		$dtp->LoadSource($addRow['softlinks']);
		if(is_array($dtp->CTags))
		{
			foreach($dtp->CTags as $ctag)
			{
				if($ctag->GetName()=='link')
				{
					$nForm .= "�����ַ".$newRowStart."��<input class='text' type='text' name='softurl".$newRowStart."'  value='".trim($ctag->GetInnerText())."' />
            ���������ƣ�<input class='text' type='text' name='servermsg".$newRowStart."' value='".$ctag->GetAtt("text")."'  />
            <br />";
					$newRowStart++;
				}
			}
		}
		$dtp->Clear();
	}
	$channelid = $row['channel'];
	$tags = GetTags($aid);
	include(DEDEMEMBER."/templets/soft_edit.htm");
	exit();
}

/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
	$description = '';
	include(DEDEMEMBER.'/inc/archives_check_edit.php');

	//���������ӱ�����
	$inadd_f = '';
	if(!empty($dede_addonfields))
	{
		$addonfields = explode(';',$dede_addonfields);
		if(is_array($addonfields))
		{
			foreach($addonfields as $v)
			{
				if($v=='')
				{
					continue;
				}else if($v == 'templet')
				{
					ShowMsg("�㱣����ֶ�����,���飡","-1");
					exit();	
				}
				$vs = explode(',',$v);
				if(!isset(${$vs[0]}))
				{
					${$vs[0]} = '';
				}
				${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$aid);
				$inadd_f .= ','.$vs[0]." ='".${$vs[0]}."' ";
			}
		}
	}
	$body = AnalyseHtmlBody($body,$description);
	$body = HtmlReplace($body,-1);

	//����ͼƬ�ĵ����Զ�������
	if($litpic!='')
	{
		$flag = 'p';
	}

	//���������ӱ�����
	$inadd_f = '';
	$inadd_v = '';
	if(!empty($dede_addonfields))
	{
		$addonfields = explode(';',$dede_addonfields);
		$inadd_f = '';
		$inadd_v = '';
		if(is_array($addonfields))
		{
			foreach($addonfields as $v)
			{
				if($v=='')
				{
					continue;
				}
				$vs = explode(',',$v);

				//HTML�ı����⴦��
				if($vs[1]=='htmltext'||$vs[1]=='textdata')
				{
					${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
				}
				else
				{
					if(!isset(${$vs[0]}))
					{
						${$vs[0]} = '';
					}
					${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$arcID);
				}
				$inadd_f .= ",`{$vs[0]}` = '".${$vs[0]}."'";
			}
		}
	}

	//������������
	$upQuery = "Update `#@__archives` set
             ismake='$ismake',
             arcrank='$arcrank',
             typeid='$typeid',
             title='$title',
             litpic='$litpic',
             description='$description',
             keywords='$keywords',            
             flag='$flag'
      where id='$aid' And mid='$mid'; ";
	if(!$dsql->ExecuteNoneQuery($upQuery))
	{
		ShowMsg("�������ݿ�archives��ʱ�������飡","-1");
		exit();
	}

	//��������б�
	$urls = '';
	for($i=1;$i<=9;$i++)
	{
		if(!empty(${'softurl'.$i}))
		{
			$servermsg = str_replace("'",'',stripslashes(${'servermsg'.$i}));
			$softurl = stripslashes(${'softurl'.$i});
			if($servermsg=='')
			{
				$servermsg = '���ص�ַ'.$i;
			}
			if($softurl!='' && $softurl!='http://')
			{
				$urls .= "{dede:link text='$servermsg'} $softurl {/dede:link}\r\n";
			}
		}
	}
	$urls = addslashes($urls);

	//���¸��ӱ�
	$needmoney = @intval($needmoney);
	if($needmoney > 100) $needmoney = 100;
	$cts = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$channelid' ");
	$addtable = trim($cts['addtable']);
	if($addtable!='')
	{
		$inQuery = "update `$addtable`
			set typeid ='$typeid',
			filetype ='$filetype',
			language ='$language',
			softtype ='$softtype',
			accredit ='$accredit',
			os ='$os',
			softrank ='$softrank',
			officialUrl ='$officialUrl',
			officialDemo ='$officialDemo',
			softsize ='$softsize',
			softlinks ='$urls',
			userip='$userip',
			needmoney='$needmoney',
			introduce='$body'{$inadd_f}
			where aid='$aid'; ";
		if(!$dsql->ExecuteNoneQuery($inQuery))
		{
			ShowMsg("�������ݿ⸽�ӱ� addonsoft ʱ��������ԭ��","-1");
			exit();
		}
	}
	UpIndexKey($aid,$arcrank,$typeid,$sortrank,$tags);
	$artUrl = MakeArt($aid,true);
	if($artUrl=='')
	{
		$artUrl = $cfg_phpurl."/view.php?aid=$aid";
	}

	//���سɹ���Ϣ
	$msg = "������ѡ����ĺ���������
		<a href='soft_add.php?cid=$typeid'><u>����������</u></a>
		&nbsp;&nbsp;
		<a href='soft_edit.php?channelid=$channelid&aid=".$aid."'><u>�鿴����</u></a>
		&nbsp;&nbsp;
		<a href='$artUrl' target='_blank'><u>�鿴����</u></a>
		&nbsp;&nbsp;
		<a href='content_list.php?channelid=$channelid'><u>��������</u></a>
		";
	$wintitle = "�ɹ��������£�";
	$wecome_info = "���¹���::��������";
	$win = new OxWindow();
	$win->AddTitle("�ɹ��������£�");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}

?>
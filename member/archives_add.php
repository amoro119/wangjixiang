<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/userlogin.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 1;
$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
$mtypesid = isset($mtypesid) && is_numeric($mtypesid) ? $mtypesid : 0;
$menutype = 'content';

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
	$cInfos = $dsql->GetOne("Select * From `#@__channeltype`  where id='$channelid'; ");
	if(!is_array($cInfos))
	{
		ShowMsg('ģ�Ͳ�����', '-1');
		exit();
	}

	//��������˻�Ա��������ͣ��������ο�Ͷ��ѡ����Ч
	if($cInfos['sendrank']>0 || $cInfos['usertype']!='')
	{
		CheckRank(0,0);
	}

	//����Ա�ȼ�����������
	if($cInfos['sendrank'] > $cfg_ml->M_Rank)
	{
		$row = $dsql->GetOne("Select membername From `#@__arcrank` where rank='".$cInfos['sendrank']."' ");
		ShowMsg("�Բ�����Ҫ[".$row['membername']."]���������Ƶ�������ĵ���","-1","0",5000);
		exit();
	}
	if($cInfos['usertype']!='' && $cInfos['usertype'] != $cfg_ml->M_MbType)
	{
		ShowMsg("�Բ�����Ҫ[".$cInfos['usertype']."�ʺ�]���������Ƶ�������ĵ���","-1","0",5000);
		exit();
	}
	include(DEDEMEMBER."/templets/archives_add.htm");
	exit();
}
/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
	include(dirname(__FILE__).'/inc/archives_check.php');

	//���������ӱ�����
	$inadd_f = $inadd_v = '';
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

				//�Զ�ժҪ��Զ��ͼƬ���ػ�
				if($vs[1]=='htmltext'||$vs[1]=='textdata')
				{
					${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$vs[1]);
				}

				${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],0);

				$inadd_f .= ','.$vs[0];
				$inadd_v .= " ,'".${$vs[0]}."' ";
			}
		}
	}

	//����ͼƬ�ĵ����Զ�������
	if($litpic!='') $flag = 'p';

	//�����ĵ�ID
	$arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$mid);
	if(empty($arcID))
	{
		ShowMsg("�޷��������������޷����к���������","-1");
		exit();
	}

	//���浽����
	$inQuery = "INSERT INTO `#@__archives`(id,typeid,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
color,writer,source,litpic,pubdate,senddate,mid,description,keywords,mtype)
VALUES ('$arcID','$typeid','$sortrank','$flag','$ismake','$channelid','$arcrank','0','$money','$title','$shorttitle',
'$color','$writer','$source','$litpic','$pubdate','$senddate','$mid','$description','$keywords','$mtypesid'); ";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		$gerr = $dsql->GetError();
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID' ");
		ShowMsg("�����ݱ��浽���ݿ����� `#@__archives` ʱ��������ϵ����Ա��","javascript:;");
		exit();
	}

	//���浽���ӱ�
	$addtable = trim($cInfos['addtable']);
	if(empty($addtable))
	{
		$dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
		ShowMsg("û�ҵ���ǰģ��[{$channelid}]��������Ϣ���޷���ɲ�����","javascript:;");
		exit();
	}
	else
	{
		$inquery = "INSERT INTO `{$addtable}`(aid,typeid,userip,redirecturl,templet{$inadd_f}) Values('$arcID','$typeid','$userip','',''{$inadd_v})";
		if(!$dsql->ExecuteNoneQuery($inquery))
		{
			$gerr = $dsql->GetError();
			$dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
			$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
			ShowMsg("�����ݱ��浽���ݿ⸽�ӱ� `{$addtable}` ʱ����<br>error:{$gerr}������ϵ����Ա��","javascript:;");
			exit();
		}
	}

	//���ӻ���
	$dsql->ExecuteNoneQuery("Update `#@__member` set scores=scores+{$cfg_sendarc_scores} where mid='".$cfg_ml->M_ID."' ; ");
	//����ͳ��
	countArchives($channelid);

	//����HTML
	InsertTags($tags,$arcID);
	$artUrl = MakeArt($arcID,true);
	if($artUrl=='')
	{
		$artUrl = $cfg_phpurl."/view.php?aid=$arcID";
	}
	
	#api{{
	if(defined('UC_API') && @include_once DEDEROOT.'/api/uc.func.php')
	{
		//�����¼�
		$feed['icon'] = 'thread';
		$feed['title_template'] = '<b>{username} ����վ������һƪ����</b>';
		$feed['title_data'] = array('username' => $cfg_ml->M_UserName);
		$feed['body_template'] = '<b>{subject}</b><br>{message}';
		$url = !strstr($artUrl,'http://') ? ($cfg_basehost.$artUrl) : $artUrl;		
		$feed['body_data'] = array('subject' => "<a href=\"".$url."\">$title</a>", 'message' => cn_substr(strip_tags(preg_replace("/\[.+?\]/is", '', $description)), 150));
		$feed['images'][] = array('url' => $cfg_basehost.'/images/scores.gif', 'link'=> $cfg_basehost);
		uc_feed_note($cfg_ml->M_LoginID,$feed);
		//ͬ������
		$row = $dsql->GetOne("SELECT `scores`,`userid` FROM `#@__member` WHERE `mid`='".$cfg_ml->M_ID."'");
		uc_credit_note($row['userid'],$cfg_sendarc_scores);
	}
	#/aip}}
	
	//��Ա��̬��¼
	$cfg_ml->RecordFeeds('add',$title,$description,$arcID);
	
	ClearMyAddon($arcID, $title);
	
	//���سɹ���Ϣ
	$msg = "
	������ѡ����ĺ���������
		<a href='archives_add.php?cid=$typeid&channelid=$channelid'><u>������������</u></a>
		&nbsp;&nbsp;
		<a href='$artUrl' target='_blank'><u>�鿴����</u></a>
		&nbsp;&nbsp;
		<a href='archives_edit.php?channelid=$channelid&aid=$arcID'><u>��������</u></a>
		&nbsp;&nbsp;
		<a href='content_list.php?channelid={$channelid}'><u>�ѷ������ݹ���</u></a>
		";
	$wintitle = "�ɹ��������ݣ�";
	$wecome_info = "���ݹ���::��������";
	$win = new OxWindow();
	$win->AddTitle("�ɹ��������ݣ�");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}
?>
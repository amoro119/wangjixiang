<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/userlogin.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(dirname(__FILE__)."/inc/inc_catalog_options.php");
require_once(dirname(__FILE__)."/inc/inc_archives_functions.php");
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
	include(DEDEMEMBER."/templets/archives_sg_add.htm");
	exit();
}
/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
	include_once(DEDEINC."/image.func.php");
	include_once(DEDEINC."/oxwindow.class.php");
	$svali = GetCkVdValue();
	if(preg_match("/3/",$safe_gdopen)){
		if(strtolower($vdcode)!=$svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg('��֤�����', '-1');
			exit();
		}
		
	}
	
	$faqkey = isset($faqkey) && is_numeric($faqkey) ? $faqkey : 0;
	if($safe_faq_send == '1')
	{
		if($safefaqs[$faqkey]['answer'] != $safeanswer || $safeanswer=='')
		{
			ShowMsg('��֤����𰸴���', '-1');
			exit();
		}
	}

	$flag = '';
	$autokey = $remote = $dellink = $autolitpic = 0;
	$userip = GetIP();

	if($typeid==0)
	{
		ShowMsg('��ָ���ĵ���������Ŀ��','-1');
		exit();
	}

	$query = "Select tp.ispart,tp.channeltype,tp.issend,ch.issend as cissend,ch.sendrank,ch.arcsta,ch.addtable,ch.usertype
          From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id='$typeid' ";
	$cInfos = $dsql->GetOne($query);

	//�����Ŀ�Ƿ���Ͷ��Ȩ��
	if($cInfos['issend']!=1 || $cInfos['ispart']!=0  || $cInfos['channeltype']!=$channelid || $cInfos['cissend']!=1)
	{
		ShowMsg("����ѡ�����Ŀ��֧��Ͷ�壡","-1");
		exit();
	}

	//���Ƶ���趨��Ͷ�����Ȩ��
	if($cInfos['sendrank'] > $cfg_ml->M_Rank )
	{
		$row = $dsql->GetOne("Select membername From #@__arcrank where rank='".$cInfos['sendrank']."' ");
		ShowMsg("�Բ�����Ҫ[".$row['membername']."]���������Ƶ�������ĵ���","-1","0",5000);
		exit();
	}

	if($cInfos['usertype'] !='' && $cInfos['usertype'] != $cfg_ml->M_MbType)
	{
		ShowMsg("�Բ�����Ҫ[".$cInfos['usertype']."]���������Ƶ�������ĵ���","-1","0",5000);
		exit();
	}

	//�ĵ���Ĭ��״̬
	if($cInfos['arcsta']==0)
	{
		$arcrank = 0;
	}
	else if($cInfos['arcsta']==1)
	{
		$arcrank = 0;
	}
	else
	{
		$arcrank = -1;
	}

	//�Ա�������ݽ��д���
	$sortrank = $senddate = $pubdate = time();
	$title = cn_substrR(HtmlReplace($title,1),$cfg_title_maxlen);
	$mid = $cfg_ml->M_ID;
	$description=empty($description)? "" : $description;

	//�����ϴ�������ͼ
	$litpic = MemberUploads('litpic','',$cfg_ml->M_ID,'image','',$cfg_ddimg_width,$cfg_ddimg_height,false);
	if($litpic!='')
	{
		SaveUploadInfo($title,$litpic,1);
	}

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

				$inadd_f .= ',`'.$vs[0].'`';
				$inadd_v .= " ,'".${$vs[0]}."' ";
			}
		}
	}

	//�����ĵ�ID
	$arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$mid);
	if(empty($arcID))
	{
		ShowMsg("�޷��������������޷����к���������","-1");
		exit();
	}

	//���浽���ӱ�
	$addtable = trim($cInfos['addtable']);
	if(empty($addtable))
	{
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
		ShowMsg("û�ҵ���ǰģ��[{$channelid}]��������Ϣ���޷���ɲ�����","javascript:;");
		exit();
	}
	else
	{
		$inquery = "INSERT INTO `{$addtable}`(aid,typeid,arcrank,mid,channel,title,senddate,litpic,userip{$inadd_f}) Values('$arcID','$typeid','$arcrank','$mid','$channelid','$title','$senddate','$litpic','$userip'{$inadd_v})";
		if(!$dsql->ExecuteNoneQuery($inquery))
		{
			$gerr = $dsql->GetError();
			$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
			ShowMsg("�����ݱ��浽���ݿ⸽�ӱ� `{$addtable}` ʱ��������ϵ����Ա��","javascript:;");
			exit();
		}
	}

	//���ӻ���
	$dsql->ExecuteNoneQuery("Update `#@__member` set scores=scores+{$cfg_sendarc_scores} where mid='".$cfg_ml->M_ID."' ; ");

	//����HTML
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
		<a href='archives_sg_add.php?channelid=$channelid'><u>������������</u></a>
		&nbsp;&nbsp;
		<a href='$artUrl' target='_blank'><u>�鿴����</u></a>
		&nbsp;&nbsp;
		<a href='archives_sg_edit.php?channelid=$channelid&aid=$arcID'><u>��������</u></a>
		&nbsp;&nbsp;
		<a href='content_sg_list.php?channelid={$channelid}'><u>�ѷ������ݹ���</u></a>
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
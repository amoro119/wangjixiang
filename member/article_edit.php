<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 1;
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
$mtypesid = isset($mtypesid) && is_numeric($mtypesid) ? $mtypesid : 0;
$menutype = 'content';

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
	//��ȡ�鵵��Ϣ
	$arcQuery = "Select arc.*,ch.addtable,ch.fieldset,arc.mtype as mtypeid,ch.arcsta
       From `#@__archives` arc left join `#@__channeltype` ch on ch.id=arc.channel
       where arc.id='$aid' And arc.mid='".$cfg_ml->M_ID."'; ";
	$row = $dsql->GetOne($arcQuery);
	if(!is_array($row))
	{
		ShowMsg("��ȡ������Ϣ����!","-1");
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
	$addRow = $dsql->GetOne("Select * From `{$row['addtable']}` where aid='$aid'; ");
	include(DEDEMEMBER."/templets/article_edit.htm");
	exit();
}

/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
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

	//�������ݿ��SQL���
	$upQuery = "Update `#@__archives` set
             ismake='$ismake',
             arcrank='$arcrank',
             typeid='$typeid',
             title='$title',
             litpic='$litpic',
             description='$description',
             mtype = '$mtypesid',
             keywords='$keywords',            
             flag='$flag'
      where id='$aid' And mid='$mid'; ";
	if(!$dsql->ExecuteNoneQuery($upQuery))
	{
		ShowMsg("�����ݱ��浽���ݿ�����ʱ��������ϵ����Ա��".$dsql->GetError(),"-1");
		exit();
	}
	if($addtable!='')
	{
		$upQuery = "Update `$addtable` set typeid='$typeid',body='$body'{$inadd_f},userip='$userip' where aid='$aid' ";
		if(!$dsql->ExecuteNoneQuery($upQuery))
		{
			ShowMsg("���¸��ӱ� `$addtable`  ʱ��������ϵ����Ա��","javascript:;");
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
		<a href='article_add.php?cid=$typeid'><u>����������</u></a>
		&nbsp;&nbsp;
		<a href='archives_do.php?channelid=$channelid&aid=".$aid."&dopost=edit'><u>�鿴����</u></a>
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
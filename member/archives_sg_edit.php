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
  $arcQuery = "Select ch.*,arc.* From `#@__arctiny` arc
    left join `#@__channeltype` ch on ch.id=arc.channel where arc.id='$aid' ";
	$cInfos = $dsql->GetOne($arcQuery);
	if(!is_array($cInfos))
	{
		ShowMsg("��ȡ�ĵ���Ϣ����!","-1");
		exit();
	}
	$addRow = $dsql->GetOne("Select * From `{$cInfos['addtable']}` where aid='$aid'; ");
	if($addRow['mid']!=$cfg_ml->M_ID)
	{
		ShowMsg("�Բ�����ûȨ�޲������ĵ���","-1");
		exit();
	}
	$addRow['id'] = $addRow['aid'];
	include(DEDEMEMBER."/templets/archives_sg_edit.htm");
	exit();
}

/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
	require_once(DEDEINC."/image.func.php");
	require_once(DEDEINC."/oxwindow.class.php");

	$flag = '';
	$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
	$userip = GetIP();
	$ckhash = md5($aid.$cfg_cookie_encode);
	if($ckhash!=$idhash)
	{
		ShowMsg('У���������ûȨ���޸Ĵ��ĵ���������Ϸ���','-1');
		exit();
	}
	
	$svali = GetCkVdValue();
	if(preg_match("/3/",$safe_gdopen)){
		if(strtolower($vdcode)!=$svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg('��֤�����', '-1');
			exit();
		}
		
	}

	if($typeid==0)
	{
		ShowMsg('��ָ���ĵ���������Ŀ��','-1');
		exit();
	}
	$query = "Select tp.ispart,tp.channeltype,tp.issend,ch.issend as cissend,ch.sendrank,ch.arcsta,ch.addtable,ch.usertype
         From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id='$typeid' ";
	$cInfos = $dsql->GetOne($query);
	$addtable = $cInfos['addtable'];

	//�����Ŀ�Ƿ���Ͷ��Ȩ��
	if($cInfos['issend']!=1 || $cInfos['ispart']!=0|| $cInfos['channeltype']!=$channelid || $cInfos['cissend']!=1)
	{
		ShowMsg("����ѡ�����Ŀ��֧��Ͷ�壡","-1");
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
	$title = cn_substrR(HtmlReplace($title,1),$cfg_title_maxlen);
	$mid = $cfg_ml->M_ID;

	//�����ϴ�������ͼ
	$litpic = MemberUploads('litpic',$oldlitpic,$mid,'image','',$cfg_ddimg_width,$cfg_ddimg_height,false);
	if($litpic!='')
	{
		SaveUploadInfo($title,$litpic,1);
	}
	else
	{
		$litpic =$oldlitpic;
	}

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

				//�Զ�ժҪ��Զ��ͼƬ���ػ�
				if($vs[1]=='htmltext'||$vs[1]=='textdata')
				{
					${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$vs[1]);
				}

				${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$aid);

				$inadd_f .= ',`'.$vs[0]."` ='".${$vs[0]}."' ";
			}
		}
	}

	if($addtable!='')
	{
		$upQuery = "Update `$addtable` set `title`='$title',`typeid`='$typeid',`arcrank`='$arcrank',litpic='$litpic',userip='$userip'{$inadd_f} where aid='$aid' ";
		if(!$dsql->ExecuteNoneQuery($upQuery))
		{
			ShowMsg("���¸��ӱ� `$addtable`  ʱ��������ϵ����Ա��","javascript:;");
			exit();
		}
	}
	
	UpIndexKey($aid,0,$typeid,$sortrank,'');
	
	$artUrl = MakeArt($aid,true);
	
	if($artUrl=='')
	{
		$artUrl = $cfg_phpurl."/view.php?aid=$aid";
	}

	//���سɹ���Ϣ
	$msg = "������ѡ����ĺ���������
		<a href='archives_sg_add.php?cid=$typeid'><u>����������</u></a>
		&nbsp;&nbsp;
		<a href='archives_do.php?channelid=$channelid&aid=".$aid."&dopost=edit'><u>�鿴����</u></a>
		&nbsp;&nbsp;
		<a href='$artUrl' target='_blank'><u>�鿴����</u></a>
		&nbsp;&nbsp;
		<a href='content_sg_list.php?channelid=$channelid'><u>��������</u></a>
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
<?php
require_once(dirname(__FILE__)."/config.php");

//���ǰ�ȫԭ�򲻹��Ƿ����ο�Ͷ�幦�ܣ����������û���ͼ��Ͷ��
CheckRank(0,0);
if($cfg_mb_lit=='Y')
{
	ShowMsg("����ϵͳ�����˾�����Ա�ռ䣬����ʵĹ��ܲ����ã�","-1");
	exit();
}
if($cfg_mb_album=='N')
{
	ShowMsg("�Բ�������ϵͳ�ر���ͼ�����ܣ�����ʵĹ��ܲ����ã�","-1");
	exit();
}
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/userlogin.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 2;
$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
$menutype = 'content';
if(empty($formhtml))
{
	$formhtml = 0;
}

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
	$query = "Select * From `#@__channeltype`  where id='$channelid'; ";
	$cInfos = $dsql->GetOne($query);
	if(!is_array($cInfos))
	{
		ShowMsg('ģ�Ͳ�������ȷ', '-1');
		exit();
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
	include(DEDEMEMBER."/templets/album_add.htm");
	exit();
}

/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
	$svali = GetCkVdValue();
	if(preg_match("/1/",$safe_gdopen)){
		if(strtolower($vdcode)!=$svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg('��֤�����', '-1');
			exit();
		}
		
	}
	$maxwidth = isset($maxwidth) && is_numeric($maxwidth) ? $maxwidth : 800;
	$pagepicnum = isset($pagepicnum) && is_numeric($pagepicnum) ? $pagepicnum : 12;
	$ddmaxwidth = isset($ddmaxwidth) && is_numeric($ddmaxwidth) ? $ddmaxwidth : 200;
	$prow = isset($prow) && is_numeric($prow) ? $prow : 3;
	$pcol = isset($pcol) && is_numeric($pcol) ? $pcol : 3;
	$pagestyle = in_array($pagestyle,array('1','2','3')) ? $pagestyle : 2;
	include(DEDEMEMBER.'/inc/archives_check.php');
	$imgurls = "{dede:pagestyle maxwidth='$maxwidth' pagepicnum='$pagepicnum' ddmaxwidth='$ddmaxwidth' row='$prow' col='$pcol' value='$pagestyle'/}\r\n";
	$hasone = false;
	$ddisfirst=1;

	//����������ָ����ͼƬ�����ϸ���
	if($formhtml==1)
	{
		$imagebody = stripslashes($imagebody);
		$imgurls .= GetCurContentAlbum($imagebody,$copysource,$litpicname);
		if($ddisfirst==1 && $litpic=='' && !empty($litpicname))
		{
			$litpic = $litpicname;
			$hasone = true;
		}
	}
	$info = '';

	//�����ϴ�
	for($i=1;$i<=120;$i++)
	{
		//����ͼƬ������
		if(isset($_FILES['imgfile'.$i]['tmp_name']) && is_uploaded_file($_FILES['imgfile'.$i]['tmp_name']) )
		{
			$iinfo = str_replace("'","`",stripslashes(${'imgmsg'.$i}));
			if(!is_uploaded_file($_FILES['imgfile'.$i]['tmp_name']))
			{
				continue;
			}
			else
			{
				$sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
				if(!in_array($_FILES['imgfile'.$i]['type'],$sparr))
				{
					continue;
				}
				$filename = MemberUploads('imgfile'.$i,'',$cfg_ml->M_ID,'image','',0,0,false);
				if($filename!='')
				{
					SaveUploadInfo($title,$filename,1);
				}

				//��ͼ
				if($pagestyle > 2)
				{
					$litpicname = GetImageMapDD($filename,$ddmaxwidth);
					if($litpicname != '')
					{
						SaveUploadInfo($title.' Сͼ',$litpicname,1);
					}
				}
				else
				{
					$litpicname = $filename;
				}
				$imgfile = $cfg_basedir.$filename;
				if(is_file($imgfile))
				{
					$iurl = $filename;
					$info = '';
					$imginfos = @getimagesize($imgfile,$info);
					$imgurls .= "{dede:img ddimg='$litpicname' text='$iinfo' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
				}
			}
			if(!$hasone && $litpic=='' && !empty($litpicname))
			{
				$litpic = $litpicname;
				$hasone = true;
			}
		}
	}//ѭ������
	$imgurls = addslashes($imgurls);

	//���������ӱ�����
	$isrm = 1;
	if(!isset($formhtml))
	{
		$formhtml = 0;
	}
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
				${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],0);
				$inadd_f .= ','.$vs[0];
				$inadd_v .= " ,'".${$vs[0]}."' ";
			}
		}
	}

	//����ͼƬ�ĵ����Զ�������
	if($litpic!='')
	{
		$flag = 'p';
	}

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
		ShowMsg("û�ҵ���ǰģ��[{$channelid}]��������Ϣ���޷���ɲ�������","javascript:;");
		exit();
	}
	else
	{
		$query = "INSERT INTO `$addtable`(aid,typeid,userip,redirecturl,templet,pagestyle,maxwidth,imgurls,row,col,isrm,ddmaxwidth,pagepicnum{$inadd_f})
     Values('$arcID','$typeid','$userip','','','$pagestyle','$maxwidth','$imgurls','$prow','$pcol','$isrm','$ddmaxwidth','$pagepicnum'{$inadd_v}); ";
		if(!$dsql->ExecuteNoneQuery($query))
		{
			$gerr = $dsql->GetError();
			$dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
			$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
			ShowMsg("�����ݱ��浽���ݿ⸽�ӱ� `{$addtable}` ʱ��������ϵ����Ա��".$gerr,"javascript:;");
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
		$feed['title_template'] = '<b>{username} ����վ������һƪͼ��</b>';
		$feed['title_data'] = array('username' => $cfg_ml->M_UserName);
		$feed['body_template'] = '<b>{subject}</b><br>{message}';
		$url = !strstr($artUrl,'http://') ? ($cfg_basehost.$artUrl) : $artUrl;
		$feed['body_data'] = array('subject' => "<a href=\"".$url."\">$title</a>", 'message' => cn_substr(strip_tags(preg_replace("/\[.+?\]/is", '', $description)), 150));		
		$feed['images'][] = array('url' => $cfg_basehost.'/images/scores.gif', 'link'=> $cfg_basehost);
		uc_feed_note($cfg_ml->M_LoginID,$feed);

		$row = $dsql->GetOne("SELECT `scores`,`userid` FROM `#@__member` WHERE `mid`='".$cfg_ml->M_ID."' AND `matt`<>10");
		uc_credit_note($row['userid'], $cfg_sendarc_scores);
	}
	#/aip}}

    //��Ա��̬��¼
	$cfg_ml->RecordFeeds('add',$title,$description,$arcID);
	
	ClearMyAddon($arcID, $title);
	
	//���سɹ���Ϣ
	$msg = "
������ѡ����ĺ���������
	<a href='album_add.php?cid=$typeid'><u>��������ͼ��</u></a>
	&nbsp;&nbsp;
	<a href='$artUrl' target='_blank'><u>�鿴ͼ��</u></a>
	&nbsp;&nbsp;
	<a href='album_edit.php?aid=".$arcID."&channelid=$channelid'><u>����ͼ��</u></a>
	&nbsp;&nbsp;
	<a href='content_list.php?channelid={$channelid}'><u>�ѷ���ͼ������</u></a>
	";
	$wintitle = "�ɹ�����ͼ����";
	$wecome_info = "ͼ������::����ͼ��";
	$win = new OxWindow();
	$win->AddTitle("�ɹ�����ͼ����");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}

?>
<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");
require_once(DEDEINC."/channelunit.func.php");
if(!isset($action))
{
	$action = '';
}
//���ݾɵ�JS����
if($action == 'good' || $action == 'bad')
{
	if(!empty($aid)) $id = $aid;
	require_once(dirname(__FILE__).'/digg_ajax.php');
	exit();
}


function GetOnebook($aid)
{
	global $dsql,$title;
	$aid = trim(ereg_replace('[^0-9]','',$aid));
	$reArr = array();

	$nquery = "Select * From `#@__story_books` where bid='$aid' ";
	
	$arcRow = $dsql->GetOne($nquery);
	if(!is_array($arcRow)) {
		return $reArr;
	}
	$reArr = $arcRow;
	$reArr['bid']    = $aid;
	$reArr['arctitle'] = $arcRow['bookname'];
	$title = $arcRow['bookname'];
	//$reArr['arcurl'] = GetFileUrl($aid,$arcRow['typeid'],$arcRow['senddate'],$reArr['bookname '],$arcRow['ismake'],$arcRow['arcrank'],$arcRow['namerule'],
	//$arcRow['typedir'],$arcRow['money'],$arcRow['filename'],$arcRow['moresite'],$arcRow['siteurl'],$arcRow['sitepath']);
	return $reArr;

}


$cfg_formmember = isset($cfg_formmember) ? true : false;
$ischeck = $cfg_feedbackcheck=='Y' ? 0 : 1;
$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
$fid = (isset($fid) && is_numeric($fid)) ? $fid : 0;
if(empty($aid) && empty($fid))
{
	ShowMsg('�ĵ�id����Ϊ��!','-1');
	exit();
}

include_once(DEDEINC."/memberlogin.class.php");
$cfg_ml = new MemberLogin();

if($action=='goodfb')
{
	AjaxHead();
	$fid = intval($fid);
	$dsql->ExecuteNoneQuery("Update `#@__bookfeedback` set good = good+1 where id='$fid' ");
	$row = $dsql->GetOne("Select good From `#@__bookfeedback` where id='$fid' ");
	echo "<a onclick=\"postBadGood('goodfb',{$aid})\">֧��</a>[{$row['good']}]";
	exit();
}
else if($action=='badfb')
{
	AjaxHead();
	$fid = intval($fid);
	$dsql->ExecuteNoneQuery("Update `#@__bookfeedback` set bad = bad+1 where id='$fid' ");
	$row = $dsql->GetOne("Select bad From `#@__bookfeedback` where id='$fid' ");
	echo "<a onclick=\"postBadGood('badfb',{$aid})\">����</a>[{$row['bad']}]";
	exit();
}
//�鿴����
/*
function __ViewFeedback(){ }
*/
//-----------------------------------
else if($action=='' || $action=='show')
{
	//��ȡ�ĵ���Ϣ
	$arcRow = GetOnebook($aid);
	if(empty($arcRow['bid']))
	{
		ShowMsg('�޷��鿴δ֪�ĵ�������!','-1');
		exit();
	}
	extract($arcRow, EXTR_SKIP);	
	include_once(DEDEINC.'/datalistcp.class.php');
	$dlist = new DataListCP();
	$dlist->pageSize = 20;

	if(empty($ftype) || ($ftype!='good' && $ftype!='bad' && $ftype!='feedback'))
	{
		$ftype = '';
	}
	$wquery = $ftype!='' ? " And ftype like '$ftype' " : '';

	//���������б�
	$querystring = "select fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores from `#@__bookfeedback` fb
                 left join `#@__member` mb on mb.mid = fb.mid
                 where fb.aid='$aid' and fb.ischeck='1' $wquery order by fb.id desc";
	$dlist->SetParameter('aid',$aid);
	$dlist->SetParameter('action','show');
	$dlist->SetTemplate($cfg_basedir.$cfg_templets_dir.'/plus/bookfeedback_templet.htm');
	$dlist->SetSource($querystring);
	$dlist->Display();
	exit();
}

//��������
//------------------------------------
/*
function __Quote(){ }
*/
else if($action=='quote')
{
	$row = $dsql->GetOne("Select * from `#@__bookfeedback` where id ='$fid'");
	require_once(DEDEINC.'/dedetemplate.class.php');
	$dtp = new DedeTemplate();
	$dtp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/bookfeedback_quote.htm');
	$dtp->Display();
	exit();
}
//��������
//------------------------------------
/*
function __SendFeedback(){ }
*/
else if($action=='send')
{
	//��ȡ�ĵ���Ϣ
	$arcRow = GetOnebook($aid);
	if((empty($arcRow['bid']) || $arcRow['notpost']=='1')&&empty($fid))
	{
		ShowMsg('�޷��Ը��ĵ���������!','-1');
		exit();
	}

	//�Ƿ����֤����ȷ��
	if(empty($isconfirm))
	{
		$isconfirm = '';
	}
	if($isconfirm!='yes' && $cfg_feedback_ck=='Y')
	{
		extract($arcRow, EXTR_SKIP);
		require_once(DEDEINC.'/dedetemplate.class.php');
		$dtp = new DedeTemplate();
		$dtp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/bookfeedback_confirm.htm');
		$dtp->Display();
		exit();
	}
	//�����֤��
	if($cfg_feedback_ck=='Y')
	{
		$validate = isset($validate) ? strtolower(trim($validate)) : '';
		$svali = strtolower(trim(GetCkVdValue()));
		if($validate != $svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg('��֤�����','-1');
			exit();
		}
	}

	//����û���¼
	if(empty($notuser))
	{
		$notuser=0;
	}

	//������������
	if($notuser==1)
	{
		$username = $cfg_ml->M_ID > 0 ? '����' : '�ο�';
	}

	//�ѵ�¼���û�
	else if($cfg_ml->M_ID > 0)
	{
		$username = $cfg_ml->M_UserName;
	}

	//�û������֤
	else
	{
		if($username!='' && $pwd!='')
		{
			$rs = $cfg_ml->CheckUser($username,$pwd);
			if($rs==1)
			{
				$dsql->ExecuteNoneQuery("Update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='{$cfg_ml->M_ID}'; ");
			}
			else
			{
				$username = '�ο�';
			}
		}
		else
		{
			$username = '�ο�';
		}
	}
	$ip = GetIP();
	$dtime = time();
	
	//������ۼ��ʱ�䣻
	if(!empty($cfg_feedback_time))
	{
		//�����󷢱�����ʱ�䣬���δ��½�жϵ�ǰIP�������ʱ��
		if($cfg_ml->M_ID > 0)
		{
			$where = "WHERE `mid` = '$cfg_ml->M_ID'";
		}
		else
		{
			$where = "WHERE `ip` = '$ip'";
		}
		$row = $dsql->GetOne("SELECT dtime FROM `#@__bookfeedback` $where ORDER BY `id` DESC ");
		if($dtime - $row['dtime'] < $cfg_feedback_time)
		{
			ResetVdValue();
			ShowMsg('����Ա���������ۼ��ʱ�䣬���Ե���Ϣһ�£�','-1');
			exit();
		}
	}

	if(empty($face))
	{
		$face = 0;
	}
	$face = intval($face);
	extract($arcRow, EXTR_SKIP);
	$msg = cn_substrR(TrimMsg($msg),1000);
	$username = cn_substrR(HtmlReplace($username,2),20);
	if($feedbacktype!='good' && $feedbacktype!='bad')
	{
		$feedbacktype = 'feedback';
	}
	//������������
	if($comtype == 'comments')
	{
		$arctitle = addslashes($arcRow['arctitle']);
		$arctitle = $arcRow['arctitle'];
		if($msg!='')
		{
			$inquery = "INSERT INTO `#@__bookfeedback`(`aid`,`catid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`, `mid`,`bad`,`good`,`ftype`,`face`,`msg`)
	               VALUES ('$aid','$catid','$username','$bookname','$ip','$ischeck','$dtime', '{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg'); ";
			$rs = $dsql->ExecuteNoneQuery($inquery);
			if(!$rs)
			{
				echo $dsql->GetError();
				exit();
			}
		}
	}
	//���ûظ�
	elseif ($comtype == 'reply')
	{
		$row = $dsql->GetOne("Select * from `#@__bookfeedback` where id ='$fid'");
		$arctitle = $row['arctitle'];
		$aid =$row['aid'];
		$msg = $quotemsg.$msg;
		$msg = HtmlReplace($msg,2);
		$inquery = "INSERT INTO `#@__bookfeedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`,`mid`,`bad`,`good`,`ftype`,`face`,`msg`)
				VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime','{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg')";
		$dsql->ExecuteNoneQuery($inquery);
	}


	if($cfg_ml->M_ID > 0)
	{
		#api{{
		if(defined('UC_APPID'))
		{
			include_once DEDEROOT.'/api/uc.func.php';
			$row = $dsql->GetOne("SELECT `scores`,`userid` FROM `#@__member` WHERE `mid`='".$cfg_ml->M_ID."'");
			uc_credit_note($row['userid'],$cfg_sendfb_scores);
		}
		#/aip}}
		$dsql->ExecuteNoneQuery("Update `#@__member` set scores=scores+{$cfg_sendfb_scores} where mid='{$cfg_ml->M_ID}' ");
	}
	//ͳ���û�����������
	if($cfg_ml->M_ID > 0)
	{
		#api{{
		if(defined('UC_APPID'))
		{
			include_once DEDEROOT.'/api/uc.func.php';
			//�����¼�
			$arcRow = GetOnebook($aid);
			$feed['icon'] = 'thread';
			$feed['title_template'] = '<b>{username} ����վ����������</b>';
			$feed['title_data'] = array('username' => $cfg_ml->M_UserName);
			$feed['body_template'] = '<b>{subject}</b><br>{message}';
			$url = !strstr($arcRow['arcurl'],'http://') ? ($cfg_basehost.$arcRow['arcurl']) : $arcRow['arcurl'];		
			$feed['body_data'] = array('subject' => "<a href=\"".$url."\">$arcRow[arctitle]</a>", 'message' => cn_substr(strip_tags(preg_replace("/\[.+?\]/is", '', $msg)), 150));
			$feed['images'][] = array('url' => $cfg_basehost.'/images/scores.gif', 'link'=> $cfg_basehost);
			uc_feed_note($cfg_ml->M_LoginID,$feed); unset($arcRow);
		}
		#/aip}}	
		$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__bookfeedback` WHERE `mid`='".$cfg_ml->M_ID."'");
		$dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET `feedback`='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");
	}
	$_SESSION['sedtime'] = time();
	if(empty($uid) && isset($cmtuser)) $uid = $cmtuser;
	$backurl = $cfg_formmember ? "index.php?uid={$uid}&action=viewarchives&aid={$aid}" : "bookfeedback.php?aid=$aid";
	if($ischeck==0)
	{
		ShowMsg("�ɹ��������ۣ�������˺�Ż���ʾ�������!",$backurl);
	}elseif($ischeck==1)
	{
		ShowMsg("�ɹ��������ۣ�����ת������ҳ��!",$backurl);
	}
	exit();
}
?>
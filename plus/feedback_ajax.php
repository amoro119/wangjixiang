<?php
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(DEDEINC.'/channelunit.func.php');
AjaxHead();

if($cfg_feedback_forbid=='Y') exit('ϵͳ�Ѿ���ֹ���۹��ܣ�');

$aid = intval($aid);
if(empty($aid)) exit('ûָ�������ĵ���ID�����ܽ��в�����');

include_once(DEDEINC.'/memberlogin.class.php');
$cfg_ml = new MemberLogin();

if(empty($dopost)) $dopost = '';
$page = empty($page) ? 1 : intval($page);
$pagesize = 10;

/*----------------------
���ָ��ҳ����������
function getlist(){ }
----------------------*/
if($dopost=='getlist')
{
	$totalcount = GetList($page);
	GetPageList($pagesize, $totalcount);
	exit();
}
/*----------------------
��������
function send(){ }
----------------------*/
else if($dopost=='send')
{
	require_once(DEDEINC.'/charset.func.php');
	
	//�����֤��
	if($cfg_feedback_ck=='Y')
	{
		$svali = strtolower(trim(GetCkVdValue()));
		if(strtolower($validate) != $svali || $svali=='')
		{
			ResetVdValue();
			echo '<font color="red">��֤�����������֤��ͼƬ������֤�룡</font>';
			exit();
		}
	}
	
	$arcRow = GetOneArchive($aid);
	if(empty($arcRow['aid']))
	{
		echo '<font color="red">�޷��鿴δ֪�ĵ�������!</font>';
		exit();
	}
	if(isset($arcRow['notpost']) && $arcRow['notpost']==1)
	{
		echo '<font color="red">��ƪ�ĵ���ֹ����!</font>';
		exit();
	}
	
	if($cfg_soft_lang != 'utf8')
	{
		$msg = UnicodeUrl2Gbk($msg);
		if(!empty($username)) $username = UnicodeUrl2Gbk($username);
	}
	//�ʻ���˼��
	if($cfg_notallowstr != '')
	{
		if(eregi($cfg_notallowstr, $msg))
		{
			echo "<font color='red'>�������ݺ��н��ôʻ㣡</font>";
			exit();
		}
	}
	if($cfg_replacestr != '')
	{
		$msg = eregi_replace($cfg_replacestr, '***', $msg);
	}
	if(empty($msg))
	{
		echo "<font color='red'>�������ݿ��ܲ��Ϸ���Ϊ�գ�</font>";
		exit();
	}
	//����û�
	$username = empty($username) ? '�ο�' : $username;
	if(empty($notuser)) $notuser = 0;
	if($notuser==1)
	{
		$username = $cfg_ml->M_ID > 0 ? '����' : '�ο�';
	}
	else if($cfg_ml->M_ID > 0)
	{
		$username = $cfg_ml->M_UserName;
	}
	else if($username!='' && $pwd!='')
	{
		$rs = $cfg_ml->CheckUser($username, $pwd);
		if($rs==1)
		{
			$dsql->ExecuteNoneQuery("Update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='{$cfg_ml->M_ID}'; ");
		}
		$cfg_ml = new MemberLogin();
	}
	
	//������ۼ��ʱ��
	$ip = GetIP();
	$dtime = time();
	if(!empty($cfg_feedback_time))
	{
		//�����󷢱�����ʱ�䣬���δ��½�жϵ�ǰIP�������ʱ��
		$where = ($cfg_ml->M_ID > 0 ? "WHERE `mid` = '$cfg_ml->M_ID' " : "WHERE `ip` = '$ip' ");
		$row = $dsql->GetOne("SELECT dtime FROM `#@__feedback` $where ORDER BY `id` DESC ");
		if(is_array($row) && $dtime - $row['dtime'] < $cfg_feedback_time)
		{
			ResetVdValue();
			echo '<font color="red">����Ա���������ۼ��ʱ�䣬���Ե���Ϣһ�£�</font>';
			exit();
		}
	}
	$face = intval($face);
	extract($arcRow, EXTR_SKIP);
	$msg = cn_substrR(TrimMsg($msg), 500);
	$username = cn_substrR(HtmlReplace($username,2), 20);
	if(empty($feedbacktype) || ($feedbacktype!='good' && $feedbacktype!='bad'))
	{
		$feedbacktype = 'feedback';
	}
	//������������
	if(!empty($fid))
	{
		$row = $dsql->GetOne("Select username,msg from `#@__feedback` where id ='$fid' ");
		$qmsg = '{quote}{title}'.$row['username'].' ��ԭ����{/title}{content}'.$row['msg'].'{/content}{/quote}';
		$msg = addslashes($qmsg).$msg;
	}
	$ischeck = ($cfg_feedbackcheck=='Y' ? 0 : 1);
	$arctitle = addslashes($title);
	$inquery = "INSERT INTO `#@__feedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`, `mid`,`bad`,`good`,`ftype`,`face`,`msg`)
	               VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime', '{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg'); ";
	$rs = $dsql->ExecuteNoneQuery($inquery);
	if( !$rs )
	{
			echo "<font color='red'>�������۳����ˣ�</font>";
			//echo $dslq->GetError();
			exit();
	}
	$newid = $dsql->GetLastID();
  //����������
	if($feedbacktype=='bad')
	{
		$dsql->ExecuteNoneQuery("Update `#@__archives` set scores=scores-{cfg_feedback_sub},badpost=badpost+1,lastpost='$dtime' where id='$aid' ");
	}
	else if($feedbacktype=='good')
	{
		$dsql->ExecuteNoneQuery("Update `#@__archives` set scores=scores+{$cfg_feedback_add},goodpost=goodpost+1,lastpost='$dtime' where id='$aid' ");
	}
	else
	{
		$dsql->ExecuteNoneQuery("Update `#@__archives` set scores=scores+1,lastpost='$dtime' where id='$aid' ");
	}
	//���û����ӻ���
	if($cfg_ml->M_ID > 0)
	{
		#api{{
		if(defined('UC_API') && @include_once DEDEROOT.'/api/uc.func.php')
		{
			//ͬ������
			uc_credit_note($cfg_ml->M_LoginID, $cfg_sendfb_scores);
			
			//�����¼�
			$arcRow = GetOneArchive($aid);
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
		$dsql->ExecuteNoneQuery("Update `#@__member` set scores=scores+{$cfg_sendfb_scores} where mid='{$cfg_ml->M_ID}' ");
		$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__feedback` WHERE `mid`='".$cfg_ml->M_ID."'");
		$dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET `feedback`='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");
	}
	$_SESSION['sedtime'] = time();
	if($ischeck==0)
	{
		echo '<font color="red">�ɹ��������ۣ�������˺�Ż���ʾ�������!</font>';
		exit();
	}
	else
	{
		$spaceurl = '#';
		if($cfg_ml->M_ID > 0) $spaceurl = "{$cfg_memberurl}/index.php?uid=".urlencode($cfg_ml->M_LoginID);
		$id = $newid;
		$msg = stripslashes($msg);
		$msg = str_replace('<', '&lt;', $msg);
		$msg = str_replace('>', '&gt;', $msg);
		$msg = Quote_replace($msg);
		if($feedbacktype=='bad') $bgimg = 'cmt-bad.gif';
		else if($feedbacktype=='good') $bgimg = 'cmt-good.gif';
		else $bgimg = 'cmt-neu.gif';
		global $dsql, $aid, $pagesize, $cfg_templeturl;
		if($cfg_ml->M_ID==""){
			 $mface=$cfg_cmspath."/member/templets/images/dfboy.png";
		}else{
		  $row = $dsql->GetOne("select face,sex from `#@__member` where mid={$cfg_ml->M_ID} ");
			if(empty($row['face']))
			{
			  if($row['sex']=="Ů") $mface=$cfg_cmspath."/member/templets/images/dfgirl.png";
			  else $mface=$cfg_cmspath."/member/templets/images/dfboy.png";
			}
		}
?>
	<div class='decmt-box'> <ul>
     <li>
      <a href='<?php echo $spaceurl; ?>' class='plpic'><img src='<?php echo $mface;?>'  height='40' width='40'/></a>
      <span class="title"><a href="<?php echo $spaceurl; ?>"><?php echo $username; ?></a></span>
      <div class="comment_act"><span class="fr"><span id='goodfb<?php echo $id; ?>'>
				<a href='#goodfb<?php echo $id; ?>' onclick="postBadGood('goodfb',<?php echo $id; ?>);">֧��</a>[0]
			</span>
			<span id='badfb<?php echo $id; ?>'>
				<a href='#badfb<?php echo $id; ?>' onclick="postBadGood('badfb',<?php echo $id; ?>);">����</a>[0]
			</span>
			<span class='quote'>
				<a href='/plus/feedback.php?fid=<?php echo $id; ?>&action=quote'">[����]</a>
			</span></span><?php echo GetDateMk($dtime); ?>����</div>			
     <p><?php echo $msg; ?><img src='<?php echo $cfg_templeturl; ?>/default/images/mood/ico-mood-<?php echo $face; ?>.gif'/></p>
  </li>
 </ul>
</div>
	<br style='clear:both' />
<?php
	}
	exit();
}

//��ȡ�б�����
function GetList($page=1)
{
	global $dsql, $aid, $pagesize, $cfg_templeturl,$cfg_cmspath;
	$querystring = "select fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores,mb.sex from `#@__feedback` fb
                 left join `#@__member` mb on mb.mid = fb.mid where fb.aid='$aid' and fb.ischeck='1' order by fb.id desc";
	$row = $dsql->GetOne("select count(*) as dd from `#@__feedback` where aid='$aid' and ischeck='1' ");
	$totalcount = (empty($row['dd']) ? 0 : $row['dd']);
	$startNum = $pagesize * ($page-1);
	if($startNum > $totalcount)
	{
		echo "��������";
		return $totalcount;
	}
	$dsql->Execute('fb', $querystring." limit $startNum, $pagesize ");
	while($fields = $dsql->GetArray('fb'))
	{
		if($fields['userid']!='') $spaceurl = $GLOBALS['cfg_memberurl'].'/index.php?uid='.$fields['userid'];
		else $spaceurl = '#';
		if($fields['username']=='����') $spaceurl = '#';
		$fields['bgimg'] = 'cmt-neu.gif';
		$fields['ftypetitle'] = '���û���ʾ����';
		if($fields['ftype']=='bad')
		{
				$fields['bgimg'] = 'cmt-bad.gif';
				$fields['ftypetitle'] = '���û���ʾ����';
		}
		else if($fields['ftype']=='good')
		{
				$fields['bgimg'] = 'cmt-good.gif';
				$fields['ftypetitle'] = '���û���ʾ����';
		}
		if(empty($fields['mface']))
		{
		  if($fields['sex']=="Ů") $fields['mface']=$cfg_cmspath."/member/templets/images/dfgirl.png";
		  else $fields['mface']=$cfg_cmspath."/member/templets/images/dfboy.png";
		}
		$fields['face'] = empty($fields['face']) ? 6 : $fields['face'];
		$fields['msg'] = str_replace('<', '&lt;', $fields['msg']);
		$fields['msg'] = str_replace('>', '&gt;', $fields['msg']);
		$fields['msg'] = Quote_replace($fields['msg']);
		extract($fields, EXTR_OVERWRITE);
?>
<div class="decmt-box">
   <ul>
     <li>
      <a href='<?php echo $spaceurl; ?>' class='plpic'><img src='<?php echo $mface;?>'  height='40' width='40'/></a>
      <span class="title"><a href="<?php echo $spaceurl; ?>"><?php echo $username; ?></a></span>
      <div class="comment_act"><span class="fr"><span id='goodfb<?php echo $id; ?>'>
				<a href='#goodfb<?php echo $id; ?>' onclick="postBadGood('goodfb',<?php echo $id; ?>);">֧��</a>[<?php echo $good; ?>]
			</span>
			<span id='badfb<?php echo $id; ?>'>
				<a href='#badfb<?php echo $id; ?>' onclick="postBadGood('badfb',<?php echo $id; ?>);">����</a>[<?php echo $bad; ?>]
			</span>
			<span class='quote'>
				<a href='/plus/feedback.php?fid=<?php echo $id; ?>&action=quote'">[����]</a>
			</span></span><?php echo GetDateMk($dtime); ?>����</div>			
     <p><?php echo $msg; ?><img src='<?php echo $cfg_templeturl; ?>/default/images/mood/ico-mood-<?php echo $face; ?>.gif'/></p>
  </li>
 </ul>
</div>
<?php
	}
	return $totalcount;            
}

//���ɷ�ҳ����
function GetPageList($pagesize, $totalcount)
{
	global $page;
	$curpage = empty($page) ? 1 : intval($page);
	$allpage = ceil($totalcount / $pagesize);
	if($allpage < 2) 
	{
		echo '';
		return ;
	}
	echo "<div id='commetpages'>";
	echo "<span>��: {$allpage} ҳ/{$totalcount} ������</span> ";
	$listsize = 5;
	$total_list = $listsize * 2 + 1;
	$totalpage = $allpage;
	$listdd = '';
	if($curpage-1 > 0 )
	{
		echo "<a href='#commettop' onclick='LoadCommets(".($curpage-1).");'>��һҳ</a> ";
	}
	if($curpage >= $total_list)
	{
			$j = $curpage - $listsize;
			$total_list = $curpage + $listsize;
			if($total_list > $totalpage)
			{
				$total_list = $totalpage;
			}
	}
	else
	{
			$j = 1;
			if($total_list > $totalpage) $total_list = $totalpage;
	}
	for($j; $j <= $total_list; $j++)
	{
			echo ($j==$curpage ? "<strong>$j</strong> " : "<a href='#commettop' onclick='LoadCommets($j);'>{$j}</a> ");
	}
	if($curpage+1 <= $totalpage )
	{
		echo "<a href='#commettop' onclick='LoadCommets(".($curpage+1).");'>��һҳ</a> ";
	}
	echo "</div>";
}

?>
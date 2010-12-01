<?php
require_once(dirname(__FILE__)."/config.php");
if(empty($dopost)) $dopost = '';
if(empty($fmdo)) $fmdo = '';

/*********************
function check_email()
*******************/
if($fmdo=='sendMail')
{
	if(!CheckEmail($cfg_ml->fields['email']) )
	{
		ShowMsg('��������ʽ�д���', '-1');
		exit();
	}
	if($cfg_ml->fields['spacesta'] != -10)
	{
		ShowMsg('����ʺŲ����ʼ���֤״̬����������Ч��', '-1');
		exit();
	}
	$userhash = md5($cfg_cookie_encode.'--'.$cfg_ml->fields['mid'].'--'.$cfg_ml->fields['email']);
  $url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/member/index_do.php?fmdo=checkMail&mid={$cfg_ml->fields['mid']}&userhash={$userhash}&do=1";
  $url = eregi_replace('http://', '', $url);
  $url = 'http://'.eregi_replace('//', '/', $url);
  $mailtitle = "{$cfg_webname}--��Ա�ʼ���֤֪ͨ";
  $mailbody = '';
  $mailbody .= "�𾴵��û�[{$cfg_ml->fields['uname']}]�����ã�\r\n";
  $mailbody .= "��ӭע���Ϊ[{$cfg_webname}]�Ļ�Ա��\r\n";
  $mailbody .= "Ҫͨ��ע�ᣬ������������һ�����������������������ӵ���ַ���������ַ��\r\n\r\n";
  $mailbody .= "{$url}\r\n\r\n";
  $mailbody .= "Power by http://www.dedecms.com ֯�����ݹ���ϵͳ��\r\n";
  
	$headers = "From: ".$cfg_adminemail."\r\nReply-To: ".$cfg_adminemail;
	if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server))
	{		
		$mailtype = 'TXT';
		require_once(DEDEINC.'/mail.class.php');
		$smtp = new smtp($cfg_smtp_server,$cfg_smtp_port,true,$cfg_smtp_usermail,$cfg_smtp_password);
		$smtp->debug = false;
		$smtp->sendmail($cfg_ml->fields['email'],$cfg_webname ,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
	}
	else
	{
		@mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
	}
	ShowMsg('�ɹ������ʼ������Ժ��¼���������н��գ�', '/member');
	exit();
}
else if($fmdo=='checkMail')
{
	$mid = intval($mid);
	if(empty($mid))
	{
		ShowMsg('���Ч�鴮���Ϸ���', '-1');
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__member` where mid='{$mid}' ");
	$needUserhash = md5($cfg_cookie_encode.'--'.$mid.'--'.$row['email']);
	if($needUserhash != $userhash)
	{
		ShowMsg('���Ч�鴮���Ϸ���', '-1');
		exit();
	}
	if($row['spacesta'] != -10)
	{
		ShowMsg('����ʺŲ����ʼ���֤״̬����������Ч��', '-1');
		exit();
	}
	$dsql->ExecuteNoneQuery("Update `#@__member` set spacesta=0 where mid='{$mid}' ");
	ShowMsg('�����ɹ��������µ�¼ϵͳ��', 'login.php');
	exit();
}
/*********************
function Case_user()
*******************/
else if($fmdo=='user')
{

	//����û����Ƿ����
	if($dopost=="checkuser")
	{
		AjaxHead();
		$msg = '';
		$uid = trim($uid);
		if($cktype==0)
		{
			$msgtitle='�û�����';
		}
		else
		{
			#api{{
			if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
			{
				$ucresult = uc_user_checkname($uid);
				if($ucresult > 0)
				{
					echo "<font color='#4E7504'><b>���û�������</b></font>";
				}
				elseif($ucresult == -1)
				{
					echo "<font color='red'><b>���û������Ϸ�</b></font>";
				}
				elseif($ucresult == -2)
				{
					echo "<font color='red'><b>������Ҫ����ע��Ĵ���</b></font>";
				}
				elseif($ucresult == -3)
				{
					echo "<font color='red'><b>���û����Ѿ�����</b></font>";
				}
				exit();
			}
			#/aip}}			
			$msgtitle='�û���';
		}
		if($cktype!=0 || $cfg_mb_wnameone=='N') {
			$msg = CheckUserID($uid, $msgtitle);
		}
		else {
			$msg = CheckUserID($uid, $msgtitle, false);
		}
		if($msg=='ok')
		{
			$msg = "<font color='#4E7504'><b>��{$msgtitle}����ʹ��</b></font>";
		}
		else
		{
			$msg = "<font color='red'><b>��{$msg}</b></font>";
		}
		echo $msg;
		exit();
	}

	//���email�Ƿ����
	else  if($dopost=="checkmail")
	{
		AjaxHead();
		
		#api{{
		if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
		{
			$ucresult = uc_user_checkemail($email);
			if($ucresult > 0) {
				echo "<font color='#4E7504'><b>�̿���ʹ��</b></font>";
			} elseif($ucresult == -4) {
				echo "<font color='red'><b>��Email ��ʽ����</b></font>";
			} elseif($ucresult == -5) {
				echo "<font color='red'><b>��Email ������ע�ᣡ</b></font>";
			} elseif($ucresult == -6) {
				echo "<font color='red'><b>���� Email �Ѿ���ע�ᣡ</b></font>";
			}
			exit();
		}
		#/aip}}	
		
		if($cfg_md_mailtest=='N')
		{
			$msg = "<font color='#4E7504'><b>�̿���ʹ��</b></font>";
		}
		else
		{
			if(!CheckEmail($email))
			{
				$msg = "<font color='#4E7504'><b>��Email��ʽ����</b></font>";
			}
			else
			{
				 $row = $dsql->GetOne("Select mid From `#@__member` where email like '$email' limit 1");
				 if(!is_array($row)) {
					 $msg = "<font color='#4E7504'><b>�̿���ʹ��</b></font>";
				 }
				 else {
					 $msg = "<font color='red'><b>��Email�Ѿ�����һ���ʺ�ռ�ã�</b></font>";
				 }
			}
		}
		echo $msg;
		exit();
	}

	//����ע��ҳ��
	else if($dopost=="regnew")
	{
		$step = empty($step)? 1 : intval(preg_replace("/[^\d]/",'', $step));
		require_once(dirname(__FILE__)."/reg_new.php");
		exit();
	}
  /***************************
  //���ֻ����
  function money2s() {  }
  ***************************/
	else if($dopost=="money2s")
	{
		CheckRank(0,0);
		if($cfg_money_scores==0)
		{
			ShowMsg('ϵͳ�����˻������Ҷһ����ܣ�', '-1');
			exit();
		}
		$money = empty($money) ? "" : abs(intval($money));
		if(empty($money))
		{
			ShowMsg('��ûָ��Ҫ�һ����ٽ�ң�', '-1');
			exit();
		}
		
		$needscores = $money * $cfg_money_scores;
		if($cfg_ml->fields['scores'] < $needscores )
		{
			ShowMsg('�����ֲ��㣬���ܻ�ȡ��ô��Ľ�ң�', '-1');
			exit();
		}
		$litmitscores = $cfg_ml->fields['scores'] - $needscores;
		
		//�����¼
		$mtime = time();
		$inquery = "INSERT INTO `#@__member_operation`(`buyid` , `pname` , `product` , `money` , `mtime` , `pid` , `mid` , `sta` ,`oldinfo`)
   		VALUES ('ScoresToMoney', '���ֻ���Ҳ���', 'stc' , '0' , '$mtime' , '0' , '{$cfg_ml->M_ID}' , '0' , '�� {$needscores} ���ֶ��˻���ң�{$money} ��'); ";
		$dsql->ExecuteNoneQuery($inquery);
		//�޸Ļ�������ֵ
		$dsql->ExecuteNoneQuery("update `#@__member` set `scores`=$litmitscores, money= money + $money  where mid='".$cfg_ml->M_ID."' ");
		ShowMsg('�ɹ��һ�ָ�����Ľ�ң�', 'operation.php');
		exit();
	}


}

/*********************
function login()
*******************/
else if($fmdo=='login')
{

	//�û���¼
	if($dopost=="login")
	{
		if(!isset($vdcode))
		{
			$vdcode = '';
		}
		$svali = GetCkVdValue();
		if(preg_match("/2/",$safe_gdopen)){
			if(strtolower($vdcode)!=$svali || $svali=='')
			{
				ResetVdValue();
				ShowMsg('��֤�����', '-1');
				exit();
			}
			
		}
		if(CheckUserID($userid,'',false)!='ok')
		{
			ShowMsg("��������û��� {$userid} ���Ϸ���","-1");
			exit();
		}
		if($pwd=='')
		{
			ShowMsg("���벻��Ϊ�գ�","-1",0,2000);
			exit();
		}

		//����ʺ�
		$rs = $cfg_ml->CheckUser($userid,$pwd);		
		
		#api{{
		if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
		{
			//����ʺ�
			list($uid, $username, $password, $email) = uc_user_login($userid, $pwd);
			if($uid > 0) {
				$password = md5($password);
				//��UC�����û�,��CMS������ʱ,��ע��һ��	
				if(!$rs) {
					//��Ա��Ĭ�Ͻ��
					$row = $dsql->GetOne("SELECT `money`,`scores` FROM `#@__arcrank` WHERE `rank`='10' ");
					$scores = is_array($row) ? $row['scores'] : 0;
					$money = is_array($row) ? $row['money'] : 0;
					$logintime = $jointime = time();
					$loginip = $joinip = GetIP();
					$res = $dsql->ExecuteNoneQuery("INSERT INTO #@__member SET `mtype`='����',`userid`='$username',`pwd`='$password',`uname`='$username',`sex`='��' ,`rank`='10',`money`='$money', `email`='$email', `scores`='$scores', `matt`='0', `face`='',`safequestion`='0',`safeanswer`='', `jointime`='$jointime',`joinip`='$joinip',`logintime`='$logintime',`loginip`='$loginip';");
					if($res) {
						$mid = $dsql->GetLastID();
						$data = array
						(
						0 => "INSERT INTO `#@__member_person` SET `mid`='$mid', `onlynet`='1', `sex`='��', `uname`='$username', `qq`='', `msn`='', `tel`='', `mobile`='', `place`='', `oldplace`='0' ,
								 `birthday`='1980-01-01', `star`='1', `income`='0', `education`='0', `height`='160', `bodytype`='0', `blood`='0', `vocation`='0', `smoke`='0', `marital`='0', `house`='0',
			           `drink`='0', `datingtype`='0', `language`='', `nature`='', `lovemsg`='', `address`='',`uptime`='0';",
						1 => "INSERT INTO `#@__member_tj` SET `mid`='$mid',`article`='0',`album`='0',`archives`='0',`homecount`='0',`pagecount`='0',`feedback`='0',`friend`='0',`stow`='0';",
						2 => "INSERT INTO `#@__member_space` SET `mid`='$mid',`pagesize`='10',`matt`='0',`spacename`='{$uname}�Ŀռ�',`spacelogo`='',`spacestyle`='person', `sign`='',`spacenews`='';",
						3 => "INSERT INTO `#@__member_flink` SET `mid`='$mid', `title`='֯�����ݹ���ϵͳ', `url`='http://www.dedecms.com';"
						);						
						foreach($data as $val) $dsql->ExecuteNoneQuery($val);
					}
				}
				$rs = 1;
				$row = $dsql->GetOne("SELECT `mid`, `pwd` FROM #@__member WHERE `userid`='$username'");
				if(isset($row['mid']))
				{
					$cfg_ml->PutLoginInfo($row['mid']);
					if($password!=$row['pwd']) $dsql->ExecuteNoneQuery("UPDATE #@__member SET `pwd`='$password' WHERE mid='$row[mid]'");
				}
				//����ͬ����¼�Ĵ���
				$ucsynlogin = uc_user_synlogin($uid);
			} elseif($uid == -1) {
				//��UC�����ڸ��ö�CMS����,��ע��һ��.
				if($rs) {
					$row = $dsql->GetOne("SELECT `email` FROM #@__member WHERE userid='$userid'");					
					$uid = uc_user_register($userid, $pwd, $row['email']);
					if($uid > 0) $ucsynlogin = uc_user_synlogin($uid);
				} else {
					$rs = -1;
				}
			} else {
				$rs = -1;
			}
		}
		#/aip}}		
		
		if($rs==0)
		{
			ShowMsg("�û��������ڣ�","-1",0,2000);
			exit();
		}
		else if($rs==-1) {
			ShowMsg("�������","-1",0,2000);
			exit();
		}
		else if($rs==-2) {
			ShowMsg("����Ա�ʺŲ������ǰ̨��¼��","-1",0,2000);
			exit();
		}
		else
		{
			if(empty($gourl) || eregi("action|_do",$gourl))
			{
				ShowMsg("�ɹ���¼��5���Ӻ�ת��ϵͳ��ҳ...","index.php",0,2000);
			}
			else
			{
				ShowMsg("�ɹ���¼������ת��ָ��ҳ��...",$gourl,0,2000);
			}
			exit();
		}
	}

	//�˳���¼
	else if($dopost=="exit")
	{
		$cfg_ml->ExitCookie();
		#api{{
		if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
		{
			$ucsynlogin = uc_user_synlogout();
		}
		#/aip}}
		ShowMsg("�ɹ��˳���¼��","index.php",0,2000);
		exit();
	}
}
/*********************
function moodmsg()
*******************/
else if($fmdo=='moodmsg')
{
	//�û���¼
	if($dopost=="sendmsg")
	{
		if(!empty($content))
		{
	    $ip = GetIP();
	    $dtime = time();
		  $ischeck = ($cfg_mb_msgischeck == 'Y')? 0 : 1;
		  if($cfg_soft_lang == 'gb2312')
		  {
		  	$content = iconv('UTF-8','gb2312',nl2br($content));
		  } 
		  $content = cn_substrR(HtmlReplace($content,1),360);
		  //�Ա�����н���
		  $content = addslashes(preg_replace("/\[face:(\d{1,2})\]/is","<img src='".$cfg_memberurl."/templets/images/smiley/\\1.gif' style='cursor: pointer; position: relative;'>",$content));
		  
			$inquery = "INSERT INTO `#@__member_msg`(`mid`,`userid`,`ip`,`ischeck`,`dtime`, `msg`)
	               VALUES ('{$cfg_ml->M_ID}','{$cfg_ml->M_LoginID}','$ip','$ischeck','$dtime', '$content'); ";
			$rs = $dsql->ExecuteNoneQuery($inquery);
			if(!$rs)
			{
				$output['type'] = 'error';
				$output['data'] = '����ʧ��,������.';
				exit();
			}
			$output['type'] = 'success';
		  if($cfg_soft_lang == 'gb2312')
		  {
		  	$content = iconv('gb2312','UTF-8',nl2br($content));
		  } 
			$output['data'] = stripslashes($content);
			exit(json_encode($output));
		}
	}
}
else
{
	ShowMsg("��ҳ���ֹ����!","index.php");
}

?>